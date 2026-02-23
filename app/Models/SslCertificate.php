<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SslCertificate extends Model
{
    protected $fillable = [
        'domain_id', 'provider', 'status', 'certificate_path', 'private_key_path',
        'chain_path', 'issued_at', 'expires_at', 'last_renewal_at', 'error_message',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'expires_at' => 'datetime',
        'last_renewal_at' => 'datetime',
    ];

    public function domain() { return $this->belongsTo(Domain::class); }

    public function isExpiringSoon(): bool
    {
        return $this->expires_at && $this->expires_at->diffInDays(now()) < 14;
    }
}
