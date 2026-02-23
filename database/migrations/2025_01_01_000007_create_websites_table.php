<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('websites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('server_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('subdomain')->unique();
            $table->string('custom_domain')->nullable();
            $table->enum('status', ['pending', 'provisioning', 'active', 'suspended', 'deleting', 'error'])->default('pending');
            $table->string('wp_admin_user')->nullable();
            $table->text('wp_admin_password')->nullable();
            $table->string('wp_admin_email')->nullable();
            $table->string('wp_db_name')->nullable();
            $table->string('wp_db_user')->nullable();
            $table->text('wp_db_password')->nullable();
            $table->string('wp_version')->nullable();
            $table->string('php_version')->default('8.2');
            $table->text('ai_prompt')->nullable();
            $table->string('ai_business_type')->nullable();
            $table->string('ai_style')->nullable();
            $table->json('ai_generated_content')->nullable();
            $table->string('screenshot_path')->nullable();
            $table->bigInteger('storage_used_mb')->default(0);
            $table->bigInteger('bandwidth_used_mb')->default(0);
            $table->timestamp('suspended_at')->nullable();
            $table->string('suspension_reason')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('websites');
    }
};
