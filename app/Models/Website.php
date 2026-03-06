<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    protected $fillable = [
        'user_id', 'server_id', 'name', 'subdomain', 'custom_domain', 'url', 'status',
        'wp_admin_user', 'wp_admin_password', 'wp_admin_email',
        'wp_db_name', 'wp_db_user', 'wp_db_password', 'wp_version', 'php_version',
        'wp_auto_login_token',
        'ai_prompt', 'ai_business_type', 'ai_style', 'ai_theme', 'ai_generated_content',
        'custom_css', 'custom_js',
        'seo_score', 'health_score', 'health_details', 'ai_suggestions', 'last_analyzed_at',
        'screenshot_path', 'storage_used_mb', 'bandwidth_used_mb',
        'suspended_at', 'suspension_reason',
    ];

    protected $casts = [
        'ai_generated_content' => 'array',
        'health_details' => 'array',
        'ai_suggestions' => 'array',
        'suspended_at' => 'datetime',
        'last_analyzed_at' => 'datetime',
    ];

    protected $hidden = ['wp_admin_password', 'wp_db_password', 'wp_auto_login_token'];

    /**
     * Get the auto-login URL for WP Admin.
     * Falls back to wp-login.php if no auto-login token exists.
     */
    public function getWpAdminAutoLoginUrl(): string
    {
        if ($this->wp_auto_login_token) {
            $token = decrypt($this->wp_auto_login_token);
            return $this->url . '/wp-auto-login.php?token=' . urlencode($token);
        }

        // Fallback: direct wp-login.php URL
        return $this->url . '/wp-login.php';
    }

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
        // Use stored URL if available (local XAMPP mode stores http://localhost/subdomain)
        $stored = $this->getRawOriginal('url');
        if ($stored) {
            return $stored;
        }

        $domain = $this->custom_domain ?: $this->subdomain . config('webnewbiz.subdomain_suffix');
        return 'https://' . $domain;
    }

    public function isActive(): bool { return $this->status === 'active'; }

    public function seoData() { return $this->hasMany(WebsiteSeoData::class); }
    public function chatMessages() { return $this->hasMany(ChatMessage::class); }
    public function analytics() { return $this->hasMany(WebsiteAnalytics::class); }

    public function scopeActive($query) { return $query->where('status', 'active'); }
    public function scopeForUser($query, $userId) { return $query->where('user_id', $userId); }
}
