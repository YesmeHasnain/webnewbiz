<?php

namespace App\Services;

use App\Models\Server;
use App\Models\Website;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WordPressService
{
    private ServerProvisioningService $serverService;

    public function __construct(ServerProvisioningService $serverService)
    {
        $this->serverService = $serverService;
    }

    public function createSite(Website $website): array
    {
        $server = $website->server;
        if (!$server) {
            return ['success' => false, 'message' => 'No server assigned to website'];
        }

        try {
            $domain = $website->subdomain . config('webnewbiz.subdomain_suffix');
            $dbName = 'wp_' . Str::slug($website->subdomain, '_');
            $dbUser = 'wpu_' . Str::slug($website->subdomain, '_');
            $dbPass = Str::random(24);
            $wpUser = $website->wp_admin_user ?: 'admin';
            $wpPass = $website->wp_admin_password ?: Str::random(16);
            $wpEmail = $website->wp_admin_email ?: $website->user->email;

            $scriptPath = base_path('scripts/create-wordpress-site.sh');
            $command = "bash {$scriptPath} " . escapeshellarg($domain) . " " . escapeshellarg($dbName) . " " . escapeshellarg($dbUser) . " " . escapeshellarg($dbPass) . " " . escapeshellarg($wpUser) . " " . escapeshellarg($wpPass) . " " . escapeshellarg($wpEmail) . " " . escapeshellarg($website->name);

            $result = $this->serverService->executeCommand($server, $command);

            if (!$result['success']) return $result;

            $website->update([
                'wp_admin_user' => $wpUser,
                'wp_admin_password' => encrypt($wpPass),
                'wp_admin_email' => $wpEmail,
                'wp_db_name' => $dbName,
                'wp_db_user' => $dbUser,
                'wp_db_password' => encrypt($dbPass),
                'status' => 'active',
            ]);

            $server->increment('current_websites');

            return ['success' => true, 'data' => [
                'domain' => $domain,
                'wp_admin_user' => $wpUser,
                'wp_admin_password' => $wpPass,
                'wp_url' => "https://{$domain}/wp-admin",
            ]];
        } catch (\Exception $e) {
            Log::error("WordPress site creation failed: {$e->getMessage()}");
            $website->update(['status' => 'error']);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function deleteSite(Website $website): array
    {
        $server = $website->server;
        if (!$server) return ['success' => false, 'message' => 'No server assigned'];

        $domain = $website->subdomain . config('webnewbiz.subdomain_suffix');
        $scriptPath = base_path('scripts/delete-wordpress-site.sh');
        $command = "bash {$scriptPath} " . escapeshellarg($domain) . " " . escapeshellarg($website->wp_db_name ?? '');

        $result = $this->serverService->executeCommand($server, $command);

        if ($result['success']) {
            $server->decrement('current_websites');
            $website->update(['status' => 'deleting']);
        }

        return $result;
    }

    public function installPlugin(Website $website, string $pluginSlug): array
    {
        $server = $website->server;
        if (!$server) return ['success' => false, 'message' => 'No server assigned'];

        $domain = $website->subdomain . config('webnewbiz.subdomain_suffix');
        $siteDir = "/home/{$domain}/htdocs/{$domain}";
        $command = "cd {$siteDir} && sudo -u {$domain} wp plugin install " . escapeshellarg($pluginSlug) . " --activate";

        return $this->serverService->executeCommand($server, $command);
    }

    public function deactivatePlugin(Website $website, string $pluginSlug): array
    {
        $server = $website->server;
        if (!$server) return ['success' => false, 'message' => 'No server assigned'];

        $domain = $website->subdomain . config('webnewbiz.subdomain_suffix');
        $siteDir = "/home/{$domain}/htdocs/{$domain}";
        $command = "cd {$siteDir} && sudo -u {$domain} wp plugin deactivate " . escapeshellarg($pluginSlug);

        return $this->serverService->executeCommand($server, $command);
    }

    public function deletePlugin(Website $website, string $pluginSlug): array
    {
        $server = $website->server;
        if (!$server) return ['success' => false, 'message' => 'No server assigned'];

        $domain = $website->subdomain . config('webnewbiz.subdomain_suffix');
        $siteDir = "/home/{$domain}/htdocs/{$domain}";
        $command = "cd {$siteDir} && sudo -u {$domain} wp plugin deactivate " . escapeshellarg($pluginSlug) . " && sudo -u {$domain} wp plugin delete " . escapeshellarg($pluginSlug);

        return $this->serverService->executeCommand($server, $command);
    }

    public function installTheme(Website $website, string $themeSlug): array
    {
        $server = $website->server;
        if (!$server) return ['success' => false, 'message' => 'No server assigned'];

        $domain = $website->subdomain . config('webnewbiz.subdomain_suffix');
        $siteDir = "/home/{$domain}/htdocs/{$domain}";
        $command = "cd {$siteDir} && sudo -u {$domain} wp theme install " . escapeshellarg($themeSlug) . " --activate";

        return $this->serverService->executeCommand($server, $command);
    }

    public function activateTheme(Website $website, string $themeSlug): array
    {
        $server = $website->server;
        if (!$server) return ['success' => false, 'message' => 'No server assigned'];

        $domain = $website->subdomain . config('webnewbiz.subdomain_suffix');
        $siteDir = "/home/{$domain}/htdocs/{$domain}";
        $command = "cd {$siteDir} && sudo -u {$domain} wp theme activate " . escapeshellarg($themeSlug);

        return $this->serverService->executeCommand($server, $command);
    }

    public function listPlugins(Website $website): array
    {
        $server = $website->server;
        if (!$server) return ['success' => false, 'message' => 'No server assigned'];

        $domain = $website->subdomain . config('webnewbiz.subdomain_suffix');
        $siteDir = "/home/{$domain}/htdocs/{$domain}";
        $command = "cd {$siteDir} && sudo -u {$domain} wp plugin list --format=json";

        return $this->serverService->executeCommand($server, $command);
    }

    public function listThemes(Website $website): array
    {
        $server = $website->server;
        if (!$server) return ['success' => false, 'message' => 'No server assigned'];

        $domain = $website->subdomain . config('webnewbiz.subdomain_suffix');
        $siteDir = "/home/{$domain}/htdocs/{$domain}";
        $command = "cd {$siteDir} && sudo -u {$domain} wp theme list --format=json";

        return $this->serverService->executeCommand($server, $command);
    }

    public function createPage(Website $website, string $title, string $content): array
    {
        $server = $website->server;
        if (!$server) return ['success' => false, 'message' => 'No server assigned'];

        $domain = $website->subdomain . config('webnewbiz.subdomain_suffix');
        $siteDir = "/home/{$domain}/htdocs/{$domain}";
        $command = "cd {$siteDir} && sudo -u {$domain} wp post create --post_type=page --post_title=" . escapeshellarg($title) . " --post_content=" . escapeshellarg($content) . " --post_status=publish";

        return $this->serverService->executeCommand($server, $command);
    }

    public function updatePostMeta(Website $website, int $postId, string $key, string $value): array
    {
        $server = $website->server;
        if (!$server) return ['success' => false, 'message' => 'No server assigned'];

        $domain = $website->subdomain . config('webnewbiz.subdomain_suffix');
        $siteDir = "/home/{$domain}/htdocs/{$domain}";
        $command = "cd {$siteDir} && sudo -u {$domain} wp post meta update {$postId} " . escapeshellarg($key) . " " . escapeshellarg($value);

        return $this->serverService->executeCommand($server, $command);
    }

    public function createElementorPage(Website $website, string $title, string $htmlContent, array $elementorData): array
    {
        $server = $website->server;
        if (!$server) return ['success' => false, 'message' => 'No server assigned'];

        $domain = $website->subdomain . config('webnewbiz.subdomain_suffix');
        $siteDir = "/home/{$domain}/htdocs/{$domain}";

        // Create the page and capture the post ID from output
        $command = "cd {$siteDir} && sudo -u {$domain} wp post create --post_type=page --post_title=" . escapeshellarg($title) . " --post_content=" . escapeshellarg($htmlContent) . " --post_status=publish --porcelain";

        $result = $this->serverService->executeCommand($server, $command);
        if (!$result['success']) return $result;

        $postId = (int) trim($result['output'] ?? $result['data'] ?? '');
        if (!$postId) {
            return ['success' => false, 'message' => 'Failed to capture post ID'];
        }

        // Set Elementor data as post meta
        $elementorJson = json_encode($elementorData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $this->updatePostMeta($website, $postId, '_elementor_data', $elementorJson);
        $this->updatePostMeta($website, $postId, '_elementor_edit_mode', 'builder');
        $this->updatePostMeta($website, $postId, '_elementor_page_settings', json_encode(['hide_title' => 'yes']));
        $this->updatePostMeta($website, $postId, '_wp_page_template', 'elementor_canvas');

        return ['success' => true, 'post_id' => $postId];
    }

    public function updateOption(Website $website, string $key, string $value): array
    {
        $server = $website->server;
        if (!$server) return ['success' => false, 'message' => 'No server assigned'];

        $domain = $website->subdomain . config('webnewbiz.subdomain_suffix');
        $siteDir = "/home/{$domain}/htdocs/{$domain}";
        $command = "cd {$siteDir} && sudo -u {$domain} wp option update " . escapeshellarg($key) . " " . escapeshellarg($value);

        return $this->serverService->executeCommand($server, $command);
    }
}
