<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deployments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('deployable'); // project_id or app_id or website_id
            $table->string('type'); // website, app
            $table->string('status')->default('pending'); // pending, deploying, active, failed, stopped
            $table->string('domain')->nullable(); // custom domain
            $table->string('subdomain')->nullable(); // auto-generated subdomain
            $table->string('url')->nullable(); // full URL
            $table->string('provider')->default('webnewbiz'); // webnewbiz, vercel, netlify, digitalocean
            $table->string('ssl_status')->default('pending'); // pending, active, failed
            $table->string('server_ip')->nullable();
            $table->json('dns_records')->nullable();
            $table->json('env_vars')->nullable();
            $table->json('build_log')->nullable();
            $table->timestamp('deployed_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deployments');
    }
};
