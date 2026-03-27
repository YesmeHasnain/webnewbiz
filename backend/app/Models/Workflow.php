<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workflow extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['trigger_config' => 'array'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function steps(): HasMany { return $this->hasMany(WorkflowStep::class)->orderBy('step_order'); }
    public function logs(): HasMany { return $this->hasMany(WorkflowLog::class); }
    public function scopeForUser($q, $userId) { return $q->where('user_id', $userId); }
}
