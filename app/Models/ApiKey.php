<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ApiKey extends Model
{
    protected $fillable = ['user_id', 'name', 'key', 'permissions', 'last_used_at', 'expires_at', 'is_active'];

    protected $casts = [
        'permissions' => 'array',
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected $hidden = ['key'];

    public function user() { return $this->belongsTo(User::class); }

    public static function generateKey(): string
    {
        return 'wnb_' . Str::random(60);
    }

    public function isValid(): bool
    {
        return $this->is_active && (!$this->expires_at || $this->expires_at->isFuture());
    }
}
