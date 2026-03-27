<?php

namespace Database\Seeders;

use App\Models\CreditPackage;
use App\Models\Plan;
use Illuminate\Database\Seeder;

class BillingSeeder extends Seeder
{
    public function run(): void
    {
        // Plans
        $plans = [
            [
                'slug' => 'free', 'name' => 'Free', 'description' => 'Get started with basic features',
                'price_monthly' => 0, 'price_yearly' => 0, 'credits_monthly' => 50,
                'features' => ['1 website', '1 app', 'Basic analytics', 'Community support'],
                'sort_order' => 0,
            ],
            [
                'slug' => 'starter', 'name' => 'Starter', 'description' => 'Perfect for individuals and freelancers',
                'price_monthly' => 1900, 'price_yearly' => 19000, 'credits_monthly' => 200,
                'features' => ['5 websites', '3 apps', 'Custom domains', 'Basic analytics', 'Email support', 'Deploy to hosting'],
                'sort_order' => 1,
            ],
            [
                'slug' => 'pro', 'name' => 'Pro', 'description' => 'For professionals and growing businesses',
                'price_monthly' => 3900, 'price_yearly' => 39000, 'credits_monthly' => 500,
                'features' => ['Unlimited websites', '10 apps', 'Custom domains', 'Advanced analytics', 'Priority support', 'App Store publishing', 'Figma plugin', 'All converters'],
                'sort_order' => 2,
            ],
            [
                'slug' => 'business', 'name' => 'Business', 'description' => 'For teams and agencies',
                'price_monthly' => 9900, 'price_yearly' => 99000, 'credits_monthly' => 1500,
                'features' => ['Everything in Pro', 'Unlimited apps', 'White-label', 'Team collaboration', 'API access', 'Dedicated support', 'Custom integrations'],
                'sort_order' => 3,
            ],
            [
                'slug' => 'enterprise', 'name' => 'Enterprise', 'description' => 'Custom solutions for large organizations',
                'price_monthly' => 0, 'price_yearly' => 0, 'credits_monthly' => 0,
                'features' => ['Everything in Business', 'Unlimited credits', 'Custom AI models', 'SLA guarantee', 'On-premise option', 'Dedicated account manager'],
                'sort_order' => 4,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }

        // Credit Packages
        $packages = [
            ['name' => 'Starter Pack', 'credits' => 100, 'price' => 999, 'bonus_credits' => 0, 'sort_order' => 0],
            ['name' => 'Builder Pack', 'credits' => 300, 'price' => 2499, 'bonus_credits' => 30, 'sort_order' => 1],
            ['name' => 'Pro Pack', 'credits' => 700, 'price' => 4999, 'bonus_credits' => 100, 'is_popular' => true, 'sort_order' => 2],
            ['name' => 'Business Pack', 'credits' => 1500, 'price' => 9999, 'bonus_credits' => 300, 'sort_order' => 3],
            ['name' => 'Agency Pack', 'credits' => 5000, 'price' => 24999, 'bonus_credits' => 1000, 'sort_order' => 4],
        ];

        foreach ($packages as $pkg) {
            CreditPackage::updateOrCreate(['name' => $pkg['name']], $pkg);
        }
    }
}
