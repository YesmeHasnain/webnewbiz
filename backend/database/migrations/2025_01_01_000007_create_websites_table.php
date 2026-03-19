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
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('business_type')->nullable();
            $table->text('ai_prompt')->nullable();
            $table->string('ai_theme')->nullable();
            $table->enum('status', ['pending', 'building', 'active', 'failed', 'suspended'])->default('pending');
            $table->string('build_step')->nullable();
            $table->json('build_log')->nullable();
            $table->json('pages')->nullable();
            $table->string('url')->nullable();
            $table->string('wp_admin_user')->nullable();
            $table->text('wp_admin_password')->nullable();
            $table->string('wp_admin_email')->nullable();
            $table->string('wp_db_name')->nullable();
            $table->text('wp_auto_login_token')->nullable();
            $table->unsignedBigInteger('home_page_id')->nullable();
            $table->json('ai_generated_content')->nullable();
            $table->string('screenshot_path')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('websites');
    }
};
