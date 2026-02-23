<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'price', 'billing_cycle',
        'max_websites', 'storage_gb', 'bandwidth_gb', 'custom_domain',
        'ssl_included', 'backup_included', 'priority_support',
        'features', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'custom_domain' => 'boolean',
        'ssl_included' => 'boolean',
        'backup_included' => 'boolean',
        'priority_support' => 'boolean',
        'features' => 'array',
        'is_active' => 'boolean',
    ];

    public function subscriptions() { return $this->hasMany(Subscription::class); }

    public function activeSubscribers() { return $this->subscriptions()->where('status', 'active'); }

    public function scopeActive($query) { return $query->where('is_active', true); }
    public function scopeOrdered($query) { return $query->orderBy('sort_order'); }
}
