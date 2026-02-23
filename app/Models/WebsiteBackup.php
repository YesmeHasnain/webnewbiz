<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteBackup extends Model
{
    protected $fillable = [
        'website_id', 'type', 'status', 'file_path', 'file_size', 'notes', 'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function website() { return $this->belongsTo(Website::class); }

    public function getFormattedSizeAttribute(): string
    {
        if (!$this->file_size) return 'N/A';
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $i = 0;
        while ($size >= 1024 && $i < count($units) - 1) { $size /= 1024; $i++; }
        return round($size, 2) . ' ' . $units[$i];
    }
}
