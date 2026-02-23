@extends('admin.layouts.app')

@section('title', 'Website: ' . $website->name)

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.websites.index') }}" class="text-sm text-blue-600 hover:text-blue-700">&larr; Back to Websites</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Website Info -->
    <div class="bg-white dark:bg-dark-100 rounded-xl border border-gray-200 dark:border-gray-800 p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Website Info</h3>
        <dl class="space-y-3">
            <div><dt class="text-xs text-gray-500">Name</dt><dd class="text-sm font-medium text-gray-800 dark:text-white">{{ $website->name }}</dd></div>
            <div><dt class="text-xs text-gray-500">Subdomain</dt><dd class="text-sm font-mono text-gray-600 dark:text-gray-400">{{ $website->subdomain }}{{ config('webnewbiz.subdomain_suffix') }}</dd></div>
            <div><dt class="text-xs text-gray-500">Custom Domain</dt><dd class="text-sm text-gray-600 dark:text-gray-400">{{ $website->custom_domain ?? 'None' }}</dd></div>
            <div><dt class="text-xs text-gray-500">Status</dt><dd><span class="px-2 py-1 text-xs rounded-full bg-{{ $website->status === 'active' ? 'green' : 'yellow' }}-100 text-{{ $website->status === 'active' ? 'green' : 'yellow' }}-700">{{ ucfirst($website->status) }}</span></dd></div>
            <div><dt class="text-xs text-gray-500">Owner</dt><dd class="text-sm text-gray-600 dark:text-gray-400">{{ $website->user?->name }} ({{ $website->user?->email }})</dd></div>
            <div><dt class="text-xs text-gray-500">Server</dt><dd class="text-sm text-gray-600 dark:text-gray-400">{{ $website->server?->name ?? 'Unassigned' }}</dd></div>
            <div><dt class="text-xs text-gray-500">WP Admin</dt><dd class="text-sm font-mono text-gray-600 dark:text-gray-400">{{ $website->wp_admin_user ?? 'N/A' }}</dd></div>
            <div><dt class="text-xs text-gray-500">AI Prompt</dt><dd class="text-sm text-gray-600 dark:text-gray-400">{{ Str::limit($website->ai_prompt, 100) ?? 'None' }}</dd></div>
            <div><dt class="text-xs text-gray-500">Created</dt><dd class="text-sm text-gray-600 dark:text-gray-400">{{ $website->created_at->format('M d, Y H:i') }}</dd></div>
        </dl>
        <div class="mt-4 flex gap-2">
            @if($website->status === 'active')
                <form method="POST" action="{{ route('admin.websites.suspend', $website) }}">@csrf <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm transition">Suspend</button></form>
            @elseif($website->status === 'suspended')
                <form method="POST" action="{{ route('admin.websites.unsuspend', $website) }}">@csrf <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition">Unsuspend</button></form>
            @endif
            <form method="POST" action="{{ route('admin.websites.destroy', $website) }}" onsubmit="return confirm('Delete this website?')">@csrf @method('DELETE') <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm transition">Delete</button></form>
        </div>
    </div>

    <!-- Domains & Plugins -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Domains -->
        <div class="bg-white dark:bg-dark-100 rounded-xl border border-gray-200 dark:border-gray-800 p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Domains ({{ $website->domains->count() }})</h3>
            <table class="w-full">
                <thead><tr><th class="text-left text-xs font-medium text-gray-500 pb-2">Domain</th><th class="text-left text-xs font-medium text-gray-500 pb-2">Type</th><th class="text-left text-xs font-medium text-gray-500 pb-2">DNS</th><th class="text-left text-xs font-medium text-gray-500 pb-2">SSL</th></tr></thead>
                <tbody>
                    @forelse($website->domains as $domain)
                        <tr><td class="py-2 text-sm text-gray-800 dark:text-gray-200">{{ $domain->domain }} @if($domain->is_primary)<span class="text-xs text-blue-600">(Primary)</span>@endif</td><td class="py-2 text-sm text-gray-500">{{ ucfirst($domain->type) }}</td><td class="py-2"><span class="text-xs px-2 py-0.5 rounded-full bg-{{ $domain->dns_status === 'active' ? 'green' : 'yellow' }}-100 text-{{ $domain->dns_status === 'active' ? 'green' : 'yellow' }}-700">{{ $domain->dns_status }}</span></td><td class="py-2"><span class="text-xs px-2 py-0.5 rounded-full bg-{{ $domain->ssl_status === 'active' ? 'green' : 'gray' }}-100 text-{{ $domain->ssl_status === 'active' ? 'green' : 'gray' }}-700">{{ $domain->ssl_status }}</span></td></tr>
                    @empty
                        <tr><td colspan="4" class="py-4 text-center text-gray-500 text-sm">No domains configured.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Plugins -->
        <div class="bg-white dark:bg-dark-100 rounded-xl border border-gray-200 dark:border-gray-800 p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Plugins ({{ $website->plugins->count() }})</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                @forelse($website->plugins as $plugin)
                    <div class="p-3 rounded-lg bg-gray-50 dark:bg-dark-300">
                        <p class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $plugin->name }}</p>
                        <p class="text-xs text-gray-500">{{ $plugin->is_active ? 'Active' : 'Inactive' }} &middot; v{{ $plugin->version ?? '?' }}</p>
                    </div>
                @empty
                    <p class="col-span-full text-gray-500 text-sm">No plugins installed.</p>
                @endforelse
            </div>
        </div>

        <!-- Backups -->
        <div class="bg-white dark:bg-dark-100 rounded-xl border border-gray-200 dark:border-gray-800 p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Backups ({{ $website->backups->count() }})</h3>
            <table class="w-full">
                <thead><tr><th class="text-left text-xs font-medium text-gray-500 pb-2">Date</th><th class="text-left text-xs font-medium text-gray-500 pb-2">Type</th><th class="text-left text-xs font-medium text-gray-500 pb-2">Size</th><th class="text-left text-xs font-medium text-gray-500 pb-2">Status</th></tr></thead>
                <tbody>
                    @forelse($website->backups as $backup)
                        <tr><td class="py-2 text-sm text-gray-600 dark:text-gray-400">{{ $backup->created_at->format('M d, Y H:i') }}</td><td class="py-2 text-sm text-gray-500">{{ ucfirst($backup->type) }}</td><td class="py-2 text-sm text-gray-500">{{ $backup->formatted_size }}</td><td class="py-2"><span class="text-xs px-2 py-0.5 rounded-full bg-{{ $backup->status === 'completed' ? 'green' : 'yellow' }}-100 text-{{ $backup->status === 'completed' ? 'green' : 'yellow' }}-700">{{ $backup->status }}</span></td></tr>
                    @empty
                        <tr><td colspan="4" class="py-4 text-center text-gray-500 text-sm">No backups yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
