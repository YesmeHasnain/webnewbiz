<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Integration extends Model
{
    protected $fillable = ['user_id', 'platform', 'store_name', 'store_url', 'api_key', 'api_secret', 'access_token', 'status', 'settings'];
    protected $casts = ['settings' => 'array'];
    protected $hidden = ['api_key', 'api_secret', 'access_token'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
