<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmailCampaign extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['stats' => 'array', 'scheduled_at' => 'datetime', 'sent_at' => 'datetime'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function sends(): HasMany { return $this->hasMany(EmailSend::class, 'campaign_id'); }
    public function scopeForUser($q, $userId) { return $q->where('user_id', $userId); }
}
