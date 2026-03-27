<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkflowLog extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['executed_at' => 'datetime'];

    public function workflow(): BelongsTo { return $this->belongsTo(Workflow::class); }
    public function contact(): BelongsTo { return $this->belongsTo(Contact::class); }
}
