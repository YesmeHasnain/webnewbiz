<nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
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
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">Dashboard</a>
                    <a href="{{ route('builder.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('builder.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">AI Builder</a>
                    <a href="{{ route('websites.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('websites.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">My Websites</a>
                    <a href="{{ route('settings') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('settings*') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">Settings</a>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="hidden md:flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-sm font-bold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 hover:text-red-600 transition">Logout</button>
                    </form>
                </div>
                <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2 rounded-lg hover:bg-gray-100">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>
    </div>
    <!-- Mobile menu -->
    <div x-show="mobileMenu" x-cloak @click.away="mobileMenu = false" class="md:hidden border-t border-gray-200 bg-white">
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-blue-600 bg-blue-50' : 'text-gray-600' }}">Dashboard</a>
            <a href="{{ route('builder.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('builder.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-600' }}">AI Builder</a>
            <a href="{{ route('websites.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('websites.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-600' }}">My Websites</a>
            <a href="{{ route('settings') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('settings*') ? 'text-blue-600 bg-blue-50' : 'text-gray-600' }}">Settings</a>
            <div class="border-t border-gray-100 pt-2 mt-2">
                <p class="px-3 py-1 text-sm text-gray-500">{{ auth()->user()->email }}</p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg">Logout</button>
                </form>
            </div>
        </div>
    </div>
</nav>
