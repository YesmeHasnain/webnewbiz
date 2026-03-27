<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contact extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'tags' => 'array',
        'custom_fields' => 'array',
        'last_activity_at' => 'datetime',
        'lifetime_value' => 'decimal:2',
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function deals(): HasMany { return $this->hasMany(Deal::class); }
    public function bookings(): HasMany { return $this->hasMany(Booking::class); }
    public function conversations(): HasMany { return $this->hasMany(Conversation::class); }
    public function invoices(): HasMany { return $this->hasMany(InvoiceCrm::class); }

    public function scopeForUser($q, $userId) { return $q->where('user_id', $userId); }

    public function fullName(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
}
