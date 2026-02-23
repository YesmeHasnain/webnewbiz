@extends('layouts.app')
@section('title', 'Website Ready!')

@push('styles')
<style>
    @keyframes confetti-fall {
        0% { transform: translateY(-100vh) rotate(0deg); opacity: 1; }
        100% { transform: translateY(100vh) rotate(720deg); opacity: 0; }
    }
    .confetti-piece {
        position: fixed;
        top: -10px;
        width: 10px;
        height: 10px;
        border-radius: 2px;
        animation: confetti-fall linear forwards;
        z-index: 100;
        pointer-events: none;
    }
</style>
@endpush

@section('content')
<div x-data x-init="createConfetti()" class="max-w-2xl mx-auto text-center">
    {{-- Success Icon --}}
    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>

    <h1 class="text-3xl font-bold text-gray-900 mb-2">Your Website is Live!</h1>
    <p class="text-lg text-gray-500 mb-8">"{{ $website->name }}" has been built and deployed successfully.</p>

    {{-- Website Preview Card --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden mb-8">
        <div class="h-48 bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center">
            @if($website->screenshot_path)
                <img src="{{ $website->screenshot_path }}" alt="{{ $website->name }}" class="w-full h-full object-cover">
            @else
                <svg class="w-16 h-16 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
            @endif
        </div>
        <div class="p-6">
            <p class="text-sm text-gray-500 mb-2">Your website URL</p>
            <a href="{{ $website->url }}" target="_blank" class="text-blue-600 hover:text-blue-700 font-medium text-lg break-all">{{ $website->url }}</a>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
        <a href="{{ $website->url }}" target="_blank"
            class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            Visit Your Website
        </a>
        <a href="{{ $website->url }}/wp-admin" target="_blank"
            class="inline-flex items-center justify-center gap-2 bg-white hover:bg-gray-50 text-gray-700 font-semibold px-6 py-3 rounded-lg border border-gray-200 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            WP Admin Panel
        </a>
    </div>

    {{-- Website Details --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6 text-left">
        <h3 class="font-semibold text-gray-900 mb-4">Website Details</h3>
        <dl class="space-y-3">
            <div class="flex justify-between">
                <dt class="text-sm text-gray-500">Name</dt>
                <dd class="text-sm font-medium text-gray-900">{{ $website->name }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-sm text-gray-500">Status</dt>
                <dd class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Active</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-sm text-gray-500">Style</dt>
                <dd class="text-sm font-medium text-gray-900 capitalize">{{ $website->ai_style }}</dd>
            </div>
            @if($website->domains->count())
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Domain</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ $website->domains->first()->domain }}</dd>
                </div>
            @endif
        </dl>
    </div>

    <div class="mt-8">
        <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-700 font-medium">Go to Dashboard &rarr;</a>
    </div>
</div>

@push('scripts')
<script>
function createConfetti() {
    const colors = ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899'];
    for (let i = 0; i < 50; i++) {
        const piece = document.createElement('div');
        piece.className = 'confetti-piece';
        piece.style.left = Math.random() * 100 + 'vw';
        piece.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
        piece.style.animationDuration = (2 + Math.random() * 3) + 's';
        piece.style.animationDelay = Math.random() * 2 + 's';
        document.body.appendChild(piece);
        setTimeout(() => piece.remove(), 7000);
    }
}
</script>
@endpush
@endsection
