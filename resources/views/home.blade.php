<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>WebNewBiz - Build Your Website with AI in 60 Seconds</title>
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
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        *, *::before, *::after { -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
        [x-cloak] { display: none !important; }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(28px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .animate-up { animation: fadeInUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
        .animate-fade { animation: fadeIn 0.6s ease-out forwards; opacity: 0; }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }
        .delay-5 { animation-delay: 0.5s; }

        .reveal {
            opacity: 0; transform: translateY(24px);
            transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal.visible { opacity: 1; transform: translateY(0); }

        .feature-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            transition: all 0.3s ease;
        }
        .feature-card:hover {
            border-color: #9ca3af;
            box-shadow: 0 20px 40px rgba(0,0,0,0.06), 0 4px 12px rgba(0,0,0,0.04);
            transform: translateY(-2px);
        }

        @property --angle {
            syntax: '<angle>';
            initial-value: 0deg;
            inherits: false;
        }
        .prompt-wrapper {
            position: relative;
            border-radius: 18px;
            padding: 2px;
            background: conic-gradient(from var(--angle), #d1d5db 0%, #111827 25%, #d1d5db 50%, #111827 75%, #d1d5db 100%);
            animation: rotateBorder 3s linear infinite;
        }
        .prompt-wrapper:focus-within {
            animation: rotateBorder 1.5s linear infinite;
            box-shadow: 0 0 30px rgba(0,0,0,0.08);
        }
        @keyframes rotateBorder {
            0% { --angle: 0deg; }
            100% { --angle: 360deg; }
        }
        /* Fallback for browsers without @property */
        @supports not (background: conic-gradient(from 0deg, red, blue)) {
            .prompt-wrapper {
                background: #111827;
            }
        }
        .prompt-input {
            background: #ffffff;
            border: none;
            border-radius: 16px;
        }
        .wizard-spinner { width: 40px; height: 40px; border: 3px solid #e5e7eb; border-top-color: #111827; border-radius: 50%; animation: spin 0.8s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        @keyframes fadeStep { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
        .step-enter { animation: fadeStep 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .style-card { transition: all 0.2s ease; border: 2px solid #e5e7eb; }
        .style-card:hover { border-color: #9ca3af; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.06); }
        .style-card.selected { border-color: #111827 !important; box-shadow: 0 4px 16px rgba(0,0,0,0.1); }
        .toggle-switch { transition: all 0.2s ease; }
    </style>
</head>
<body class="bg-white text-gray-900 overflow-x-hidden" x-data="websiteBuilder()">

    {{-- Navbar --}}
    <nav class="fixed top-0 w-full z-50 animate-fade"
         x-data="{ scrolled: false, activeSection: 'hero' }"
         x-init="
            window.addEventListener('scroll', () => { scrolled = window.scrollY > 20 });
            const sections = ['pricing','features','how-it-works'];
            const obs = new IntersectionObserver((entries) => {
                entries.forEach(e => { if(e.isIntersecting) activeSection = e.target.id || 'hero' });
            }, { threshold: 0.3 });
            sections.forEach(id => { const el = document.getElementById(id); if(el) obs.observe(el) });
         "
         :class="scrolled ? 'bg-white/80 backdrop-blur-2xl shadow-[0_1px_3px_rgba(0,0,0,0.05)] border-b border-gray-200/50' : 'bg-transparent'"
         style="transition: background 0.4s ease, box-shadow 0.4s ease, border-color 0.4s ease;">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                {{-- Left: Logo + Nav --}}
                <div class="flex items-center gap-10">
                    <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
                        <div class="w-9 h-9 bg-gray-900 rounded-xl flex items-center justify-center transition-all duration-300 group-hover:scale-110 group-hover:rotate-3 group-hover:shadow-lg group-hover:shadow-gray-900/20">
                            <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                            </svg>
                        </div>
                        <span class="text-lg font-bold tracking-tight text-gray-900">Webnewbiz</span>
                    </a>

                    {{-- Desktop Nav Links with pill indicator --}}
                    <div class="hidden md:flex items-center">
                        <div class="flex items-center bg-gray-100/80 rounded-full p-1 gap-0.5">
                            <a href="#features"
                               :class="activeSection === 'features' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                               class="px-4 py-1.5 text-sm font-medium rounded-full transition-all duration-300">Features</a>
                            <a href="#pricing"
                               :class="activeSection === 'pricing' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                               class="px-4 py-1.5 text-sm font-medium rounded-full transition-all duration-300">Pricing</a>
                            <a href="#how-it-works"
                               :class="activeSection === 'how-it-works' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                               class="px-4 py-1.5 text-sm font-medium rounded-full transition-all duration-300">How It Works</a>
                        </div>
                    </div>
                </div>

                {{-- Right: Auth buttons --}}
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="hidden sm:inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                            <div class="w-6 h-6 bg-gray-900 rounded-full flex items-center justify-center text-white text-[10px] font-bold">{{ substr(auth()->user()->name, 0, 1) }}</div>
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:inline-flex text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">Log in</a>
                    @endauth
                    <a href="#prompt-box" @click.prevent="document.getElementById('prompt-box').scrollIntoView({behavior:'smooth'}); setTimeout(() => document.getElementById('prompt-box').focus(), 500)" class="hidden sm:inline-flex items-center gap-2 bg-gray-900 hover:bg-black text-white text-sm font-semibold px-5 py-2.5 rounded-full transition-all duration-200 hover:shadow-lg hover:shadow-gray-900/20 hover:-translate-y-px active:translate-y-0 cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Build Website
                    </a>

                    {{-- Mobile hamburger with animated icon --}}
                    <button @click="mobileMenu = !mobileMenu" class="md:hidden relative w-10 h-10 rounded-xl hover:bg-gray-100 transition-colors flex items-center justify-center">
                        <div class="w-5 h-4 flex flex-col justify-between">
                            <span class="block h-0.5 bg-gray-700 rounded-full transition-all duration-300 origin-center"
                                  :class="mobileMenu ? 'rotate-45 translate-y-[7px]' : ''"></span>
                            <span class="block h-0.5 bg-gray-700 rounded-full transition-all duration-300"
                                  :class="mobileMenu ? 'opacity-0 scale-x-0' : ''"></span>
                            <span class="block h-0.5 bg-gray-700 rounded-full transition-all duration-300 origin-center"
                                  :class="mobileMenu ? '-rotate-45 -translate-y-[7px]' : ''"></span>
                        </div>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Menu (slide down) --}}
        <div x-show="mobileMenu" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             @click.away="mobileMenu = false"
             class="md:hidden absolute top-full left-4 right-4 bg-white rounded-2xl shadow-2xl shadow-gray-900/10 border border-gray-200/80 overflow-hidden mt-2">
            <div class="p-2">
                <a href="#features" @click="mobileMenu = false" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-xl transition-colors">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/></svg>
                    Features
                </a>
                <a href="#pricing" @click="mobileMenu = false" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-xl transition-colors">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Pricing
                </a>
                <a href="#how-it-works" @click="mobileMenu = false" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-xl transition-colors">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    How It Works
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
                <a href="#prompt-box" @click.prevent="mobileMenu = false; document.getElementById('prompt-box').scrollIntoView({behavior:'smooth'}); setTimeout(() => document.getElementById('prompt-box').focus(), 500)" class="flex items-center justify-center gap-2 mx-2 mt-1 mb-1 bg-gray-900 text-white text-sm font-semibold px-5 py-3 rounded-xl transition-all hover:bg-black cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Build Website
                </a>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="relative pt-16 overflow-hidden bg-gradient-to-b from-gray-50 via-white to-white">

        {{-- Subtle dot pattern --}}
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 32px 32px;"></div>

        <div class="relative z-10 max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28 lg:py-36 text-center">
            {{-- Badge --}}
            <div class="animate-up">
                <div class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 text-sm font-semibold px-4 py-2 rounded-full border border-gray-200 mb-8">
                    <div class="w-2 h-2 bg-gray-900 rounded-full animate-pulse"></div>
                    AI-Powered Website Builder
                </div>
            </div>

            {{-- Main heading --}}
            <h1 class="text-4xl sm:text-5xl lg:text-[68px] font-extrabold tracking-tight leading-[1.08] mb-6 animate-up delay-1">
                <span class="text-gray-900">Launch and Grow Your</span><br>
                <span class="text-gray-900">Business Online</span>
            </h1>

            <p class="text-lg sm:text-xl text-gray-500 max-w-2xl mx-auto mb-10 leading-relaxed animate-up delay-2">
                Describe your business and our AI creates a complete, professional<br class="hidden sm:block"> WordPress website for you in under 60 seconds.
            </p>

            {{-- Prompt Box --}}
            <div class="max-w-2xl mx-auto animate-up delay-3">
                <form @submit.prevent="handleGenerate()">
                    <div class="prompt-wrapper">
                        <div class="prompt-input">
                            <div class="flex items-start p-2">
                                <div class="flex-shrink-0 mt-3 ml-2">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/></svg>
                                </div>
                                <textarea
                                    id="prompt-box"
                                    x-model="prompt"
                                    rows="3"
                                    class="w-full resize-none border-0 bg-transparent text-gray-800 placeholder-gray-400 text-base px-3 py-2.5 focus:outline-none focus:ring-0"
                                    :placeholder="placeholders[placeholderIndex]"
                                ></textarea>
                                <button type="submit"
                                    :disabled="!prompt.trim()"
                                    class="self-end m-0.5 bg-gray-900 hover:bg-black disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed text-white font-semibold px-6 py-2.5 rounded-xl text-sm transition-all duration-200 flex items-center gap-2 whitespace-nowrap">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    Generate
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                {{-- Example prompts --}}
                <div class="flex flex-wrap justify-center gap-2 mt-5">
                    <span class="text-xs text-gray-400 font-medium">Try:</span>
                    <button @click="prompt = 'A cozy Italian restaurant in downtown with online reservations and a beautiful menu page'" class="text-xs text-gray-500 hover:text-gray-900 bg-white hover:bg-gray-50 border border-gray-200 hover:border-gray-400 px-3 py-1.5 rounded-full transition-all cursor-pointer">Restaurant</button>
                    <button @click="prompt = 'An online clothing store selling trendy fashion for young adults with a modern lookbook'" class="text-xs text-gray-500 hover:text-gray-900 bg-white hover:bg-gray-50 border border-gray-200 hover:border-gray-400 px-3 py-1.5 rounded-full transition-all cursor-pointer">E-commerce</button>
                    <button @click="prompt = 'A creative digital marketing agency helping startups grow with SEO, social media, and branding'" class="text-xs text-gray-500 hover:text-gray-900 bg-white hover:bg-gray-50 border border-gray-200 hover:border-gray-400 px-3 py-1.5 rounded-full transition-all cursor-pointer">Agency</button>
                    <button @click="prompt = 'A personal portfolio website showcasing my web development projects and skills'" class="text-xs text-gray-500 hover:text-gray-900 bg-white hover:bg-gray-50 border border-gray-200 hover:border-gray-400 px-3 py-1.5 rounded-full transition-all cursor-pointer">Portfolio</button>
                </div>
            </div>

            {{-- Trusted by --}}
            <div class="mt-16 animate-up delay-5">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-widest mb-5">Trusted by businesses worldwide</p>
                <div class="flex flex-wrap justify-center items-center gap-x-10 gap-y-4 opacity-30">
                    <span class="text-lg font-bold text-gray-500 tracking-tight">WordPress</span>
                    <span class="text-lg font-bold text-gray-500 tracking-tight">Elementor</span>
                    <span class="text-lg font-bold text-gray-500 tracking-tight">Google Cloud</span>
                    <span class="text-lg font-bold text-gray-500 tracking-tight">Cloudflare</span>
                    <span class="text-lg font-bold text-gray-500 tracking-tight">Let's Encrypt</span>
                </div>
            </div>
        </div>
    </section>

    {{-- Stats Bar --}}
    <section class="py-16 bg-white border-b border-gray-100">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
                <div class="text-center reveal" x-data x-intersect.once="$el.classList.add('visible')">
                    <div class="inline-flex items-center justify-center w-14 h-14 bg-gray-100 rounded-2xl mb-4">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/></svg>
                    </div>
                    <p class="text-4xl font-extrabold tracking-tight text-gray-900">{{ number_format($websiteCount) }}+</p>
                    <p class="text-sm text-gray-500 mt-1">Websites built</p>
                </div>
                <div class="text-center reveal" x-data x-intersect.once="$el.classList.add('visible')" style="transition-delay: 0.1s">
                    <div class="inline-flex items-center justify-center w-14 h-14 bg-gray-100 rounded-2xl mb-4">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-4xl font-extrabold tracking-tight text-gray-900">60s</p>
                    <p class="text-sm text-gray-500 mt-1">Average build time</p>
                </div>
                <div class="text-center reveal" x-data x-intersect.once="$el.classList.add('visible')" style="transition-delay: 0.2s">
                    <div class="inline-flex items-center justify-center w-14 h-14 bg-gray-100 rounded-2xl mb-4">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.098 19.902a3.75 3.75 0 005.304 0l6.401-6.402M6.75 21A3.75 3.75 0 013 17.25V4.125C3 3.504 3.504 3 4.125 3h5.25c.621 0 1.125.504 1.125 1.125v4.072M6.75 21a3.75 3.75 0 003.75-3.75V8.197M6.75 21h13.125c.621 0 1.125-.504 1.125-1.125v-5.25c0-.621-.504-1.125-1.125-1.125h-4.072M10.5 8.197l2.88-2.88c.438-.439 1.15-.439 1.59 0l3.712 3.713c.44.44.44 1.152 0 1.59l-2.879 2.88M6.75 17.25h.008v.008H6.75v-.008z"/></svg>
                    </div>
                    <p class="text-4xl font-extrabold tracking-tight text-gray-900">50+</p>
                    <p class="text-sm text-gray-500 mt-1">Professional themes</p>
                </div>
            </div>
        </div>
    </section>

    {{-- How It Works --}}
    <section id="how-it-works" class="py-24 bg-gray-50">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="reveal" x-data x-intersect.once="$el.classList.add('visible')">
                    <div class="inline-flex items-center gap-2 bg-white text-gray-700 text-xs font-semibold px-3 py-1.5 rounded-full border border-gray-200 mb-4 uppercase tracking-wider">How it works</div>
                    <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-gray-900">Three steps to your website</h2>
                    <p class="text-gray-500 mt-3 max-w-lg mx-auto">Get your professional website up and running in minutes, not days.</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-10 relative">
                {{-- Connector line --}}
                <div class="hidden md:block absolute top-[52px] left-[calc(16.67%+40px)] right-[calc(16.67%+40px)] h-[2px] bg-gradient-to-r from-gray-200 via-gray-300 to-gray-200 rounded-full"></div>

                <div class="relative reveal" x-data x-intersect.once="$el.classList.add('visible')">
                    <div class="text-center">
                        <div class="relative inline-flex items-center justify-center w-[72px] h-[72px] bg-gray-900 rounded-2xl text-white text-2xl font-bold mb-6 shadow-lg z-10">1</div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Describe your business</h3>
                        <p class="text-gray-500 text-sm leading-relaxed max-w-xs mx-auto">Tell us about your business, pick a style, and let AI understand your vision.</p>
                    </div>
                </div>
                <div class="relative reveal" x-data x-intersect.once="$el.classList.add('visible')" style="transition-delay: 0.15s">
                    <div class="text-center">
                        <div class="relative inline-flex items-center justify-center w-[72px] h-[72px] bg-gray-900 rounded-2xl text-white text-2xl font-bold mb-6 shadow-lg z-10">2</div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">AI generates your site</h3>
                        <p class="text-gray-500 text-sm leading-relaxed max-w-xs mx-auto">Our AI creates custom content, sets up WordPress, configures hosting, and designs your pages.</p>
                    </div>
                </div>
                <div class="relative reveal" x-data x-intersect.once="$el.classList.add('visible')" style="transition-delay: 0.3s">
                    <div class="text-center">
                        <div class="relative inline-flex items-center justify-center w-[72px] h-[72px] bg-gray-900 rounded-2xl text-white text-2xl font-bold mb-6 shadow-lg z-10">3</div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Your site goes live</h3>
                        <p class="text-gray-500 text-sm leading-relaxed max-w-xs mx-auto">In under a minute, your website is live with hosting, SSL, and a custom subdomain.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section id="features" class="py-24 bg-white">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="reveal" x-data x-intersect.once="$el.classList.add('visible')">
                    <div class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 text-xs font-semibold px-3 py-1.5 rounded-full border border-gray-200 mb-4 uppercase tracking-wider">Features</div>
                    <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-gray-900">Everything you need to succeed online</h2>
                    <p class="text-gray-500 mt-3 max-w-lg mx-auto">Powerful tools and features to build, manage, and grow your website.</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    $features = [
                        ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z"/>', 'title' => 'AI Content Generation', 'desc' => 'Our AI writes professional website content based on your business description and style.'],
                        ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.737 5.1a3.375 3.375 0 012.7-1.35h7.126c1.062 0 2.062.5 2.7 1.35l2.587 3.45a4.5 4.5 0 01.9 2.7m0 0a3 3 0 01-3 3m0 3h.008v.008h-.008v-.008zm0-6h.008v.008h-.008v-.008zm-3 6h.008v.008h-.008v-.008zm0-6h.008v.008h-.008v-.008z"/>', 'title' => 'Managed WordPress Hosting', 'desc' => 'High-performance servers with automatic updates, backups, and monitoring.'],
                        ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>', 'title' => 'Free SSL Certificate', 'desc' => 'Every website gets a free SSL certificate for secure HTTPS connections.'],
                        ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m9.86-2.556a4.5 4.5 0 00-6.364-6.364L4.5 8.28a4.5 4.5 0 006.364 6.364l4.5-4.5z"/>', 'title' => 'Custom Domains', 'desc' => 'Connect your own domain name or use our free subdomain to get started.'],
                        ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 15a4.5 4.5 0 004.5 4.5H18a3.75 3.75 0 001.332-7.257 3 3 0 00-3.758-3.848 5.25 5.25 0 00-10.233 2.33A4.502 4.502 0 002.25 15z"/>', 'title' => 'Automatic Backups', 'desc' => 'Your website is backed up automatically. Restore to any point with one click.'],
                        ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"/>', 'title' => 'Full Management', 'desc' => 'Manage plugins, themes, domains, and settings all from one dashboard.'],
                    ];
                @endphp
                @foreach($features as $i => $feature)
                    <div class="feature-card p-7 group reveal"
                         x-data x-intersect.once="$el.classList.add('visible')"
                         style="transition-delay: {{ ($i % 3) * 0.1 }}s">
                        <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center mb-5 transition-all duration-300 group-hover:bg-gray-900">
                            <svg class="w-6 h-6 text-gray-600 transition-colors duration-300 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $feature['icon'] !!}</svg>
                        </div>
                        <h3 class="text-base font-bold text-gray-900 mb-2">{{ $feature['title'] }}</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">{{ $feature['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Pricing --}}
    <section id="pricing" class="py-24 bg-gray-50" x-data="{ billing: 'monthly' }">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <div class="reveal" x-data x-intersect.once="$el.classList.add('visible')">
                    <div class="inline-flex items-center gap-2 bg-white text-gray-700 text-xs font-semibold px-3 py-1.5 rounded-full border border-gray-200 mb-4 uppercase tracking-wider">Pricing</div>
                    <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-gray-900 mb-3">Simple, transparent pricing</h2>
                    <p class="text-gray-500 mb-8">Choose the plan that fits your business needs.</p>

                    {{-- Monthly/Yearly toggle --}}
                    <div class="inline-flex items-center bg-white border border-gray-200 rounded-full p-1 shadow-sm">
                        <button @click="billing = 'monthly'" :class="billing === 'monthly' ? 'bg-gray-900 text-white shadow-md' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-2 rounded-full text-sm font-semibold transition-all duration-200">Monthly</button>
                        <button @click="billing = 'yearly'" :class="billing === 'yearly' ? 'bg-gray-900 text-white shadow-md' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-2 rounded-full text-sm font-semibold transition-all duration-200 flex items-center gap-1.5">
                            Yearly
                            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full" :class="billing === 'yearly' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-600'">SAVE 15%</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto items-start">
                {{-- Starter --}}
                <div class="relative bg-white border border-gray-200 rounded-2xl p-8 transition-all duration-300 hover:shadow-xl hover:border-gray-300 reveal"
                     x-data x-intersect.once="$el.classList.add('visible')">
                    <h3 class="text-xl font-bold text-gray-900">Starter</h3>
                    <p class="text-sm text-gray-500 mt-1">Perfect for individuals, freelancers, and startups</p>
                    <div class="mt-5 mb-1">
                        <div x-show="billing === 'monthly'">
                            <span class="text-5xl font-extrabold tracking-tight text-gray-900">$1</span>
                            <span class="text-gray-500">/month</span>
                        </div>
                        <div x-show="billing === 'yearly'" x-cloak>
                            <span class="text-5xl font-extrabold tracking-tight text-gray-900">$84</span>
                            <span class="text-gray-500">/year</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mb-6" x-show="billing === 'monthly'">First 3 months, then $9/month</p>
                    <p class="text-xs text-gray-400 mb-6" x-show="billing === 'yearly'" x-cloak>Billed annually</p>

                    <a href="#prompt-box" @click.prevent="document.getElementById('prompt-box').scrollIntoView({behavior:'smooth'}); setTimeout(() => document.getElementById('prompt-box').focus(), 500)" class="block text-center w-full bg-gray-900 hover:bg-black text-white font-semibold py-3 rounded-xl text-sm transition-all mb-8 cursor-pointer">
                        Get Started
                    </a>

                    <ul class="space-y-3">
                        @php $starterFeatures = ['AI Builder', 'AI Dashboard', 'Built-in AI Assistant', 'Drag-and-Drop Editor', 'AI Image Generation', 'Free SSL', 'AI WordPress Converter', '24/7 Support']; @endphp
                        @foreach($starterFeatures as $feature)
                        <li class="flex items-start gap-2.5 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-gray-900 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            {{ $feature }}
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Professional (Popular) --}}
                <div class="relative bg-gray-900 text-white rounded-2xl p-8 transition-all duration-300 hover:shadow-2xl ring-4 ring-gray-900/10 scale-[1.03] reveal"
                     x-data x-intersect.once="$el.classList.add('visible')" style="transition-delay: 0.1s">
                    <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-white text-gray-900 text-xs font-bold px-4 py-1 rounded-full shadow-md border border-gray-200">Most popular</div>

                    <h3 class="text-xl font-bold text-white">Professional</h3>
                    <p class="text-sm text-gray-400 mt-1">For growing businesses wanting advanced automation</p>
                    <div class="mt-5 mb-1">
                        <div x-show="billing === 'monthly'">
                            <span class="text-5xl font-extrabold tracking-tight text-white">$29</span>
                            <span class="text-gray-400">/month</span>
                        </div>
                        <div x-show="billing === 'yearly'" x-cloak>
                            <span class="text-5xl font-extrabold tracking-tight text-white">$296</span>
                            <span class="text-gray-400">/year</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mb-6" x-show="billing === 'monthly'">Billed monthly</p>
                    <p class="text-xs text-gray-500 mb-6" x-show="billing === 'yearly'" x-cloak>Save $52 vs monthly</p>

                    <a href="#prompt-box" @click.prevent="document.getElementById('prompt-box').scrollIntoView({behavior:'smooth'}); setTimeout(() => document.getElementById('prompt-box').focus(), 500)" class="block text-center w-full bg-white hover:bg-gray-50 text-gray-900 font-semibold py-3 rounded-xl text-sm transition-all mb-8 cursor-pointer">
                        Get Started
                    </a>

                    <ul class="space-y-3">
                        @php $proFeatures = ['Everything in Starter', '24/7 AI Chatbot', 'AI Order Processing', 'E-commerce System', 'Appointment Booking', 'Lead Capture & Follow-up Emails', 'Business Email', 'Priority Support', 'High-Performance Hosting', '99.9% Uptime', 'Migration', 'SEO Tools', 'Complete Website Design']; @endphp
                        @foreach($proFeatures as $feature)
                        <li class="flex items-start gap-2.5 text-sm text-gray-300">
                            <svg class="w-4 h-4 text-white flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            {{ $feature }}
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Premium --}}
                <div class="relative bg-white border border-gray-200 rounded-2xl p-8 transition-all duration-300 hover:shadow-xl hover:border-gray-300 reveal"
                     x-data x-intersect.once="$el.classList.add('visible')" style="transition-delay: 0.2s">
                    <h3 class="text-xl font-bold text-gray-900">Premium</h3>
                    <p class="text-sm text-gray-500 mt-1">Full automation, enterprise performance</p>
                    <div class="mt-5 mb-1">
                        <div x-show="billing === 'monthly'">
                            <span class="text-5xl font-extrabold tracking-tight text-gray-900">$49</span>
                            <span class="text-gray-500">/month</span>
                        </div>
                        <div x-show="billing === 'yearly'" x-cloak>
                            <span class="text-5xl font-extrabold tracking-tight text-gray-900">$500</span>
                            <span class="text-gray-500">/year</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mb-6" x-show="billing === 'monthly'">Billed monthly</p>
                    <p class="text-xs text-gray-400 mb-6" x-show="billing === 'yearly'" x-cloak>Save $88 vs monthly</p>

                    <a href="#prompt-box" @click.prevent="document.getElementById('prompt-box').scrollIntoView({behavior:'smooth'}); setTimeout(() => document.getElementById('prompt-box').focus(), 500)" class="block text-center w-full bg-gray-900 hover:bg-black text-white font-semibold py-3 rounded-xl text-sm transition-all mb-8 cursor-pointer">
                        Get Started
                    </a>

                    <ul class="space-y-3">
                        @php $premiumFeatures = ['Everything in Professional', 'AI Social Media Manager', 'Auto-Publishing', 'Ad Campaign Suggestions', 'Analytics', 'Advanced E-commerce', '90+ PageSpeed Optimization', 'Cloudflare CDN', '10x Faster Load Times', 'Instagram/Facebook/WhatsApp AI Chatbots']; @endphp
                        @foreach($premiumFeatures as $feature)
                        <li class="flex items-start gap-2.5 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-gray-900 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            {{ $feature }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-24 bg-gray-900 relative overflow-hidden">
        <div class="absolute inset-0 opacity-5" style="background-image: radial-gradient(rgba(255,255,255,0.4) 1px, transparent 1px); background-size: 24px 24px;"></div>

        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <div class="reveal" x-data x-intersect.once="$el.classList.add('visible')">
                <h2 class="text-3xl sm:text-5xl font-extrabold tracking-tight text-white mb-4">Ready to build your website?</h2>
                <p class="text-lg text-gray-400 mb-10">Describe your business below and let AI do the rest.</p>

                <form class="max-w-xl mx-auto" @submit.prevent="prompt = ctaPrompt; handleGenerate()">
                    <div class="flex gap-3">
                        <input type="text" x-model="ctaPrompt" placeholder="Describe your business..."
                            class="flex-1 px-5 py-3.5 rounded-xl text-base bg-white/10 text-white placeholder-gray-500 border border-white/15 focus:outline-none focus:border-white/30 focus:ring-2 focus:ring-white/10 transition-all">
                        <button type="submit" :disabled="!ctaPrompt.trim()"
                            class="bg-white hover:bg-gray-100 disabled:bg-white/10 disabled:text-gray-500 text-gray-900 font-semibold px-7 py-3.5 rounded-xl text-sm transition-all whitespace-nowrap disabled:shadow-none">
                            Generate
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-gray-950 border-t border-white/5">
        {{-- Main Footer --}}
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-10 lg:gap-12">
                {{-- Brand Column --}}
                <div class="col-span-2 md:col-span-4 lg:col-span-2">
                    <a href="{{ url('/') }}" class="flex items-center gap-2.5 group mb-5">
                        <div class="w-9 h-9 bg-white rounded-xl flex items-center justify-center transition-transform duration-200 group-hover:scale-105">
                            <svg class="w-5 h-5 text-gray-900" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                            </svg>
                        </div>
                        <span class="text-lg font-bold tracking-tight text-white">Webnewbiz</span>
                    </a>
                    <p class="text-sm text-gray-400 leading-relaxed max-w-xs mb-6">Build professional AI-powered WordPress websites in under 60 seconds. Just describe your business and we handle the rest.</p>
                    {{-- Social Icons --}}
                    <div class="flex items-center gap-3">
                        <a href="#" class="w-9 h-9 bg-white/[0.06] hover:bg-white/[0.12] border border-white/[0.06] rounded-lg flex items-center justify-center text-gray-400 hover:text-white transition-all duration-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                        </a>
                        <a href="#" class="w-9 h-9 bg-white/[0.06] hover:bg-white/[0.12] border border-white/[0.06] rounded-lg flex items-center justify-center text-gray-400 hover:text-white transition-all duration-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                        </a>
                        <a href="#" class="w-9 h-9 bg-white/[0.06] hover:bg-white/[0.12] border border-white/[0.06] rounded-lg flex items-center justify-center text-gray-400 hover:text-white transition-all duration-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </a>
                        <a href="#" class="w-9 h-9 bg-white/[0.06] hover:bg-white/[0.12] border border-white/[0.06] rounded-lg flex items-center justify-center text-gray-400 hover:text-white transition-all duration-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Product --}}
                <div>
                    <h4 class="text-xs font-bold text-white uppercase tracking-widest mb-5">Product</h4>
                    <ul class="space-y-3">
                        <li><a href="#features" class="text-sm text-gray-400 hover:text-white transition-colors duration-200">Features</a></li>
                        <li><a href="#pricing" class="text-sm text-gray-400 hover:text-white transition-colors duration-200">Pricing</a></li>
                        <li><a href="#prompt-box" @click.prevent="document.getElementById('prompt-box').scrollIntoView({behavior:'smooth'})" class="text-sm text-gray-400 hover:text-white transition-colors duration-200 cursor-pointer">AI Builder</a></li>
                        <li><a href="{{ route('dashboard') }}" class="text-sm text-gray-400 hover:text-white transition-colors duration-200">Dashboard</a></li>
                    </ul>
                </div>

                {{-- Company --}}
                <div>
                    <h4 class="text-xs font-bold text-white uppercase tracking-widest mb-5">Company</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-sm text-gray-400 hover:text-white transition-colors duration-200">About Us</a></li>
                        <li><a href="#" class="text-sm text-gray-400 hover:text-white transition-colors duration-200">Blog</a></li>
                        <li><a href="#" class="text-sm text-gray-400 hover:text-white transition-colors duration-200">Careers</a></li>
                        <li><a href="#" class="text-sm text-gray-400 hover:text-white transition-colors duration-200">Contact</a></li>
                    </ul>
                </div>

                {{-- Support --}}
                <div>
                    <h4 class="text-xs font-bold text-white uppercase tracking-widest mb-5">Support</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-sm text-gray-400 hover:text-white transition-colors duration-200">Help Center</a></li>
                        <li><a href="#" class="text-sm text-gray-400 hover:text-white transition-colors duration-200">Documentation</a></li>
                        <li><a href="#" class="text-sm text-gray-400 hover:text-white transition-colors duration-200">Status</a></li>
                        <li><a href="#" class="text-sm text-gray-400 hover:text-white transition-colors duration-200">Community</a></li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="border-t border-white/[0.06]">
            <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <p class="text-xs text-gray-500">&copy; {{ date('Y') }} Webnewbiz. All rights reserved.</p>
                    <div class="flex items-center gap-6">
                        <a href="#" class="text-xs text-gray-500 hover:text-gray-300 transition-colors duration-200">Privacy Policy</a>
                        <a href="#" class="text-xs text-gray-500 hover:text-gray-300 transition-colors duration-200">Terms of Service</a>
                        <a href="#" class="text-xs text-gray-500 hover:text-gray-300 transition-colors duration-200">Cookie Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                document.querySelectorAll('.reveal').forEach(el => {
                    if (!el.classList.contains('visible')) el.classList.add('visible');
                });
            }, 1500);
            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) { entry.target.classList.add('visible'); observer.unobserve(entry.target); }
                    });
                }, { threshold: 0.1 });
                document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
            } else {
                document.querySelectorAll('.reveal').forEach(el => el.classList.add('visible'));
            }
        });
    </script>

    {{-- ============ AI BUILDER WIZARD (10Web Style) ============ --}}
    <div x-show="showWizard" x-cloak class="fixed inset-0 z-[100] bg-white"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

        <div class="flex h-screen">

            {{-- Left Sidebar (desktop) --}}
            <div class="hidden lg:flex flex-col w-[280px] bg-gray-50 border-r border-gray-200 p-8">
                <a href="/" class="flex items-center gap-2.5 mb-14">
                    <div class="w-8 h-8 bg-gray-900 rounded-xl flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
                    </div>
                    <span class="text-base font-bold text-gray-900">WebNewBiz</span>
                </a>

                <div class="space-y-1 flex-1">
                    <template x-for="(stepName, i) in stepNames" :key="i">
                        <div class="flex items-center gap-3 py-2.5 px-3 rounded-xl transition-all duration-200"
                             :class="wizardStep === i ? 'bg-white shadow-sm' : ''">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 transition-all duration-200"
                                 :class="wizardStep > i ? 'bg-green-500 text-white' : wizardStep === i ? 'bg-gray-900 text-white' : 'bg-gray-200 text-gray-400'">
                                <template x-if="wizardStep > i">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </template>
                                <template x-if="wizardStep <= i">
                                    <span x-text="i + 1"></span>
                                </template>
                            </div>
                            <span class="text-sm font-medium transition-colors duration-200"
                                  :class="wizardStep === i ? 'text-gray-900' : wizardStep > i ? 'text-gray-600' : 'text-gray-400'"
                                  x-text="stepName"></span>
                        </div>
                    </template>
                </div>

                <button @click="closeWizard()" x-show="wizardStep < 3"
                        class="flex items-center gap-2 text-sm text-gray-400 hover:text-gray-600 transition-colors mt-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to home
                </button>
            </div>

            {{-- Main Content --}}
            <div class="flex-1 flex flex-col min-w-0">

                {{-- Top Bar --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <div class="lg:hidden flex items-center gap-2">
                        <div class="w-7 h-7 bg-gray-900 rounded-lg flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
                        </div>
                        <span class="text-sm font-bold text-gray-900">WebNewBiz</span>
                    </div>
                    <div class="hidden lg:block"></div>
                    <div x-show="wizardStep < 3" class="flex items-center gap-3">
                        <div class="flex items-center gap-1.5">
                            <template x-for="i in 3" :key="i">
                                <div class="h-1.5 rounded-full transition-all duration-300"
                                     :class="wizardStep >= (i-1) ? 'bg-gray-900 w-6' : 'bg-gray-200 w-4'"></div>
                            </template>
                        </div>
                        <span class="text-xs text-gray-400 font-medium" x-text="'Step ' + (wizardStep + 1) + ' of 3'"></span>
                    </div>
                    <button @click="closeWizard()" x-show="wizardStep < 3"
                            class="w-8 h-8 rounded-lg hover:bg-gray-100 flex items-center justify-center text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Scrollable Content --}}
                <div class="flex-1 overflow-y-auto">
                    <div class="max-w-2xl mx-auto px-6 py-10 sm:py-16">

                        {{-- STEP 0: Website Type --}}
                        <div x-show="wizardStep === 0" x-transition class="step-enter">
                            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">What type of website do you need?</h2>
                            <p class="text-gray-500 mb-10">Choose the type that best describes your project.</p>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <button @click="websiteType = 'business'"
                                        class="style-card p-6 rounded-2xl text-left cursor-pointer"
                                        :class="websiteType === 'business' ? 'selected bg-gray-50' : 'bg-white hover:bg-gray-50'">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4 transition-colors"
                                         :class="websiteType === 'business' ? 'bg-gray-900' : 'bg-gray-100'">
                                        <svg class="w-6 h-6 transition-colors" :class="websiteType === 'business' ? 'text-white' : 'text-gray-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582"/></svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900 mb-1">Business Website</h3>
                                    <p class="text-sm text-gray-500">Service businesses, portfolios, agencies, blogs, and informational sites.</p>
                                </button>

                                <button @click="websiteType = 'ecommerce'"
                                        class="style-card p-6 rounded-2xl text-left cursor-pointer"
                                        :class="websiteType === 'ecommerce' ? 'selected bg-gray-50' : 'bg-white hover:bg-gray-50'">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4 transition-colors"
                                         :class="websiteType === 'ecommerce' ? 'bg-gray-900' : 'bg-gray-100'">
                                        <svg class="w-6 h-6 transition-colors" :class="websiteType === 'ecommerce' ? 'text-white' : 'text-gray-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900 mb-1">Online Store</h3>
                                    <p class="text-sm text-gray-500">Sell products online with catalog, cart, checkout, and payment processing.</p>
                                </button>
                            </div>
                        </div>

                        {{-- STEP 1: Business Details --}}
                        <div x-show="wizardStep === 1" x-transition class="step-enter">
                            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Tell us about your business</h2>
                            <p class="text-gray-500 mb-10">Help us understand your business to create the perfect website.</p>

                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Business Name</label>
                                    <input type="text" x-model="businessName" placeholder="e.g., Bella's Bakery"
                                           class="w-full px-4 py-3 rounded-xl border border-gray-300 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900 transition-all">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Business Category</label>
                                    <div class="relative">
                                        <select x-model="businessType"
                                                class="w-full px-4 py-3 pr-10 rounded-xl border border-gray-300 text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900 transition-all appearance-none">
                                            <option value="">Select a category...</option>
                                            @php
                                                $categories = ['Restaurant', 'E-commerce', 'Portfolio', 'Blog', 'Agency', 'SaaS', 'Nonprofit', 'Consulting', 'Healthcare', 'Education', 'Real Estate', 'Fitness', 'Photography', 'Legal', 'Finance', 'Technology', 'Travel', 'Fashion', 'Beauty & Spa', 'Construction', 'Entertainment', 'Marketing', 'Architecture', 'Interior Design', 'Pet Services', 'Music', 'Sports', 'Automotive', 'Food & Beverage', 'Other'];
                                            @endphp
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat }}">{{ $cat }}</option>
                                            @endforeach
                                        </select>
                                        <svg class="w-4 h-4 text-gray-400 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Describe your business</label>
                                    <textarea x-model="prompt" rows="4" placeholder="Describe what your business does, who your customers are, and what makes you unique..."
                                              class="w-full px-4 py-3 rounded-xl border border-gray-300 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900 transition-all resize-none"></textarea>
                                    <p class="text-xs text-gray-400 mt-1.5" x-text="prompt.length + ' / 2000 characters'"></p>
                                </div>
                            </div>
                        </div>

                        {{-- STEP 2: Review --}}
                        <div x-show="wizardStep === 2" x-transition class="step-enter">
                            {{-- Loading --}}
                            <div x-show="isLoading" class="text-center py-20">
                                <div class="wizard-spinner mx-auto mb-6"></div>
                                <h3 class="text-lg font-bold text-gray-900 mb-2">Planning your website...</h3>
                                <p class="text-sm text-gray-400">AI is analyzing your business and creating a custom plan</p>
                                <div x-show="errorMessage" class="mt-6 text-sm text-red-600 bg-red-50 border border-red-200 rounded-xl px-4 py-3 max-w-sm mx-auto" x-text="errorMessage"></div>
                                <button x-show="errorMessage" @click="wizardStep = 1; errorMessage = ''; isLoading = false" class="mt-4 text-sm text-gray-400 hover:text-gray-600 transition-colors">Go back</button>
                            </div>

                            {{-- Review content --}}
                            <div x-show="!isLoading">
                                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Review your website plan</h2>
                                <p class="text-gray-500 mb-8">Here's what we'll build for you. Toggle pages on or off.</p>

                                {{-- Summary card --}}
                                <div class="bg-gray-50 rounded-2xl p-6 mb-6 border border-gray-100">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="w-10 h-10 bg-gray-900 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900" x-text="businessName"></div>
                                            <div class="text-xs text-gray-400" x-text="businessType + ' &middot; ' + (websiteType === 'ecommerce' ? 'Online Store' : 'Business Website')"></div>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 leading-relaxed" x-text="buildSummary"></p>
                                </div>

                                {{-- Features --}}
                                <div class="mb-6">
                                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Features Included</h4>
                                    <div class="flex flex-wrap gap-2">
                                        <template x-for="f in features" :key="f">
                                            <span class="text-xs font-medium px-3 py-1.5 rounded-full bg-gray-100 text-gray-700 border border-gray-200" x-text="f"></span>
                                        </template>
                                    </div>
                                </div>

                                {{-- Pages --}}
                                <div>
                                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Website Pages</h4>
                                    <div class="space-y-2">
                                        <template x-for="(page, i) in pages" :key="i">
                                            <div class="flex items-center justify-between p-4 rounded-xl border transition-all"
                                                 :class="page.enabled ? 'bg-white border-gray-200' : 'bg-gray-50 border-gray-100 opacity-50'">
                                                <div class="flex items-center gap-3 min-w-0">
                                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold flex-shrink-0 transition-colors"
                                                         :class="page.enabled ? 'bg-gray-900 text-white' : 'bg-gray-200 text-gray-400'"
                                                         x-text="i + 1"></div>
                                                    <div class="min-w-0">
                                                        <div class="text-sm font-semibold text-gray-900 truncate" x-text="page.name"></div>
                                                        <div class="text-xs text-gray-400 truncate" x-text="page.description"></div>
                                                    </div>
                                                </div>
                                                <button @click="page.enabled = !page.enabled"
                                                        class="w-10 h-6 rounded-full transition-all duration-200 flex items-center p-0.5 flex-shrink-0 ml-3"
                                                        :class="page.enabled ? 'bg-gray-900 justify-end' : 'bg-gray-300 justify-start'">
                                                    <div class="w-5 h-5 bg-white rounded-full shadow-sm"></div>
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <div x-show="errorMessage" class="mt-6 text-sm text-red-600 bg-red-50 border border-red-200 rounded-xl px-4 py-3" x-text="errorMessage"></div>
                            </div>
                        </div>

                        {{-- STEP 3: Generating --}}
                        <div x-show="wizardStep === 3" x-transition class="step-enter text-center py-20">
                            <div class="wizard-spinner mx-auto mb-6"></div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-3">Building your website...</h2>
                            <p class="text-sm text-gray-500 mb-1">WebNewBiz AI is creating your site. This takes about 60 seconds.</p>
                            <p class="text-xs text-gray-400">Please don't close this window.</p>

                            <div class="mt-10 max-w-xs mx-auto space-y-3 text-left">
                                <div class="flex items-center gap-3 text-sm transition-colors" :class="genProgress >= 1 ? 'text-gray-900' : 'text-gray-300'">
                                    <template x-if="genProgress >= 1"><svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg></template>
                                    <template x-if="genProgress < 1"><div class="w-4 h-4 rounded-full border-2 border-gray-200 flex-shrink-0"></div></template>
                                    <span>Setting up WordPress</span>
                                </div>
                                <div class="flex items-center gap-3 text-sm transition-colors" :class="genProgress >= 2 ? 'text-gray-900' : 'text-gray-300'">
                                    <template x-if="genProgress >= 2"><svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg></template>
                                    <template x-if="genProgress < 2"><div class="w-4 h-4 rounded-full border-2 border-gray-200 flex-shrink-0"></div></template>
                                    <span>Generating AI content</span>
                                </div>
                                <div class="flex items-center gap-3 text-sm transition-colors" :class="genProgress >= 3 ? 'text-gray-900' : 'text-gray-300'">
                                    <template x-if="genProgress >= 3"><svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg></template>
                                    <template x-if="genProgress < 3"><div class="w-4 h-4 rounded-full border-2 border-gray-200 flex-shrink-0"></div></template>
                                    <span>Designing pages</span>
                                </div>
                                <div class="flex items-center gap-3 text-sm transition-colors" :class="genProgress >= 4 ? 'text-gray-900' : 'text-gray-300'">
                                    <template x-if="genProgress >= 4"><svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg></template>
                                    <template x-if="genProgress < 4"><div class="w-4 h-4 rounded-full border-2 border-gray-200 flex-shrink-0"></div></template>
                                    <span>Launching your site</span>
                                </div>
                            </div>

                            <div x-show="errorMessage" class="mt-8 text-sm text-red-600 bg-red-50 border border-red-200 rounded-xl px-4 py-3 max-w-sm mx-auto" x-text="errorMessage"></div>
                            <button x-show="errorMessage" @click="wizardStep = 2; errorMessage = ''" class="mt-4 text-sm text-gray-400 hover:text-gray-600 transition-colors">Go back and try again</button>
                        </div>

                    </div>
                </div>

                {{-- Bottom Navigation --}}
                <div class="border-t border-gray-200 px-6 py-4" x-show="wizardStep < 3">
                    <div class="max-w-2xl mx-auto flex items-center justify-between">
                        <button x-show="wizardStep > 0" @click="prevStep()"
                                class="flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors px-4 py-2.5 rounded-xl hover:bg-gray-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            Back
                        </button>
                        <div x-show="wizardStep === 0"></div>

                        <button @click="nextStep()" :disabled="!canProceed()"
                                class="flex items-center gap-2 text-sm font-semibold px-6 py-2.5 rounded-xl transition-all duration-200"
                                :class="canProceed() ? 'bg-gray-900 text-white hover:bg-black hover:shadow-lg' : 'bg-gray-100 text-gray-400 cursor-not-allowed'">
                            <span x-text="wizardStep === 2 ? 'Build My Website' : 'Continue'"></span>
                            <svg x-show="wizardStep < 2" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            <svg x-show="wizardStep === 2" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ============ ALPINE COMPONENT ============ --}}
    <script>
    function websiteBuilder() {
        return {
            mobileMenu: false,

            // Prompt
            prompt: '',
            ctaPrompt: '',
            placeholderIndex: 0,
            placeholders: [
                'A modern restaurant in downtown Chicago with online reservations...',
                'An e-commerce store selling handmade jewelry and accessories...',
                'A digital marketing agency helping small businesses grow...',
                'A fitness studio offering yoga, pilates and personal training...'
            ],

            // Wizard state
            showWizard: false,
            wizardStep: 0,
            stepNames: ['Website Type', 'Business Details', 'Review & Build'],
            isLoading: false,
            errorMessage: '',

            // Step data
            websiteType: '',
            businessName: '',
            businessType: '',

            // Plan data
            buildSummary: '',
            features: [],
            pages: [],
            theme: '',

            // Generating
            genProgress: 0,
            isLoggedIn: {{ auth()->check() ? 'true' : 'false' }},

            init() {
                setInterval(() => {
                    this.placeholderIndex = (this.placeholderIndex + 1) % this.placeholders.length;
                }, 4000);

                const saved = sessionStorage.getItem('wnb_builder_state');
                if (saved && this.isLoggedIn) {
                    try {
                        const state = JSON.parse(saved);
                        sessionStorage.removeItem('wnb_builder_state');
                        Object.assign(this, state);
                        this.showWizard = true;
                        this.wizardStep = this.pages.length > 0 ? 2 : 0;
                    } catch(e) {
                        sessionStorage.removeItem('wnb_builder_state');
                    }
                }
            },

            handleGenerate() {
                if (!this.prompt.trim() || this.prompt.trim().length < 10) {
                    this.prompt = this.prompt || '';
                    document.getElementById('prompt-box')?.focus();
                    return;
                }
                this.showWizard = true;
                this.wizardStep = 0;
                this.errorMessage = '';
                this.websiteType = '';
                this.businessName = '';
                this.businessType = '';
                this.pages = [];
                this.features = [];
                this.buildSummary = '';
            },

            canProceed() {
                switch (this.wizardStep) {
                    case 0: return !!this.websiteType;
                    case 1: return this.businessName.trim().length >= 2 && !!this.businessType && this.prompt.trim().length >= 10;
                    case 2: return this.pages.filter(p => p.enabled).length > 0 && !this.isLoading;
                    default: return false;
                }
            },

            async nextStep() {
                if (!this.canProceed()) return;

                if (this.wizardStep === 1) {
                    this.wizardStep = 2;
                    this.isLoading = true;
                    this.errorMessage = '';
                    await this.generatePlan();
                } else if (this.wizardStep === 2) {
                    await this.buildWebsite();
                } else {
                    this.wizardStep++;
                }
            },

            prevStep() {
                if (this.wizardStep > 0 && this.wizardStep < 3) {
                    this.wizardStep--;
                    this.errorMessage = '';
                }
            },

            async generatePlan() {
                try {
                    const res = await fetch('/builder/plan-site', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            website_type: this.websiteType,
                            business_name: this.businessName,
                            business_type: this.businessType,
                            description: this.prompt,
                            style: 'auto'
                        })
                    });
                    const data = await res.json();
                    if (data.success) {
                        this.buildSummary = data.summary || '';
                        this.features = data.features || [];
                        this.pages = (data.pages || []).map(p => ({
                            name: typeof p === 'string' ? p : (p.name || 'Page'),
                            description: typeof p === 'string' ? '' : (p.description || ''),
                            enabled: typeof p === 'string' ? true : (p.enabled !== false)
                        }));
                        this.theme = data.theme || 'auto';
                    } else {
                        this.errorMessage = data.message || 'Failed to generate plan. Please try again.';
                    }
                } catch(e) {
                    this.errorMessage = 'Network error. Please check your connection.';
                } finally {
                    this.isLoading = false;
                }
            },

            async buildWebsite() {
                if (!this.isLoggedIn) {
                    this.saveStateAndLogin();
                    return;
                }

                this.wizardStep = 3;
                this.errorMessage = '';
                this.genProgress = 0;

                const timer = setInterval(() => {
                    if (this.genProgress < 3) this.genProgress++;
                }, 8000);

                try {
                    const enabledPages = this.pages.filter(p => p.enabled).map(p => ({ name: p.name, sections: [] }));

                    const formData = new FormData();
                    formData.append('name', this.businessName);
                    formData.append('business_type', this.businessType);
                    formData.append('prompt', this.prompt);
                    formData.append('style', this.theme || 'auto');
                    formData.append('theme', this.theme || 'auto');
                    formData.append('pages_structure', JSON.stringify(enabledPages));

                    const res = await fetch('/builder/generate', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    });
                    const data = await res.json();
                    clearInterval(timer);

                    if (data.success && data.redirect) {
                        this.genProgress = 4;
                        setTimeout(() => { window.location.href = data.redirect; }, 500);
                    } else {
                        this.errorMessage = data.message || 'Build failed. Please try again.';
                    }
                } catch(e) {
                    clearInterval(timer);
                    this.errorMessage = 'Network error. Please try again.';
                }
            },

            closeWizard() {
                if (this.wizardStep < 3) this.showWizard = false;
            },

            saveStateAndLogin() {
                const state = {
                    prompt: this.prompt,
                    businessName: this.businessName,
                    businessType: this.businessType,
                    websiteType: this.websiteType,
                    buildSummary: this.buildSummary,
                    features: this.features,
                    pages: this.pages,
                    theme: this.theme
                };
                sessionStorage.setItem('wnb_builder_state', JSON.stringify(state));
                window.location.href = '/login?redirect=' + encodeURIComponent('/');
            }
        };
    }
    </script>

</body>
</html>
