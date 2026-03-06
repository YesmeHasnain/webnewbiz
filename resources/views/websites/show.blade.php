@extends('layouts.app')
@section('title', $website->name)

@section('content')
<div x-data="websiteDashboard({{ $website->id }}, '{{ $website->wp_db_name }}')">
    {{-- Top Bar --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-indigo-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-xl font-bold text-slate-900">{{ $website->name }}</h1>
                <p class="text-sm text-slate-500">{{ $website->subdomain }}</p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold
                {{ $website->status === 'active' ? 'bg-emerald-50 text-emerald-700' : '' }}
                {{ $website->status === 'provisioning' ? 'bg-amber-50 text-amber-700' : '' }}
                {{ $website->status === 'error' ? 'bg-red-50 text-red-700' : '' }}
                {{ $website->status === 'suspended' ? 'bg-slate-100 text-slate-700' : '' }}
            ">
                @if($website->status === 'active')<span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>@endif
                {{ ucfirst($website->status) }}
            </span>
            @if($website->health_score !== null)
            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-bold"
                  :class="{'bg-emerald-50 text-emerald-700': {{ $website->health_score }} >= 70, 'bg-amber-50 text-amber-700': {{ $website->health_score }} >= 40 && {{ $website->health_score }} < 70, 'bg-red-50 text-red-700': {{ $website->health_score }} < 40}">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ $website->health_score }}/100
            </span>
            @endif
        </div>
        <div class="flex items-center gap-2">
            @if($website->status === 'active')
            <a href="{{ $website->url }}" target="_blank" class="inline-flex items-center gap-1.5 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white text-sm font-medium px-4 py-2 rounded-xl shadow-md shadow-indigo-500/20 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                Visit Site
            </a>
            @endif
            <form method="POST" action="{{ route('websites.destroy', $website) }}" x-data
                @submit.prevent="if(confirm('Delete this website permanently?')) $el.submit()">
                @csrf @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-1.5 text-red-500 hover:text-red-600 hover:bg-red-50 text-sm font-medium px-3 py-2 rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Delete
                </button>
            </form>
        </div>
    </div>

    @if($website->status === 'active')
    {{-- Tab Navigation --}}
    <div class="flex gap-1 mb-6 bg-white rounded-2xl border border-slate-200/60 p-1.5 shadow-sm overflow-x-auto">
        <template x-for="tab in tabs" :key="tab.key">
            <button @click="activeTab = tab.key"
                :class="activeTab === tab.key ? 'bg-gradient-to-r from-indigo-600 to-violet-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium transition-all whitespace-nowrap">
                <span x-html="tab.icon"></span>
                <span x-text="tab.label"></span>
            </button>
        </template>
    </div>

    {{-- ═══ OVERVIEW TAB ═══ --}}
    <div x-show="activeTab === 'overview'" x-cloak>
        {{-- Quick Actions Grid --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
            <a href="{{ $website->url }}" target="_blank" class="bg-white rounded-2xl border border-slate-200/60 p-4 hover:border-indigo-300 hover:shadow-lg hover:shadow-indigo-500/10 transition-all group text-center">
                <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center mx-auto mb-2 group-hover:bg-gradient-to-br group-hover:from-indigo-500 group-hover:to-violet-500 transition-all">
                    <svg class="w-5 h-5 text-indigo-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
                <h3 class="font-semibold text-slate-900 text-xs">View Site</h3>
            </a>
            <a href="{{ route('websites.wp-admin', $website) }}" target="_blank" class="bg-white rounded-2xl border border-slate-200/60 p-4 hover:border-violet-300 hover:shadow-lg hover:shadow-violet-500/10 transition-all group text-center">
                <div class="w-10 h-10 bg-violet-50 rounded-xl flex items-center justify-center mx-auto mb-2 group-hover:bg-gradient-to-br group-hover:from-violet-500 group-hover:to-purple-500 transition-all">
                    <svg class="w-5 h-5 text-violet-600 group-hover:text-white transition-colors" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.49 2 2 6.49 2 12s4.49 10 10 10 10-4.49 10-10S17.51 2 12 2z"/></svg>
                </div>
                <h3 class="font-semibold text-slate-900 text-xs">WP Admin</h3>
            </a>
            <a href="{{ $website->url }}/wp-admin/edit.php?post_type=page" target="_blank" class="bg-white rounded-2xl border border-slate-200/60 p-4 hover:border-pink-300 hover:shadow-lg hover:shadow-pink-500/10 transition-all group text-center">
                <div class="w-10 h-10 bg-pink-50 rounded-xl flex items-center justify-center mx-auto mb-2 group-hover:bg-gradient-to-br group-hover:from-pink-500 group-hover:to-rose-500 transition-all">
                    <svg class="w-5 h-5 text-pink-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <h3 class="font-semibold text-slate-900 text-xs">Edit Pages</h3>
            </a>
            <button @click="activeTab = 'health'; runHealthCheck()" class="bg-white rounded-2xl border border-slate-200/60 p-4 hover:border-emerald-300 hover:shadow-lg hover:shadow-emerald-500/10 transition-all group text-center">
                <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center mx-auto mb-2 group-hover:bg-gradient-to-br group-hover:from-emerald-500 group-hover:to-teal-500 transition-all">
                    <svg class="w-5 h-5 text-emerald-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="font-semibold text-slate-900 text-xs">Health Check</h3>
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Preview --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden shadow-sm">
                    <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                        <h2 class="font-bold text-slate-900 text-sm">Site Preview</h2>
                        <div class="flex gap-2">
                            <button @click="previewMode='desktop'" :class="previewMode==='desktop' ? 'bg-indigo-100 text-indigo-700' : 'text-slate-400'" class="p-1.5 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </button>
                            <button @click="previewMode='tablet'" :class="previewMode==='tablet' ? 'bg-indigo-100 text-indigo-700' : 'text-slate-400'" class="p-1.5 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            </button>
                            <button @click="previewMode='mobile'" :class="previewMode==='mobile' ? 'bg-indigo-100 text-indigo-700' : 'text-slate-400'" class="p-1.5 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            </button>
                        </div>
                    </div>
                    <div class="flex justify-center bg-slate-100 p-4" style="min-height: 400px;">
                        <div :style="previewMode==='desktop' ? 'width:100%' : previewMode==='tablet' ? 'width:768px' : 'width:375px'" class="transition-all duration-300 bg-white rounded-lg overflow-hidden shadow-inner" style="height: 400px;">
                            <iframe src="{{ $website->url }}" class="w-full h-full border-0" loading="lazy" sandbox="allow-same-origin allow-scripts"></iframe>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Sidebar --}}
            <div class="space-y-4">
                {{-- Site Details --}}
                <div class="bg-white rounded-2xl border border-slate-200/60 p-5 shadow-sm">
                    <h2 class="font-bold text-slate-900 text-sm mb-3">Site Details</h2>
                    <dl class="space-y-2.5">
                        <div class="flex justify-between">
                            <dt class="text-xs text-slate-400 font-semibold">URL</dt>
                            <dd class="text-xs font-medium"><a href="{{ $website->url }}" target="_blank" class="text-indigo-600 hover:underline">{{ Str::limit($website->url, 30) }}</a></dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-xs text-slate-400 font-semibold">Business</dt>
                            <dd class="text-xs font-medium text-slate-900 capitalize">{{ $website->ai_business_type ?? 'N/A' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-xs text-slate-400 font-semibold">Style</dt>
                            <dd class="text-xs font-medium text-slate-900 capitalize">{{ $website->ai_style ?? 'N/A' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-xs text-slate-400 font-semibold">Theme</dt>
                            <dd class="text-xs font-medium text-slate-900">{{ $website->ai_theme ?? 'flavor' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-xs text-slate-400 font-semibold">Created</dt>
                            <dd class="text-xs font-medium text-slate-900">{{ $website->created_at->format('M d, Y') }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- Health Mini --}}
                <div class="bg-white rounded-2xl border border-slate-200/60 p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="font-bold text-slate-900 text-sm">Health Score</h2>
                        <button @click="activeTab = 'health'; runHealthCheck()" class="text-xs text-indigo-600 hover:text-indigo-700 font-medium">Details &rarr;</button>
                    </div>
                    @if($website->health_score !== null)
                    <div class="flex items-center gap-4">
                        <div class="relative w-16 h-16">
                            <svg class="w-16 h-16 -rotate-90" viewBox="0 0 36 36">
                                <circle cx="18" cy="18" r="15.91" fill="none" stroke="#e2e8f0" stroke-width="3"/>
                                <circle cx="18" cy="18" r="15.91" fill="none"
                                    stroke="{{ $website->health_score >= 70 ? '#10b981' : ($website->health_score >= 40 ? '#f59e0b' : '#ef4444') }}"
                                    stroke-width="3" stroke-dasharray="{{ $website->health_score }},100" stroke-linecap="round"/>
                            </svg>
                            <span class="absolute inset-0 flex items-center justify-center text-sm font-bold text-slate-900">{{ $website->health_score }}</span>
                        </div>
                        <div class="flex-1 space-y-1">
                            @foreach(['seo' => 'SEO', 'content' => 'Content', 'performance' => 'Speed', 'design' => 'Design'] as $key => $label)
                            @php $val = $website->health_details[$key]['score'] ?? 0; @endphp
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] text-slate-400 w-12">{{ $label }}</span>
                                <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full {{ $val >= 70 ? 'bg-emerald-500' : ($val >= 40 ? 'bg-amber-400' : 'bg-red-400') }}" style="width: {{ $val }}%"></div>
                                </div>
                                <span class="text-[10px] font-medium text-slate-600 w-6 text-right">{{ $val }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <button @click="runHealthCheck()" class="w-full py-3 bg-slate-50 hover:bg-indigo-50 border border-dashed border-slate-200 hover:border-indigo-300 rounded-xl text-sm text-slate-500 hover:text-indigo-600 transition">
                        Run health check
                    </button>
                    @endif
                </div>

                {{-- Quick Links --}}
                <div class="bg-white rounded-2xl border border-slate-200/60 p-5 shadow-sm">
                    <h2 class="font-bold text-slate-900 text-sm mb-3">Quick Links</h2>
                    <div class="space-y-1">
                        <a href="{{ $website->url }}/wp-admin/customize.php" target="_blank" class="flex items-center gap-2 p-2 rounded-lg hover:bg-slate-50 text-sm text-slate-700 transition">
                            <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                            Customize Theme
                        </a>
                        <a href="{{ $website->url }}/wp-admin/nav-menus.php" target="_blank" class="flex items-center gap-2 p-2 rounded-lg hover:bg-slate-50 text-sm text-slate-700 transition">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                            Navigation Menus
                        </a>
                        <a href="{{ $website->url }}/wp-admin/plugins.php" target="_blank" class="flex items-center gap-2 p-2 rounded-lg hover:bg-slate-50 text-sm text-slate-700 transition">
                            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z"/></svg>
                            Plugins
                        </a>
                        <a href="{{ $website->url }}/wp-admin/upload.php" target="_blank" class="flex items-center gap-2 p-2 rounded-lg hover:bg-slate-50 text-sm text-slate-700 transition">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Media Library
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ HEALTH TAB ═══ --}}
    <div x-show="activeTab === 'health'" x-cloak>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Health Score Circle --}}
            <div class="bg-white rounded-2xl border border-slate-200/60 p-6 shadow-sm text-center">
                <h2 class="font-bold text-slate-900 mb-4">Overall Health</h2>
                <div class="relative w-32 h-32 mx-auto mb-4">
                    <svg class="w-32 h-32 -rotate-90" viewBox="0 0 36 36">
                        <circle cx="18" cy="18" r="15.91" fill="none" stroke="#e2e8f0" stroke-width="2.5"/>
                        <circle cx="18" cy="18" r="15.91" fill="none"
                            :stroke="healthScore >= 70 ? '#10b981' : healthScore >= 40 ? '#f59e0b' : '#ef4444'"
                            stroke-width="2.5" :stroke-dasharray="healthScore + ',100'" stroke-linecap="round"
                            class="transition-all duration-1000"/>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-3xl font-bold text-slate-900" x-text="healthScore"></span>
                        <span class="text-xs text-slate-400">/ 100</span>
                    </div>
                </div>
                <button @click="runHealthCheck()" :disabled="healthLoading"
                    class="w-full py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 disabled:from-slate-300 disabled:to-slate-300 text-white text-sm font-medium rounded-xl transition-all shadow-md shadow-indigo-500/20">
                    <span x-show="!healthLoading">Analyze Now</span>
                    <span x-show="healthLoading" class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        Analyzing...
                    </span>
                </button>
            </div>

            {{-- Category Scores --}}
            <div class="bg-white rounded-2xl border border-slate-200/60 p-6 shadow-sm">
                <h2 class="font-bold text-slate-900 mb-4">Categories</h2>
                <div class="space-y-4">
                    <template x-for="(cat, key) in healthDetails" :key="key">
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-sm font-medium text-slate-700 capitalize" x-text="key"></span>
                                <span class="text-sm font-bold" :class="cat.score >= 70 ? 'text-emerald-600' : cat.score >= 40 ? 'text-amber-600' : 'text-red-600'" x-text="cat.score + '/100'"></span>
                            </div>
                            <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-700"
                                    :class="cat.score >= 70 ? 'bg-emerald-500' : cat.score >= 40 ? 'bg-amber-400' : 'bg-red-400'"
                                    :style="'width:' + cat.score + '%'"></div>
                            </div>
                            <div class="mt-1.5 space-y-0.5">
                                <template x-for="pass in (cat.passes || []).slice(0,3)" :key="pass">
                                    <p class="text-xs text-emerald-600 flex items-center gap-1">
                                        <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <span x-text="pass"></span>
                                    </p>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Issues & Auto-Fix --}}
            <div class="bg-white rounded-2xl border border-slate-200/60 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-slate-900">Issues Found</h2>
                    <button @click="autoFixAll()" :disabled="healthLoading" x-show="healthSuggestions.length > 0"
                        class="text-xs bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-medium px-3 py-1.5 rounded-lg transition">
                        Auto-Fix All
                    </button>
                </div>
                <div class="space-y-2 max-h-[400px] overflow-y-auto">
                    <template x-for="(s, i) in healthSuggestions" :key="i">
                        <div class="flex items-start gap-2 p-2.5 rounded-lg"
                            :class="s.priority === 'high' ? 'bg-red-50' : s.priority === 'medium' ? 'bg-amber-50' : 'bg-slate-50'">
                            <span class="w-1.5 h-1.5 rounded-full mt-1.5 flex-shrink-0"
                                :class="s.priority === 'high' ? 'bg-red-500' : s.priority === 'medium' ? 'bg-amber-500' : 'bg-slate-400'"></span>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-slate-700" x-text="s.message"></p>
                                <span class="text-[10px] text-slate-400 uppercase" x-text="s.category"></span>
                            </div>
                        </div>
                    </template>
                    <p x-show="healthSuggestions.length === 0" class="text-sm text-slate-400 text-center py-6">Run analysis to see issues</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ CONTENT STUDIO TAB ═══ --}}
    <div x-show="activeTab === 'content'" x-cloak>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Page Map --}}
            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/60 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-slate-900">Pages & Sections</h2>
                    <button @click="loadPageMap()" :disabled="pageMapLoading" class="text-xs text-indigo-600 hover:text-indigo-700 font-medium">
                        <span x-show="!pageMapLoading">Refresh</span>
                        <span x-show="pageMapLoading">Loading...</span>
                    </button>
                </div>
                <div class="space-y-3">
                    <template x-for="page in pageMap" :key="page.id">
                        <div class="border border-slate-200 rounded-xl overflow-hidden">
                            <button @click="page._open = !page._open" class="w-full flex items-center justify-between p-3 hover:bg-slate-50 transition">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 bg-indigo-50 rounded-lg flex items-center justify-center text-xs font-bold text-indigo-600" x-text="page.sections.length"></span>
                                    <div class="text-left">
                                        <p class="text-sm font-medium text-slate-900" x-text="page.title"></p>
                                        <p class="text-xs text-slate-400" x-text="'/' + page.slug + ' — ' + page.section_count + ' sections'"></p>
                                    </div>
                                </div>
                                <svg class="w-4 h-4 text-slate-400 transition-transform" :class="page._open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="page._open" x-collapse class="border-t border-slate-100 bg-slate-50 p-3 space-y-2">
                                <template x-for="(section, si) in page.sections" :key="si">
                                    <div class="bg-white rounded-lg p-3 border border-slate-100 flex items-center justify-between">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="text-[10px] bg-indigo-50 text-indigo-600 px-1.5 py-0.5 rounded font-medium" x-text="'#' + section.index"></span>
                                                <template x-for="w in section.widgets.slice(0,3)" :key="w">
                                                    <span class="text-[10px] bg-slate-100 text-slate-500 px-1.5 py-0.5 rounded" x-text="w"></span>
                                                </template>
                                            </div>
                                            <p class="text-xs text-slate-500 truncate" x-text="section.text_preview || 'No text content'"></p>
                                        </div>
                                        <div class="flex items-center gap-1 ml-3">
                                            <button @click="regenerateSection(page.id, section.index)" title="Regenerate"
                                                class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                            </button>
                                            <button @click="expandSection(page.id, section.index)" title="Expand content"
                                                class="p-1.5 text-slate-400 hover:text-violet-600 hover:bg-violet-50 rounded-lg transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                    <p x-show="pageMap.length === 0 && !pageMapLoading" class="text-center text-sm text-slate-400 py-8">Click refresh to load pages</p>
                </div>
            </div>

            {{-- AI Tools Sidebar --}}
            <div class="space-y-4">
                {{-- Tone Changer --}}
                <div class="bg-white rounded-2xl border border-slate-200/60 p-5 shadow-sm">
                    <h3 class="font-bold text-slate-900 text-sm mb-3">Change Tone</h3>
                    <p class="text-xs text-slate-400 mb-3">Transform your content's voice</p>
                    <div class="grid grid-cols-2 gap-2">
                        <template x-for="tone in ['professional','casual','friendly','formal','luxury','playful','urgent','technical']" :key="tone">
                            <button @click="changeTone(tone)"
                                class="px-3 py-2 text-xs font-medium rounded-lg border border-slate-200 hover:border-indigo-300 hover:bg-indigo-50 text-slate-600 hover:text-indigo-700 transition capitalize"
                                x-text="tone"></button>
                        </template>
                    </div>
                </div>

                {{-- Translate --}}
                <div class="bg-white rounded-2xl border border-slate-200/60 p-5 shadow-sm">
                    <h3 class="font-bold text-slate-900 text-sm mb-3">Translate Content</h3>
                    <select x-model="translateLang" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mb-2">
                        <option value="">Select language...</option>
                        <option value="Spanish">Spanish</option>
                        <option value="French">French</option>
                        <option value="German">German</option>
                        <option value="Arabic">Arabic</option>
                        <option value="Chinese">Chinese</option>
                        <option value="Japanese">Japanese</option>
                        <option value="Korean">Korean</option>
                        <option value="Portuguese">Portuguese</option>
                        <option value="Russian">Russian</option>
                        <option value="Hindi">Hindi</option>
                        <option value="Urdu">Urdu</option>
                        <option value="Turkish">Turkish</option>
                    </select>
                    <button @click="translatePage()" :disabled="!translateLang || contentLoading"
                        class="w-full py-2 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 disabled:from-slate-300 disabled:to-slate-300 text-white text-sm font-medium rounded-lg transition-all">
                        Translate Selected Page
                    </button>
                </div>

                {{-- SEO Generator --}}
                <div class="bg-white rounded-2xl border border-slate-200/60 p-5 shadow-sm">
                    <h3 class="font-bold text-slate-900 text-sm mb-3">SEO Optimizer</h3>
                    <p class="text-xs text-slate-400 mb-3">Generate meta descriptions, titles & keywords for all pages</p>
                    <button @click="generateSeo()" :disabled="contentLoading"
                        class="w-full py-2.5 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 disabled:from-slate-300 disabled:to-slate-300 text-white text-sm font-medium rounded-lg transition-all shadow-md shadow-emerald-500/20">
                        Generate SEO for All Pages
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ DESIGN TAB ═══ --}}
    <div x-show="activeTab === 'design'" x-cloak>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- One-Click Redesign --}}
            <div class="bg-white rounded-2xl border border-slate-200/60 p-6 shadow-sm">
                <h2 class="font-bold text-slate-900 mb-2">One-Click Redesign</h2>
                <p class="text-sm text-slate-500 mb-5">Transform your entire website's look with one click</p>
                <div class="grid grid-cols-3 gap-3">
                    <template x-for="style in redesignStyles" :key="style.key">
                        <button @click="redesign(style.key)" :disabled="designLoading"
                            class="group p-4 rounded-xl border-2 transition-all text-center hover:shadow-lg"
                            :class="designLoading ? 'opacity-50 cursor-not-allowed border-slate-200' : 'border-slate-200 hover:border-indigo-400'">
                            <div class="w-10 h-10 rounded-xl mx-auto mb-2 transition-all" :style="'background: linear-gradient(135deg, ' + style.c1 + ', ' + style.c2 + ')'"></div>
                            <p class="text-xs font-semibold text-slate-700" x-text="style.label"></p>
                            <p class="text-[10px] text-slate-400 mt-0.5" x-text="style.desc"></p>
                        </button>
                    </template>
                </div>
            </div>

            {{-- Custom CSS/JS --}}
            <div class="bg-white rounded-2xl border border-slate-200/60 p-6 shadow-sm">
                <h2 class="font-bold text-slate-900 mb-4">Custom Code</h2>
                <div class="space-y-4">
                    <div>
                        <label class="text-xs font-semibold text-slate-700 mb-1 block">Custom CSS</label>
                        <textarea x-model="customCss" rows="5" placeholder="/* Your custom CSS here */" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-xs font-mono bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500/50"></textarea>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-700 mb-1 block">Custom JavaScript</label>
                        <textarea x-model="customJs" rows="5" placeholder="// Your custom JS here" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-xs font-mono bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500/50"></textarea>
                    </div>
                    <button @click="saveCustomCode()" :disabled="designLoading"
                        class="w-full py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 disabled:from-slate-300 disabled:to-slate-300 text-white text-sm font-medium rounded-xl transition-all">
                        Save & Apply
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ SOCIAL TAB ═══ --}}
    <div x-show="activeTab === 'social'" x-cloak>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Generator --}}
            <div class="bg-white rounded-2xl border border-slate-200/60 p-6 shadow-sm">
                <h2 class="font-bold text-slate-900 mb-4">Generate Social Posts</h2>
                <div class="space-y-3">
                    <div>
                        <label class="text-xs font-semibold text-slate-700 mb-1 block">Platforms</label>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="p in ['facebook','instagram','twitter','linkedin']" :key="p">
                                <label class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border cursor-pointer text-xs font-medium transition"
                                    :class="socialPlatforms.includes(p) ? 'border-indigo-400 bg-indigo-50 text-indigo-700' : 'border-slate-200 text-slate-500'">
                                    <input type="checkbox" :value="p" x-model="socialPlatforms" class="sr-only">
                                    <span class="capitalize" x-text="p"></span>
                                </label>
                            </template>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-700 mb-1 block">Topic (optional)</label>
                        <input type="text" x-model="socialTopic" placeholder="e.g., Holiday sale, New service..." class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm">
                    </div>
                    <button @click="generateSocialPosts()" :disabled="socialLoading || socialPlatforms.length === 0"
                        class="w-full py-2.5 bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-400 hover:to-rose-400 disabled:from-slate-300 disabled:to-slate-300 text-white text-sm font-medium rounded-xl transition-all shadow-md shadow-pink-500/20">
                        <span x-show="!socialLoading">Generate Posts</span>
                        <span x-show="socialLoading">Generating...</span>
                    </button>
                </div>
            </div>

            {{-- Generated Posts --}}
            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/60 p-6 shadow-sm">
                <h2 class="font-bold text-slate-900 mb-4">Generated Posts</h2>
                <div class="space-y-3 max-h-[500px] overflow-y-auto">
                    <template x-for="(post, i) in socialPosts" :key="i">
                        <div class="border border-slate-200 rounded-xl p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold uppercase px-2 py-0.5 rounded"
                                    :class="post.platform === 'facebook' ? 'bg-blue-50 text-blue-600' : post.platform === 'instagram' ? 'bg-pink-50 text-pink-600' : post.platform === 'twitter' ? 'bg-sky-50 text-sky-600' : 'bg-blue-50 text-blue-700'"
                                    x-text="post.platform"></span>
                                <span class="text-[10px] text-slate-400 capitalize" x-text="post.type"></span>
                            </div>
                            <p class="text-sm text-slate-700 whitespace-pre-wrap mb-2" x-text="post.content"></p>
                            <div class="flex items-center justify-between">
                                <div class="flex flex-wrap gap-1">
                                    <template x-for="tag in (post.hashtags || []).slice(0,5)" :key="tag">
                                        <span class="text-[10px] text-indigo-500" x-text="tag"></span>
                                    </template>
                                </div>
                                <button @click="navigator.clipboard.writeText(post.content + ' ' + (post.hashtags || []).join(' '))"
                                    class="text-xs text-indigo-600 hover:text-indigo-700 font-medium flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                    Copy
                                </button>
                            </div>
                        </div>
                    </template>
                    <p x-show="socialPosts.length === 0" class="text-center text-sm text-slate-400 py-8">Generate posts to see them here</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ SUGGESTIONS TAB ═══ --}}
    <div x-show="activeTab === 'suggestions'" x-cloak>
        <div class="bg-white rounded-2xl border border-slate-200/60 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="font-bold text-slate-900">AI Smart Suggestions</h2>
                    <p class="text-sm text-slate-500 mt-1">Personalized recommendations to improve your website</p>
                </div>
                <button @click="loadSuggestions()" :disabled="suggestionsLoading"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 disabled:from-slate-300 disabled:to-slate-300 text-white text-sm font-medium rounded-xl transition-all">
                    <svg class="w-4 h-4" :class="suggestionsLoading && 'animate-spin'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span x-show="!suggestionsLoading">Get AI Suggestions</span>
                    <span x-show="suggestionsLoading">Analyzing...</span>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <template x-for="(s, i) in aiSuggestions" :key="i">
                    <div class="border rounded-xl p-4 transition-all hover:shadow-md"
                        :class="s.impact === 'high' ? 'border-indigo-200 bg-indigo-50/30' : s.impact === 'medium' ? 'border-amber-200 bg-amber-50/30' : 'border-slate-200'">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-bold uppercase px-1.5 py-0.5 rounded"
                                    :class="s.category === 'content' ? 'bg-blue-100 text-blue-600' : s.category === 'design' ? 'bg-purple-100 text-purple-600' : s.category === 'seo' ? 'bg-emerald-100 text-emerald-600' : s.category === 'conversion' ? 'bg-amber-100 text-amber-600' : 'bg-slate-100 text-slate-600'"
                                    x-text="s.category"></span>
                                <span class="text-[10px] text-slate-400" x-text="s.impact + ' impact / ' + s.effort + ' effort'"></span>
                            </div>
                        </div>
                        <h3 class="text-sm font-semibold text-slate-900 mb-1" x-text="s.title"></h3>
                        <p class="text-xs text-slate-500 mb-3" x-text="s.description"></p>
                        <div x-show="s.ai_command" class="flex items-center gap-2">
                            <code class="flex-1 text-[10px] bg-slate-100 text-slate-600 px-2 py-1 rounded font-mono truncate" x-text="s.ai_command"></code>
                            <button @click="useSuggestion(s.ai_command)" class="text-xs text-indigo-600 hover:text-indigo-700 font-medium whitespace-nowrap">
                                Use in Chat
                            </button>
                        </div>
                    </div>
                </template>
                <p x-show="aiSuggestions.length === 0 && !suggestionsLoading" class="col-span-2 text-center text-sm text-slate-400 py-8">Click "Get AI Suggestions" to get personalized recommendations</p>
            </div>
        </div>
    </div>

    @else
    {{-- Not active state --}}
    <div class="bg-white rounded-2xl border border-slate-200/60 p-12 shadow-sm text-center">
        <svg class="w-16 h-16 mx-auto mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        <h2 class="text-lg font-bold text-slate-900 mb-2">Website is {{ $website->status }}</h2>
        <p class="text-sm text-slate-500">All features will be available once your website is active.</p>
    </div>
    @endif

    {{-- ═══ FLOATING CHAT BUTTON ═══ --}}
    @if($website->status === 'active')
    <button @click="chatOpen = !chatOpen" class="fixed bottom-6 right-6 z-50 w-14 h-14 bg-gradient-to-br from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white rounded-full shadow-lg shadow-indigo-500/30 flex items-center justify-center transition-all duration-300 hover:scale-105">
        <svg x-show="!chatOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        <svg x-show="chatOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>

    {{-- Chat Panel --}}
    <div x-show="chatOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
         class="fixed top-0 right-0 z-40 w-[420px] h-full bg-white shadow-2xl shadow-indigo-500/10 flex flex-col" style="max-width: 90vw;">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 flex-shrink-0 bg-gradient-to-r from-indigo-50 to-violet-50">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-gradient-to-br from-indigo-500 to-violet-500 rounded-full flex items-center justify-center shadow-md shadow-indigo-500/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900">AI Website Editor</h3>
                    <p class="text-xs text-slate-400">30+ actions available</p>
                </div>
            </div>
            <button @click="chatOpen = false" class="text-slate-400 hover:text-slate-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Quick Actions --}}
        <div class="px-4 py-2 border-b border-slate-100 flex gap-1.5 overflow-x-auto flex-shrink-0">
            <button @click="chatInput = 'Make my website look more modern'; sendChat()" class="text-[10px] bg-indigo-50 text-indigo-600 px-2 py-1 rounded-full whitespace-nowrap hover:bg-indigo-100">Modernize</button>
            <button @click="chatInput = 'Add SEO to all pages'; sendChat()" class="text-[10px] bg-emerald-50 text-emerald-600 px-2 py-1 rounded-full whitespace-nowrap hover:bg-emerald-100">Add SEO</button>
            <button @click="chatInput = 'Add animations to all pages'; sendChat()" class="text-[10px] bg-purple-50 text-purple-600 px-2 py-1 rounded-full whitespace-nowrap hover:bg-purple-100">Animations</button>
            <button @click="chatInput = 'Improve my content'; sendChat()" class="text-[10px] bg-amber-50 text-amber-600 px-2 py-1 rounded-full whitespace-nowrap hover:bg-amber-100">Better Content</button>
            <button @click="chatInput = 'Add testimonials section'; sendChat()" class="text-[10px] bg-pink-50 text-pink-600 px-2 py-1 rounded-full whitespace-nowrap hover:bg-pink-100">Testimonials</button>
        </div>

        <div class="flex-1 overflow-y-auto p-4 space-y-4" x-ref="chatMessages">
            <template x-if="chatMessages.length === 0 && !chatLoading">
                <div class="flex gap-3">
                    <div class="w-7 h-7 bg-gradient-to-br from-indigo-500 to-violet-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5 shadow-sm">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div class="bg-slate-100 rounded-2xl rounded-tl-md px-4 py-3 max-w-[85%]">
                        <p class="text-sm text-slate-700 leading-relaxed">Hi! I'm your AI website editor with 30+ actions. Try:</p>
                        <ul class="text-xs text-slate-500 mt-2 space-y-1">
                            <li>"Change colors to blue and gold"</li>
                            <li>"Add a FAQ section to the home page"</li>
                            <li>"Update all button text to Get Started"</li>
                            <li>"Add social media links"</li>
                            <li>"Redesign with modern fonts"</li>
                        </ul>
                    </div>
                </div>
            </template>

            <template x-for="(msg, i) in chatMessages" :key="i">
                <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex gap-3'">
                    <template x-if="msg.role === 'assistant'">
                        <div class="w-7 h-7 bg-gradient-to-br from-indigo-500 to-violet-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5 shadow-sm">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                    </template>
                    <div :class="msg.role === 'user' ? 'bg-gradient-to-r from-indigo-600 to-violet-600 text-white rounded-2xl rounded-tr-md px-4 py-3 max-w-[85%] shadow-md shadow-indigo-500/20' : 'bg-slate-100 text-slate-700 rounded-2xl rounded-tl-md px-4 py-3 max-w-[85%]'">
                        <p class="text-sm leading-relaxed whitespace-pre-wrap" x-text="msg.content"></p>
                        <template x-if="msg.actions_taken && msg.actions_taken.length > 0">
                            <div class="mt-2 pt-2 border-t" :class="msg.role === 'user' ? 'border-indigo-400/30' : 'border-slate-200'">
                                <template x-for="(act, ai) in msg.actions_taken" :key="ai">
                                    <div class="flex items-center gap-1.5 text-xs mt-1" :class="msg.role === 'user' ? 'text-indigo-200' : 'text-slate-500'">
                                        <svg class="w-3 h-3" :class="act.result === 'success' ? 'text-emerald-400' : 'text-red-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <span x-text="act.detail || act.action"></span>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <div x-show="chatLoading" class="flex gap-3">
                <div class="w-7 h-7 bg-gradient-to-br from-indigo-500 to-violet-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5 shadow-sm">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div class="bg-slate-100 rounded-2xl rounded-tl-md px-4 py-3">
                    <div class="flex gap-1.5">
                        <span class="w-2 h-2 bg-indigo-400 rounded-full animate-pulse"></span>
                        <span class="w-2 h-2 bg-indigo-400 rounded-full animate-pulse" style="animation-delay:0.2s"></span>
                        <span class="w-2 h-2 bg-indigo-400 rounded-full animate-pulse" style="animation-delay:0.4s"></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t border-slate-100 p-4 flex-shrink-0">
            <form @submit.prevent="sendChat()" class="flex gap-2">
                <input type="text" x-model="chatInput" placeholder="Ask me to edit your website..." :disabled="chatLoading"
                    class="flex-1 px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-300 disabled:opacity-50 transition-all" />
                <button type="submit" :disabled="!chatInput.trim() || chatLoading"
                    class="bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 disabled:from-slate-300 disabled:to-slate-300 disabled:cursor-not-allowed text-white px-4 py-2.5 rounded-xl transition-all flex-shrink-0 shadow-md shadow-indigo-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                </button>
            </form>
        </div>
    </div>
    @endif
</div>

<script>
function websiteDashboard(websiteId, dbName) {
    return {
        // Tabs
        activeTab: 'overview',
        tabs: [
            {key:'overview', label:'Overview', icon:'<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>'},
            {key:'health', label:'Health', icon:'<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'},
            {key:'content', label:'Content Studio', icon:'<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>'},
            {key:'design', label:'Design', icon:'<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343"/></svg>'},
            {key:'social', label:'Social Media', icon:'<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>'},
            {key:'suggestions', label:'AI Suggestions', icon:'<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>'},
        ],
        previewMode: 'desktop',

        // Chat
        chatOpen: false,
        chatMessages: [],
        chatInput: '',
        chatLoading: false,

        // Health
        healthScore: {{ $website->health_score ?? 0 }},
        healthDetails: @json($website->health_details ?? []),
        healthSuggestions: @json($website->ai_suggestions ?? []),
        healthLoading: false,

        // Content Studio
        pageMap: [],
        pageMapLoading: false,
        contentLoading: false,
        translateLang: '',
        selectedPageId: null,

        // Design
        designLoading: false,
        customCss: @json($website->custom_css ?? ''),
        customJs: @json($website->custom_js ?? ''),
        redesignStyles: [
            {key:'modern', label:'Modern', desc:'Clean & minimal', c1:'#6366f1', c2:'#ec4899'},
            {key:'classic', label:'Classic', desc:'Timeless elegance', c1:'#1e3a5f', c2:'#c69c6d'},
            {key:'bold', label:'Bold', desc:'Strong & striking', c1:'#000000', c2:'#ff3d00'},
            {key:'minimal', label:'Minimal', desc:'Less is more', c1:'#111827', c2:'#059669'},
            {key:'luxury', label:'Luxury', desc:'Premium & refined', c1:'#1a1a2e', c2:'#d4af37'},
            {key:'vibrant', label:'Vibrant', desc:'Colorful & fun', c1:'#7c3aed', c2:'#f59e0b'},
        ],

        // Social
        socialPlatforms: ['facebook', 'instagram'],
        socialTopic: '',
        socialPosts: [],
        socialLoading: false,

        // Suggestions
        aiSuggestions: @json($website->ai_suggestions ?? []),
        suggestionsLoading: false,

        get csrf() { return document.querySelector('meta[name="csrf-token"]')?.content || '' },

        init() {
            this.loadHistory();
            if (this.activeTab === 'content') this.loadPageMap();
        },

        // ─── Chat ─────────────────────
        async loadHistory() {
            try {
                const res = await fetch(`/websites/${websiteId}/chat/history`, {headers: {'Accept':'application/json','X-CSRF-TOKEN':this.csrf}});
                const data = await res.json();
                if (data.messages) this.chatMessages = data.messages;
            } catch(e) {}
        },
        async sendChat() {
            if (!this.chatInput.trim() || this.chatLoading) return;
            const msg = this.chatInput.trim();
            this.chatInput = '';
            this.chatMessages.push({role:'user', content:msg});
            this.chatLoading = true;
            this.scrollChat();
            try {
                const res = await fetch(`/websites/${websiteId}/chat`, {method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':this.csrf}, body:JSON.stringify({message:msg})});
                const data = await res.json();
                this.chatMessages.push({role:'assistant', content:data.reply||'Something went wrong.', actions_taken:data.actions_taken||[]});
            } catch(e) { this.chatMessages.push({role:'assistant', content:'Network error.'}); }
            this.chatLoading = false;
            this.scrollChat();
        },
        scrollChat() { this.$nextTick(() => { const el = this.$refs.chatMessages; if (el) el.scrollTop = el.scrollHeight; }); },

        // ─── Health ───────────────────
        async runHealthCheck() {
            this.healthLoading = true;
            try {
                const res = await fetch(`/websites/${websiteId}/health/analyze`, {method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':this.csrf}});
                const data = await res.json();
                if (data.success) {
                    this.healthScore = data.score;
                    this.healthDetails = data.details;
                    this.healthSuggestions = data.suggestions;
                }
            } catch(e) {}
            this.healthLoading = false;
        },
        async autoFixAll() {
            this.healthLoading = true;
            try {
                const fixes = ['set_permalink','set_homepage','add_meta_descriptions','add_alt_text','set_tagline'];
                const res = await fetch(`/websites/${websiteId}/health/auto-fix`, {method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':this.csrf}, body:JSON.stringify({fixes})});
                const data = await res.json();
                if (data.success) { this.healthScore = data.new_score; alert('Fixed: ' + data.fixed.join(', ')); this.runHealthCheck(); }
            } catch(e) {}
            this.healthLoading = false;
        },

        // ─── Content Studio ───────────
        async loadPageMap() {
            this.pageMapLoading = true;
            try {
                const res = await fetch(`/websites/${websiteId}/content-studio/page-map`, {headers:{'Accept':'application/json','X-CSRF-TOKEN':this.csrf}});
                const data = await res.json();
                if (data.pages) this.pageMap = data.pages.map(p => ({...p, _open:false}));
                if (this.pageMap.length > 0) this.selectedPageId = this.pageMap[0].id;
            } catch(e) {}
            this.pageMapLoading = false;
        },
        async regenerateSection(pageId, sectionIndex) {
            this.contentLoading = true;
            try {
                const res = await fetch(`/websites/${websiteId}/content-studio/regenerate-section`, {method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':this.csrf}, body:JSON.stringify({page_id:pageId, section_index:sectionIndex})});
                const data = await res.json();
                if (data.success) { alert('Section regenerated!'); this.loadPageMap(); }
            } catch(e) {}
            this.contentLoading = false;
        },
        async expandSection(pageId, sectionIndex) {
            this.contentLoading = true;
            try {
                const res = await fetch(`/websites/${websiteId}/content-studio/expand`, {method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':this.csrf}, body:JSON.stringify({page_id:pageId, section_index:sectionIndex})});
                const data = await res.json();
                if (data.success) { alert('Content expanded!'); this.loadPageMap(); }
            } catch(e) {}
            this.contentLoading = false;
        },
        async changeTone(tone) {
            if (!this.selectedPageId) { alert('Load pages first'); return; }
            this.contentLoading = true;
            try {
                const res = await fetch(`/websites/${websiteId}/content-studio/change-tone`, {method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':this.csrf}, body:JSON.stringify({page_id:this.selectedPageId, tone})});
                const data = await res.json();
                if (data.success) alert(`Tone changed to ${tone}!`);
            } catch(e) {}
            this.contentLoading = false;
        },
        async translatePage() {
            if (!this.selectedPageId || !this.translateLang) return;
            this.contentLoading = true;
            try {
                const res = await fetch(`/websites/${websiteId}/content-studio/translate`, {method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':this.csrf}, body:JSON.stringify({page_id:this.selectedPageId, language:this.translateLang})});
                const data = await res.json();
                if (data.success) alert(`Translated to ${this.translateLang}!`);
            } catch(e) {}
            this.contentLoading = false;
        },
        async generateSeo() {
            this.contentLoading = true;
            try {
                const res = await fetch(`/websites/${websiteId}/content-studio/generate-seo`, {method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':this.csrf}});
                const data = await res.json();
                if (data.success) alert(data.message);
            } catch(e) {}
            this.contentLoading = false;
        },

        // ─── Design ──────────────────
        async redesign(style) {
            if (!confirm(`Redesign your website with ${style} style? This will update colors, fonts, and typography.`)) return;
            this.designLoading = true;
            try {
                const res = await fetch(`/websites/${websiteId}/redesign`, {method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':this.csrf}, body:JSON.stringify({style})});
                const data = await res.json();
                if (data.success) alert(data.message);
            } catch(e) {}
            this.designLoading = false;
        },
        async saveCustomCode() {
            this.designLoading = true;
            // Use chatbot to inject CSS/JS
            const actions = [];
            if (this.customCss) { this.chatInput = `Inject this custom CSS: ${this.customCss}`; await this.sendChat(); }
            if (this.customJs) { this.chatInput = `Inject this custom JS: ${this.customJs}`; await this.sendChat(); }
            if (!this.customCss && !this.customJs) alert('Enter CSS or JS first');
            this.designLoading = false;
        },

        // ─── Social ──────────────────
        async generateSocialPosts() {
            this.socialLoading = true;
            try {
                const res = await fetch(`/websites/${websiteId}/content-studio/social-content`, {method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':this.csrf}, body:JSON.stringify({platforms:this.socialPlatforms, topic:this.socialTopic})});
                const data = await res.json();
                if (data.posts) this.socialPosts = data.posts;
            } catch(e) {}
            this.socialLoading = false;
        },

        // ─── Suggestions ─────────────
        async loadSuggestions() {
            this.suggestionsLoading = true;
            try {
                const res = await fetch(`/websites/${websiteId}/smart-suggestions`, {headers:{'Accept':'application/json','X-CSRF-TOKEN':this.csrf}});
                const data = await res.json();
                if (data.suggestions) this.aiSuggestions = data.suggestions;
            } catch(e) {}
            this.suggestionsLoading = false;
        },
        useSuggestion(command) {
            this.chatOpen = true;
            this.chatInput = command;
            this.$nextTick(() => this.sendChat());
        },
    };
}
</script>
@endsection
