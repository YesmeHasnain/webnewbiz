<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebsiteSeoData extends Model
{
    protected $table = 'website_seo_data';

    protected $fillable = [
        'website_id', 'page_slug', 'meta_title', 'meta_description',
        'meta_keywords', 'og_title', 'og_description', 'og_image',
        'schema_markup', 'canonical_url', 'robots',
    ];

    protected $casts = [
        'meta_keywords' => 'array',
        'schema_markup' => 'array',
    ];

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }
}
