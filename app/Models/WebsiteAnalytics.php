<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebsiteAnalytics extends Model
{
    protected $fillable = [
        'website_id', 'date', 'page_views', 'unique_visitors',
        'bounce_rate', 'avg_session_duration', 'top_page',
        'top_referrer', 'device_breakdown',
    ];

    protected $casts = [
        'date' => 'date',
        'device_breakdown' => 'array',
    ];

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }
}
