<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('provider')->default('digitalocean');
            $table->string('provider_id')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('region')->nullable();
            $table->string('size')->nullable();
            $table->string('image')->nullable();
            $table->enum('status', ['provisioning', 'active', 'inactive', 'error', 'deleting'])->default('provisioning');
            $table->text('ssh_private_key')->nullable();
            $table->text('ssh_public_key')->nullable();
            $table->string('ssh_key_fingerprint')->nullable();
            $table->integer('ssh_port')->default(22);
            $table->integer('max_websites')->default(50);
            $table->integer('current_websites')->default(0);
            $table->float('cpu_usage')->nullable();
            $table->float('memory_usage')->nullable();
            $table->float('disk_usage')->nullable();
            $table->timestamp('last_health_check')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('servers');
    }
};
