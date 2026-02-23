@extends('admin.layouts.app')

@section('title', 'Plans')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div><h1 class="text-2xl font-bold text-gray-800 dark:text-white">Plans</h1><p class="text-sm text-gray-500 mt-1">Manage subscription plans</p></div>
    <a href="{{ route('admin.plans.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">+ Create Plan</a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    @foreach($plans as $plan)
        <div class="bg-white dark:bg-dark-100 rounded-xl border border-gray-200 dark:border-gray-800 p-6">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">{{ $plan->name }}</h3>
                @if(!$plan->is_active) <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">Inactive</span> @endif
            </div>
            <p class="text-3xl font-bold text-gray-800 dark:text-white mb-1">${{ number_format($plan->price, 2) }}<span class="text-sm font-normal text-gray-500">/{{ $plan->billing_cycle }}</span></p>
            <p class="text-sm text-gray-500 mb-4">{{ $plan->description }}</p>
            <ul class="space-y-2 mb-4">
                <li class="text-sm text-gray-600 dark:text-gray-400">{{ $plan->max_websites }} Websites</li>
                <li class="text-sm text-gray-600 dark:text-gray-400">{{ $plan->storage_gb }} GB Storage</li>
                <li class="text-sm text-gray-600 dark:text-gray-400">{{ $plan->bandwidth_gb }} GB Bandwidth</li>
                <li class="text-sm text-gray-600 dark:text-gray-400">{{ $plan->custom_domain ? 'Custom Domain' : 'Subdomain Only' }}</li>
            </ul>
            <p class="text-xs text-gray-500 mb-3">{{ $plan->subscriptions_count ?? 0 }} subscribers</p>
            <div class="flex gap-2">
                <a href="{{ route('admin.plans.edit', $plan) }}" class="flex-1 text-center bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 px-3 py-2 rounded-lg text-sm transition">Edit</a>
                <form method="POST" action="{{ route('admin.plans.destroy', $plan) }}" onsubmit="return confirm('Delete this plan?')" class="flex-1">@csrf @method('DELETE') <button class="w-full bg-red-50 dark:bg-red-900/20 hover:bg-red-100 text-red-600 px-3 py-2 rounded-lg text-sm transition">Delete</button></form>
            </div>
        </div>
    @endforeach
</div>
@endsection
