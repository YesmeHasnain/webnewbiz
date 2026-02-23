<?php

namespace App\Services;

use App\Models\Website;
use App\Models\WebsiteBackup;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BackupService
{
    private ServerProvisioningService $serverService;

    public function __construct(ServerProvisioningService $serverService)
    {
        $this->serverService = $serverService;
    }

    public function createBackup(Website $website, string $type = 'full'): array
    {
        $server = $website->server;
        if (!$server) return ['success' => false, 'message' => 'No server assigned'];

        $backup = WebsiteBackup::create([
            'website_id' => $website->id,
            'type' => $type,
            'status' => 'in_progress',
        ]);

        try {
            $domain = $website->subdomain . config('webnewbiz.subdomain_suffix');
            $timestamp = now()->format('Y-m-d_His');
            $filename = "{$domain}_{$type}_{$timestamp}.tar.gz";
            $remotePath = "/tmp/{$filename}";

            $scriptPath = base_path('scripts/backup-wordpress-site.sh');
            $command = "bash {$scriptPath} " . escapeshellarg($domain) . " " . escapeshellarg($type) . " " . escapeshellarg($remotePath);

            $result = $this->serverService->executeCommand($server, $command);

            if (!$result['success']) {
                $backup->update(['status' => 'failed']);
                return $result;
            }

            // Get file size
            $sizeResult = $this->serverService->executeCommand($server, "stat -c%s " . escapeshellarg($remotePath));
            $fileSize = trim($sizeResult['data']['output'] ?? '0');

            $backup->update([
                'status' => 'completed',
                'file_path' => $remotePath,
                'file_size' => (int) $fileSize,
                'completed_at' => now(),
            ]);

            return ['success' => true, 'data' => $backup->fresh()];
        } catch (\Exception $e) {
            Log::error("Backup failed: {$e->getMessage()}");
            $backup->update(['status' => 'failed']);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function restoreBackup(WebsiteBackup $backup): array
    {
        $website = $backup->website;
        $server = $website->server;
        if (!$server) return ['success' => false, 'message' => 'No server assigned'];

        if (!$backup->file_path) return ['success' => false, 'message' => 'Backup file not found'];

        $domain = $website->subdomain . config('webnewbiz.subdomain_suffix');
        $siteDir = "/home/{$domain}/htdocs/{$domain}";
        $command = "cd {$siteDir} && tar -xzf " . escapeshellarg($backup->file_path) . " -C {$siteDir}";

        return $this->serverService->executeCommand($server, $command);
    }

    public function deleteBackup(WebsiteBackup $backup): array
    {
        $website = $backup->website;
        $server = $website->server;

        if ($server && $backup->file_path) {
            $this->serverService->executeCommand($server, "rm -f " . escapeshellarg($backup->file_path));
        }

        $backup->delete();
        return ['success' => true, 'message' => 'Backup deleted'];
    }

    public function listBackups(Website $website): array
    {
        $backups = $website->backups()->orderByDesc('created_at')->get();
        return ['success' => true, 'data' => $backups];
    }
}
