@extends('admin.layouts.app')

@section('title', $plan ? 'Edit Plan: ' . $plan->name : 'Create Plan')

@section('content')
<div class="mb-6"><a href="{{ route('admin.plans.index') }}" class="text-sm text-blue-600 hover:text-blue-700">&larr; Back to Plans</a></div>

<div class="max-w-2xl">
    <div class="bg-white dark:bg-dark-100 rounded-xl border border-gray-200 dark:border-gray-800 p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-6">{{ $plan ? 'Edit' : 'Create' }} Plan</h3>
        <form method="POST" action="{{ $plan ? route('admin.plans.update', $plan) : route('admin.plans.store') }}">
            @csrf
            @if($plan) @method('PUT') @endif
            <div class="space-y-4">
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label><input type="text" name="name" value="{{ old('name', $plan?->name) }}" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-dark-300 dark:text-white focus:ring-2 focus:ring-blue-500"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label><textarea name="description" rows="2" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-dark-300 dark:text-white focus:ring-2 focus:ring-blue-500">{{ old('description', $plan?->description) }}</textarea></div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Price ($)</label><input type="number" step="0.01" name="price" value="{{ old('price', $plan?->price ?? 0) }}" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-dark-300 dark:text-white focus:ring-2 focus:ring-blue-500"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Billing Cycle</label><select name="billing_cycle" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-dark-300 dark:text-white"><option value="monthly" {{ old('billing_cycle', $plan?->billing_cycle) === 'monthly' ? 'selected' : '' }}>Monthly</option><option value="yearly" {{ old('billing_cycle', $plan?->billing_cycle) === 'yearly' ? 'selected' : '' }}>Yearly</option><option value="lifetime" {{ old('billing_cycle', $plan?->billing_cycle) === 'lifetime' ? 'selected' : '' }}>Lifetime</option></select></div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Max Websites</label><input type="number" name="max_websites" value="{{ old('max_websites', $plan?->max_websites ?? 1) }}" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-dark-300 dark:text-white focus:ring-2 focus:ring-blue-500"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Storage (GB)</label><input type="number" name="storage_gb" value="{{ old('storage_gb', $plan?->storage_gb ?? 1) }}" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-dark-300 dark:text-white focus:ring-2 focus:ring-blue-500"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bandwidth (GB)</label><input type="number" name="bandwidth_gb" value="{{ old('bandwidth_gb', $plan?->bandwidth_gb ?? 10) }}" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-dark-300 dark:text-white focus:ring-2 focus:ring-blue-500"></div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <label class="flex items-center gap-2"><input type="checkbox" name="custom_domain" value="1" {{ old('custom_domain', $plan?->custom_domain) ? 'checked' : '' }} class="rounded"><span class="text-sm text-gray-700 dark:text-gray-300">Custom Domain</span></label>
                    <label class="flex items-center gap-2"><input type="checkbox" name="ssl_included" value="1" {{ old('ssl_included', $plan?->ssl_included ?? true) ? 'checked' : '' }} class="rounded"><span class="text-sm text-gray-700 dark:text-gray-300">SSL Included</span></label>
                    <label class="flex items-center gap-2"><input type="checkbox" name="backup_included" value="1" {{ old('backup_included', $plan?->backup_included) ? 'checked' : '' }} class="rounded"><span class="text-sm text-gray-700 dark:text-gray-300">Backup Included</span></label>
                    <label class="flex items-center gap-2"><input type="checkbox" name="priority_support" value="1" {{ old('priority_support', $plan?->priority_support) ? 'checked' : '' }} class="rounded"><span class="text-sm text-gray-700 dark:text-gray-300">Priority Support</span></label>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" {{ old('is_active', $plan?->is_active ?? true) ? 'checked' : '' }} class="rounded"><span class="text-sm text-gray-700 dark:text-gray-300">Active</span></label>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sort Order</label><input type="number" name="sort_order" value="{{ old('sort_order', $plan?->sort_order ?? 0) }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-dark-300 dark:text-white focus:ring-2 focus:ring-blue-500"></div>
                </div>
            </div>
            <div class="mt-6 flex gap-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition">{{ $plan ? 'Update' : 'Create' }} Plan</button>
                <a href="{{ route('admin.plans.index') }}" class="bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 text-gray-700 dark:text-gray-300 px-6 py-2 rounded-lg text-sm transition">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
