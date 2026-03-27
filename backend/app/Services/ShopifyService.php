<?php

namespace App\Services;

use App\Models\Integration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShopifyService
{
    /**
     * Get Shopify API client for a store.
     */
    private function api(Integration $integration): \Illuminate\Http\Client\PendingRequest
    {
        $creds = $integration->credentials ?? [];
        $storeUrl = $creds['store_url'] ?? '';
        $accessToken = $creds['access_token'] ?? '';

        return Http::baseUrl("https://{$storeUrl}/admin/api/2024-01")
            ->withHeaders(['X-Shopify-Access-Token' => $accessToken, 'Content-Type' => 'application/json']);
    }

    // ─── Products ───

    public function listProducts(Integration $integration, int $limit = 50): array
    {
        $res = $this->api($integration)->get('/products.json', ['limit' => $limit]);
        return $res->successful() ? $res->json('products', []) : [];
    }

    public function getProduct(Integration $integration, int $productId): ?array
    {
        $res = $this->api($integration)->get("/products/{$productId}.json");
        return $res->successful() ? $res->json('product') : null;
    }

    public function createProduct(Integration $integration, array $data): ?array
    {
        $res = $this->api($integration)->post('/products.json', ['product' => $data]);
        return $res->successful() ? $res->json('product') : null;
    }

    public function updateProduct(Integration $integration, int $productId, array $data): ?array
    {
        $res = $this->api($integration)->put("/products/{$productId}.json", ['product' => $data]);
        return $res->successful() ? $res->json('product') : null;
    }

    public function deleteProduct(Integration $integration, int $productId): bool
    {
        return $this->api($integration)->delete("/products/{$productId}.json")->successful();
    }

    // ─── Orders ───

    public function listOrders(Integration $integration, int $limit = 50): array
    {
        $res = $this->api($integration)->get('/orders.json', ['limit' => $limit, 'status' => 'any']);
        return $res->successful() ? $res->json('orders', []) : [];
    }

    // ─── Customers ───

    public function listCustomers(Integration $integration, int $limit = 50): array
    {
        $res = $this->api($integration)->get('/customers.json', ['limit' => $limit]);
        return $res->successful() ? $res->json('customers', []) : [];
    }

    // ─── Collections ───

    public function listCollections(Integration $integration): array
    {
        $res = $this->api($integration)->get('/custom_collections.json');
        return $res->successful() ? $res->json('custom_collections', []) : [];
    }

    public function createCollection(Integration $integration, array $data): ?array
    {
        $res = $this->api($integration)->post('/custom_collections.json', ['custom_collection' => $data]);
        return $res->successful() ? $res->json('custom_collection') : null;
    }

    // ─── Themes ───

    public function listThemes(Integration $integration): array
    {
        $res = $this->api($integration)->get('/themes.json');
        return $res->successful() ? $res->json('themes', []) : [];
    }

    // ─── Discount Codes ───

    public function createDiscount(Integration $integration, array $ruleData, string $code): ?array
    {
        $rule = $this->api($integration)->post('/price_rules.json', ['price_rule' => $ruleData]);
        if (!$rule->successful()) return null;

        $ruleId = $rule->json('price_rule.id');
        $discount = $this->api($integration)->post("/price_rules/{$ruleId}/discount_codes.json", [
            'discount_code' => ['code' => $code],
        ]);
        return $discount->successful() ? $discount->json('discount_code') : null;
    }

    // ─── Analytics ───

    public function getAnalytics(Integration $integration): array
    {
        $orders = $this->listOrders($integration);
        $totalRevenue = collect($orders)->sum(fn($o) => (float) ($o['total_price'] ?? 0));
        $totalOrders = count($orders);
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        return [
            'total_revenue' => round($totalRevenue, 2),
            'total_orders' => $totalOrders,
            'avg_order_value' => round($avgOrderValue, 2),
            'total_customers' => count($this->listCustomers($integration)),
            'total_products' => count($this->listProducts($integration)),
        ];
    }

    // ─── AI Store Builder ───

    public function aiGenerateProducts(Integration $integration, string $storeType, int $count = 10): array
    {
        // In production: Call Claude API to generate product data based on store type
        // Then use createProduct() to add each product to Shopify
        $generated = [];
        for ($i = 1; $i <= $count; $i++) {
            $generated[] = [
                'title' => "AI Product {$i} for {$storeType}",
                'body_html' => "<p>Premium quality product for your {$storeType} store.</p>",
                'vendor' => 'WebNewBiz AI',
                'product_type' => $storeType,
                'status' => 'draft',
            ];
        }
        return $generated;
    }
}
