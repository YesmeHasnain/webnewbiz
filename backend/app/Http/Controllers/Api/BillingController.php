<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CreditPackage;
use App\Models\Plan;
use App\Services\CreditService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function __construct(private CreditService $creditService) {}

    /**
     * Get user's billing overview (credits, plan, usage).
     */
    public function overview(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'credits'            => $user->credits,
            'subscription_tier'  => $user->subscription_tier,
            'subscription_ends'  => $user->subscription_ends_at,
            'costs'              => CreditService::COSTS,
        ]);
    }

    /**
     * Get available plans.
     */
    public function plans(): JsonResponse
    {
        return response()->json($this->creditService->getPlans());
    }

    /**
     * Get available credit packages.
     */
    public function packages(): JsonResponse
    {
        return response()->json($this->creditService->getPackages());
    }

    /**
     * Get transaction history.
     */
    public function transactions(Request $request): JsonResponse
    {
        return response()->json(
            $this->creditService->getTransactions($request->user())
        );
    }

    /**
     * Get invoices.
     */
    public function invoices(Request $request): JsonResponse
    {
        return response()->json(
            $this->creditService->getInvoices($request->user())
        );
    }

    /**
     * Purchase credits (create Stripe checkout session).
     */
    public function purchaseCredits(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:credit_packages,id',
        ]);

        $package = CreditPackage::findOrFail($validated['package_id']);
        $user = $request->user();

        // For now (dev mode), add credits directly without Stripe
        // In production, create Stripe Checkout session here
        $this->creditService->purchasePackage($user, $package, 'dev_' . uniqid());

        return response()->json([
            'success' => true,
            'credits' => $user->fresh()->credits,
            'message' => "Added {$package->credits} credits!",
        ]);
    }

    /**
     * Subscribe to a plan.
     */
    public function subscribe(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'billing_cycle' => 'in:monthly,yearly',
        ]);

        $plan = Plan::findOrFail($validated['plan_id']);
        $user = $request->user();

        // For now (dev mode), activate directly without Stripe
        $this->creditService->activateSubscription(
            $user,
            $plan,
            'dev_sub_' . uniqid(),
            $validated['billing_cycle'] ?? 'monthly',
        );

        return response()->json([
            'success' => true,
            'credits' => $user->fresh()->credits,
            'tier'    => $plan->slug,
            'message' => "Subscribed to {$plan->name}!",
        ]);
    }

    /**
     * Stripe webhook handler (for production).
     */
    public function stripeWebhook(Request $request): JsonResponse
    {
        // TODO: Implement Stripe webhook verification + handling
        // - checkout.session.completed → add credits
        // - invoice.paid → renew subscription credits
        // - customer.subscription.deleted → downgrade to free

        return response()->json(['received' => true]);
    }
}
