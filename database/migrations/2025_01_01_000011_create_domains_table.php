<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained()->cascadeOnDelete();
            $table->string('domain')->unique();
            $table->enum('type', ['subdomain', 'custom'])->default('subdomain');
            $table->boolean('is_primary')->default(false);
            $table->string('cloudflare_record_id')->nullable();
            $table->enum('dns_status', ['pending', 'active', 'error'])->default('pending');
            $table->enum('ssl_status', ['none', 'pending', 'active', 'expired', 'error'])->default('none');
            $table->timestamp('ssl_expires_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('domains');
    }
};
