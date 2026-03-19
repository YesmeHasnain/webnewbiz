<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Website;
use App\Jobs\ProvisionWebsiteJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebsiteController extends Controller
{
    public function index(Request $request)
    {
        $websites = $request->user()->websites()
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($website) {
                $data = $website->toArray();
                if ($website->status === 'active' && $website->url) {
                    $data['wp_admin_url'] = $website->url . '/wp-admin/';
                    $elementorPath = '/wp-admin/post.php?post=' . ($website->home_page_id ?? 0) . '&action=elementor';
                    $data['elementor_url'] = $website->url . $elementorPath;

                    if ($website->wp_auto_login_token) {
                        try {
                            $token = decrypt($website->wp_auto_login_token);
                            $data['wp_admin_url'] = $website->url . '/wp-auto-login.php?token=' . $token;
                            $data['elementor_url'] = $website->url . '/wp-auto-login.php?token=' . $token . '&redirect=' . urlencode($elementorPath);
                        } catch (\Exception $e) {
                            // fall back to plain wp-admin URL
                        }
                    }
                }
                return $data;
            });

        return response()->json($websites);
    }

    public function show(Request $request, $id)
    {
        $website = $request->user()->websites()->findOrFail($id);

        $data = $website->toArray();

        // Generate auto-login URL if site is active
        if ($website->status === 'active' && $website->url) {
            $data['wp_admin_url'] = $website->url . '/wp-admin/';

            $elementorPath = '/wp-admin/post.php?post=' . ($website->home_page_id ?? 0) . '&action=elementor';
            $data['elementor_url'] = $website->url . $elementorPath;

            if ($website->wp_auto_login_token) {
                try {
                    $token = decrypt($website->wp_auto_login_token);
                    $data['wp_admin_url'] = $website->url . '/wp-auto-login.php?token=' . $token;
                    $data['elementor_url'] = $website->url . '/wp-auto-login.php?token=' . $token . '&redirect=' . urlencode($elementorPath);
                } catch (\Exception $e) {
                    // Token decryption failed, fall back to plain wp-admin URL
                }
            }
        }

        return response()->json($data);
    }

    public function generate(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'business_type' => 'required|string|max:255',
            'prompt' => 'required|string|min:10',
            'layout' => 'required|string|in:noir,ivory,azure,blush,ember,forest,slate,royal,biddut',
            'pages' => 'nullable|array',
        ]);

        $slug = strtolower(preg_replace('/[^a-z0-9]+/', '-', strtolower($request->business_name)));
        $slug = trim($slug, '-');

        // Ensure uniqueness
        $baseSlug = $slug;
        $counter = 1;
        while (Website::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        $website = Website::create([
            'user_id' => $request->user()->id,
            'name' => $request->business_name,
            'slug' => $slug,
            'business_type' => $request->business_type,
            'ai_prompt' => $request->prompt,
            'ai_theme' => $request->layout,
            'status' => 'pending',
            'build_step' => 'queued',
            'build_log' => ['Queued for building...'],
            'pages' => $request->pages ?? ['home', 'about', 'services', 'contact'],
        ]);

        ProvisionWebsiteJob::dispatch($website);

        // Auto-start queue worker for local dev (processes this one job)
        $this->ensureQueueWorker();

        return response()->json($website, 201);
    }

    /**
     * Spawn a queue worker in the background if using database driver.
     * This ensures jobs don't get stuck when no worker is running.
     */
    private function ensureQueueWorker(): void
    {
        if (config('queue.default') !== 'database') {
            return;
        }

        try {
            $php = PHP_BINARY;
            $artisan = base_path('artisan');
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                pclose(popen("start /B \"\" \"{$php}\" \"{$artisan}\" queue:work --once --timeout=600 > NUL 2>&1", 'r'));
            } else {
                exec("\"{$php}\" \"{$artisan}\" queue:work --once --timeout=600 > /dev/null 2>&1 &");
            }
        } catch (\Exception $e) {
            Log::warning("Failed to start queue worker: {$e->getMessage()}");
        }
    }

    public function status(Request $request, $id)
    {
        $website = $request->user()->websites()->findOrFail($id);

        $data = [
            'id' => $website->id,
            'status' => $website->status,
            'build_step' => $website->build_step,
            'build_log' => $website->build_log ?? [],
            'url' => $website->url,
            'name' => $website->name,
        ];

        if ($website->status === 'active' && $website->wp_auto_login_token) {
            try {
                $token = decrypt($website->wp_auto_login_token);
                $data['wp_admin_url'] = $website->url . '/wp-auto-login.php?token=' . $token . '&redirect=/wp-admin/';
            } catch (\Exception $e) {
                $data['wp_admin_url'] = $website->url . '/wp-admin/';
            }
        }

        return response()->json($data);
    }

    public function rebuild(Request $request, $id)
    {
        $request->validate([
            'prompt' => 'required|string|min:10',
            'layout' => 'required|string|in:noir,ivory,azure,blush,ember,forest,slate,royal,biddut',
            'pages' => 'nullable|array',
            'business_name' => 'nullable|string|max:255',
            'business_type' => 'nullable|string|max:255',
        ]);

        $website = $request->user()->websites()->findOrFail($id);

        $website->update([
            'ai_prompt' => $request->prompt,
            'ai_theme' => $request->layout,
            'pages' => $request->pages ?? $website->pages ?? ['home', 'about', 'services', 'contact'],
            'name' => $request->business_name ?? $website->name,
            'business_type' => $request->business_type ?? $website->business_type,
            'status' => 'pending',
            'build_step' => 'queued',
            'build_log' => ['Queued for rebuilding...'],
        ]);

        ProvisionWebsiteJob::dispatch($website->fresh());
        $this->ensureQueueWorker();

        return response()->json($website->fresh());
    }

    public function destroy(Request $request, $id)
    {
        $website = $request->user()->websites()->findOrFail($id);

        // TODO: Actually delete WP site from XAMPP
        $website->delete();

        return response()->json(['message' => 'Website deleted']);
    }
}
