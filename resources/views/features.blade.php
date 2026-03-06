<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Features - Webnewbiz</title>
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
        .hero-glow { position: absolute; width: 800px; height: 600px; background: radial-gradient(ellipse at center, rgba(99,102,241,0.15) 0%, rgba(139,92,246,0.08) 40%, transparent 70%); top: -200px; left: 50%; transform: translateX(-50%); pointer-events: none; }
        .gradient-text { background: linear-gradient(135deg, #79c0ff 0%, #d2a8ff 50%, #f778ba 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .gh-card { background: #fff; border: 1px solid #d0d7de; border-radius: 12px; transition: all 0.3s ease; }
        .gh-card:hover { border-color: #0969da; box-shadow: 0 8px 24px rgba(140,149,159,0.15); }
        .grid-bg { background-image: linear-gradient(rgba(48,54,61,0.3) 1px, transparent 1px), linear-gradient(90deg, rgba(48,54,61,0.3) 1px, transparent 1px); background-size: 64px 64px; }
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
                            <a href="{{ url('/features') }}" class="bg-white text-gray-900 shadow-sm px-4 py-1.5 text-sm font-medium rounded-full transition-all duration-300">Features</a>
                            <a href="{{ url('/pricing') }}" class="text-gray-500 hover:text-gray-700 px-4 py-1.5 text-sm font-medium rounded-full transition-all duration-300">Pricing</a>
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
                <a href="{{ url('/features') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-900 bg-gray-50 rounded-xl">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/></svg>
                    Features
                </a>
                <a href="{{ url('/pricing') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-xl transition-colors">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
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

    {{-- Hero --}}
    <section class="relative pt-16 overflow-hidden bg-gh-bg">
        <div class="hero-glow"></div>
        <div class="grid-bg absolute inset-0 opacity-40"></div>
        <div class="relative z-10 max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28 text-center">
            <div class="animate-up">
                <div class="inline-flex items-center gap-2 bg-[#161b22] text-[#58a6ff] text-sm font-medium px-4 py-1.5 rounded-full border border-[#30363d] mb-8">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/></svg>
                    Platform Features
                </div>
            </div>
            <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight mb-6 animate-up delay-1">
                <span class="text-white">Powerful features for</span><br>
                <span class="gradient-text">your business</span>
            </h1>
            <p class="text-lg text-[#8b949e] max-w-2xl mx-auto animate-up delay-2">Everything you need to build, manage, and grow your online presence — all powered by AI.</p>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-white to-transparent"></div>
    </section>

    {{-- Features Grid --}}
    <section class="py-24">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
            @php
                $features = [
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456z"/>', 'title' => 'AI-Powered Content', 'desc' => 'Our advanced AI analyzes your business type and generates professional website content including headlines, descriptions, and page layouts tailored to your industry.'],
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.737 5.1a3.375 3.375 0 012.7-1.35h7.126c1.062 0 2.062.5 2.7 1.35l2.587 3.45a4.5 4.5 0 01.9 2.7m0 0a3 3 0 01-3 3m0 3h.008v.008h-.008v-.008zm0-6h.008v.008h-.008v-.008zm-3 6h.008v.008h-.008v-.008zm0-6h.008v.008h-.008v-.008z"/>', 'title' => 'Managed WordPress Hosting', 'desc' => 'High-performance cloud servers with automatic WordPress updates, server monitoring, and optimized configurations for maximum speed and reliability.'],
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>', 'title' => 'Free SSL Certificates', 'desc' => 'Every website gets automatic SSL/TLS encryption. Your visitors see the reassuring padlock icon and your site ranks better in search engines.'],
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m9.86-2.556a4.5 4.5 0 00-6.364-6.364L4.5 8.28a4.5 4.5 0 006.364 6.364l4.5-4.5z"/>', 'title' => 'Custom Domain Support', 'desc' => 'Connect your own domain name with automatic DNS configuration, or use our free subdomain to get started instantly.'],
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 15a4.5 4.5 0 004.5 4.5H18a3.75 3.75 0 001.332-7.257 3 3 0 00-3.758-3.848 5.25 5.25 0 00-10.233 2.33A4.502 4.502 0 002.25 15z"/>', 'title' => 'Automatic Backups', 'desc' => 'Your website is backed up automatically on a regular schedule. Restore to any previous version with a single click whenever you need.'],
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"/>', 'title' => 'Plugin & Theme Management', 'desc' => 'Install, activate, and manage WordPress plugins and themes directly from your dashboard without touching any code.'],
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/>', 'title' => 'AI Website Editor', 'desc' => 'Chat with AI to make instant changes to your live website. Update titles, colors, add pages, and more — just by typing what you want.'],
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>', 'title' => '60-Second Setup', 'desc' => 'From description to live website in under a minute. Our AI handles everything — server provisioning, WordPress installation, content generation, and DNS setup.'],
                ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @foreach($features as $i => $feature)
                    <div class="gh-card p-7 group reveal"
                         x-data x-intersect.once="$el.classList.add('visible')"
                         style="transition-delay: {{ ($i % 2) * 0.08 }}s">
                        <div class="flex gap-5">
                            <div class="w-11 h-11 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0 transition-colors duration-200 group-hover:bg-gray-900">
                                <svg class="w-5 h-5 text-gray-600 transition-colors duration-200 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $feature['icon'] !!}</svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-gray-900 mb-1.5">{{ $feature['title'] }}</h3>
                                <p class="text-gray-500 text-sm leading-relaxed">{{ $feature['desc'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-24 bg-gh-bg relative overflow-hidden">
        <div class="hero-glow" style="top: -100px;"></div>
        <div class="grid-bg absolute inset-0 opacity-30"></div>
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <div class="reveal" x-data x-intersect.once="$el.classList.add('visible')">
                <h2 class="text-3xl sm:text-5xl font-extrabold tracking-tight text-white mb-4">Ready to experience these features?</h2>
                <p class="text-lg text-[#8b949e] mb-10">Start building your website for free today.</p>
                <a href="{{ route('builder.index') }}" class="inline-flex items-center gap-2 bg-white hover:bg-gray-100 text-gray-900 font-semibold px-7 py-3 rounded-lg text-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Build Website
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
