<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Backup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class BackupController extends Controller
{
    public function index(Request $request, $websiteId)
    {
        $website = $request->user()->websites()->findOrFail($websiteId);
        return response()->json($website->backups()->orderByDesc('created_at')->get());
    }

    public function store(Request $request, $websiteId)
    {
        $website = $request->user()->websites()->findOrFail($websiteId);
        $notes = $request->input('notes', '');

        $sitePath = config('webnewbiz.xampp_htdocs', 'C:\\xampp\\htdocs') . DIRECTORY_SEPARATOR . $website->slug;
        if (!is_dir($sitePath)) {
            return response()->json(['error' => 'Site directory not found'], 404);
        }

        $timestamp = now()->format('Y-m-d_His');
        $filename = "{$timestamp}.zip";
        $backupDir = storage_path("app/backups/{$website->id}");

        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $zipPath = "{$backupDir}/{$filename}";

        try {
            // SQL dump
            $dumpPath = "{$backupDir}/{$timestamp}.sql";
            $mysqlBin = config('webnewbiz.mysql_bin', 'C:\\xampp\\mysql\\bin');
            $dbName = $website->wp_db_name;

            $cmd = "\"{$mysqlBin}\\mysqldump.exe\" --user=root --port=3306 {$dbName} > \"{$dumpPath}\" 2>&1";
            exec($cmd, $output, $retval);

            if ($retval !== 0) {
                Log::warning("mysqldump failed for {$dbName}: " . implode("\n", $output));
            }

            // Create ZIP
            $zip = new ZipArchive();
            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                return response()->json(['error' => 'Failed to create backup archive'], 500);
            }

            // Add SQL dump
            if (file_exists($dumpPath)) {
                $zip->addFile($dumpPath, 'database.sql');
            }

            // Add site files (skip wp-content/cache and large dirs)
            $this->addDirectoryToZip($zip, $sitePath, '', [
                'wp-content/cache',
                'wp-content/uploads/elementor/css',
            ]);

            $zip->close();

            // Clean up temp SQL
            if (file_exists($dumpPath)) {
                unlink($dumpPath);
            }

            $sizeBytes = file_exists($zipPath) ? filesize($zipPath) : 0;

            $backup = $website->backups()->create([
                'filename'   => $filename,
                'size_bytes' => $sizeBytes,
                'type'       => 'manual',
                'notes'      => $notes,
                'status'     => 'completed',
            ]);

            return response()->json($backup, 201);
        } catch (\Exception $e) {
            Log::error("Backup failed for {$website->slug}: {$e->getMessage()}");
            // Clean up partial files
            if (isset($dumpPath) && file_exists($dumpPath)) unlink($dumpPath);
            if (file_exists($zipPath)) unlink($zipPath);
            return response()->json(['error' => 'Backup failed: ' . $e->getMessage()], 500);
        }
    }

    public function download(Request $request, $websiteId, $backupId)
    {
        $website = $request->user()->websites()->findOrFail($websiteId);
        $backup = $website->backups()->findOrFail($backupId);

        if (!file_exists($backup->path)) {
            return response()->json(['error' => 'Backup file not found'], 404);
        }

        return response()->download($backup->path, "{$website->slug}_{$backup->filename}");
    }

    public function restore(Request $request, $websiteId, $backupId)
    {
        $website = $request->user()->websites()->findOrFail($websiteId);
        $backup = $website->backups()->findOrFail($backupId);

        if (!file_exists($backup->path)) {
            return response()->json(['error' => 'Backup file not found'], 404);
        }

        $sitePath = config('webnewbiz.xampp_htdocs', 'C:\\xampp\\htdocs') . DIRECTORY_SEPARATOR . $website->slug;
        $tempDir = storage_path("app/backups/{$website->id}/restore_temp");

        try {
            // Extract ZIP
            $zip = new ZipArchive();
            if ($zip->open($backup->path) !== true) {
                return response()->json(['error' => 'Failed to open backup'], 500);
            }
            $zip->extractTo($tempDir);
            $zip->close();

            // Restore SQL
            $sqlFile = "{$tempDir}/database.sql";
            if (file_exists($sqlFile)) {
                $mysqlBin = config('webnewbiz.mysql_bin', 'C:\\xampp\\mysql\\bin');
                $dbName = $website->wp_db_name;
                $cmd = "\"{$mysqlBin}\\mysql.exe\" --user=root --port=3306 {$dbName} < \"{$sqlFile}\" 2>&1";
                exec($cmd, $output, $retval);
                unlink($sqlFile);
            }

            // Restore files (robocopy on Windows)
            if (PHP_OS_FAMILY === 'Windows') {
                $cmd = "robocopy \"{$tempDir}\" \"{$sitePath}\" /MIR /XD restore_temp /NFL /NDL /NJH /NJS 2>&1";
            } else {
                $cmd = "rsync -a --delete \"{$tempDir}/\" \"{$sitePath}/\" 2>&1";
            }
            exec($cmd);

            // Clean up temp
            $this->deleteDirectory($tempDir);

            return response()->json(['success' => true, 'message' => 'Backup restored']);
        } catch (\Exception $e) {
            if (is_dir($tempDir)) $this->deleteDirectory($tempDir);
            return response()->json(['error' => 'Restore failed: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request, $websiteId, $backupId)
    {
        $website = $request->user()->websites()->findOrFail($websiteId);
        $backup = $website->backups()->findOrFail($backupId);

        if (file_exists($backup->path)) {
            unlink($backup->path);
        }

        $backup->delete();

        return response()->json(['success' => true]);
    }

    private function addDirectoryToZip(ZipArchive $zip, string $rootPath, string $relativePath, array $exclude = []): void
    {
        $fullPath = $relativePath ? "{$rootPath}/{$relativePath}" : $rootPath;

        foreach (scandir($fullPath) as $item) {
            if ($item === '.' || $item === '..') continue;

            $itemRelative = $relativePath ? "{$relativePath}/{$item}" : $item;
            $itemFull = "{$fullPath}/{$item}";

            // Check exclusions
            foreach ($exclude as $ex) {
                if (str_starts_with(str_replace('\\', '/', $itemRelative), $ex)) {
                    continue 2;
                }
            }

            if (is_dir($itemFull)) {
                $zip->addEmptyDir($itemRelative);
                $this->addDirectoryToZip($zip, $rootPath, $itemRelative, $exclude);
            } else {
                $zip->addFile($itemFull, $itemRelative);
            }
        }
    }

    private function deleteDirectory(string $dir): void
    {
        if (!is_dir($dir)) return;
        foreach (scandir($dir) as $item) {
            if ($item === '.' || $item === '..') continue;
            $path = "{$dir}/{$item}";
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }
        rmdir($dir);
    }
}
