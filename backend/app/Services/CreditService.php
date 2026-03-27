<?php

namespace App\Services;

use App\Models\CreditPackage;
use App\Models\CreditTransaction;
use App\Models\Invoice;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreditService
{
    /**
     * Credit costs per action.
     */
    public const COSTS = [
        'website_generate'    => 50,
        'app_generate'        => 100,
        'ai_edit'             => 10,
        'ai_chat'             => 5,
        'deploy_website'      => 20,
        'deploy_app'          => 30,
        'appstore_submit'     => 50,
        'playstore_submit'    => 50,
        'image_to_code'       => 15,
        'figma_export'        => 20,
        'platform_convert'    => 25,
        'hosting_monthly'     => 30,
        'analytics_advanced'  => 20,
    ];

    /**
     * Check if user has enough credits for an action.
     */
    public function hasCredits(User $user, string $action): bool
    {
        $cost = self::COSTS[$action] ?? 0;
        return $user->credits >= $cost;
    }

    /**
     * Get cost for an action.
     */
    public function getCost(string $action): int
    {
        return self::COSTS[$action] ?? 0;
    }

    /**
     * Deduct credits for an action. Returns false if insufficient.
     */
    public function deduct(User $user, string $action, ?string $description = null): bool
    {
        $cost = self::COSTS[$action] ?? 0;
        if ($cost === 0) return true;

        return DB::transaction(function () use ($user, $action, $cost, $description) {
            $user = User::lockForUpdate()->find($user->id);

            if ($user->credits < $cost) {
                return false;
            }

            $newBalance = $user->credits - $cost;
            $user->update(['credits' => $newBalance]);

            CreditTransaction::create([
                'user_id'       => $user->id,
                'amount'        => -$cost,
                'balance_after' => $newBalance,
                'type'          => 'usage',
                'action'        => $action,
                'description'   => $description ?? "Used {$cost} credits for {$action}",
            ]);

            return true;
        });
    }

    /**
     * Add credits to user (purchase, bonus, refund).
     */
    public function addCredits(User $user, int $amount, string $type, string $description, ?string $stripePaymentId = null): void
    {
        DB::transaction(function () use ($user, $amount, $type, $description, $stripePaymentId) {
            $user = User::lockForUpdate()->find($user->id);
            $newBalance = $user->credits + $amount;
            $user->update(['credits' => $newBalance]);

            CreditTransaction::create([
                'user_id'          => $user->id,
                'amount'           => $amount,
                'balance_after'    => $newBalance,
                'type'             => $type,
                'description'      => $description,
                'stripe_payment_id' => $stripePaymentId,
            ]);
        });
    }

    /**
     * Purchase a credit package.
     */
    public function purchasePackage(User $user, CreditPackage $package, string $stripePaymentId): void
    {
        $totalCredits = $package->credits + $package->bonus_credits;

        $this->addCredits(
            $user,
            $totalCredits,
            'purchase',
            "Purchased {$package->name}: {$package->credits} + {$package->bonus_credits} bonus credits",
            $stripePaymentId,
        );

        Invoice::create([
            'user_id'          => $user->id,
            'stripe_invoice_id' => $stripePaymentId,
            'type'             => 'credit_purchase',
            'amount'           => $package->price,
            'status'           => 'paid',
            'description'      => "Credit Package: {$package->name}",
            'paid_at'          => now(),
        ]);
    }

    /**
     * Activate a subscription plan.
     */
    public function activateSubscription(User $user, Plan $plan, string $stripeSubId, string $billingCycle = 'monthly'): void
    {
        $user->update([
            'subscription_tier'      => $plan->slug,
            'stripe_subscription_id' => $stripeSubId,
            'subscription_ends_at'   => $billingCycle === 'yearly' ? now()->addYear() : now()->addMonth(),
        ]);

        // Add monthly credits
        $this->addCredits(
            $user,
            $plan->credits_monthly,
            'subscription',
            "Subscription credits: {$plan->name} ({$plan->credits_monthly} credits/month)",
        );

        Invoice::create([
            'user_id'     => $user->id,
            'type'        => 'subscription',
            'amount'      => $billingCycle === 'yearly' ? $plan->price_yearly : $plan->price_monthly,
            'status'      => 'paid',
            'description' => "Subscription: {$plan->name} ({$billingCycle})",
            'paid_at'     => now(),
        ]);
    }

    /**
     * Get user's transaction history.
     */
    public function getTransactions(User $user, int $limit = 50): mixed
    {
        return CreditTransaction::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Get user's invoices.
     */
    public function getInvoices(User $user): mixed
    {
        return Invoice::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Get all active plans.
     */
    public function getPlans(): mixed
    {
        return Plan::where('is_active', true)->orderBy('sort_order')->get();
    }

    /**
     * Get all active credit packages.
     */
    public function getPackages(): mixed
    {
        return CreditPackage::where('is_active', true)->orderBy('sort_order')->get();
    }
}
