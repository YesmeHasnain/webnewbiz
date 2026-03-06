@extends('layouts.app')
@section('title', 'My Websites')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">My Websites</h1>
            <p class="text-slate-500 mt-1 text-sm">{{ $websites->total() }} website{{ $websites->total() !== 1 ? 's' : '' }} total</p>
        </div>
        <a href="{{ route('builder.index') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-medium px-5 py-2.5 rounded-xl text-sm transition-all duration-300 shadow-md shadow-indigo-500/20 hover:shadow-lg hover:shadow-indigo-500/30 hover:-translate-y-0.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Create New Website
        </a>
    </div>

    @if($websites->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($websites as $website)
                <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden group transition-all duration-300 hover:shadow-xl hover:shadow-indigo-500/10 hover:border-indigo-200">
                    <div class="relative h-44 bg-gradient-to-br from-slate-100 to-slate-50 overflow-hidden">
                        @if($website->screenshot_path)
                            <img src="{{ $website->screenshot_path }}" alt="{{ $website->name }}" class="w-full h-full object-cover object-top transition-transform duration-500 group-hover:scale-105">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-indigo-50 to-violet-50">
                                <svg class="w-16 h-16 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                            </div>
                        @endif

                        <div class="absolute top-3 left-3">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold backdrop-blur-sm shadow-sm
                                {{ $website->status === 'active' ? 'bg-emerald-500/90 text-white' : '' }}
                                {{ $website->status === 'provisioning' ? 'bg-amber-500/90 text-white' : '' }}
                                {{ $website->status === 'error' ? 'bg-red-500/90 text-white' : '' }}
                                {{ $website->status === 'suspended' ? 'bg-slate-500/90 text-white' : '' }}
                            ">
                                @if($website->status === 'active')<span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span>@endif
                                {{ ucfirst($website->status) }}
                            </span>
                        </div>

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
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="p-4">
                        <h3 class="font-semibold text-slate-900 truncate">{{ $website->name }}</h3>
                        <p class="text-sm text-slate-500 truncate mt-0.5">{{ $website->subdomain }}</p>

                        <div class="flex items-center gap-2 mt-4 pt-4 border-t border-slate-100">
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
                                   class="inline-flex items-center justify-center border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-medium py-2 px-3 rounded-lg transition">
                                    Manage
                                </a>
                            @else
                                <a href="{{ route('websites.show', $website) }}"
                                   class="flex-1 inline-flex items-center justify-center border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-medium py-2 px-3 rounded-lg transition">
                                    View Details
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $websites->links() }}
        </div>
    @else
        <div class="bg-white rounded-2xl border border-slate-200/60 p-16 text-center shadow-sm">
            <div class="w-20 h-20 bg-gradient-to-br from-indigo-100 to-violet-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-2">No websites yet</h3>
            <p class="text-slate-500 mb-8 text-sm max-w-sm mx-auto">Create your first AI-powered website in minutes.</p>
            <a href="{{ route('builder.index') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-medium px-6 py-3 rounded-xl transition-all duration-300 shadow-lg shadow-indigo-500/20">
                Build Your First Website
            </a>
        </div>
    @endif
@endsection
