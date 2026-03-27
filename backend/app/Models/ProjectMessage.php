<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectMessage extends Model
{
    protected $fillable = [
        'project_id', 'role', 'content', 'files_changed',
    ];

    protected $casts = [
        'files_changed' => 'array',
    ];

    public function project() { return $this->belongsTo(Project::class); }
}
