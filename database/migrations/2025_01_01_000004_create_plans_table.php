<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2)->default(0);
            $table->enum('billing_cycle', ['monthly', 'yearly', 'lifetime'])->default('monthly');
            $table->integer('max_websites')->default(1);
            $table->integer('storage_gb')->default(1);
            $table->integer('bandwidth_gb')->default(10);
            $table->boolean('custom_domain')->default(false);
            $table->boolean('ssl_included')->default(true);
            $table->boolean('backup_included')->default(false);
            $table->boolean('priority_support')->default(false);
            $table->json('features')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
