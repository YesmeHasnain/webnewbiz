<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreSubmission extends Model
{
    protected $fillable = ['user_id', 'app_id', 'store', 'status', 'app_name', 'description', 'category', 'screenshots', 'build_url', 'store_url', 'review_notes', 'submitted_at', 'approved_at'];
    protected $casts = ['screenshots' => 'array', 'review_notes' => 'array', 'submitted_at' => 'datetime', 'approved_at' => 'datetime'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function app(): BelongsTo { return $this->belongsTo(App::class); }
}
