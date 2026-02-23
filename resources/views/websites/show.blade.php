@extends('layouts.app')
@section('title', $website->name)

@section('content')
    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <a href="{{ route('websites.index') }}" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <h1 class="text-2xl font-bold text-gray-900">{{ $website->name }}</h1>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                    {{ $website->status === 'active' ? 'bg-green-100 text-green-700' : '' }}
                    {{ $website->status === 'provisioning' ? 'bg-yellow-100 text-yellow-700' : '' }}
                    {{ $website->status === 'error' ? 'bg-red-100 text-red-700' : '' }}
                    {{ $website->status === 'suspended' ? 'bg-gray-100 text-gray-700' : '' }}
                ">{{ ucfirst($website->status) }}</span>
            </div>
            <p class="text-gray-500">Created {{ $website->created_at->format('M d, Y') }}</p>
        </div>
        <div class="flex items-center gap-3">
            @if($website->status === 'active')
                <a href="{{ $website->url }}" target="_blank" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg text-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Visit Site
                </a>
            @endif
            <form method="POST" action="{{ route('websites.destroy', $website) }}" x-data
                @submit.prevent="if(confirm('Are you sure you want to delete this website? This cannot be undone.')) $el.submit()">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 text-red-600 hover:text-red-700 hover:bg-red-50 font-medium px-4 py-2 rounded-lg text-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Delete
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Info --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Overview --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Overview</h2>
                <dl class="grid grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm text-gray-500">Subdomain</dt>
                        <dd class="text-sm font-medium text-gray-900 mt-0.5">{{ $website->subdomain }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Business Type</dt>
                        <dd class="text-sm font-medium text-gray-900 mt-0.5 capitalize">{{ $website->ai_business_type ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Style</dt>
                        <dd class="text-sm font-medium text-gray-900 mt-0.5 capitalize">{{ $website->ai_style ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Server</dt>
                        <dd class="text-sm font-medium text-gray-900 mt-0.5">{{ $website->server->name ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Storage Used</dt>
                        <dd class="text-sm font-medium text-gray-900 mt-0.5">{{ $website->storage_used_mb ?? 0 }} MB</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">WordPress Version</dt>
                        <dd class="text-sm font-medium text-gray-900 mt-0.5">{{ $website->wp_version ?? 'N/A' }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Domains --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Domains</h2>
                @if($website->domains->count())
                    <div class="space-y-3">
                        @foreach($website->domains as $domain)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $domain->domain }}</p>
                                        <p class="text-xs text-gray-500">{{ ucfirst($domain->type) }} {{ $domain->is_primary ? '- Primary' : '' }}</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $domain->dns_status === 'active' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ ucfirst($domain->dns_status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500">No domains configured.</p>
                @endif
            </div>

            {{-- Plugins --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Plugins</h2>
                @if($website->plugins->count())
                    <div class="space-y-2">
                        @foreach($website->plugins as $plugin)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $plugin->name }}</p>
                                    <p class="text-xs text-gray-500">v{{ $plugin->version ?? 'Unknown' }}</p>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $plugin->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $plugin->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500">No plugins installed.</p>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Quick Links --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Links</h2>
                <div class="space-y-2">
                    @if($website->status === 'active')
                        <a href="{{ $website->url }}" target="_blank" class="flex items-center gap-3 p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                            <span class="text-sm font-medium text-gray-700">Visit Website</span>
                        </a>
                        <a href="{{ $website->url }}/wp-admin" target="_blank" class="flex items-center gap-3 p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="text-sm font-medium text-gray-700">WP Admin</span>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Themes --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Themes</h2>
                @if($website->themes->count())
                    @foreach($website->themes as $theme)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $theme->name }}</p>
                                <p class="text-xs text-gray-500">v{{ $theme->version ?? 'Unknown' }}</p>
                            </div>
                            @if($theme->is_active)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Active</span>
                            @endif
                        </div>
                    @endforeach
                @else
                    <p class="text-sm text-gray-500">No themes installed.</p>
                @endif
            </div>

            {{-- Backups --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Backups</h2>
                @if($website->backups->count())
                    <div class="space-y-2">
                        @foreach($website->backups->take(5) as $backup)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $backup->created_at->format('M d, Y') }}</p>
                                    <p class="text-xs text-gray-500">{{ $backup->size_mb ?? 0 }} MB</p>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $backup->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ ucfirst($backup->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500">No backups yet.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
