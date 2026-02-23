<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    protected $fillable = [
        'user_id', 'server_id', 'name', 'subdomain', 'custom_domain', 'status',
        'wp_admin_user', 'wp_admin_password', 'wp_admin_email',
        'wp_db_name', 'wp_db_user', 'wp_db_password', 'wp_version', 'php_version',
        'ai_prompt', 'ai_business_type', 'ai_style', 'ai_generated_content',
        'screenshot_path', 'storage_used_mb', 'bandwidth_used_mb',
        'suspended_at', 'suspension_reason',
    ];

    protected $casts = [
        'ai_generated_content' => 'array',
        'suspended_at' => 'datetime',
    ];

    protected $hidden = ['wp_admin_password', 'wp_db_password'];

    public function user() { return $this->belongsTo(User::class); }
    public function server() { return $this->belongsTo(Server::class); }
    public function backups() { return $this->hasMany(WebsiteBackup::class); }
    public function plugins() { return $this->hasMany(WebsitePlugin::class); }
    public function themes() { return $this->hasMany(WebsiteTheme::class); }
    public function domains() { return $this->hasMany(Domain::class); }

    public function primaryDomain()
    {
        return $this->hasOne(Domain::class)->where('is_primary', true);
    }

    public function getUrlAttribute(): string
    {
        $domain = $this->custom_domain ?: $this->subdomain . config('webnewbiz.subdomain_suffix');
        return 'https://' . $domain;
    }

    public function isActive(): bool { return $this->status === 'active'; }

    public function scopeActive($query) { return $query->where('status', 'active'); }
    public function scopeForUser($query, $userId) { return $query->where('user_id', $userId); }
}
