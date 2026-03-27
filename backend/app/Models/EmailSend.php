<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailSend extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['opened_at' => 'datetime', 'clicked_at' => 'datetime'];

    public function contact(): BelongsTo { return $this->belongsTo(Contact::class); }
}
