<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Website;
use App\Models\WebsiteBackup;
use App\Services\BackupService;
use Illuminate\Http\Request;

class BackupController extends Controller
{
    public function __construct(private BackupService $backupService) {}

    public function index(Request $request, Website $website)
    {
        if ($website->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $result = $this->backupService->listBackups($website);
        return response()->json($result);
    }

    public function store(Request $request, Website $website)
    {
        if ($website->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'type' => 'sometimes|in:full,database,files',
        ]);

        $result = $this->backupService->createBackup($website, $validated['type'] ?? 'full');

        return response()->json($result, $result['success'] ? 201 : 422);
    }

    public function restore(Request $request, Website $website, WebsiteBackup $backup)
    {
        if ($website->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $result = $this->backupService->restoreBackup($backup);
        return response()->json($result);
    }

    public function destroy(Request $request, Website $website, WebsiteBackup $backup)
    {
        if ($website->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $result = $this->backupService->deleteBackup($backup);
        return response()->json($result);
    }

    public function download(Request $request, Website $website, WebsiteBackup $backup)
    {
        if ($website->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if (!$backup->file_path || $backup->status !== 'completed') {
            return response()->json(['success' => false, 'message' => 'Backup not available'], 404);
        }

        // In production, this would stream from the server
        return response()->json([
            'success' => true,
            'data' => ['download_url' => "Server file: {$backup->file_path}", 'size' => $backup->formatted_size],
        ]);
    }
}
