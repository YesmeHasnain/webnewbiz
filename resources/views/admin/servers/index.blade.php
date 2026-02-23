@extends('admin.layouts.app')

@section('title', 'Servers')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Servers</h1>
        <p class="text-sm text-gray-500 mt-1">Manage your hosting servers</p>
    </div>
    <a href="{{ route('admin.servers.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
        + Add Server
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($servers as $server)
        <div class="bg-white dark:bg-dark-100 rounded-xl border border-gray-200 dark:border-gray-800 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800 dark:text-white">{{ $server->name }}</h3>
                @php
                    $colors = ['active' => 'green', 'provisioning' => 'yellow', 'inactive' => 'gray', 'error' => 'red'];
                    $c = $colors[$server->status] ?? 'gray';
                @endphp
                <span class="px-2 py-1 text-xs rounded-full bg-{{ $c }}-100 text-{{ $c }}-700 dark:bg-{{ $c }}-900/30 dark:text-{{ $c }}-400">{{ ucfirst($server->status) }}</span>
            </div>
            <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400 mb-4">
                <p>IP: <span class="font-mono">{{ $server->ip_address ?? 'Pending' }}</span></p>
                <p>Region: {{ $server->region ?? 'N/A' }}</p>
                <p>Websites: {{ $server->websites_count ?? $server->current_websites }}/{{ $server->max_websites }}</p>
            </div>
            <!-- Resource Gauges -->
            <div class="space-y-2 mb-4">
                <div>
                    <div class="flex justify-between text-xs text-gray-500 mb-1"><span>CPU</span><span>{{ number_format($server->cpu_usage ?? 0, 1) }}%</span></div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                        <div class="bg-blue-600 h-1.5 rounded-full transition-all" style="width: {{ $server->cpu_usage ?? 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-xs text-gray-500 mb-1"><span>Memory</span><span>{{ number_format($server->memory_usage ?? 0, 1) }}%</span></div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                        <div class="bg-green-600 h-1.5 rounded-full transition-all" style="width: {{ $server->memory_usage ?? 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-xs text-gray-500 mb-1"><span>Disk</span><span>{{ number_format($server->disk_usage ?? 0, 1) }}%</span></div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                        <div class="bg-yellow-600 h-1.5 rounded-full transition-all" style="width: {{ $server->disk_usage ?? 0 }}%"></div>
                    </div>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.servers.show', $server) }}" class="flex-1 text-center bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 px-3 py-2 rounded-lg text-sm transition">Details</a>
                <form method="POST" action="{{ route('admin.servers.health-check', $server) }}" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/40 text-blue-600 px-3 py-2 rounded-lg text-sm transition">Health Check</button>
                </form>
            </div>
        </div>
    @empty
        <div class="col-span-full text-center py-12 text-gray-500">
            <p class="text-lg mb-2">No servers yet</p>
            <p class="text-sm">Add your first server to start hosting websites.</p>
        </div>
    @endforelse
</div>
@endsection
