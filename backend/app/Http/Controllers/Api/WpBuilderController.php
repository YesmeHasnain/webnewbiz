<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WpBridgeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WpBuilderController extends Controller
{
    public function __construct(private WpBridgeService $bridge) {}

    private function website(Request $request, $websiteId)
    {
        return $request->user()->websites()->findOrFail($websiteId);
    }

    // Dashboard
    public function dashboard(Request $request, $websiteId): JsonResponse
    {
        $data = $this->bridge->wnbDashboard($this->website($request, $websiteId));
        return response()->json($data['data'] ?? $data);
    }

    // Analytics
    public function analytics(Request $request, $websiteId): JsonResponse
    {
        $period = $request->query('period', '7days');
        $data = $this->bridge->wnbAnalytics($this->website($request, $websiteId), $period);
        return response()->json($data['data'] ?? $data);
    }

    // Performance
    public function performanceGet(Request $request, $websiteId): JsonResponse
    {
        $data = $this->bridge->wnbPerformanceGet($this->website($request, $websiteId));
        return response()->json($data['data'] ?? $data);
    }

    public function performanceSave(Request $request, $websiteId): JsonResponse
    {
        $data = $this->bridge->wnbPerformanceSave($this->website($request, $websiteId), $request->all());
        return response()->json($data['data'] ?? $data);
    }

    // Cache
    public function cacheStats(Request $request, $websiteId): JsonResponse
    {
        $data = $this->bridge->wnbCacheStats($this->website($request, $websiteId));
        return response()->json($data['data'] ?? $data);
    }

    public function cachePurge(Request $request, $websiteId): JsonResponse
    {
        $request->validate(['type' => 'required|string']);
        $data = $this->bridge->wnbCachePurge($this->website($request, $websiteId), $request->input('type'));
        return response()->json($data);
    }

    public function cacheSettings(Request $request, $websiteId): JsonResponse
    {
        $data = $this->bridge->wnbCacheSettings($this->website($request, $websiteId), $request->all());
        return response()->json($data['data'] ?? $data);
    }

    // Security
    public function securityGet(Request $request, $websiteId): JsonResponse
    {
        $data = $this->bridge->wnbSecurityGet($this->website($request, $websiteId));
        return response()->json($data['data'] ?? $data);
    }

    public function securitySave(Request $request, $websiteId): JsonResponse
    {
        $data = $this->bridge->wnbSecuritySave($this->website($request, $websiteId), $request->all());
        return response()->json($data['data'] ?? $data);
    }

    // Backups
    public function backupList(Request $request, $websiteId): JsonResponse
    {
        $data = $this->bridge->wnbBackupList($this->website($request, $websiteId));
        return response()->json($data['data'] ?? $data);
    }

    public function backupCreate(Request $request, $websiteId): JsonResponse
    {
        $request->validate(['type' => 'required|in:full,database,files']);
        $data = $this->bridge->wnbBackupCreate($this->website($request, $websiteId), $request->input('type'));
        return response()->json($data['data'] ?? $data);
    }

    public function backupDelete(Request $request, $websiteId, $backupId): JsonResponse
    {
        $data = $this->bridge->wnbBackupDelete($this->website($request, $websiteId), $backupId);
        return response()->json($data['data'] ?? $data);
    }

    public function backupRestore(Request $request, $websiteId, $backupId): JsonResponse
    {
        $data = $this->bridge->wnbBackupRestore($this->website($request, $websiteId), $backupId);
        return response()->json($data['data'] ?? $data);
    }

    // Database
    public function databaseStats(Request $request, $websiteId): JsonResponse
    {
        $data = $this->bridge->wnbDatabaseStats($this->website($request, $websiteId));
        return response()->json($data['data'] ?? $data);
    }

    public function databaseCleanup(Request $request, $websiteId): JsonResponse
    {
        $request->validate(['type' => 'required|string']);
        $data = $this->bridge->wnbDatabaseCleanup($this->website($request, $websiteId), $request->input('type'));
        return response()->json($data['data'] ?? $data);
    }

    public function databaseOptimize(Request $request, $websiteId): JsonResponse
    {
        $data = $this->bridge->wnbDatabaseOptimize($this->website($request, $websiteId));
        return response()->json($data['data'] ?? $data);
    }

    // Maintenance
    public function maintenanceGet(Request $request, $websiteId): JsonResponse
    {
        $data = $this->bridge->wnbMaintenanceGet($this->website($request, $websiteId));
        return response()->json($data['data'] ?? $data);
    }

    public function maintenanceToggle(Request $request, $websiteId): JsonResponse
    {
        $request->validate(['enabled' => 'required|boolean']);
        $data = $this->bridge->wnbMaintenanceToggle($this->website($request, $websiteId), $request->boolean('enabled'));
        return response()->json($data['data'] ?? $data);
    }

    public function maintenanceSave(Request $request, $websiteId): JsonResponse
    {
        $data = $this->bridge->wnbMaintenanceSave($this->website($request, $websiteId), $request->all());
        return response()->json($data['data'] ?? $data);
    }

    // Images
    public function imagesStats(Request $request, $websiteId): JsonResponse
    {
        $data = $this->bridge->wnbImagesStats($this->website($request, $websiteId));
        return response()->json($data['data'] ?? $data);
    }

    public function imagesOptimize(Request $request, $websiteId): JsonResponse
    {
        $limit = $request->input('limit', 10);
        $data = $this->bridge->wnbImagesOptimize($this->website($request, $websiteId), (int) $limit);
        return response()->json($data['data'] ?? $data);
    }

    public function imagesSettings(Request $request, $websiteId): JsonResponse
    {
        $data = $this->bridge->wnbImagesSettings($this->website($request, $websiteId), $request->all());
        return response()->json($data['data'] ?? $data);
    }

    // SEO
    public function seoGet(Request $request, $websiteId): JsonResponse
    {
        $data = $this->bridge->wnbSeoGet($this->website($request, $websiteId));
        return response()->json($data['data'] ?? $data);
    }

    public function seoSave(Request $request, $websiteId): JsonResponse
    {
        $data = $this->bridge->wnbSeoSave($this->website($request, $websiteId), $request->all());
        return response()->json($data['data'] ?? $data);
    }

    public function seoRedirectAdd(Request $request, $websiteId): JsonResponse
    {
        $request->validate(['from' => 'required|string', 'to' => 'required|string']);
        $data = $this->bridge->wnbSeoRedirectAdd(
            $this->website($request, $websiteId),
            $request->input('from'),
            $request->input('to')
        );
        return response()->json($data['data'] ?? $data);
    }

    public function seoRedirectDelete(Request $request, $websiteId): JsonResponse
    {
        $request->validate(['from' => 'required|string']);
        $data = $this->bridge->wnbSeoRedirectDelete($this->website($request, $websiteId), $request->input('from'));
        return response()->json($data['data'] ?? $data);
    }

    public function seoSitemap(Request $request, $websiteId): JsonResponse
    {
        $data = $this->bridge->wnbSeoSitemap($this->website($request, $websiteId));
        return response()->json($data['data'] ?? $data);
    }

    public function seoRobots(Request $request, $websiteId): JsonResponse
    {
        $request->validate(['content' => 'required|string']);
        $data = $this->bridge->wnbSeoRobots($this->website($request, $websiteId), $request->input('content'));
        return response()->json($data['data'] ?? $data);
    }

    // AI
    public function aiGenerate(Request $request, $websiteId): JsonResponse
    {
        $request->validate(['prompt' => 'required|string|min:5', 'type' => 'required|string']);
        $data = $this->bridge->wnbAiGenerate($this->website($request, $websiteId), $request->only(['type', 'prompt', 'tone', 'length', 'language']));
        return response()->json($data['data'] ?? $data);
    }

    public function aiHistory(Request $request, $websiteId): JsonResponse
    {
        $action = $request->input('action', 'get');
        $data = $this->bridge->wnbAiHistory($this->website($request, $websiteId), $action);
        return response()->json($data['data'] ?? $data);
    }
}
