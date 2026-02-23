@extends('admin.layouts.app')

@section('title', 'User: ' . $user->name)

@section('content')
<div class="mb-6"><a href="{{ route('admin.users.index') }}" class="text-sm text-blue-600 hover:text-blue-700">&larr; Back to Users</a></div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white dark:bg-dark-100 rounded-xl border border-gray-200 dark:border-gray-800 p-6">
        <div class="text-center mb-4">
            <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center text-white text-2xl font-bold mx-auto mb-3">{{ substr($user->name, 0, 1) }}</div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $user->name }}</h3>
            <p class="text-sm text-gray-500">{{ $user->email }}</p>
        </div>
        <dl class="space-y-3">
            <div><dt class="text-xs text-gray-500">Role</dt><dd><span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-700">{{ ucfirst($user->role) }}</span></dd></div>
            <div><dt class="text-xs text-gray-500">Status</dt><dd><span class="px-2 py-1 text-xs rounded-full bg-{{ $user->status === 'active' ? 'green' : 'red' }}-100 text-{{ $user->status === 'active' ? 'green' : 'red' }}-700">{{ ucfirst($user->status) }}</span></dd></div>
            <div><dt class="text-xs text-gray-500">Joined</dt><dd class="text-sm text-gray-600 dark:text-gray-400">{{ $user->created_at->format('M d, Y') }}</dd></div>
            <div><dt class="text-xs text-gray-500">Last Login</dt><dd class="text-sm text-gray-600 dark:text-gray-400">{{ $user->last_login_at?->diffForHumans() ?? 'Never' }}</dd></div>
        </dl>
        <div class="mt-4 space-y-2">
            <form method="POST" action="{{ route('admin.users.update-status', $user) }}" class="flex gap-2">@csrf @method('PATCH')
                <select name="status" class="flex-1 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-dark-300 dark:text-white text-sm">
                    <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="suspended" {{ $user->status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    <option value="banned" {{ $user->status === 'banned' ? 'selected' : '' }}>Banned</option>
                </select>
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">Update</button>
            </form>
        </div>
    </div>

    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white dark:bg-dark-100 rounded-xl border border-gray-200 dark:border-gray-800 p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Websites ({{ $user->websites->count() }})</h3>
            <table class="w-full">
                <thead><tr><th class="text-left text-xs font-medium text-gray-500 pb-2">Website</th><th class="text-left text-xs font-medium text-gray-500 pb-2">Status</th><th class="text-left text-xs font-medium text-gray-500 pb-2">Server</th></tr></thead>
                <tbody>
                    @forelse($user->websites as $website)
                        <tr><td class="py-2"><a href="{{ route('admin.websites.show', $website) }}" class="text-sm text-blue-600">{{ $website->name }}</a></td><td class="py-2"><span class="text-xs px-2 py-0.5 rounded-full bg-{{ $website->status === 'active' ? 'green' : 'yellow' }}-100 text-{{ $website->status === 'active' ? 'green' : 'yellow' }}-700">{{ ucfirst($website->status) }}</span></td><td class="py-2 text-sm text-gray-500">{{ $website->server?->name ?? 'N/A' }}</td></tr>
                    @empty
                        <tr><td colspan="3" class="py-4 text-center text-gray-500 text-sm">No websites.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-white dark:bg-dark-100 rounded-xl border border-gray-200 dark:border-gray-800 p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Subscriptions</h3>
            @forelse($user->subscriptions as $sub)
                <div class="p-3 rounded-lg bg-gray-50 dark:bg-dark-300 mb-2">
                    <div class="flex justify-between"><span class="text-sm font-medium text-gray-800 dark:text-white">{{ $sub->plan->name }}</span><span class="text-xs px-2 py-0.5 rounded-full bg-{{ $sub->status === 'active' ? 'green' : 'gray' }}-100 text-{{ $sub->status === 'active' ? 'green' : 'gray' }}-700">{{ ucfirst($sub->status) }}</span></div>
                    <p class="text-xs text-gray-500 mt-1">${{ $sub->amount_paid }}/{{ $sub->plan->billing_cycle }} &middot; Since {{ $sub->starts_at?->format('M d, Y') ?? 'N/A' }}</p>
                </div>
            @empty
                <p class="text-gray-500 text-sm">No subscriptions.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
