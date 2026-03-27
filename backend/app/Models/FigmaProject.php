<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FigmaProject extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['last_synced_at' => 'datetime'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function exports(): HasMany { return $this->hasMany(FigmaExport::class); }
}
