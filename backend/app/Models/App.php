<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class App extends Model
{
    protected $fillable = [
        'user_id', 'name', 'slug', 'description', 'framework', 'status',
        'app_icon', 'bundle_id', 'version', 'file_tree', 'platforms',
        'build_config', 'expo_project_id', 'last_build_id',
        'ios_build_url', 'android_build_url',
    ];

    protected $casts = [
        'file_tree' => 'array',
        'platforms' => 'array',
        'build_config' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(AppMessage::class);
    }

    public static function generateSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;
        while (static::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }

    public function storagePath(): string
    {
        return storage_path('app/apps/' . $this->id);
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}
