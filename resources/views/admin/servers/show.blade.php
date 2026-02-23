@extends('admin.layouts.app')

@section('title', 'Server: ' . $server->name)

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.servers.index') }}" class="text-sm text-blue-600 hover:text-blue-700">&larr; Back to Servers</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Server Info -->
    <div class="bg-white dark:bg-dark-100 rounded-xl border border-gray-200 dark:border-gray-800 p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Server Info</h3>
        <dl class="space-y-3">
            <div><dt class="text-xs text-gray-500">Name</dt><dd class="text-sm font-medium text-gray-800 dark:text-white">{{ $server->name }}</dd></div>
            <div><dt class="text-xs text-gray-500">Provider</dt><dd class="text-sm text-gray-600 dark:text-gray-400">{{ ucfirst($server->provider) }}</dd></div>
            <div><dt class="text-xs text-gray-500">IP Address</dt><dd class="text-sm font-mono text-gray-600 dark:text-gray-400">{{ $server->ip_address ?? 'Pending' }}</dd></div>
            <div><dt class="text-xs text-gray-500">Region</dt><dd class="text-sm text-gray-600 dark:text-gray-400">{{ $server->region }}</dd></div>
            <div><dt class="text-xs text-gray-500">Size</dt><dd class="text-sm text-gray-600 dark:text-gray-400">{{ $server->size }}</dd></div>
            <div><dt class="text-xs text-gray-500">Status</dt><dd><span class="px-2 py-1 text-xs rounded-full bg-{{ $server->status === 'active' ? 'green' : 'yellow' }}-100 text-{{ $server->status === 'active' ? 'green' : 'yellow' }}-700">{{ ucfirst($server->status) }}</span></dd></div>
            <div><dt class="text-xs text-gray-500">Last Health Check</dt><dd class="text-sm text-gray-600 dark:text-gray-400">{{ $server->last_health_check?->diffForHumans() ?? 'Never' }}</dd></div>
        </dl>
        <div class="mt-4 flex gap-2">
            <form method="POST" action="{{ route('admin.servers.provision', $server) }}">@csrf <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">Provision</button></form>
            <form method="POST" action="{{ route('admin.servers.health-check', $server) }}">@csrf <button class="bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg text-sm transition">Health Check</button></form>
        </div>
    </div>

    <!-- Resources -->
    <div class="lg:col-span-2 bg-white dark:bg-dark-100 rounded-xl border border-gray-200 dark:border-gray-800 p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Websites on Server ({{ $server->websites->count() }})</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-dark-300">
                    <tr>
                        <th class="text-left px-4 py-2 text-xs font-medium text-gray-500 uppercase">Website</th>
                        <th class="text-left px-4 py-2 text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="text-left px-4 py-2 text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse($server->websites as $website)
                        <tr>
                            <td class="px-4 py-3"><a href="{{ route('admin.websites.show', $website) }}" class="text-sm text-blue-600 hover:text-blue-700">{{ $website->name }}</a></td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $website->user?->name }}</td>
                            <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full bg-{{ $website->status === 'active' ? 'green' : 'yellow' }}-100 text-{{ $website->status === 'active' ? 'green' : 'yellow' }}-700">{{ ucfirst($website->status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-4 py-8 text-center text-gray-500 text-sm">No websites on this server.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
