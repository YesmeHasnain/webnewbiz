<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Calendar extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['availability' => 'array'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function bookings(): HasMany { return $this->hasMany(Booking::class); }
    public function scopeForUser($q, $userId) { return $q->where('user_id', $userId); }
}
