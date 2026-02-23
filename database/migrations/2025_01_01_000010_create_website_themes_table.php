<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('website_themes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained()->cascadeOnDelete();
            $table->string('slug');
            $table->string('name');
            $table->string('version')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            $table->unique(['website_id', 'slug']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('website_themes');
    }
};
