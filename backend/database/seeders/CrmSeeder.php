<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Pipeline;
use App\Models\Deal;
use App\Models\EmailCampaign;
use App\Models\Workflow;
use App\Models\Calendar;
use Illuminate\Database\Seeder;

class CrmSeeder extends Seeder
{
    public function run(): void
    {
        $userId = 1; // Default user

        // Demo Contacts
        $contacts = [
            ['first_name' => 'Ahmed', 'last_name' => 'Khan', 'email' => 'ahmed@example.com', 'phone' => '+92 300 1234567', 'company' => 'TechFlow', 'source' => 'website', 'tags' => ['lead', 'tech']],
            ['first_name' => 'Sara', 'last_name' => 'Ali', 'email' => 'sara@example.com', 'phone' => '+92 321 9876543', 'company' => 'DesignHub', 'source' => 'referral', 'tags' => ['client', 'design']],
            ['first_name' => 'Bilal', 'last_name' => 'Raza', 'email' => 'bilal@example.com', 'phone' => '+92 333 5555555', 'company' => 'CloudBase', 'source' => 'linkedin', 'tags' => ['lead', 'saas']],
            ['first_name' => 'Fatima', 'last_name' => 'Noor', 'email' => 'fatima@example.com', 'phone' => '+92 345 1111111', 'company' => 'EduPro', 'source' => 'website', 'tags' => ['client', 'education']],
            ['first_name' => 'John', 'last_name' => 'Smith', 'email' => 'john@example.com', 'phone' => '+1 555 0123', 'company' => 'StartupX', 'source' => 'advertisement', 'tags' => ['lead', 'startup']],
        ];

        foreach ($contacts as $c) {
            Contact::create(['user_id' => $userId, ...$c, 'status' => 'active']);
        }

        // Demo Pipeline
        $pipeline = Pipeline::create([
            'user_id' => $userId,
            'name' => 'Sales Pipeline',
            'stages' => ['Lead', 'Qualified', 'Proposal', 'Negotiation', 'Won', 'Lost'],
            'is_default' => true,
        ]);

        // Demo Deals
        $deals = [
            ['title' => 'Website Redesign — TechFlow', 'value' => 5000, 'stage' => 'Proposal', 'probability' => 70, 'contact_id' => 1],
            ['title' => 'Mobile App — DesignHub', 'value' => 12000, 'stage' => 'Negotiation', 'probability' => 85, 'contact_id' => 2],
            ['title' => 'SaaS Platform — CloudBase', 'value' => 25000, 'stage' => 'Qualified', 'probability' => 40, 'contact_id' => 3],
            ['title' => 'E-commerce — EduPro', 'value' => 8000, 'stage' => 'Won', 'probability' => 100, 'contact_id' => 4],
            ['title' => 'Landing Page — StartupX', 'value' => 2000, 'stage' => 'Lead', 'probability' => 20, 'contact_id' => 5],
        ];

        foreach ($deals as $d) {
            Deal::create(['user_id' => $userId, 'pipeline_id' => $pipeline->id, ...$d]);
        }

        // Demo Campaign
        EmailCampaign::create([
            'user_id' => $userId,
            'name' => 'March Newsletter',
            'subject' => 'What\'s new at WebNewBiz — March 2026',
            'body_html' => '<h1>March Updates</h1><p>Check out our latest features including the new CRM system!</p>',
            'status' => 'draft',
        ]);

        // Demo Workflow
        Workflow::create([
            'user_id' => $userId,
            'name' => 'New Lead Welcome',
            'trigger_type' => 'form_submit',
            'trigger_config' => ['form' => 'contact'],
            'status' => 'active',
        ]);

        // Demo Calendar
        Calendar::create([
            'user_id' => $userId,
            'name' => 'Consultations',
            'timezone' => 'Asia/Karachi',
            'booking_duration' => 30,
            'buffer_minutes' => 15,
            'availability' => [
                'monday' => ['09:00-17:00'],
                'tuesday' => ['09:00-17:00'],
                'wednesday' => ['09:00-17:00'],
                'thursday' => ['09:00-17:00'],
                'friday' => ['09:00-14:00'],
            ],
        ]);
    }
}
