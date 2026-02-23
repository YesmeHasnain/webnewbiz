<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['user', 'admin', 'superadmin'])->default('user')->after('email');
            $table->enum('status', ['active', 'suspended', 'banned'])->default('active')->after('role');
            $table->timestamp('last_login_at')->nullable()->after('status');
            $table->string('phone')->nullable()->after('last_login_at');
            $table->string('company')->nullable()->after('phone');
        });
    }
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'status', 'last_login_at', 'phone', 'company']);
        });
    }
};
