<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    protected $fillable = [
        'user_id', 'name', 'slug', 'business_type', 'url', 'status',
        'ai_prompt', 'ai_theme', 'ai_generated_content',
        'build_step', 'build_log', 'pages',
        'wp_admin_user', 'wp_admin_password', 'wp_admin_email',
        'wp_db_name', 'wp_auto_login_token', 'home_page_id',
        'screenshot_path',
    ];

    protected $casts = [
        'ai_generated_content' => 'array',
        'build_log' => 'array',
        'pages' => 'array',
    ];

    protected $hidden = ['wp_admin_password', 'wp_auto_login_token'];

    public function user() { return $this->belongsTo(User::class); }
    public function backups() { return $this->hasMany(Backup::class); }
    public function domains() { return $this->hasMany(Domain::class); }

    public function isActive(): bool { return $this->status === 'active'; }

    public function scopeActive($query) { return $query->where('status', 'active'); }
    public function scopeForUser($query, $userId) { return $query->where('user_id', $userId); }
}
