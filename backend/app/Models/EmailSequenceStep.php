<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailSequenceStep extends Model
{
    protected $guarded = ['id'];

    public function sequence(): BelongsTo { return $this->belongsTo(EmailSequence::class, 'email_sequence_id'); }
}
