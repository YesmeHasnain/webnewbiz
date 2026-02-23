<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DnsService
{
    private ?string $apiToken;
    private ?string $zoneId;
    private string $apiUrl = 'https://api.cloudflare.com/client/v4';

    public function __construct()
    {
        $this->apiToken = config('services.cloudflare.api_token');
        $this->zoneId = config('services.cloudflare.zone_id');
    }

    private function request(string $method, string $endpoint, array $data = []): array
    {
        if (!$this->apiToken) {
            Log::warning('Cloudflare API not configured');
            return ['success' => false, 'message' => 'Cloudflare API not configured'];
        }

        try {
            $response = Http::withToken($this->apiToken)
                ->timeout(30)
                ->{$method}("{$this->apiUrl}/{$endpoint}", $data);

            $body = $response->json();

            if ($response->successful() && ($body['success'] ?? false)) {
                return ['success' => true, 'data' => $body['result'] ?? $body];
            }

            $errors = collect($body['errors'] ?? [])->pluck('message')->implode(', ');
            return ['success' => false, 'message' => $errors ?: 'Cloudflare API request failed'];
        } catch (\Exception $e) {
            Log::error("Cloudflare API error: {$e->getMessage()}");
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function createRecord(string $type, string $name, string $content, bool $proxied = true): array
    {
        return $this->request('post', "zones/{$this->zoneId}/dns_records", [
            'type' => $type,
            'name' => $name,
            'content' => $content,
            'proxied' => $proxied,
            'ttl' => 1, // auto
        ]);
    }

    public function deleteRecord(string $recordId): array
    {
        return $this->request('delete', "zones/{$this->zoneId}/dns_records/{$recordId}");
    }

    public function updateRecord(string $recordId, string $type, string $name, string $content, bool $proxied = true): array
    {
        return $this->request('put', "zones/{$this->zoneId}/dns_records/{$recordId}", [
            'type' => $type,
            'name' => $name,
            'content' => $content,
            'proxied' => $proxied,
        ]);
    }

    public function listRecords(?string $name = null): array
    {
        $params = ['per_page' => 100];
        if ($name) $params['name'] = $name;
        return $this->request('get', "zones/{$this->zoneId}/dns_records?" . http_build_query($params));
    }

    public function verifyDomain(string $domain): array
    {
        $records = $this->listRecords($domain);
        if (!$records['success']) return $records;

        $hasRecord = !empty($records['data']);
        return ['success' => true, 'data' => ['verified' => $hasRecord, 'records' => $records['data']]];
    }

    public function setSslMode(string $mode = 'full'): array
    {
        return $this->request('patch', "zones/{$this->zoneId}/settings/ssl", ['value' => $mode]);
    }
}
