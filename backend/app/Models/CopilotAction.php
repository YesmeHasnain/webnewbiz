<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CopilotAction extends Model
{
    protected $fillable = [
        'session_id', 'website_id', 'action_type',
        'action_params', 'before_state', 'result', 'status',
    ];

    protected $casts = [
        'action_params' => 'array',
        'result' => 'array',
    ];

    public function session() { return $this->belongsTo(CopilotSession::class, 'session_id'); }
    public function website() { return $this->belongsTo(Website::class); }
}
