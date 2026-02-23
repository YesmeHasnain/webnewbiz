<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminPlanController extends Controller
{
    public function index()
    {
        $plans = Plan::withCount('subscriptions')->ordered()->get();
        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.form', ['plan' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,yearly,lifetime',
            'max_websites' => 'required|integer|min:1',
            'storage_gb' => 'required|integer|min:1',
            'bandwidth_gb' => 'required|integer|min:1',
            'custom_domain' => 'boolean',
            'ssl_included' => 'boolean',
            'backup_included' => 'boolean',
            'priority_support' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['custom_domain'] = $request->boolean('custom_domain');
        $validated['ssl_included'] = $request->boolean('ssl_included');
        $validated['backup_included'] = $request->boolean('backup_included');
        $validated['priority_support'] = $request->boolean('priority_support');
        $validated['is_active'] = $request->boolean('is_active', true);

        Plan::create($validated);
        return redirect()->route('admin.plans.index')->with('success', 'Plan created.');
    }

    public function edit(Plan $plan)
    {
        return view('admin.plans.form', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,yearly,lifetime',
            'max_websites' => 'required|integer|min:1',
            'storage_gb' => 'required|integer|min:1',
            'bandwidth_gb' => 'required|integer|min:1',
            'custom_domain' => 'boolean',
            'ssl_included' => 'boolean',
            'backup_included' => 'boolean',
            'priority_support' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['custom_domain'] = $request->boolean('custom_domain');
        $validated['ssl_included'] = $request->boolean('ssl_included');
        $validated['backup_included'] = $request->boolean('backup_included');
        $validated['priority_support'] = $request->boolean('priority_support');
        $validated['is_active'] = $request->boolean('is_active', true);

        $plan->update($validated);
        return redirect()->route('admin.plans.index')->with('success', 'Plan updated.');
    }

    public function destroy(Plan $plan)
    {
        if ($plan->subscriptions()->where('status', 'active')->exists()) {
            return back()->withErrors(['error' => 'Cannot delete plan with active subscriptions.']);
        }

        $plan->delete();
        return redirect()->route('admin.plans.index')->with('success', 'Plan deleted.');
    }
}
