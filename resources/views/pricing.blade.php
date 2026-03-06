<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricing - Webnewbiz</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                    colors: {
                        gh: { bg: '#0d1117', canvas: '#161b22', border: '#30363d', text: '#e6edf3', muted: '#8b949e' }
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        *, *::before, *::after { -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
        [x-cloak] { display: none !important; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(28px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .animate-up { animation: fadeInUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
        .animate-fade { animation: fadeIn 0.6s ease-out forwards; opacity: 0; }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .reveal { opacity: 0; transform: translateY(24px); transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1); }
        .reveal.visible { opacity: 1; transform: translateY(0); }
    </style>
</head>
<body class="bg-white text-gray-900 overflow-x-hidden" x-data="{ mobileMenu: false }">

    {{-- Navbar --}}
    <nav class="fixed top-0 w-full z-50 animate-fade"
         x-data="{ scrolled: false }"
         x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 20 })"
         :class="scrolled ? 'bg-white/80 backdrop-blur-2xl shadow-[0_1px_3px_rgba(0,0,0,0.05)] border-b border-gray-200/50' : 'bg-transparent'"
         style="transition: background 0.4s ease, box-shadow 0.4s ease, border-color 0.4s ease;">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-10">
                    <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
                        <div class="w-9 h-9 bg-gray-900 rounded-xl flex items-center justify-center transition-all duration-300 group-hover:scale-110 group-hover:rotate-3 group-hover:shadow-lg group-hover:shadow-gray-900/20">
                            <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                            </svg>
                        </div>
                        <span class="text-lg font-bold tracking-tight text-gray-900">Webnewbiz</span>
                    </a>
                    <div class="hidden md:flex items-center">
                        <div class="flex items-center bg-gray-100/80 rounded-full p-1 gap-0.5">
                            <a href="{{ url('/features') }}" class="text-gray-500 hover:text-gray-700 px-4 py-1.5 text-sm font-medium rounded-full transition-all duration-300">Features</a>
                            <a href="{{ url('/pricing') }}" class="bg-white text-gray-900 shadow-sm px-4 py-1.5 text-sm font-medium rounded-full transition-all duration-300">Pricing</a>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="hidden sm:inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                            <div class="w-6 h-6 bg-gray-900 rounded-full flex items-center justify-center text-white text-[10px] font-bold">{{ substr(auth()->user()->name, 0, 1) }}</div>
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:inline-flex text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">Log in</a>
                    @endauth
                    <a href="{{ route('builder.index') }}" class="hidden sm:inline-flex items-center gap-2 bg-gray-900 hover:bg-black text-white text-sm font-semibold px-5 py-2.5 rounded-full transition-all duration-200 hover:shadow-lg hover:shadow-gray-900/20 hover:-translate-y-px active:translate-y-0">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Build Website
                    </a>
                    <button @click="mobileMenu = !mobileMenu" class="md:hidden relative w-10 h-10 rounded-xl hover:bg-gray-100 transition-colors flex items-center justify-center">
                        <div class="w-5 h-4 flex flex-col justify-between">
                            <span class="block h-0.5 bg-gray-700 rounded-full transition-all duration-300 origin-center" :class="mobileMenu ? 'rotate-45 translate-y-[7px]' : ''"></span>
                            <span class="block h-0.5 bg-gray-700 rounded-full transition-all duration-300" :class="mobileMenu ? 'opacity-0 scale-x-0' : ''"></span>
                            <span class="block h-0.5 bg-gray-700 rounded-full transition-all duration-300 origin-center" :class="mobileMenu ? '-rotate-45 -translate-y-[7px]' : ''"></span>
                        </div>
                    </button>
                </div>
            </div>
        </div>
        <div x-show="mobileMenu" x-cloak
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4"
             @click.away="mobileMenu = false"
             class="md:hidden absolute top-full left-4 right-4 bg-white rounded-2xl shadow-2xl shadow-gray-900/10 border border-gray-200/80 overflow-hidden mt-2">
            <div class="p-2">
                <a href="{{ url('/features') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-xl transition-colors">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/></svg>
                    Features
                </a>
                <a href="{{ url('/pricing') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-900 bg-gray-50 rounded-xl">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Pricing
                </a>
            </div>
            <div class="border-t border-gray-100 p-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-xl transition-colors">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zm0 6a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1h-4a1 1 0 01-1-1v-5zM4 13a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1v-2z"/></svg>
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-xl transition-colors">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        Log in
                    </a>
                @endauth
                <a href="{{ route('builder.index') }}" class="flex items-center justify-center gap-2 mx-2 mt-1 mb-1 bg-gray-900 text-white text-sm font-semibold px-5 py-3 rounded-xl transition-all hover:bg-black">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Build Website
                </a>
            </div>
        </div>
    </nav>

    {{-- Header --}}
    <section class="pt-32 pb-12">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3 animate-up">Pricing</p>
            <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight text-gray-900 mb-4 animate-up delay-1">Simple, transparent pricing</h1>
            <p class="text-lg text-gray-500 max-w-xl mx-auto animate-up delay-2">Start with a free plan and upgrade as your business grows. No hidden fees.</p>
        </div>
    </section>

    {{-- Pricing Cards --}}
    <section class="py-12 pb-24">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-{{ min($plans->count(), 4) }} gap-5">
                @foreach($plans as $i => $plan)
                    <div class="relative {{ $plan->slug === 'business' ? 'bg-gray-900 text-white border-2 border-gray-900 ring-4 ring-gray-900/10' : 'bg-white border border-gray-200' }} rounded-xl p-8 transition-all duration-300 hover:shadow-lg reveal"
                         x-data x-intersect.once="$el.classList.add('visible')"
                         style="transition-delay: {{ $i * 0.1 }}s">
                        @if($plan->slug === 'business')
                            <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-white text-gray-900 text-xs font-bold px-3 py-0.5 rounded-full">Most popular</div>
                        @endif
                        <h3 class="text-xl font-bold {{ $plan->slug === 'business' ? 'text-white' : 'text-gray-900' }}">{{ $plan->name }}</h3>
                        <p class="text-sm {{ $plan->slug === 'business' ? 'text-gray-400' : 'text-gray-500' }} mt-1 min-h-[40px]">{{ $plan->description }}</p>
                        <div class="mt-5 mb-6">
                            <span class="text-5xl font-extrabold tracking-tight">${{ intval($plan->price) }}</span>
                            @if($plan->price > 0)
                                <span class="{{ $plan->slug === 'business' ? 'text-gray-400' : 'text-gray-500' }}">/{{ $plan->billing_cycle }}</span>
                            @else
                                <span class="{{ $plan->slug === 'business' ? 'text-gray-400' : 'text-gray-500' }}">/forever</span>
                            @endif
                        </div>
                        <ul class="space-y-2.5 mb-8">
                            <li class="flex items-center gap-2.5 text-sm {{ $plan->slug === 'business' ? 'text-gray-300' : 'text-gray-600' }}">
                                <svg class="w-4 h-4 {{ $plan->slug === 'business' ? 'text-green-400' : 'text-green-600' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Unlimited Websites
                            </li>
                            <li class="flex items-center gap-2.5 text-sm {{ $plan->slug === 'business' ? 'text-gray-300' : 'text-gray-600' }}">
                                <svg class="w-4 h-4 {{ $plan->slug === 'business' ? 'text-green-400' : 'text-green-600' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ $plan->storage_gb }}GB Storage
                            </li>
                            <li class="flex items-center gap-2.5 text-sm {{ $plan->slug === 'business' ? 'text-gray-300' : 'text-gray-600' }}">
                                <svg class="w-4 h-4 {{ $plan->slug === 'business' ? 'text-green-400' : 'text-green-600' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ $plan->bandwidth_gb }}GB Bandwidth
                            </li>
                            <li class="flex items-center gap-2.5 text-sm {{ $plan->slug === 'business' ? 'text-gray-300' : 'text-gray-600' }}">
                                <svg class="w-4 h-4 {{ $plan->ssl_included ? ($plan->slug === 'business' ? 'text-green-400' : 'text-green-600') : 'text-gray-400' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $plan->ssl_included ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12' }}"/></svg>
                                <span class="{{ !$plan->ssl_included ? 'text-gray-400' : '' }}">Free SSL</span>
                            </li>
                            <li class="flex items-center gap-2.5 text-sm {{ $plan->slug === 'business' ? 'text-gray-300' : 'text-gray-600' }}">
                                <svg class="w-4 h-4 {{ $plan->custom_domain ? ($plan->slug === 'business' ? 'text-green-400' : 'text-green-600') : 'text-gray-400' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $plan->custom_domain ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12' }}"/></svg>
                                <span class="{{ !$plan->custom_domain ? 'text-gray-400' : '' }}">Custom Domain</span>
                            </li>
                            <li class="flex items-center gap-2.5 text-sm {{ $plan->slug === 'business' ? 'text-gray-300' : 'text-gray-600' }}">
                                <svg class="w-4 h-4 {{ $plan->backup_included ? ($plan->slug === 'business' ? 'text-green-400' : 'text-green-600') : 'text-gray-400' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $plan->backup_included ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12' }}"/></svg>
                                <span class="{{ !$plan->backup_included ? 'text-gray-400' : '' }}">Automatic Backups</span>
                            </li>
                            <li class="flex items-center gap-2.5 text-sm {{ $plan->slug === 'business' ? 'text-gray-300' : 'text-gray-600' }}">
                                <svg class="w-4 h-4 {{ $plan->priority_support ? ($plan->slug === 'business' ? 'text-green-400' : 'text-green-600') : 'text-gray-400' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $plan->priority_support ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12' }}"/></svg>
                                <span class="{{ !$plan->priority_support ? 'text-gray-400' : '' }}">Priority Support</span>
                            </li>
                        </ul>
                        <a href="{{ route('builder.index') }}" class="block text-center w-full {{ $plan->slug === 'business' ? 'bg-white hover:bg-gray-100 text-gray-900' : 'bg-gray-900 hover:bg-gray-800 text-white' }} font-semibold py-2.5 rounded-lg text-sm transition-colors">
                            Get started
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- FAQ-style section --}}
    <section class="py-20 bg-gray-50 border-t border-gray-200">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="reveal" x-data x-intersect.once="$el.classList.add('visible')">
                <h2 class="text-2xl font-bold text-gray-900 mb-3">Questions? We've got answers.</h2>
                <p class="text-gray-500 mb-8">Contact us anytime and we'll help you pick the right plan.</p>
                <a href="{{ route('builder.index') }}" class="inline-flex items-center gap-2 bg-gray-900 hover:bg-gray-800 text-white font-semibold px-6 py-2.5 rounded-lg text-sm transition-colors">
                    Start building now
                </a>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-gh-bg border-t border-[#21262d] py-10">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 bg-white rounded-md flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-gray-900" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                        </svg>
                    </div>
                    <span class="text-white font-semibold text-sm">Webnewbiz</span>
                </div>
                <div class="flex items-center gap-6">
                    <a href="{{ url('/features') }}" class="text-sm text-[#8b949e] hover:text-white transition-colors">Features</a>
                    <a href="{{ url('/pricing') }}" class="text-sm text-[#8b949e] hover:text-white transition-colors">Pricing</a>
                    <a href="{{ route('dashboard') }}" class="text-sm text-[#8b949e] hover:text-white transition-colors">Dashboard</a>
                </div>
                <p class="text-xs text-[#484f58]">&copy; {{ date('Y') }} Webnewbiz. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
