<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 'loggable_type', 'loggable_id', 'action',
        'description', 'properties', 'ip_address', 'user_agent',
    ];

    protected $casts = ['properties' => 'array'];

    public function user() { return $this->belongsTo(User::class); }

    public function loggable() { return $this->morphTo(); }

    public static function log(string $action, ?string $description = null, $loggable = null, ?array $properties = null): self
    {
        return self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
            'loggable_type' => $loggable ? get_class($loggable) : null,
            'loggable_id' => $loggable?->id,
            'properties' => $properties,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
