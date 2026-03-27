<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('framework', 20)->default('html'); // html, react, nextjs
            $table->string('status', 20)->default('draft');   // draft, generating, ready, deployed
            $table->text('ai_prompt')->nullable();
            $table->json('file_tree')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'updated_at']);
            $table->index('status');
        });

        Schema::create('project_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('role', 20); // user, assistant
            $table->longText('content');
            $table->json('files_changed')->nullable();
            $table->timestamps();

            $table->index(['project_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_messages');
        Schema::dropIfExists('projects');
    }
};
