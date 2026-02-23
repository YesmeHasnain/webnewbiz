@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Websites</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalWebsites }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
            </div>
        </div>
        <p class="text-xs text-green-500 mt-2">{{ $activeWebsites }} active</p>
    </div>
    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Users</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalUsers }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
        </div>
        <p class="text-xs text-green-500 mt-2">{{ $activeUsers }} active</p>
    </div>
    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Servers</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalServers }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
            </div>
        </div>
        <p class="text-xs text-green-500 mt-2">{{ $activeServers }} active</p>
    </div>
    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Server Health</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $activeServers > 0 ? 'OK' : 'N/A' }}</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
        </div>
        <p class="text-xs text-gray-500 mt-2">All systems operational</p>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white dark:bg-dark-100 rounded-xl p-6 border border-gray-200 dark:border-gray-800">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Websites Created (7 Days)</h3>
        <canvas id="websitesChart" height="200"></canvas>
    </div>
    <div class="bg-white dark:bg-dark-100 rounded-xl p-6 border border-gray-200 dark:border-gray-800">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">New Users (7 Days)</h3>
        <canvas id="usersChart" height="200"></canvas>
    </div>
</div>

<!-- Server Health + Recent Activity -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Server Health -->
    <div class="bg-white dark:bg-dark-100 rounded-xl p-6 border border-gray-200 dark:border-gray-800">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Server Health</h3>
        @forelse($servers as $server)
            <div class="mb-4 last:mb-0">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $server->name }}</span>
                    <span class="text-xs px-2 py-1 rounded-full {{ $server->status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700' }}">{{ ucfirst($server->status) }}</span>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <div>
                        <p class="text-xs text-gray-500">CPU</p>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-1">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $server->cpu_usage ?? 0 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Memory</p>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-1">
                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ $server->memory_usage ?? 0 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Disk</p>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-1">
                            <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ $server->disk_usage ?? 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-gray-500 text-sm">No servers configured yet.</p>
        @endforelse
    </div>

    <!-- Recent Activity -->
    <div class="bg-white dark:bg-dark-100 rounded-xl p-6 border border-gray-200 dark:border-gray-800">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Recent Activity</h3>
        <div class="space-y-3">
            @forelse($recentActivity as $log)
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-800 dark:text-gray-200 truncate">{{ $log->description ?? $log->action }}</p>
                        <p class="text-xs text-gray-500">{{ $log->user?->name ?? 'System' }} &middot; {{ $log->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-sm">No recent activity.</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Recent Websites -->
<div class="bg-white dark:bg-dark-100 rounded-xl border border-gray-200 dark:border-gray-800">
    <div class="p-6 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Recent Websites</h3>
        <a href="{{ route('admin.websites.index') }}" class="text-sm text-blue-600 hover:text-blue-700">View All</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 dark:bg-dark-300">
                <tr>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Website</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">User</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Server</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Created</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                @forelse($recentWebsites as $website)
                    <tr class="hover:bg-gray-50 dark:hover:bg-dark-300">
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.websites.show', $website) }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">{{ $website->name }}</a>
                            <p class="text-xs text-gray-500">{{ $website->subdomain }}{{ config('webnewbiz.subdomain_suffix') }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $website->user?->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4">
                            @php
                                $statusColors = ['active' => 'green', 'provisioning' => 'yellow', 'pending' => 'gray', 'suspended' => 'red', 'error' => 'red'];
                                $color = $statusColors[$website->status] ?? 'gray';
                            @endphp
                            <span class="px-2 py-1 text-xs rounded-full bg-{{ $color }}-100 text-{{ $color }}-700 dark:bg-{{ $color }}-900/30 dark:text-{{ $color }}-400">{{ ucfirst($website->status) }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $website->server?->name ?? 'Unassigned' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $website->created_at->diffForHumans() }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">No websites yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: 'rgba(156, 163, 175, 0.1)' } },
            x: { grid: { display: false } }
        }
    };

    new Chart(document.getElementById('websitesChart'), {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [{ data: @json($websitesData), backgroundColor: 'rgba(59, 130, 246, 0.5)', borderColor: 'rgb(59, 130, 246)', borderWidth: 1, borderRadius: 4 }]
        },
        options: chartOptions
    });

    new Chart(document.getElementById('usersChart'), {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{ data: @json($usersData), borderColor: 'rgb(34, 197, 94)', backgroundColor: 'rgba(34, 197, 94, 0.1)', fill: true, tension: 0.4 }]
        },
        options: chartOptions
    });
</script>
@endsection
