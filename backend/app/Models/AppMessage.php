<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppMessage extends Model
{
    protected $fillable = ['app_id', 'role', 'content', 'files_changed'];

    protected $casts = ['files_changed' => 'array'];

    public function app(): BelongsTo
    {
        return $this->belongsTo(App::class);
    }
}
