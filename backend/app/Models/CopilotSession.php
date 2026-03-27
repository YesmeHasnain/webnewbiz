<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CopilotSession extends Model
{
    protected $fillable = ['website_id', 'user_id'];

    public function website() { return $this->belongsTo(Website::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function actions() { return $this->hasMany(CopilotAction::class, 'session_id'); }
}
