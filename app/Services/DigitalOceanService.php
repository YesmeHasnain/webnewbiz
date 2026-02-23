<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DigitalOceanService
{
    private ?string $apiToken;
    private string $apiUrl = 'https://api.digitalocean.com/v2';

    public function __construct()
    {
        $this->apiToken = config('services.digitalocean.api_token');
    }

    private function request(string $method, string $endpoint, array $data = []): array
    {
        if (!$this->apiToken) {
            Log::warning('DigitalOcean API not configured');
            return ['success' => false, 'message' => 'DigitalOcean API not configured'];
        }

        try {
            $response = Http::withToken($this->apiToken)
                ->timeout(60)
                ->{$method}("{$this->apiUrl}/{$endpoint}", $data);

            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json()];
            }

            return ['success' => false, 'message' => $response->json('message', 'API request failed'), 'status' => $response->status()];
        } catch (\Exception $e) {
            Log::error("DigitalOcean API error: {$e->getMessage()}");
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function createDroplet(string $name, ?string $region = null, ?string $size = null, ?string $image = null, array $sshKeys = []): array
    {
        return $this->request('post', 'droplets', [
            'name' => $name,
            'region' => $region ?? config('services.digitalocean.default_region'),
            'size' => $size ?? config('services.digitalocean.default_size'),
            'image' => $image ?? config('services.digitalocean.default_image'),
            'ssh_keys' => $sshKeys,
            'monitoring' => true,
            'tags' => ['webnewbiz'],
        ]);
    }

    public function getDroplet(int $dropletId): array
    {
        return $this->request('get', "droplets/{$dropletId}");
    }

    public function deleteDroplet(int $dropletId): array
    {
        return $this->request('delete', "droplets/{$dropletId}");
    }

    public function listDroplets(): array
    {
        return $this->request('get', 'droplets?tag_name=webnewbiz&per_page=100');
    }

    public function createSshKey(string $name, string $publicKey): array
    {
        return $this->request('post', 'account/keys', [
            'name' => $name,
            'public_key' => $publicKey,
        ]);
    }

    public function getDropletMetrics(int $dropletId): array
    {
        $droplet = $this->getDroplet($dropletId);
        if (!$droplet['success']) return $droplet;

        return [
            'success' => true,
            'data' => [
                'status' => $droplet['data']['droplet']['status'] ?? 'unknown',
                'memory' => $droplet['data']['droplet']['memory'] ?? 0,
                'vcpus' => $droplet['data']['droplet']['vcpus'] ?? 0,
                'disk' => $droplet['data']['droplet']['disk'] ?? 0,
                'ip_address' => $droplet['data']['droplet']['networks']['v4'][0]['ip_address'] ?? null,
            ],
        ];
    }

    public function powerActions(int $dropletId, string $action): array
    {
        return $this->request('post', "droplets/{$dropletId}/actions", ['type' => $action]);
    }
}
