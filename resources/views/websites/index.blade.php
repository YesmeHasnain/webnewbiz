@extends('layouts.app')
@section('title', 'My Websites')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">My Websites</h1>
            <p class="text-gray-500 mt-1">{{ $websites->total() }} website{{ $websites->total() !== 1 ? 's' : '' }} total</p>
        </div>
        <a href="{{ route('builder.index') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg text-sm transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Build New
        </a>
    </div>

    @if($websites->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($websites as $website)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-200">
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
                        <p class="text-sm text-gray-500 truncate mb-4">{{ $website->subdomain }}</p>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('websites.show', $website) }}" class="flex-1 text-center text-sm font-medium text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 py-1.5 rounded-lg transition">
                                Manage
                            </a>
                            @if($website->status === 'active')
                                <a href="{{ $website->url }}" target="_blank" class="flex-1 text-center text-sm font-medium text-gray-600 hover:text-gray-700 bg-gray-50 hover:bg-gray-100 py-1.5 rounded-lg transition">
                                    Visit
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
        <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No websites yet</h3>
            <p class="text-gray-500 mb-6">Create your first AI-powered website in under a minute.</p>
            <a href="{{ route('builder.index') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-lg transition">
                Build Your First Website
            </a>
        </div>
    @endif
@endsection
