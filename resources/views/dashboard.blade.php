@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    {{-- Welcome --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}</h1>
        <p class="text-gray-500 mt-1">Here's an overview of your websites</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Websites</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalWebsites }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Active</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $activeWebsites }}</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Building</p>
                    <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $buildingWebsites }}</p>
                </div>
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Plan Limit</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalWebsites }}/{{ $maxWebsites }}</p>
                </div>
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Action --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold text-gray-900">Your Websites</h2>
        @if($totalWebsites < $maxWebsites)
            <a href="{{ route('builder.index') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg text-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Build New Website
            </a>
        @endif
    </div>

    {{-- Websites Grid --}}
    @if($websites->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($websites as $website)
                <a href="{{ route('websites.show', $website) }}" class="block bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                    <div class="h-40 bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center">
                        @if($website->screenshot_path)
                            <img src="{{ $website->screenshot_path }}" alt="{{ $website->name }}" class="w-full h-full object-cover">
                        @else
                            <svg class="w-12 h-12 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                        @endif
                    </div>
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-semibold text-gray-900 truncate">{{ $website->name }}</h3>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                {{ $website->status === 'active' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $website->status === 'provisioning' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $website->status === 'error' ? 'bg-red-100 text-red-700' : '' }}
                                {{ $website->status === 'suspended' ? 'bg-gray-100 text-gray-700' : '' }}
                            ">{{ ucfirst($website->status) }}</span>
                        </div>
                        <p class="text-sm text-gray-500 truncate">{{ $website->subdomain }}</p>
                    </div>
                </a>
            @endforeach
        </div>
        @if($totalWebsites > 6)
            <div class="mt-6 text-center">
                <a href="{{ route('websites.index') }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm">View all websites &rarr;</a>
            </div>
        @endif
    @else
        <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No websites yet</h3>
            <p class="text-gray-500 mb-6">Create your first AI-powered website in under a minute.</p>
            <a href="{{ route('builder.index') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Build Your First Website
            </a>
        </div>
    @endif

    {{-- Current Plan --}}
    @if($plan)
        <div class="mt-8 bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Current Plan</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $plan->name }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $totalWebsites }} of {{ $maxWebsites }} websites used</p>
                </div>
                <div class="w-32 h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-600 rounded-full" style="width: {{ min(100, ($totalWebsites / max(1, $maxWebsites)) * 100) }}%"></div>
                </div>
            </div>
        </div>
    @endif
@endsection
