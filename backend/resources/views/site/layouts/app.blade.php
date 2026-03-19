<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'WebNewBiz — AI-Powered Website Builder')</title>
    <meta name="description" content="@yield('description', 'Build your AI-powered website in minutes. No coding required.')">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Space Grotesk', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f7f7f7',
                            100: '#e3e3e3',
                            200: '#c8c8c8',
                            300: '#a4a4a4',
                            400: '#818181',
                            500: '#666666',
                            600: '#515151',
                            700: '#434343',
                            800: '#383838',
                            900: '#1a1a1a',
                            950: '#0d0d0d',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        * { -webkit-font-smoothing: antialiased; }
        .gradient-text {
            background: linear-gradient(135deg, #fff 0%, #a0a0a0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .glass {
            background: rgba(255,255,255,0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.06);
        }
        .glass-card {
            background: rgba(255,255,255,0.02);
            border: 1px solid rgba(255,255,255,0.06);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .glass-card:hover {
            background: rgba(255,255,255,0.05);
            border-color: rgba(255,255,255,0.12);
            transform: translateY(-4px);
        }
        .glow { box-shadow: 0 0 60px rgba(255,255,255,0.03); }
        .line-glow {
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            height: 1px;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .float { animation: float 6s ease-in-out infinite; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-in { animation: fadeInUp 0.6s ease-out forwards; }
        .nav-link {
            position: relative;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 1px;
            background: white;
            transition: width 0.3s ease;
        }
        .nav-link:hover::after {
            width: 100%;
        }
        .btn-primary {
            background: white;
            color: #0d0d0d;
            padding: 12px 32px;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.875rem;
            letter-spacing: 0.025em;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: #e3e3e3;
            transform: scale(1.02);
        }
        .btn-outline {
            border: 1px solid rgba(255,255,255,0.2);
            color: white;
            padding: 12px 32px;
            border-radius: 9999px;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }
        .btn-outline:hover {
            border-color: rgba(255,255,255,0.5);
            background: rgba(255,255,255,0.05);
        }
        .marquee {
            animation: marquee 30s linear infinite;
        }
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .noise {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.03'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 1;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-brand-950 text-white font-sans relative overflow-x-hidden">
    <div class="noise"></div>

    {{-- Navigation --}}
    <nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-6 py-5 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3 relative z-10">
                <img src="{{ asset('assets/logo/Web-New-Biz-logo.png') }}" alt="WebNewBiz" class="h-8 brightness-0 invert">
            </a>

            <div class="hidden lg:flex items-center gap-8">
                @foreach([
                    'home' => 'Home',
                    'about' => 'About',
                    'services' => 'Services',
                    'solutions' => 'Solutions',
                    'pricing' => 'Pricing',
                    'faqs' => 'FAQs',
                    'contact' => 'Contact',
                ] as $routeName => $label)
                    <a href="{{ route($routeName) }}"
                       class="nav-link text-sm text-brand-300 hover:text-white transition-colors {{ request()->routeIs($routeName) ? 'text-white' : '' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <div class="hidden lg:flex items-center gap-4">
                <a href="#" class="btn-primary">Get Started</a>
            </div>

            <button class="lg:hidden relative z-10 text-white" id="mobile-toggle">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path id="menu-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        {{-- Mobile Menu --}}
        <div class="lg:hidden hidden fixed inset-0 bg-brand-950/98 backdrop-blur-xl z-40 pt-24 px-8" id="mobile-menu">
            <div class="flex flex-col gap-6">
                @foreach([
                    'home' => 'Home',
                    'about' => 'About',
                    'services' => 'Services',
                    'solutions' => 'Solutions',
                    'pricing' => 'Pricing',
                    'faqs' => 'FAQs',
                    'contact' => 'Contact',
                ] as $routeName => $label)
                    <a href="{{ route($routeName) }}" class="text-2xl font-display font-medium text-brand-200 hover:text-white transition-colors">{{ $label }}</a>
                @endforeach
                <a href="#" class="btn-primary text-center mt-4">Get Started</a>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="relative z-10">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="relative z-10 border-t border-white/5 bg-brand-950">
        <div class="max-w-7xl mx-auto px-6 py-20">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                <div class="lg:col-span-1">
                    <img src="{{ asset('assets/logo/Web-New-Biz-logo.png') }}" alt="WebNewBiz" class="h-8 brightness-0 invert mb-6">
                    <p class="text-brand-400 text-sm leading-relaxed">
                        AI-Powered Website Builder for Modern Businesses. Create stunning, professional websites in minutes with artificial intelligence.
                    </p>
                    <div class="flex gap-4 mt-6">
                        <a href="#" class="w-9 h-9 rounded-full border border-white/10 flex items-center justify-center hover:border-white/30 transition-colors">
                            <svg class="w-4 h-4 text-brand-400" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="w-9 h-9 rounded-full border border-white/10 flex items-center justify-center hover:border-white/30 transition-colors">
                            <svg class="w-4 h-4 text-brand-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a href="#" class="w-9 h-9 rounded-full border border-white/10 flex items-center justify-center hover:border-white/30 transition-colors">
                            <svg class="w-4 h-4 text-brand-400" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1v-3.5a6.37 6.37 0 00-.79-.05A6.34 6.34 0 003.15 15.2a6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.34-6.34V8.92a8.28 8.28 0 004.76 1.5V7a4.84 4.84 0 01-1-.31z"/></svg>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="text-xs font-semibold uppercase tracking-widest text-brand-500 mb-5">Resources</h4>
                    <ul class="space-y-3">
                        @foreach(['Help Center', 'Documentation', 'Video Tutorials', 'Community', 'API Reference'] as $link)
                            <li><a href="#" class="text-sm text-brand-400 hover:text-white transition-colors">{{ $link }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <div>
                    <h4 class="text-xs font-semibold uppercase tracking-widest text-brand-500 mb-5">Company</h4>
                    <ul class="space-y-3">
                        @foreach([
                            'home' => 'Home',
                            'about' => 'About',
                            'services' => 'Services',
                            'solutions' => 'Solutions',
                            'pricing' => 'Pricing',
                            'faqs' => 'FAQs',
                            'contact' => 'Contact',
                        ] as $routeName => $label)
                            <li><a href="{{ route($routeName) }}" class="text-sm text-brand-400 hover:text-white transition-colors">{{ $label }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <div>
                    <h4 class="text-xs font-semibold uppercase tracking-widest text-brand-500 mb-5">Contact</h4>
                    <ul class="space-y-3">
                        <li><a href="mailto:info@webnewbiz.com" class="text-sm text-brand-400 hover:text-white transition-colors">info@webnewbiz.com</a></li>
                    </ul>
                </div>
            </div>

            <div class="line-glow mb-8"></div>

            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-xs text-brand-500">&copy; {{ date('Y') }} WebNewBiz. All Rights Reserved.</p>
                <div class="flex gap-6">
                    <a href="#" class="text-xs text-brand-500 hover:text-brand-300 transition-colors">Privacy Policy</a>
                    <a href="#" class="text-xs text-brand-500 hover:text-brand-300 transition-colors">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('bg-brand-950/80', 'backdrop-blur-xl', 'border-b', 'border-white/5');
                navbar.querySelector('div').classList.remove('py-5');
                navbar.querySelector('div').classList.add('py-3');
            } else {
                navbar.classList.remove('bg-brand-950/80', 'backdrop-blur-xl', 'border-b', 'border-white/5');
                navbar.querySelector('div').classList.add('py-5');
                navbar.querySelector('div').classList.remove('py-3');
            }
        });

        // Mobile menu toggle
        const toggle = document.getElementById('mobile-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        toggle.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden');
        });

        // Intersection Observer for animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('[data-animate]').forEach(el => {
            el.style.opacity = '0';
            observer.observe(el);
        });
    </script>
    @stack('scripts')
</body>
</html>
