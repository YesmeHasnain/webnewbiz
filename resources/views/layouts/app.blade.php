<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Webnewbiz</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        *, *::before, *::after { -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
        [x-cloak] { display: none !important; }

        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }
        .page-enter { animation: fadeInUp 0.5s ease-out; }
        .card-hover { transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(99, 102, 241, 0.1), 0 8px 16px rgba(0,0,0,0.06); }

        .glass { background: rgba(255,255,255,0.8); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); }
        .glass-dark { background: rgba(15,23,42,0.6); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); }

        .gradient-border { position: relative; }
        .gradient-border::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            padding: 1px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6, #ec4899);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }

        /* Sidebar */
        .sidebar-nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 0.875rem;
            border-radius: 0.625rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: rgba(148, 163, 184, 1);
            transition: all 0.2s ease;
        }
        .sidebar-nav-item:hover {
            color: #fff;
            background: rgba(255,255,255,0.06);
        }
        .sidebar-nav-item.active {
            color: #fff;
            background: linear-gradient(135deg, rgba(99,102,241,0.25), rgba(139,92,246,0.18));
            box-shadow: inset 0 0 0 1px rgba(99,102,241,0.25);
        }
        .sidebar-nav-item .nav-icon {
            width: 1.25rem;
            height: 1.25rem;
            flex-shrink: 0;
        }

        /* Scrollbar for main content */
        .main-scrollbar::-webkit-scrollbar { width: 6px; }
        .main-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .main-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        .main-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen bg-slate-50" x-data="{ sidebarOpen: false, sidebarCollapsed: false }">
    <div class="flex min-h-screen">

        {{-- Mobile Overlay --}}
        <div x-show="sidebarOpen" x-cloak
             x-transition:enter="transition-opacity ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 lg:hidden"></div>

        {{-- Sidebar --}}
        <aside class="fixed inset-y-0 left-0 z-50 flex flex-col transition-all duration-300 ease-in-out lg:translate-x-0"
               :class="{
                   'w-[260px]': !sidebarCollapsed,
                   'w-[72px]': sidebarCollapsed,
                   '-translate-x-full': !sidebarOpen,
                   'translate-x-0': sidebarOpen
               }"
               style="background: linear-gradient(180deg, #0f172a 0%, #1e1b4b 100%);">

            {{-- Logo --}}
            <div class="flex items-center h-16 px-5 flex-shrink-0 border-b border-white/[0.06]">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group overflow-hidden">
                    <div class="w-9 h-9 bg-gradient-to-br from-indigo-500 to-violet-500 rounded-xl flex items-center justify-center flex-shrink-0 transition-transform duration-300 group-hover:scale-110 shadow-lg shadow-indigo-500/20">
                        <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                        </svg>
                    </div>
                    <span class="text-lg font-bold tracking-tight text-white whitespace-nowrap transition-opacity duration-200"
                          :class="sidebarCollapsed ? 'opacity-0 w-0' : 'opacity-100'">Webnewbiz</span>
                </a>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-3 py-5 space-y-1 overflow-y-auto">
                {{-- Main Section --}}
                <p class="px-3 mb-2 text-[10px] font-bold uppercase tracking-widest text-slate-500"
                   :class="sidebarCollapsed ? 'text-center px-0' : ''"
                   x-text="sidebarCollapsed ? '...' : 'MAIN'"></p>

                <a href="{{ route('dashboard') }}"
                   class="sidebar-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zm0 6a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1h-4a1 1 0 01-1-1v-5zM4 13a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1v-2z"/></svg>
                    <span :class="sidebarCollapsed ? 'hidden' : ''">Dashboard</span>
                </a>

                <a href="{{ route('builder.index') }}"
                   class="sidebar-nav-item {{ request()->routeIs('builder.*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span :class="sidebarCollapsed ? 'hidden' : ''">AI Builder</span>
                </a>

                {{-- Manage Section --}}
                <p class="px-3 mt-6 mb-2 text-[10px] font-bold uppercase tracking-widest text-slate-500"
                   :class="sidebarCollapsed ? 'text-center px-0' : ''"
                   x-text="sidebarCollapsed ? '...' : 'MANAGE'"></p>

                <a href="{{ route('websites.index') }}"
                   class="sidebar-nav-item {{ request()->routeIs('websites.*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                    <span :class="sidebarCollapsed ? 'hidden' : ''">My Websites</span>
                </a>

                <a href="{{ route('settings') }}"
                   class="sidebar-nav-item {{ request()->routeIs('settings*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span :class="sidebarCollapsed ? 'hidden' : ''">Settings</span>
                </a>
            </nav>

            {{-- Collapse toggle (desktop only) --}}
            <button @click="sidebarCollapsed = !sidebarCollapsed"
                    class="hidden lg:flex items-center justify-center mx-3 mb-2 h-8 rounded-lg text-slate-500 hover:text-slate-300 hover:bg-white/[0.06] transition-all">
                <svg class="w-4 h-4 transition-transform duration-300" :class="sidebarCollapsed ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                </svg>
            </button>

            {{-- User Info --}}
            <div class="flex-shrink-0 border-t border-white/[0.06] p-3">
                <div class="flex items-center gap-3 px-2 py-2 rounded-xl hover:bg-white/[0.04] transition-colors"
                     :class="sidebarCollapsed ? 'justify-center px-0' : ''">
                    <div class="w-9 h-9 bg-gradient-to-br from-indigo-400 to-violet-400 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0 shadow-md shadow-indigo-500/20">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0" :class="sidebarCollapsed ? 'hidden' : ''">
                        <p class="text-sm font-medium text-slate-200 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" :class="sidebarCollapsed ? 'hidden' : ''">
                        @csrf
                        <button type="submit" class="p-1.5 rounded-lg text-slate-500 hover:text-red-400 hover:bg-white/[0.06] transition-all" title="Logout">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Main Content Area --}}
        <div class="flex-1 flex flex-col min-h-screen transition-all duration-300"
             :class="sidebarCollapsed ? 'lg:ml-[72px]' : 'lg:ml-[260px]'">

            {{-- Top Bar --}}
            <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-xl border-b border-slate-200/60 h-16 flex items-center px-6 lg:px-8">
                {{-- Mobile hamburger --}}
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 -ml-2 mr-3 rounded-lg hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>

                {{-- Page title --}}
                <h1 class="text-lg font-semibold text-slate-800">@yield('title', 'Dashboard')</h1>

                <div class="flex-1"></div>

                {{-- Right side actions --}}
                <div class="flex items-center gap-3">
                    <a href="{{ route('builder.index') }}" class="hidden sm:inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white text-sm font-medium px-4 py-2 rounded-lg transition-all shadow-sm shadow-indigo-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        New Website
                    </a>
                </div>
            </header>

            {{-- Flash Messages --}}
            @include('partials.flash-messages')

            {{-- Page Content --}}
            <main class="flex-1 px-6 lg:px-8 py-8 page-enter">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
