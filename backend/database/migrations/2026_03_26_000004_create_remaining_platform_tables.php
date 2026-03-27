<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Step 6: Universal Converter
        Schema::create('conversions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('input_type'); // image, code, figma, url
            $table->string('output_type'); // wordpress, react, shopify, react-native, html
            $table->string('status')->default('pending'); // pending, converting, done, failed
            $table->text('input_data')->nullable(); // URL, file path, or code snippet
            $table->json('output_files')->nullable();
            $table->integer('project_id')->nullable(); // resulting project
            $table->timestamps();
        });

        // Step 7: App Store Submissions
        Schema::create('store_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('app_id')->constrained()->onDelete('cascade');
            $table->string('store'); // appstore, playstore
            $table->string('status')->default('preparing'); // preparing, submitted, in_review, approved, rejected
            $table->string('app_name');
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->json('screenshots')->nullable();
            $table->string('build_url')->nullable();
            $table->string('store_url')->nullable(); // after approval
            $table->json('review_notes')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        // Step 8: Analytics
        Schema::create('analytics_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('trackable'); // website, app, deployment
            $table->string('event'); // pageview, click, download, install, purchase
            $table->string('source')->nullable(); // direct, google, social, referral
            $table->string('country')->nullable();
            $table->string('device')->nullable(); // desktop, mobile, tablet
            $table->string('browser')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('occurred_at');
            $table->timestamps();
        });

        // Step 9: Shopify/Squarespace integrations
        Schema::create('integrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('platform'); // shopify, squarespace, woocommerce
            $table->string('store_name')->nullable();
            $table->string('store_url')->nullable();
            $table->string('api_key')->nullable();
            $table->string('api_secret')->nullable();
            $table->string('access_token')->nullable();
            $table->string('status')->default('connected'); // connected, disconnected, error
            $table->json('settings')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('integrations');
        Schema::dropIfExists('analytics_events');
        Schema::dropIfExists('store_submissions');
        Schema::dropIfExists('conversions');
    }
};
