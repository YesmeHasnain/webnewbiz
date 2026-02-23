<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ssl_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('domain_id')->constrained()->cascadeOnDelete();
            $table->string('provider')->default('letsencrypt');
            $table->enum('status', ['pending', 'active', 'expired', 'revoked', 'error'])->default('pending');
            $table->text('certificate_path')->nullable();
            $table->text('private_key_path')->nullable();
            $table->text('chain_path')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('last_renewal_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('ssl_certificates');
    }
};
