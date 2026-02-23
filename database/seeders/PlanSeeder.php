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
                'name' => 'Free',
                'slug' => 'free',
                'description' => 'Get started with a basic website',
                'price' => 0,
                'billing_cycle' => 'monthly',
                'max_websites' => 1,
                'storage_gb' => 1,
                'bandwidth_gb' => 10,
                'custom_domain' => false,
                'ssl_included' => true,
                'backup_included' => false,
                'priority_support' => false,
                'features' => ['1 Website', '1 GB Storage', '10 GB Bandwidth', 'Free SSL', 'Subdomain Only'],
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'description' => 'Perfect for small businesses',
                'price' => 9.99,
                'billing_cycle' => 'monthly',
                'max_websites' => 5,
                'storage_gb' => 10,
                'bandwidth_gb' => 100,
                'custom_domain' => true,
                'ssl_included' => true,
                'backup_included' => true,
                'priority_support' => false,
                'features' => ['5 Websites', '10 GB Storage', '100 GB Bandwidth', 'Free SSL', 'Custom Domain', 'Daily Backups'],
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Business',
                'slug' => 'business',
                'description' => 'For growing businesses',
                'price' => 24.99,
                'billing_cycle' => 'monthly',
                'max_websites' => 20,
                'storage_gb' => 50,
                'bandwidth_gb' => 500,
                'custom_domain' => true,
                'ssl_included' => true,
                'backup_included' => true,
                'priority_support' => true,
                'features' => ['20 Websites', '50 GB Storage', '500 GB Bandwidth', 'Free SSL', 'Custom Domain', 'Daily Backups', 'Priority Support'],
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Agency',
                'slug' => 'agency',
                'description' => 'For agencies and enterprises',
                'price' => 49.99,
                'billing_cycle' => 'monthly',
                'max_websites' => 100,
                'storage_gb' => 200,
                'bandwidth_gb' => 2000,
                'custom_domain' => true,
                'ssl_included' => true,
                'backup_included' => true,
                'priority_support' => true,
                'features' => ['100 Websites', '200 GB Storage', '2 TB Bandwidth', 'Free SSL', 'Custom Domain', 'Daily Backups', 'Priority Support', 'White Label'],
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }
    }
}
