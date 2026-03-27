<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Deployment extends Model
{
    protected $fillable = [
        'user_id', 'deployable_type', 'deployable_id', 'type', 'status',
        'domain', 'subdomain', 'url', 'provider', 'ssl_status',
        'server_ip', 'dns_records', 'env_vars', 'build_log',
        'deployed_at', 'expires_at',
    ];

    protected $casts = [
        'dns_records' => 'array', 'env_vars' => 'array', 'build_log' => 'array',
        'deployed_at' => 'datetime', 'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function deployable(): MorphTo { return $this->morphTo(); }
}
