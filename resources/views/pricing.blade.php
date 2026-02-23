<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricing - Webnewbiz</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { primary: { 50: '#eff6ff', 100: '#dbeafe', 200: '#bfdbfe', 300: '#93c5fd', 400: '#60a5fa', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8', 800: '#1e40af', 900: '#1e3a8a' } } } }
        }
    </script>
</head>
<body class="bg-white">
    {{-- Navbar --}}
    <nav class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-8">
                    <a href="{{ url('/') }}" class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                        </div>
                        <span class="text-lg font-bold text-gray-900">Webnewbiz</span>
                    </a>
                    <div class="hidden md:flex items-center gap-6">
                        <a href="{{ url('/features') }}" class="text-sm text-gray-600 hover:text-gray-900">Features</a>
                        <a href="{{ url('/pricing') }}" class="text-sm text-blue-600 font-medium">Pricing</a>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Login</a>
                        <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">Get Started</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Pricing Header --}}
    <section class="py-20 bg-gradient-to-br from-blue-50 via-white to-indigo-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Simple, Transparent Pricing</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Start with a free plan and upgrade as your business grows. No hidden fees.</p>
        </div>
    </section>

    {{-- Pricing Cards --}}
    <section class="py-16 -mt-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-{{ min($plans->count(), 4) }} gap-8">
                @foreach($plans as $plan)
                    <div class="relative bg-white rounded-2xl border-2 {{ $plan->slug === 'business' ? 'border-blue-600 shadow-xl shadow-blue-100' : 'border-gray-200' }} p-8">
                        @if($plan->slug === 'business')
                            <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full">MOST POPULAR</div>
                        @endif
                        <h3 class="text-xl font-semibold text-gray-900">{{ $plan->name }}</h3>
                        <p class="text-sm text-gray-500 mt-1 h-10">{{ $plan->description }}</p>
                        <div class="mt-4 mb-6">
                            <span class="text-5xl font-bold text-gray-900">${{ intval($plan->price) }}</span>
                            @if($plan->price > 0)
                                <span class="text-gray-500">/{{ $plan->billing_cycle }}</span>
                            @else
                                <span class="text-gray-500">/forever</span>
                            @endif
                        </div>
                        <ul class="space-y-3 mb-8">
                            <li class="flex items-center gap-2 text-sm text-gray-600">
                                <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ $plan->max_websites }} Website{{ $plan->max_websites > 1 ? 's' : '' }}
                            </li>
                            <li class="flex items-center gap-2 text-sm text-gray-600">
                                <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ $plan->storage_gb }}GB Storage
                            </li>
                            <li class="flex items-center gap-2 text-sm text-gray-600">
                                <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ $plan->bandwidth_gb }}GB Bandwidth
                            </li>
                            <li class="flex items-center gap-2 text-sm text-gray-600">
                                <svg class="w-4 h-4 {{ $plan->ssl_included ? 'text-green-500' : 'text-gray-300' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Free SSL
                            </li>
                            <li class="flex items-center gap-2 text-sm text-gray-600">
                                <svg class="w-4 h-4 {{ $plan->custom_domain ? 'text-green-500' : 'text-gray-300' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $plan->custom_domain ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12' }}"/></svg>
                                Custom Domain
                            </li>
                            <li class="flex items-center gap-2 text-sm text-gray-600">
                                <svg class="w-4 h-4 {{ $plan->backup_included ? 'text-green-500' : 'text-gray-300' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $plan->backup_included ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12' }}"/></svg>
                                Automatic Backups
                            </li>
                            <li class="flex items-center gap-2 text-sm text-gray-600">
                                <svg class="w-4 h-4 {{ $plan->priority_support ? 'text-green-500' : 'text-gray-300' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $plan->priority_support ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12' }}"/></svg>
                                Priority Support
                            </li>
                        </ul>
                        <a href="{{ route('register') }}" class="block text-center w-full {{ $plan->slug === 'business' ? 'bg-blue-600 hover:bg-blue-700 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700' }} font-semibold py-3 rounded-lg transition">
                            Get Started
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-gray-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm text-gray-500">&copy; {{ date('Y') }} Webnewbiz. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
