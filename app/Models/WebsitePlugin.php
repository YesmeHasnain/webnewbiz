<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsitePlugin extends Model
{
    protected $fillable = ['website_id', 'slug', 'name', 'version', 'is_active', 'auto_update'];

    protected $casts = ['is_active' => 'boolean', 'auto_update' => 'boolean'];

    public function website() { return $this->belongsTo(Website::class); }
}
