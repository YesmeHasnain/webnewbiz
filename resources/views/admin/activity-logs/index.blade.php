@extends('admin.layouts.app')

@section('title', 'Activity Logs')

@section('content')
<div class="mb-6"><h1 class="text-2xl font-bold text-gray-800 dark:text-white">Activity Logs</h1><p class="text-sm text-gray-500 mt-1">View all platform activity</p></div>

<div class="bg-white dark:bg-dark-100 rounded-xl border border-gray-200 dark:border-gray-800 p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-4">
        <input type="text" name="action" value="{{ request('action') }}" placeholder="Filter by action..." class="flex-1 min-w-[200px] px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-dark-300 dark:text-white text-sm focus:ring-2 focus:ring-blue-500">
        <input type="date" name="from" value="{{ request('from') }}" class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-dark-300 dark:text-white text-sm">
        <input type="date" name="to" value="{{ request('to') }}" class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-dark-300 dark:text-white text-sm">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">Filter</button>
    </form>
</div>

<div class="bg-white dark:bg-dark-100 rounded-xl border border-gray-200 dark:border-gray-800 overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 dark:bg-dark-300">
            <tr>
                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Time</th>
                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">User</th>
                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Action</th>
                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Description</th>
                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">IP</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
            @forelse($logs as $log)
                <tr class="hover:bg-gray-50 dark:hover:bg-dark-300">
                    <td class="px-6 py-3 text-sm text-gray-500">{{ $log->created_at->format('M d, H:i:s') }}</td>
                    <td class="px-6 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $log->user?->name ?? 'System' }}</td>
                    <td class="px-6 py-3"><span class="text-xs px-2 py-0.5 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 font-mono">{{ $log->action }}</span></td>
                    <td class="px-6 py-3 text-sm text-gray-600 dark:text-gray-400">{{ Str::limit($log->description, 60) }}</td>
                    <td class="px-6 py-3 text-xs font-mono text-gray-500">{{ $log->ip_address }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-6 py-12 text-center text-gray-500">No activity logs yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($logs->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800">{{ $logs->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
