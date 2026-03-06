<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'description' => 'Perfect for individuals, freelancers, and startups',
                'price' => 1,
                'billing_cycle' => 'monthly',
                'max_websites' => 999999,
                'storage_gb' => 10,
                'bandwidth_gb' => 100,
                'custom_domain' => false,
                'ssl_included' => true,
                'backup_included' => false,
                'priority_support' => false,
                'features' => [
                    'AI Builder',
                    'AI Dashboard',
                    'Built-in AI Assistant',
                    'Drag-and-Drop Editor',
                    'AI Image Generation',
                    'Free SSL',
                    'AI WordPress Converter',
                    '24/7 Support',
                ],
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Professional',
                'slug' => 'business',
                'description' => 'For growing businesses wanting advanced automation and e-commerce',
                'price' => 29,
                'billing_cycle' => 'monthly',
                'max_websites' => 999999,
                'storage_gb' => 50,
                'bandwidth_gb' => 500,
                'custom_domain' => true,
                'ssl_included' => true,
                'backup_included' => true,
                'priority_support' => true,
                'features' => [
                    'Everything in Starter',
                    '24/7 AI Chatbot',
                    'AI Order Processing',
                    'E-commerce System',
                    'Appointment Booking',
                    'Lead Capture & Follow-up Emails',
                    'Business Email',
                    'Priority Support',
                    'High-Performance Hosting',
                    '99.9% Uptime',
                    'Migration',
                    'SEO Tools',
                    'Complete Website Design',
                ],
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Premium',
                'slug' => 'agency',
                'description' => 'For full automation, enterprise performance, and complete digital ecosystem',
                'price' => 49,
                'billing_cycle' => 'monthly',
                'max_websites' => 999999,
                'storage_gb' => 200,
                'bandwidth_gb' => 2000,
                'custom_domain' => true,
                'ssl_included' => true,
                'backup_included' => true,
                'priority_support' => true,
                'features' => [
                    'Everything in Professional',
                    'AI Social Media Manager',
                    'Auto-Publishing',
                    'Ad Campaign Suggestions',
                    'Analytics',
                    'Advanced E-commerce',
                    '90+ PageSpeed Optimization',
                    'Cloudflare CDN',
                    '10x Faster Load Times',
                    'Instagram/Facebook/WhatsApp AI Chatbots',
                ],
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        // Remove old plans that no longer exist
        Plan::whereNotIn('slug', ['starter', 'business', 'agency'])->delete();

        foreach ($plans as $plan) {
            Plan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }
    }
}
