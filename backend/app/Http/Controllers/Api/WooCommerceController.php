<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Website;
use App\Services\WpBridgeService;
use Illuminate\Http\Request;

class WooCommerceController extends Controller
{
    public function __construct(private WpBridgeService $bridge) {}

    // ─── Products ───

    public function products(Request $request, $websiteId)
    {
        $website = $request->user()->websites()->findOrFail($websiteId);
        return response()->json($this->bridge->listProducts($website, $request->only([
            'page', 'per_page', 'search', 'status', 'category',
        ])));
    }

    public function showProduct(Request $request, $websiteId, $productId)
    {
        $website = $request->user()->websites()->findOrFail($websiteId);
        return response()->json($this->bridge->getProduct($website, (int) $productId));
    }

    public function createProduct(Request $request, $websiteId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'regular_price' => 'required|string',
        ]);

        $website = $request->user()->websites()->findOrFail($websiteId);
        $data = $request->only([
            'name', 'description', 'short_description',
            'regular_price', 'sale_price', 'sku',
            'stock_status', 'stock_quantity', 'weight',
            'virtual', 'status', 'category_ids', 'image_url',
        ]);

        // Handle file upload → store temporarily and pass URL to bridge
        if ($request->hasFile('image')) {
            $data['image_url'] = $this->storeUploadedImage($request, $website);
        }

        return response()->json($this->bridge->createProduct($website, $data));
    }

    public function updateProduct(Request $request, $websiteId, $productId)
    {
        $website = $request->user()->websites()->findOrFail($websiteId);
        $data = $request->only([
            'name', 'description', 'short_description',
            'regular_price', 'sale_price', 'sku',
            'stock_status', 'stock_quantity', 'weight',
            'virtual', 'status', 'category_ids', 'image_url',
        ]);

        if ($request->hasFile('image')) {
            $data['image_url'] = $this->storeUploadedImage($request, $website);
        }

        return response()->json($this->bridge->updateProduct($website, (int) $productId, $data));
    }

    private function storeUploadedImage(Request $request, Website $website): string
    {
        $file = $request->file('image');
        $filename = uniqid('prod_') . '.' . $file->getClientOriginalExtension();

        // Copy directly to WP site's uploads folder so no network download needed
        $wpUploadsDir = 'C:\\xampp\\htdocs\\' . $website->slug . '\\wp-content\\uploads';
        $subDir = date('Y/m');
        $destDir = $wpUploadsDir . '\\' . $subDir;
        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }
        $file->move($destDir, $filename);

        return rtrim($website->url, '/') . '/wp-content/uploads/' . $subDir . '/' . $filename;
    }

    public function deleteProduct(Request $request, $websiteId, $productId)
    {
        $website = $request->user()->websites()->findOrFail($websiteId);
        $force = $request->boolean('force', false);
        return response()->json($this->bridge->deleteProduct($website, (int) $productId, $force));
    }

    // ─── Orders ───

    public function orders(Request $request, $websiteId)
    {
        $website = $request->user()->websites()->findOrFail($websiteId);
        return response()->json($this->bridge->listOrders($website, $request->only([
            'page', 'per_page', 'status',
        ])));
    }

    // ─── Categories ───

    public function categories(Request $request, $websiteId)
    {
        $website = $request->user()->websites()->findOrFail($websiteId);
        return response()->json($this->bridge->listProductCategories($website));
    }
}
