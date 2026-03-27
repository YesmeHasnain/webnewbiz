<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmailSequence extends Model
{
    protected $guarded = ['id'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function steps(): HasMany { return $this->hasMany(EmailSequenceStep::class)->orderBy('step_order'); }
    public function scopeForUser($q, $userId) { return $q->where('user_id', $userId); }
}
