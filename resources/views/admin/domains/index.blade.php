@extends('admin.layouts.app')

@section('title', 'Domains')

@section('content')
<div class="mb-6"><h1 class="text-2xl font-bold text-gray-800 dark:text-white">Domains</h1><p class="text-sm text-gray-500 mt-1">Manage all domains across websites</p></div>

<div class="bg-white dark:bg-dark-100 rounded-xl border border-gray-200 dark:border-gray-800 p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-4">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search domains..." class="flex-1 min-w-[200px] px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-dark-300 dark:text-white text-sm focus:ring-2 focus:ring-blue-500">
        <select name="ssl_status" class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-dark-300 dark:text-white text-sm">
            <option value="">All SSL Status</option>
            <option value="active" {{ request('ssl_status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="pending" {{ request('ssl_status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="none" {{ request('ssl_status') === 'none' ? 'selected' : '' }}>None</option>
        </select>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">Filter</button>
    </form>
</div>

<div class="bg-white dark:bg-dark-100 rounded-xl border border-gray-200 dark:border-gray-800 overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 dark:bg-dark-300">
            <tr>
                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Domain</th>
                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Website</th>
                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Type</th>
                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">DNS</th>
                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">SSL</th>
                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
            @forelse($domains as $domain)
                <tr class="hover:bg-gray-50 dark:hover:bg-dark-300">
                    <td class="px-6 py-4"><span class="text-sm font-medium text-gray-800 dark:text-white">{{ $domain->domain }}</span> @if($domain->is_primary) <span class="text-xs text-blue-600">(Primary)</span> @endif</td>
                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $domain->website?->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4"><span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400">{{ ucfirst($domain->type) }}</span></td>
                    <td class="px-6 py-4"><span class="text-xs px-2 py-0.5 rounded-full bg-{{ $domain->dns_status === 'active' ? 'green' : 'yellow' }}-100 text-{{ $domain->dns_status === 'active' ? 'green' : 'yellow' }}-700">{{ $domain->dns_status }}</span></td>
                    <td class="px-6 py-4"><span class="text-xs px-2 py-0.5 rounded-full bg-{{ $domain->ssl_status === 'active' ? 'green' : 'gray' }}-100 text-{{ $domain->ssl_status === 'active' ? 'green' : 'gray' }}-700">{{ $domain->ssl_status }}</span></td>
                    <td class="px-6 py-4"><form method="POST" action="{{ route('admin.domains.verify', $domain) }}" class="inline">@csrf <button class="text-blue-600 hover:text-blue-700 text-sm">Verify DNS</button></form></td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">No domains found.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($domains->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800">{{ $domains->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
