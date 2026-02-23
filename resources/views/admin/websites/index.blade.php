@extends('admin.layouts.app')

@section('title', 'Websites')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Websites</h1>
        <p class="text-sm text-gray-500 mt-1">Manage all WordPress websites</p>
    </div>
</div>

<!-- Filters -->
<div class="bg-white dark:bg-dark-100 rounded-xl border border-gray-200 dark:border-gray-800 p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-4">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search websites..." class="flex-1 min-w-[200px] px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-dark-300 dark:text-white text-sm focus:ring-2 focus:ring-blue-500">
        <select name="status" class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-dark-300 dark:text-white text-sm">
            <option value="">All Status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="provisioning" {{ request('status') === 'provisioning' ? 'selected' : '' }}>Provisioning</option>
            <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
            <option value="error" {{ request('status') === 'error' ? 'selected' : '' }}>Error</option>
        </select>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">Filter</button>
    </form>
</div>

<!-- Websites Table -->
<div class="bg-white dark:bg-dark-100 rounded-xl border border-gray-200 dark:border-gray-800 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 dark:bg-dark-300">
                <tr>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Website</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">User</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Server</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Created</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                @forelse($websites as $website)
                    <tr class="hover:bg-gray-50 dark:hover:bg-dark-300">
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.websites.show', $website) }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">{{ $website->name }}</a>
                            <p class="text-xs text-gray-500">{{ $website->subdomain }}{{ config('webnewbiz.subdomain_suffix') }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $website->user?->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $website->server?->name ?? 'Unassigned' }}</td>
                        <td class="px-6 py-4">
                            @php $c = ['active'=>'green','provisioning'=>'yellow','pending'=>'gray','suspended'=>'red','error'=>'red'][$website->status] ?? 'gray'; @endphp
                            <span class="px-2 py-1 text-xs rounded-full bg-{{ $c }}-100 text-{{ $c }}-700 dark:bg-{{ $c }}-900/30 dark:text-{{ $c }}-400">{{ ucfirst($website->status) }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $website->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.websites.show', $website) }}" class="text-blue-600 hover:text-blue-700 text-sm">View</a>
                                @if($website->status === 'active')
                                    <form method="POST" action="{{ route('admin.websites.suspend', $website) }}" class="inline">@csrf <button class="text-yellow-600 hover:text-yellow-700 text-sm">Suspend</button></form>
                                @elseif($website->status === 'suspended')
                                    <form method="POST" action="{{ route('admin.websites.unsuspend', $website) }}" class="inline">@csrf <button class="text-green-600 hover:text-green-700 text-sm">Unsuspend</button></form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">No websites found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($websites->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800">
            {{ $websites->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
