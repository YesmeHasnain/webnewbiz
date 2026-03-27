<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Contacts
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('title')->nullable();
            $table->string('avatar')->nullable();
            $table->string('source')->default('manual');
            $table->string('status')->default('active');
            $table->json('tags')->nullable();
            $table->json('custom_fields')->nullable();
            $table->timestamp('last_activity_at')->nullable();
            $table->decimal('lifetime_value', 12, 2)->default(0);
            $table->timestamps();
            $table->index(['user_id', 'email']);
        });

        // Pipelines
        Schema::create('pipelines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->json('stages');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        // Deals
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pipeline_id')->constrained()->cascadeOnDelete();
            $table->foreignId('contact_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->decimal('value', 12, 2)->default(0);
            $table->string('stage');
            $table->integer('probability')->default(50);
            $table->date('expected_close')->nullable();
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->string('status')->default('open');
            $table->timestamps();
            $table->index(['user_id', 'pipeline_id', 'stage']);
        });

        // Deal Activities
        Schema::create('deal_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deal_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // call, email, meeting, note, task
            $table->text('content');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        // Email Campaigns
        Schema::create('email_campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('subject');
            $table->longText('body_html');
            $table->string('status')->default('draft');
            $table->string('segment_id')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->json('stats')->nullable();
            $table->timestamps();
        });

        // Email Sequences (drip campaigns)
        Schema::create('email_sequences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('trigger')->default('manual');
            $table->string('status')->default('inactive');
            $table->timestamps();
        });

        // Email Sequence Steps
        Schema::create('email_sequence_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_sequence_id')->constrained()->cascadeOnDelete();
            $table->integer('step_order');
            $table->integer('delay_hours')->default(24);
            $table->string('subject');
            $table->longText('body_html');
            $table->string('type')->default('email');
            $table->timestamps();
        });

        // Email Sends (tracking)
        Schema::create('email_sends', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campaign_id')->nullable();
            $table->foreignId('contact_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('sent');
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('clicked_at')->nullable();
            $table->timestamps();
        });

        // Automation Workflows
        Schema::create('workflows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('trigger_type');
            $table->json('trigger_config')->nullable();
            $table->string('status')->default('inactive');
            $table->timestamps();
        });

        // Workflow Steps
        Schema::create('workflow_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_id')->constrained()->cascadeOnDelete();
            $table->integer('step_order');
            $table->string('type'); // send_email, send_sms, add_tag, wait, condition
            $table->json('config');
            $table->unsignedBigInteger('next_step_id')->nullable();
            $table->timestamps();
        });

        // Workflow Logs
        Schema::create('workflow_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_id')->constrained()->cascadeOnDelete();
            $table->foreignId('contact_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('step_id')->nullable();
            $table->string('status');
            $table->timestamp('executed_at');
            $table->timestamps();
        });

        // Calendars
        Schema::create('calendars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('timezone')->default('UTC');
            $table->json('availability')->nullable();
            $table->integer('booking_duration')->default(30);
            $table->integer('buffer_minutes')->default(15);
            $table->timestamps();
        });

        // Bookings
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calendar_id')->constrained()->cascadeOnDelete();
            $table->foreignId('contact_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->string('status')->default('confirmed');
            $table->text('notes')->nullable();
            $table->string('meeting_link')->nullable();
            $table->timestamps();
        });

        // CRM Invoices
        Schema::create('invoices_crm', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('contact_id')->nullable()->constrained()->nullOnDelete();
            $table->string('number')->unique();
            $table->string('status')->default('draft');
            $table->date('due_date')->nullable();
            $table->json('items');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->timestamp('paid_at')->nullable();
            $table->string('stripe_invoice_id')->nullable();
            $table->timestamps();
        });

        // Conversations (unified inbox)
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('contact_id')->nullable()->constrained()->nullOnDelete();
            $table->string('channel')->default('chat');
            $table->string('status')->default('open');
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
        });

        // CRM Messages
        Schema::create('messages_crm', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->cascadeOnDelete();
            $table->string('sender_type');
            $table->unsignedBigInteger('sender_id')->nullable();
            $table->text('content');
            $table->string('type')->default('text');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages_crm');
        Schema::dropIfExists('conversations');
        Schema::dropIfExists('invoices_crm');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('calendars');
        Schema::dropIfExists('workflow_logs');
        Schema::dropIfExists('workflow_steps');
        Schema::dropIfExists('workflows');
        Schema::dropIfExists('email_sends');
        Schema::dropIfExists('email_sequence_steps');
        Schema::dropIfExists('email_sequences');
        Schema::dropIfExists('email_campaigns');
        Schema::dropIfExists('deal_activities');
        Schema::dropIfExists('deals');
        Schema::dropIfExists('pipelines');
        Schema::dropIfExists('contacts');
    }
};
