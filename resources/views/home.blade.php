<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webnewbiz - Build Your Website with AI in 60 Seconds</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { 50: '#eff6ff', 100: '#dbeafe', 200: '#bfdbfe', 300: '#93c5fd', 400: '#60a5fa', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8', 800: '#1e40af', 900: '#1e3a8a' },
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-white">

    {{-- Navbar --}}
    <nav class="fixed top-0 w-full bg-white/80 backdrop-blur-md border-b border-gray-100 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-8">
                    <a href="{{ url('/') }}" class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                            </svg>
                        </div>
                        <span class="text-lg font-bold text-gray-900">Webnewbiz</span>
                    </a>
                    <div class="hidden md:flex items-center gap-6">
                        <a href="#features" class="text-sm text-gray-600 hover:text-gray-900">Features</a>
                        <a href="#pricing" class="text-sm text-gray-600 hover:text-gray-900">Pricing</a>
                        <a href="#how-it-works" class="text-sm text-gray-600 hover:text-gray-900">How It Works</a>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Login</a>
                        <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">Get Started Free</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="pt-32 pb-20 bg-gradient-to-br from-blue-50 via-white to-indigo-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center gap-2 bg-blue-100 text-blue-700 text-sm font-medium px-4 py-1.5 rounded-full mb-6">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                AI-Powered Website Builder
            </div>
            <h1 class="text-5xl sm:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                Build Your WordPress Website<br>
                <span class="text-blue-600">with AI in 60 Seconds</span>
            </h1>
            <p class="text-xl text-gray-600 mb-10 max-w-2xl mx-auto">
                Just describe your business and our AI builds a fully-hosted WordPress website for you. No coding, no design skills needed.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3.5 rounded-xl text-lg transition shadow-lg shadow-blue-200">
                    Start Building Free
                </a>
                <a href="#how-it-works" class="bg-white hover:bg-gray-50 text-gray-700 font-semibold px-8 py-3.5 rounded-xl text-lg transition border border-gray-200">
                    See How It Works
                </a>
            </div>
        </div>
    </section>

    {{-- Social Proof --}}
    <section class="py-12 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 text-center">
                <div>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($websiteCount) }}+</p>
                    <p class="text-gray-500 mt-1">Websites Built</p>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900">AI-Powered</p>
                    <p class="text-gray-500 mt-1">Smart Content Generation</p>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900">Free SSL</p>
                    <p class="text-gray-500 mt-1">Included on Every Site</p>
                </div>
            </div>
        </div>
    </section>

    {{-- How It Works --}}
    <section id="how-it-works" class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">How It Works</h2>
                <p class="text-lg text-gray-600">Three simple steps to your professional website</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-blue-600">1</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Describe Your Business</h3>
                    <p class="text-gray-600">Tell us about your business, choose a style, and describe your vision for the website.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-blue-600">2</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">AI Generates Your Site</h3>
                    <p class="text-gray-600">Our AI creates custom content, sets up WordPress, configures hosting, and designs your site.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-blue-600">3</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Your Site Goes Live</h3>
                    <p class="text-gray-600">In under a minute, your website is live with hosting, SSL, and a custom subdomain.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section id="features" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Everything You Need</h2>
                <p class="text-lg text-gray-600">All the tools to build and manage your online presence</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @php
                    $features = [
                        ['icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'title' => 'AI Content Generation', 'desc' => 'Our AI writes your website content based on your business description and style preferences.'],
                        ['icon' => 'M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2', 'title' => 'Managed WordPress Hosting', 'desc' => 'High-performance servers with automatic updates, backups, and monitoring.'],
                        ['icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z', 'title' => 'Free SSL Certificate', 'desc' => 'Every website gets a free SSL certificate for secure HTTPS connections.'],
                        ['icon' => 'M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1', 'title' => 'Custom Domains', 'desc' => 'Connect your own domain name or use our free subdomain to get started.'],
                        ['icon' => 'M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4', 'title' => 'Automatic Backups', 'desc' => 'Your website is backed up automatically. Restore to any point with one click.'],
                        ['icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z', 'title' => 'Full Management', 'desc' => 'Manage plugins, themes, domains, and settings all from one dashboard.'],
                    ];
                @endphp
                @foreach($features as $feature)
                    <div class="bg-white rounded-xl p-6 border border-gray-200 hover:shadow-lg hover:-translate-y-1 transition-all duration-200">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feature['icon'] }}"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $feature['title'] }}</h3>
                        <p class="text-gray-600">{{ $feature['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Pricing --}}
    <section id="pricing" class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Simple, Transparent Pricing</h2>
                <p class="text-lg text-gray-600">Start free, upgrade when you need more</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-{{ min($plans->count(), 4) }} gap-8 max-w-5xl mx-auto">
                @foreach($plans as $plan)
                    <div class="relative bg-white rounded-2xl border-2 {{ $plan->slug === 'business' ? 'border-blue-600 shadow-xl shadow-blue-100' : 'border-gray-200' }} p-8">
                        @if($plan->slug === 'business')
                            <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full">MOST POPULAR</div>
                        @endif
                        <h3 class="text-lg font-semibold text-gray-900">{{ $plan->name }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $plan->description }}</p>
                        <div class="mt-4 mb-6">
                            <span class="text-4xl font-bold text-gray-900">${{ intval($plan->price) }}</span>
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
                            @if($plan->ssl_included)
                                <li class="flex items-center gap-2 text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Free SSL
                                </li>
                            @endif
                            @if($plan->custom_domain)
                                <li class="flex items-center gap-2 text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Custom Domain
                                </li>
                            @endif
                            @if($plan->backup_included)
                                <li class="flex items-center gap-2 text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Automatic Backups
                                </li>
                            @endif
                            @if($plan->priority_support)
                                <li class="flex items-center gap-2 text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Priority Support
                                </li>
                            @endif
                        </ul>
                        <a href="{{ route('register') }}" class="block text-center w-full {{ $plan->slug === 'business' ? 'bg-blue-600 hover:bg-blue-700 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700' }} font-semibold py-2.5 rounded-lg transition">
                            Get Started
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Final CTA --}}
    <section class="py-20 bg-gradient-to-r from-blue-600 to-indigo-700">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">Ready to Build Your Website?</h2>
            <p class="text-xl text-blue-100 mb-8">Join thousands of businesses already using Webnewbiz to create stunning websites with AI.</p>
            <a href="{{ route('register') }}" class="inline-block bg-white hover:bg-gray-100 text-blue-600 font-semibold px-8 py-3.5 rounded-xl text-lg transition shadow-lg">
                Start Building Free
            </a>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-gray-900 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                    </div>
                    <span class="text-white font-bold">Webnewbiz</span>
                </div>
                <div class="flex items-center gap-6">
                    <a href="{{ url('/features') }}" class="text-sm text-gray-400 hover:text-white">Features</a>
                    <a href="{{ url('/pricing') }}" class="text-sm text-gray-400 hover:text-white">Pricing</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm text-gray-400 hover:text-white">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-white">Login</a>
                    @endauth
                </div>
                <p class="text-sm text-gray-500">&copy; {{ date('Y') }} Webnewbiz. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>
