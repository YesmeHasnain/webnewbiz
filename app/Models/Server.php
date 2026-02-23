<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    protected $fillable = [
        'name', 'provider', 'provider_id', 'ip_address', 'region', 'size', 'image',
        'status', 'ssh_private_key', 'ssh_public_key', 'ssh_key_fingerprint', 'ssh_port',
        'max_websites', 'current_websites', 'cpu_usage', 'memory_usage', 'disk_usage',
        'last_health_check', 'metadata',
    ];

    protected $casts = [
        'last_health_check' => 'datetime',
        'metadata' => 'array',
        'cpu_usage' => 'float',
        'memory_usage' => 'float',
        'disk_usage' => 'float',
    ];

    protected $hidden = ['ssh_private_key'];

    public function websites() { return $this->hasMany(Website::class); }

    public function hasCapacity(): bool
    {
        return $this->current_websites < $this->max_websites;
    }

    public function isHealthy(): bool
    {
        return $this->status === 'active' && $this->cpu_usage < 90 && $this->memory_usage < 90;
    }

    public function scopeActive($query) { return $query->where('status', 'active'); }
    public function scopeWithCapacity($query) { return $query->whereColumn('current_websites', '<', 'max_websites'); }
}
