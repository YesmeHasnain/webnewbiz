<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('framework')->default('react-native'); // react-native, flutter
            $table->string('status')->default('draft'); // draft, generating, ready, building, published
            $table->string('app_icon')->nullable();
            $table->string('bundle_id')->nullable(); // com.example.app
            $table->string('version')->default('1.0.0');
            $table->json('file_tree')->nullable();
            $table->json('platforms')->default('["ios","android"]'); // ios, android
            $table->json('build_config')->nullable(); // EAS build config
            $table->string('expo_project_id')->nullable();
            $table->string('last_build_id')->nullable();
            $table->string('ios_build_url')->nullable();
            $table->string('android_build_url')->nullable();
            $table->timestamps();
        });

        Schema::create('app_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('app_id')->constrained()->onDelete('cascade');
            $table->string('role'); // user, assistant
            $table->longText('content');
            $table->json('files_changed')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_messages');
        Schema::dropIfExists('apps');
    }
};
