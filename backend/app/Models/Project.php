<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Project extends Model
{
    protected $fillable = [
        'user_id', 'name', 'slug', 'description', 'framework',
        'status', 'ai_prompt', 'file_tree',
    ];

    protected $casts = [
        'file_tree' => 'array',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function messages() { return $this->hasMany(ProjectMessage::class); }

    public function scopeForUser($query, $userId) { return $query->where('user_id', $userId); }

    public function storagePath(): string
    {
        return storage_path("app/projects/{$this->id}");
    }

    public static function generateSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;
        while (static::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }
        return $slug;
    }
}
