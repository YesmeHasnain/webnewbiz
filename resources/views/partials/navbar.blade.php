<nav class="glass border-b border-white/60 sticky top-0 z-50 shadow-sm shadow-indigo-500/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-8">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
                    <div class="w-9 h-9 bg-gradient-to-br from-indigo-600 to-violet-600 rounded-xl flex items-center justify-center transition-all duration-300 group-hover:scale-110 group-hover:shadow-lg group-hover:shadow-indigo-500/30">
                        <svg class="w-4.5 h-4.5 text-white" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                        </svg>
                    </div>
                    <span class="text-lg font-bold tracking-tight bg-gradient-to-r from-indigo-700 to-violet-700 bg-clip-text text-transparent">Webnewbiz</span>
                </a>
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('dashboard') }}" class="px-3.5 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('dashboard') ? 'text-indigo-700 bg-indigo-50' : 'text-slate-500 hover:text-indigo-700 hover:bg-indigo-50/50' }}">Dashboard</a>
                    <a href="{{ route('builder.index') }}" class="px-3.5 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('builder.*') ? 'text-indigo-700 bg-indigo-50' : 'text-slate-500 hover:text-indigo-700 hover:bg-indigo-50/50' }}">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            AI Builder
                        </span>
                    </a>
                    <a href="{{ route('websites.index') }}" class="px-3.5 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('websites.*') ? 'text-indigo-700 bg-indigo-50' : 'text-slate-500 hover:text-indigo-700 hover:bg-indigo-50/50' }}">My Websites</a>
                    <a href="{{ route('settings') }}" class="px-3.5 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('settings*') ? 'text-indigo-700 bg-indigo-50' : 'text-slate-500 hover:text-indigo-700 hover:bg-indigo-50/50' }}">Settings</a>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="hidden md:flex items-center gap-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-violet-500 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-md shadow-indigo-500/20">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <span class="text-sm font-medium text-slate-700">{{ auth()->user()->name }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-slate-400 hover:text-red-500 transition-colors font-medium">Logout</button>
                    </form>
                </div>
                <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2 rounded-lg hover:bg-indigo-50 transition-colors">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>
    </div>
    <!-- Mobile menu -->
    <div x-show="mobileMenu" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" @click.away="mobileMenu = false" class="md:hidden border-t border-indigo-100/50 glass">
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'text-indigo-700 bg-indigo-50' : 'text-slate-500' }}">Dashboard</a>
            <a href="{{ route('builder.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('builder.*') ? 'text-indigo-700 bg-indigo-50' : 'text-slate-500' }}">AI Builder</a>
            <a href="{{ route('websites.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('websites.*') ? 'text-indigo-700 bg-indigo-50' : 'text-slate-500' }}">My Websites</a>
            <a href="{{ route('settings') }}" class="block px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('settings*') ? 'text-indigo-700 bg-indigo-50' : 'text-slate-500' }}">Settings</a>
            <div class="border-t border-indigo-100/50 pt-2 mt-2">
                <p class="px-3 py-1 text-sm text-slate-400">{{ auth()->user()->email }}</p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-3 py-2 text-sm text-red-500 hover:bg-red-50 rounded-lg transition-colors">Logout</button>
                </form>
            </div>
        </div>
    </div>
</nav>
