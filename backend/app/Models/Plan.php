<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'slug', 'name', 'description', 'price_monthly', 'price_yearly',
        'credits_monthly', 'features', 'stripe_price_monthly', 'stripe_price_yearly',
        'is_active', 'sort_order',
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
    ];
}
