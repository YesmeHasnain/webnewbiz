<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add credits + subscription to users
        Schema::table('users', function (Blueprint $table) {
            $table->integer('credits')->default(50); // free starter credits
            $table->string('subscription_tier')->default('free'); // free, starter, pro, business, enterprise
            $table->string('stripe_customer_id')->nullable();
            $table->string('stripe_subscription_id')->nullable();
            $table->timestamp('subscription_ends_at')->nullable();
        });

        // Credit transactions log
        Schema::create('credit_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('amount'); // positive = credit, negative = debit
            $table->integer('balance_after');
            $table->string('type'); // purchase, usage, bonus, refund, subscription
            $table->string('action')->nullable(); // website_generate, app_generate, ai_edit, deploy, etc.
            $table->string('description');
            $table->string('stripe_payment_id')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        // Subscription plans
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // free, starter, pro, business, enterprise
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('price_monthly'); // cents
            $table->integer('price_yearly'); // cents
            $table->integer('credits_monthly'); // credits per month
            $table->json('features'); // feature flags
            $table->string('stripe_price_monthly')->nullable();
            $table->string('stripe_price_yearly')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Credit packages (one-time purchases)
        Schema::create('credit_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('credits');
            $table->integer('price'); // cents
            $table->integer('bonus_credits')->default(0); // extra credits as bonus
            $table->string('stripe_price_id')->nullable();
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Invoices / payment history
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('stripe_invoice_id')->nullable();
            $table->string('type'); // subscription, credit_purchase, one_time
            $table->integer('amount'); // cents
            $table->string('currency', 3)->default('usd');
            $table->string('status'); // paid, pending, failed, refunded
            $table->string('description');
            $table->json('metadata')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('credit_packages');
        Schema::dropIfExists('plans');
        Schema::dropIfExists('credit_transactions');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['credits', 'subscription_tier', 'stripe_customer_id', 'stripe_subscription_id', 'subscription_ends_at']);
        });
    }
};
