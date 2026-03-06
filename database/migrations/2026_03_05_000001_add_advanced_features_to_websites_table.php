<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('websites', function (Blueprint $table) {
            $table->text('custom_css')->nullable()->after('ai_generated_content');
            $table->text('custom_js')->nullable()->after('custom_css');
            $table->integer('seo_score')->nullable()->after('custom_js');
            $table->integer('health_score')->nullable()->after('seo_score');
            $table->json('health_details')->nullable()->after('health_score');
            $table->json('ai_suggestions')->nullable()->after('health_details');
            $table->timestamp('last_analyzed_at')->nullable()->after('ai_suggestions');
        });

        Schema::create('website_seo_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained()->onDelete('cascade');
            $table->string('page_slug');
            $table->string('meta_title', 200)->nullable();
            $table->text('meta_description')->nullable();
            $table->json('meta_keywords')->nullable();
            $table->string('og_title', 200)->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->json('schema_markup')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('robots', 50)->default('index,follow');
            $table->timestamps();

            $table->unique(['website_id', 'page_slug']);
        });

        Schema::create('website_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->integer('page_views')->default(0);
            $table->integer('unique_visitors')->default(0);
            $table->integer('bounce_rate')->default(0);
            $table->float('avg_session_duration')->default(0);
            $table->string('top_page')->nullable();
            $table->string('top_referrer')->nullable();
            $table->json('device_breakdown')->nullable();
            $table->timestamps();

            $table->unique(['website_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_analytics');
        Schema::dropIfExists('website_seo_data');

        Schema::table('websites', function (Blueprint $table) {
            $table->dropColumn([
                'custom_css', 'custom_js', 'seo_score', 'health_score',
                'health_details', 'ai_suggestions', 'last_analyzed_at',
            ]);
        });
    }
};
