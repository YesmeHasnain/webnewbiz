<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AnalyticsEvent extends Model
{
    protected $fillable = ['user_id', 'trackable_type', 'trackable_id', 'event', 'source', 'country', 'device', 'browser', 'metadata', 'occurred_at'];
    protected $casts = ['metadata' => 'array', 'occurred_at' => 'datetime'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function trackable(): MorphTo { return $this->morphTo(); }
}
