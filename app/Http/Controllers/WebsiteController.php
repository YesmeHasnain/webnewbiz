<?php

namespace App\Http\Controllers;

use App\Models\Website;
use App\Services\WebsiteBuilderService;
use App\Services\WordPressService;

class WebsiteController extends Controller
{
    public function __construct(
        private WebsiteBuilderService $builderService,
        private WordPressService $wpService,
    ) {}

    public function index()
    {
        $user = auth()->user();
        $query = $user->isAdmin() ? Website::query() : $user->websites();
        $websites = $query->with(['server', 'domains'])
            ->latest()
            ->paginate(12);

        return view('websites.index', compact('websites'));
    }

    public function show(Website $website)
    {
        abort_if($website->user_id !== auth()->id() && !auth()->user()->isAdmin(), 403);

        $website->load(['server', 'domains', 'plugins', 'themes', 'backups']);

        return view('websites.show', compact('website'));
    }

    public function destroy(Website $website)
    {
        abort_if($website->user_id !== auth()->id() && !auth()->user()->isAdmin(), 403);

        try {
            $this->builderService->deleteWebsite($website);
        } catch (\Exception $e) {
            // Even if WP deletion fails, still delete the record
            \Illuminate\Support\Facades\Log::error("Website delete error: {$e->getMessage()}");
            $website->delete();
        }

        $redirectTo = url()->previous() ?: route('dashboard');

        return redirect($redirectTo)
            ->with('success', 'Website deleted successfully.');
    }

    /**
     * Regenerate auto-login token and script for a website,
     * then redirect to WP Admin via auto-login.
     */
    public function wpAdmin(Website $website)
    {
        abort_if($website->user_id !== auth()->id() && !auth()->user()->isAdmin(), 403);
        abort_if($website->status !== 'active', 404);

        // If token already exists, just redirect
        if ($website->wp_auto_login_token) {
            return redirect()->away($website->getWpAdminAutoLoginUrl());
        }

        // Regenerate token and auto-login script for older sites
        $htdocsPath = config('webnewbiz.xampp_htdocs', 'C:/xampp/htdocs');
        $sitePath = $htdocsPath . '/' . $website->subdomain;
        $scriptPath = $sitePath . '/wp-auto-login.php';

        if (is_dir($sitePath)) {
            $token = \Illuminate\Support\Str::random(32);
            $wpPass = $website->wp_admin_password ? decrypt($website->wp_admin_password) : null;

            if ($wpPass) {
                // Create the auto-login script file
                $this->wpService->updateAutoLoginPassword($sitePath, $token, $wpPass);

                // If the script doesn't exist yet, we need to create it first
                if (!file_exists($scriptPath)) {
                    $this->createAutoLoginScript($sitePath, $token, $wpPass);
                }

                // Save the token to the database
                $website->update(['wp_auto_login_token' => encrypt($token)]);

                return redirect()->away($website->getWpAdminAutoLoginUrl());
            }
        }

        // Final fallback: just go to wp-login.php
        return redirect()->away($website->url . '/wp-login.php');
    }

    /**
     * Create the auto-login PHP script for sites that don't have one.
     */
    private function createAutoLoginScript(string $sitePath, string $token, string $password): void
    {
        $script = <<<'PHPTPL'
<?php
$token = isset($_GET['token']) ? $_GET['token'] : '';
$expected = '%TOKEN%';
$password = '%PASSWORD%';

if (empty($token) || !hash_equals($expected, $token)) {
    http_response_code(403);
    die('Invalid token');
}

define('ABSPATH', __DIR__ . '/');
require_once ABSPATH . 'wp-load.php';

$creds = array(
    'user_login'    => 'admin',
    'user_password' => $password,
    'remember'      => true,
);
$user = wp_signon($creds, false);

if (is_wp_error($user)) {
    wp_die('Auto-login failed: ' . $user->get_error_message());
}

wp_set_current_user($user->ID);
wp_set_auth_cookie($user->ID, true);

wp_safe_redirect(admin_url());
exit;
PHPTPL;

        $script = str_replace('%TOKEN%', $token, $script);
        $script = str_replace('%PASSWORD%', addslashes($password), $script);
        file_put_contents($sitePath . '/wp-auto-login.php', $script);
    }
}
