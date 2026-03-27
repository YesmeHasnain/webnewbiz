<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MessageCrm extends Model
{
    protected $table = 'messages_crm';
    protected $guarded = ['id'];
    protected $casts = ['read_at' => 'datetime'];

    public function conversation(): BelongsTo { return $this->belongsTo(Conversation::class); }
}
