<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('copilot_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->index(['website_id', 'updated_at']);
        });

        Schema::create('copilot_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('copilot_sessions')->cascadeOnDelete();
            $table->foreignId('website_id')->constrained()->cascadeOnDelete();
            $table->string('action_type', 50);
            $table->json('action_params')->nullable();
            $table->longText('before_state')->nullable();
            $table->json('result')->nullable();
            $table->string('status', 20)->default('completed'); // completed, failed, undone
            $table->timestamps();

            $table->index(['session_id', 'created_at']);
            $table->index(['website_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('copilot_actions');
        Schema::dropIfExists('copilot_sessions');
    }
};
