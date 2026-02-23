<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteTheme extends Model
{
    protected $fillable = ['website_id', 'slug', 'name', 'version', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function website() { return $this->belongsTo(Website::class); }
}
