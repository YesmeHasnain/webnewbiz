<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $fillable = [
        'website_id', 'domain', 'type', 'is_primary', 'cloudflare_record_id',
        'dns_status', 'ssl_status', 'ssl_expires_at', 'verified_at',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'ssl_expires_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function website() { return $this->belongsTo(Website::class); }
    public function sslCertificates() { return $this->hasMany(SslCertificate::class); }
    public function activeCertificate() { return $this->hasOne(SslCertificate::class)->where('status', 'active'); }

    public function isVerified(): bool { return $this->verified_at !== null; }
    public function hasActiveSsl(): bool { return $this->ssl_status === 'active'; }
}
