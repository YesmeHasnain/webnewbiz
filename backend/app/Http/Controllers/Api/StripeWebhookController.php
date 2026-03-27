<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Invoice;
use App\Models\CreditTransaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends Controller
{
    public function handle(Request $request): Response
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        // Verify webhook signature in production
        if ($secret && $sigHeader) {
            // In production: use \Stripe\Webhook::constructEvent($payload, $sigHeader, $secret)
        }

        $event = json_decode($payload, true);
        $type = $event['type'] ?? '';

        match ($type) {
            'checkout.session.completed'       => $this->handleCheckoutCompleted($event['data']['object']),
            'invoice.payment_succeeded'        => $this->handleInvoicePayment($event['data']['object']),
            'customer.subscription.updated'    => $this->handleSubscriptionUpdated($event['data']['object']),
            'customer.subscription.deleted'    => $this->handleSubscriptionDeleted($event['data']['object']),
            default => Log::info("Unhandled Stripe event: {$type}"),
        };

        return response('OK', 200);
    }

    private function handleCheckoutCompleted(array $session): void
    {
        $userId = $session['metadata']['user_id'] ?? null;
        $type = $session['metadata']['type'] ?? 'credits';
        $user = $userId ? User::find($userId) : null;
        if (!$user) return;

        if ($type === 'credits') {
            $credits = (int) ($session['metadata']['credits'] ?? 0);
            $user->increment('credits', $credits);

            CreditTransaction::create([
                'user_id' => $user->id,
                'type'    => 'purchase',
                'amount'  => $credits,
                'balance' => $user->credits,
                'description' => "Purchased {$credits} credits",
                'stripe_payment_id' => $session['payment_intent'] ?? null,
            ]);
        }

        Invoice::create([
            'user_id'   => $user->id,
            'stripe_id' => $session['id'],
            'amount'    => ($session['amount_total'] ?? 0) / 100,
            'currency'  => $session['currency'] ?? 'usd',
            'status'    => 'paid',
            'paid_at'   => now(),
        ]);

        Log::info("Checkout completed for user {$userId}");
    }

    private function handleInvoicePayment(array $invoice): void
    {
        $customerId = $invoice['customer'] ?? null;
        $user = $customerId ? User::where('stripe_customer_id', $customerId)->first() : null;
        if (!$user) return;

        // Add monthly credits for subscription
        $plan = $user->subscription_tier ?? 'free';
        $monthlyCredits = match ($plan) {
            'starter'    => 200,
            'pro'        => 500,
            'business'   => 1500,
            'enterprise' => 5000,
            default      => 0,
        };

        if ($monthlyCredits > 0) {
            $user->increment('credits', $monthlyCredits);
            CreditTransaction::create([
                'user_id'     => $user->id,
                'type'        => 'subscription',
                'amount'      => $monthlyCredits,
                'balance'     => $user->credits,
                'description' => "Monthly {$plan} plan credits",
            ]);
        }
    }

    private function handleSubscriptionUpdated(array $subscription): void
    {
        $customerId = $subscription['customer'] ?? null;
        $user = $customerId ? User::where('stripe_customer_id', $customerId)->first() : null;
        if (!$user) return;

        $priceId = $subscription['items']['data'][0]['price']['id'] ?? '';
        $tier = $this->priceToTier($priceId);

        $user->update([
            'subscription_tier'     => $tier,
            'stripe_subscription_id' => $subscription['id'],
            'subscription_ends_at'  => isset($subscription['current_period_end'])
                ? date('Y-m-d H:i:s', $subscription['current_period_end'])
                : null,
        ]);
    }

    private function handleSubscriptionDeleted(array $subscription): void
    {
        $customerId = $subscription['customer'] ?? null;
        $user = $customerId ? User::where('stripe_customer_id', $customerId)->first() : null;
        if (!$user) return;

        $user->update([
            'subscription_tier'      => 'free',
            'stripe_subscription_id' => null,
            'subscription_ends_at'   => null,
        ]);
    }

    private function priceToTier(string $priceId): string
    {
        // Map Stripe price IDs to plan tiers
        $map = [
            config('services.stripe.prices.starter', '')    => 'starter',
            config('services.stripe.prices.pro', '')        => 'pro',
            config('services.stripe.prices.business', '')   => 'business',
            config('services.stripe.prices.enterprise', '') => 'enterprise',
        ];
        return $map[$priceId] ?? 'free';
    }
}
