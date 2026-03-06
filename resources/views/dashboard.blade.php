@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    {{-- Greeting --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Welcome back, {{ auth()->user()->name }}</h1>
            <p class="text-slate-500 text-sm mt-1">Manage and build your AI-powered websites</p>
        </div>
        <a href="{{ route('builder.index') }}" class="sm:hidden inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-semibold px-5 py-2.5 rounded-xl text-sm transition-all shadow-sm shadow-indigo-500/20 self-start">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Create Website
        </a>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        {{-- Total Sites --}}
        <div class="bg-white rounded-2xl border border-slate-200/60 p-5 shadow-sm hover:shadow-md transition-all duration-300" style="animation: fadeInUp 0.4s ease-out both;">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                </div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Sites</p>
            </div>
            <p class="text-3xl font-bold text-slate-900">{{ $totalWebsites }}</p>
        </div>

        {{-- Active --}}
        <div class="bg-white rounded-2xl border border-slate-200/60 p-5 shadow-sm hover:shadow-md transition-all duration-300" style="animation: fadeInUp 0.4s ease-out both; animation-delay: 0.05s;">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Active</p>
            </div>
            <p class="text-3xl font-bold text-emerald-600">{{ $activeWebsites }}</p>
        </div>

        {{-- Building --}}
        <div class="bg-white rounded-2xl border border-slate-200/60 p-5 shadow-sm hover:shadow-md transition-all duration-300" style="animation: fadeInUp 0.4s ease-out both; animation-delay: 0.1s;">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600 animate-spin" style="animation-duration: 3s;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Building</p>
            </div>
            <p class="text-3xl font-bold text-amber-600">{{ $buildingWebsites }}</p>
        </div>

        {{-- Plan Limit --}}
        <div class="bg-white rounded-2xl border border-slate-200/60 p-5 shadow-sm hover:shadow-md transition-all duration-300" style="animation: fadeInUp 0.4s ease-out both; animation-delay: 0.15s;">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-violet-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Plan Limit</p>
            </div>
            <p class="text-3xl font-bold text-slate-900">{{ $totalWebsites }}<span class="text-lg text-slate-400">/{{ $maxWebsites > 0 && $maxWebsites < 999999 ? $maxWebsites : '&infin;' }}</span></p>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        {{-- Left Column: Websites --}}
        <div class="xl:col-span-2">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-lg font-bold text-slate-900">My Websites</h2>
                @if($totalWebsites > 6)
                    <a href="{{ route('websites.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium transition-colors">View all &rarr;</a>
                @endif
            </div>

            @if($websites->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @foreach($websites as $i => $website)
                        <div class="relative bg-white rounded-2xl border border-slate-200/60 overflow-hidden group transition-all duration-300 hover:shadow-xl hover:shadow-indigo-500/10 hover:border-indigo-200" style="animation: fadeInUp 0.4s ease-out both; animation-delay: {{ $i * 0.05 }}s;">
                            {{-- Thumbnail --}}
                            <div class="relative h-40 bg-gradient-to-br from-slate-100 to-slate-50 overflow-hidden">
                                @if($website->screenshot_path)
                                    <img src="{{ $website->screenshot_path }}" alt="{{ $website->name }}" class="w-full h-full object-cover object-top transition-transform duration-500 group-hover:scale-105">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-indigo-50 to-violet-50">
                                        <svg class="w-14 h-14 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                    </div>
                                @endif

                                {{-- Status Badge --}}
                                <div class="absolute top-3 left-3">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold backdrop-blur-sm shadow-sm
                                        {{ $website->status === 'active' ? 'bg-emerald-500/90 text-white' : '' }}
                                        {{ $website->status === 'provisioning' ? 'bg-amber-500/90 text-white' : '' }}
                                        {{ $website->status === 'error' ? 'bg-red-500/90 text-white' : '' }}
                                        {{ $website->status === 'suspended' ? 'bg-slate-500/90 text-white' : '' }}
                                    ">
                                        @if($website->status === 'active')
                                            <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span>
                                        @endif
                                        {{ ucfirst($website->status) }}
                                    </span>
                                </div>

                                {{-- Quick hover actions --}}
                                @if($website->status === 'active')
                                    <div class="absolute top-3 right-3 flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-1 group-hover:translate-y-0">
                                        <a href="{{ route('websites.wp-admin', $website) }}" target="_blank"
                                           class="w-8 h-8 bg-white/90 backdrop-blur-sm rounded-lg flex items-center justify-center text-slate-700 hover:bg-indigo-600 hover:text-white transition-all shadow-sm"
                                           title="WordPress Admin">
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.49 2 2 6.49 2 12s4.49 10 10 10 10-4.49 10-10S17.51 2 12 2zm-1.03 15.78l-3.5-9.56h1.85l2.11 6.34.8-2.77L11 8.22h1.85l2.78 7.6 1.06-3.93c.46-1.56.68-2.68.68-3.36 0-.65-.17-1.1-.5-1.35-.24-.18-.64-.28-1.18-.3L15.79 7h4.11l-.1.18c-.58.05-1 .18-1.26.38-.43.33-.82 1.05-1.15 2.16l-3.28 10.96h-.18l-2.96-8.15-2.73 8.15h-.18l-3.5-10.96c-.32-1.01-.65-1.67-.99-1.98-.24-.22-.62-.39-1.14-.49L4.33 7h4.75l-.1.18c-.71.03-1.2.14-1.48.31-.28.18-.42.44-.42.79 0 .43.19 1.33.58 2.7l1.88 6.24L10.72 14l-.72-2.31h1.85l.16.44 1.92-5.91h1.86L12.97 17.78h-.18z"/></svg>
                                        </a>
                                        <a href="{{ $website->url }}" target="_blank"
                                           class="w-8 h-8 bg-white/90 backdrop-blur-sm rounded-lg flex items-center justify-center text-slate-700 hover:bg-indigo-600 hover:text-white transition-all shadow-sm"
                                           title="View Website">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </a>
                                    </div>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="p-4">
                                <h3 class="font-semibold text-slate-900 truncate">{{ $website->name }}</h3>
                                <p class="text-sm text-slate-400 truncate mt-0.5">{{ $website->subdomain }}</p>

                                <div class="flex items-center gap-2 mt-3 pt-3 border-t border-slate-100">
                                    @if($website->status === 'active')
                                        <a href="{{ route('websites.wp-admin', $website) }}" target="_blank"
                                           class="flex-1 inline-flex items-center justify-center gap-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-medium py-2 px-3 rounded-lg transition">
                                            WP Admin
                                        </a>
                                        <a href="{{ $website->url }}" target="_blank"
                                           class="flex-1 inline-flex items-center justify-center gap-1.5 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white text-xs font-medium py-2 px-3 rounded-lg transition-all shadow-sm shadow-indigo-500/20">
                                            View Site
                                        </a>
                                        <a href="{{ route('websites.show', $website) }}"
                                           class="inline-flex items-center justify-center border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-medium py-2 px-3 rounded-lg transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </a>
                                        <button type="button" @click="$dispatch('open-delete-modal', { id: {{ $website->id }}, name: '{{ addslashes($website->name) }}' })" class="inline-flex items-center justify-center border border-slate-200 hover:bg-red-50 hover:border-red-200 text-slate-400 hover:text-red-500 text-xs font-medium py-2 px-3 rounded-lg transition" title="Delete">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    @elseif($website->status === 'provisioning')
                                        <a href="{{ route('builder.status', $website) }}"
                                           class="flex-1 inline-flex items-center justify-center gap-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-medium py-2 px-3 rounded-lg transition">
                                            <svg class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                            View Progress
                                        </a>
                                    @elseif($website->status === 'error')
                                        <form method="POST" action="{{ route('websites.retry', $website) }}" class="flex-1">
                                            @csrf
                                            <button type="submit"
                                               class="w-full inline-flex items-center justify-center gap-1.5 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white text-xs font-medium py-2 px-3 rounded-lg transition-all shadow-sm shadow-indigo-500/20">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                Retry Build
                                            </button>
                                        </form>
                                        <a href="{{ route('websites.show', $website) }}"
                                           class="inline-flex items-center justify-center border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-medium py-2 px-3 rounded-lg transition">
                                            Details
                                        </a>
                                        <button type="button" @click="$dispatch('open-delete-modal', { id: {{ $website->id }}, name: '{{ addslashes($website->name) }}' })" class="inline-flex items-center justify-center border border-slate-200 hover:bg-red-50 hover:border-red-200 text-slate-400 hover:text-red-500 text-xs font-medium py-2 px-3 rounded-lg transition" title="Delete">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    @else
                                        <a href="{{ route('websites.show', $website) }}"
                                           class="flex-1 inline-flex items-center justify-center gap-1.5 border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-medium py-2 px-3 rounded-lg transition">
                                            View Details
                                        </a>
                                        <button type="button" @click="$dispatch('open-delete-modal', { id: {{ $website->id }}, name: '{{ addslashes($website->name) }}' })" class="inline-flex items-center justify-center border border-slate-200 hover:bg-red-50 hover:border-red-200 text-slate-400 hover:text-red-500 text-xs font-medium py-2 px-3 rounded-lg transition" title="Delete">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    @endif
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Empty State --}}
                <div class="bg-white rounded-2xl border border-slate-200/60 p-16 text-center shadow-sm" style="animation: fadeInUp 0.5s ease-out both;">
                    <div class="w-20 h-20 bg-gradient-to-br from-indigo-100 to-violet-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">No websites yet</h3>
                    <p class="text-slate-500 mb-8 text-sm max-w-sm mx-auto">Create your first AI-powered website in minutes. Just describe your business and we'll handle the rest.</p>
                    <a href="{{ route('builder.index') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-semibold px-7 py-3.5 rounded-xl transition-all duration-300 shadow-lg shadow-indigo-500/20 hover:shadow-xl hover:shadow-indigo-500/30 hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Build Your First Website
                    </a>
                </div>
            @endif
        </div>

        {{-- Right Sidebar --}}
        <div class="space-y-6">
            {{-- Current Plan --}}
            @if($plan)
                <div class="bg-white rounded-2xl border border-slate-200/60 p-5 shadow-sm" style="animation: fadeInUp 0.4s ease-out both; animation-delay: 0.1s;">
                    <h3 class="text-sm font-bold text-slate-900 mb-4">Current Plan</h3>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-violet-500 rounded-xl flex items-center justify-center shadow-md shadow-indigo-500/20">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        </div>
                        <div>
                            <p class="font-bold text-slate-900">{{ $plan->name }}</p>
                            <p class="text-xs text-slate-400">
                                @if($plan->price > 0)
                                    ${{ number_format($plan->price, 0) }}/{{ $plan->billing_cycle }}
                                @else
                                    Free Plan
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- Usage bar --}}
                    @php $limit = ($maxWebsites > 0 && $maxWebsites < 999999) ? $maxWebsites : 0; @endphp
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-500">Websites Used</span>
                            <span class="font-semibold text-slate-700">{{ $totalWebsites }} / {{ $limit > 0 ? $limit : 'Unlimited' }}</span>
                        </div>
                        @if($limit > 0)
                            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-indigo-500 to-violet-500 rounded-full transition-all duration-1000" style="width: {{ min(100, ($totalWebsites / max(1, $limit)) * 100) }}%"></div>
                            </div>
                        @else
                            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-emerald-400 to-emerald-500 rounded-full" style="width: 5%"></div>
                            </div>
                        @endif
                    </div>

                    {{-- Plan features --}}
                    @if($plan->features && is_array($plan->features))
                        <div class="mt-4 pt-4 border-t border-slate-100">
                            <div class="space-y-1.5">
                                @foreach(array_slice($plan->features, 0, 4) as $feature)
                                    <div class="flex items-center gap-2 text-xs text-slate-500">
                                        <svg class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        {{ $feature }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Recent Activity --}}
            @if($recentWebsites->count() > 0)
                <div class="bg-white rounded-2xl border border-slate-200/60 p-5 shadow-sm" style="animation: fadeInUp 0.4s ease-out both; animation-delay: 0.15s;">
                    <h3 class="text-sm font-bold text-slate-900 mb-4">Recent Activity</h3>
                    <div class="space-y-3">
                        @foreach($recentWebsites as $site)
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                                    {{ $site->status === 'active' ? 'bg-emerald-50' : '' }}
                                    {{ $site->status === 'provisioning' ? 'bg-amber-50' : '' }}
                                    {{ $site->status === 'error' ? 'bg-red-50' : '' }}
                                    {{ !in_array($site->status, ['active', 'provisioning', 'error']) ? 'bg-slate-50' : '' }}
                                ">
                                    @if($site->status === 'active')
                                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    @elseif($site->status === 'provisioning')
                                        <svg class="w-4 h-4 text-amber-500 animate-spin" style="animation-duration:2s" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    @elseif($site->status === 'error')
                                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @else
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-slate-800 truncate">{{ $site->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $site->created_at->diffForHumans() }}</p>
                                </div>
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full
                                    {{ $site->status === 'active' ? 'bg-emerald-50 text-emerald-600' : '' }}
                                    {{ $site->status === 'provisioning' ? 'bg-amber-50 text-amber-600' : '' }}
                                    {{ $site->status === 'error' ? 'bg-red-50 text-red-600' : '' }}
                                    {{ !in_array($site->status, ['active', 'provisioning', 'error']) ? 'bg-slate-50 text-slate-500' : '' }}
                                ">{{ ucfirst($site->status) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
    {{-- Delete Confirmation Modal (Global) --}}
    <div x-data="{ open: false, deleteId: null, deleteName: '' }"
         @open-delete-modal.window="open = true; deleteId = $event.detail.id; deleteName = $event.detail.name"
         @keydown.escape.window="open = false">

        <div x-show="open" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
             style="position: fixed;">

            {{-- Overlay --}}
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false"></div>

            {{-- Modal --}}
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                 class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden">

                {{-- Red top accent --}}
                <div class="h-1 bg-gradient-to-r from-red-500 to-rose-500"></div>

                <div class="p-6 text-center">
                    {{-- Icon --}}
                    <div class="w-14 h-14 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>

                    {{-- Title --}}
                    <h3 class="text-lg font-bold text-slate-900 mb-1">Delete Website</h3>
                    <p class="text-sm text-slate-500 mb-1">Are you sure you want to delete</p>
                    <p class="text-sm font-semibold text-slate-800 mb-4" x-text="deleteName"></p>

                    {{-- Warning --}}
                    <div class="bg-red-50 border border-red-100 rounded-xl px-4 py-3 mb-6">
                        <div class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <p class="text-xs text-red-600 text-left leading-relaxed">This will permanently delete the WordPress database, all site files, and DNS records. This action <strong>cannot be undone</strong>.</p>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-3">
                        <button @click="open = false" class="flex-1 px-4 py-2.5 text-sm font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                            Cancel
                        </button>
                        <button type="button"
                            @click="
                                let f = document.createElement('form');
                                f.method = 'POST';
                                f.action = '{{ url("/websites") }}/' + deleteId;
                                let t = document.createElement('input');
                                t.type = 'hidden'; t.name = '_token'; t.value = '{{ csrf_token() }}';
                                let m = document.createElement('input');
                                m.type = 'hidden'; m.name = '_method'; m.value = 'DELETE';
                                f.appendChild(t);
                                f.appendChild(m);
                                document.body.appendChild(f);
                                f.submit();
                            "
                            class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-red-500 hover:bg-red-600 rounded-xl transition-colors shadow-sm shadow-red-500/20">
                            Yes, Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
