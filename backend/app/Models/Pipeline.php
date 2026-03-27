<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pipeline extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['stages' => 'array'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function deals(): HasMany { return $this->hasMany(Deal::class); }
    public function scopeForUser($q, $userId) { return $q->where('user_id', $userId); }
}
