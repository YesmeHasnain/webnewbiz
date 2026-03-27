<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Deal extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['expected_close' => 'date', 'value' => 'decimal:2'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function pipeline(): BelongsTo { return $this->belongsTo(Pipeline::class); }
    public function contact(): BelongsTo { return $this->belongsTo(Contact::class); }
    public function activities(): HasMany { return $this->hasMany(DealActivity::class); }
    public function scopeForUser($q, $userId) { return $q->where('user_id', $userId); }
}
