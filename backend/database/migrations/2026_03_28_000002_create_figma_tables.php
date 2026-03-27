<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('figma_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('figma_file_id');
            $table->string('name');
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();
        });

        Schema::create('figma_exports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('figma_project_id')->nullable();
            $table->string('output_type'); // react, html, wordpress, react-native
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('app_id')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('figma_exports');
        Schema::dropIfExists('figma_projects');
    }
};
