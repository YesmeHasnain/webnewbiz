<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['start_time' => 'datetime', 'end_time' => 'datetime'];

    public function calendar(): BelongsTo { return $this->belongsTo(Calendar::class); }
    public function contact(): BelongsTo { return $this->belongsTo(Contact::class); }
}
