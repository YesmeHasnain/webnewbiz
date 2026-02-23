<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Features - Webnewbiz</title>
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
                        <a href="{{ url('/features') }}" class="text-sm text-blue-600 font-medium">Features</a>
                        <a href="{{ url('/pricing') }}" class="text-sm text-gray-600 hover:text-gray-900">Pricing</a>
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

    {{-- Hero --}}
    <section class="py-20 bg-gradient-to-br from-blue-50 via-white to-indigo-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Powerful Features for Your Business</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Everything you need to build, manage, and grow your online presence — all powered by AI.</p>
        </div>
    </section>

    {{-- Features Grid --}}
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @php
                $features = [
                    ['icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'title' => 'AI-Powered Content', 'desc' => 'Our advanced AI analyzes your business type and generates professional website content including headlines, descriptions, and page layouts tailored to your industry.'],
                    ['icon' => 'M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2', 'title' => 'Managed WordPress Hosting', 'desc' => 'High-performance cloud servers with automatic WordPress updates, server monitoring, and optimized configurations for maximum speed and reliability.'],
                    ['icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z', 'title' => 'Free SSL Certificates', 'desc' => 'Every website gets automatic SSL/TLS encryption. Your visitors see the reassuring padlock icon and your site ranks better in search engines.'],
                    ['icon' => 'M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1', 'title' => 'Custom Domain Support', 'desc' => 'Connect your own domain name with automatic DNS configuration, or use our free subdomain to get started instantly.'],
                    ['icon' => 'M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4', 'title' => 'Automatic Backups', 'desc' => 'Your website is backed up automatically on a regular schedule. Restore to any previous version with a single click whenever you need.'],
                    ['icon' => 'M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z', 'title' => 'Plugin & Theme Management', 'desc' => 'Install, activate, and manage WordPress plugins and themes directly from your dashboard without touching any code.'],
                    ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'title' => 'Performance Dashboard', 'desc' => 'Monitor your website storage, bandwidth, and performance metrics in real-time from a clean, intuitive dashboard.'],
                    ['icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => '60-Second Setup', 'desc' => 'From description to live website in under a minute. Our AI handles everything — server provisioning, WordPress installation, content generation, and DNS setup.'],
                ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach($features as $feature)
                    <div class="flex gap-5 p-6 rounded-xl border border-gray-200 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feature['icon'] }}"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $feature['title'] }}</h3>
                            <p class="text-gray-600">{{ $feature['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-20 bg-gradient-to-r from-blue-600 to-indigo-700">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">Ready to Experience These Features?</h2>
            <p class="text-xl text-blue-100 mb-8">Start building your website for free today.</p>
            <a href="{{ route('register') }}" class="inline-block bg-white hover:bg-gray-100 text-blue-600 font-semibold px-8 py-3.5 rounded-xl text-lg transition shadow-lg">
                Get Started Free
            </a>
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
