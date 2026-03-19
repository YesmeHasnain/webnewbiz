<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Backup extends Model
{
    protected $fillable = [
        'website_id', 'filename', 'size_bytes', 'type', 'notes', 'status',
    ];

    protected $casts = [
        'size_bytes' => 'integer',
    ];

    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function getPathAttribute(): string
    {
        return storage_path("app/backups/{$this->website_id}/{$this->filename}");
    }
}
