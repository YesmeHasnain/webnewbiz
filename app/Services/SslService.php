<?php

namespace App\Services;

use App\Models\Domain;
use App\Models\Server;
use App\Models\SslCertificate;
use Illuminate\Support\Facades\Log;

class SslService
{
    private ServerProvisioningService $serverService;

    public function __construct(ServerProvisioningService $serverService)
    {
        $this->serverService = $serverService;
    }

    public function issueCertificate(Domain $domain): array
    {
        $website = $domain->website;
        $server = $website->server;

        if (!$server) {
            return ['success' => false, 'message' => 'No server assigned'];
        }

        try {
            $acmePath = config('cloudpanel.paths.acme');
            $command = "{$acmePath} --issue -d " . escapeshellarg($domain->domain) . " --webroot /home/" . escapeshellarg($domain->domain) . "/htdocs/" . escapeshellarg($domain->domain) . " --keylength 2048";

            $result = $this->serverService->executeCommand($server, $command);

            if (!$result['success']) return $result;

            $cert = SslCertificate::create([
                'domain_id' => $domain->id,
                'provider' => 'letsencrypt',
                'status' => 'active',
                'certificate_path' => "/root/.acme.sh/{$domain->domain}/fullchain.cer",
                'private_key_path' => "/root/.acme.sh/{$domain->domain}/{$domain->domain}.key",
                'chain_path' => "/root/.acme.sh/{$domain->domain}/ca.cer",
                'issued_at' => now(),
                'expires_at' => now()->addDays(90),
            ]);

            $domain->update(['ssl_status' => 'active', 'ssl_expires_at' => $cert->expires_at]);

            return ['success' => true, 'data' => $cert];
        } catch (\Exception $e) {
            Log::error("SSL issuance failed: {$e->getMessage()}");
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function renewCertificate(Domain $domain): array
    {
        $website = $domain->website;
        $server = $website->server;
        if (!$server) return ['success' => false, 'message' => 'No server assigned'];

        $acmePath = config('cloudpanel.paths.acme');
        $command = "{$acmePath} --renew -d " . escapeshellarg($domain->domain) . " --force";
        $result = $this->serverService->executeCommand($server, $command);

        if ($result['success']) {
            $cert = $domain->activeCertificate;
            if ($cert) {
                $cert->update([
                    'expires_at' => now()->addDays(90),
                    'last_renewal_at' => now(),
                ]);
            }
            $domain->update(['ssl_expires_at' => now()->addDays(90)]);
        }

        return $result;
    }

    public function revokeCertificate(Domain $domain): array
    {
        $website = $domain->website;
        $server = $website->server;
        if (!$server) return ['success' => false, 'message' => 'No server assigned'];

        $acmePath = config('cloudpanel.paths.acme');
        $command = "{$acmePath} --revoke -d " . escapeshellarg($domain->domain);
        $result = $this->serverService->executeCommand($server, $command);

        if ($result['success']) {
            $cert = $domain->activeCertificate;
            if ($cert) $cert->update(['status' => 'revoked']);
            $domain->update(['ssl_status' => 'none']);
        }

        return $result;
    }
}
