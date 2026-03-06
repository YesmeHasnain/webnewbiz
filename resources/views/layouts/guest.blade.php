<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Welcome') - Webnewbiz</title>
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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        *, *::before, *::after { -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
        [x-cloak] { display: none !important; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
        .animate-up { animation: fadeInUp 0.5s ease-out forwards; }
        .grid-bg { background-image: linear-gradient(rgba(48,54,61,0.3) 1px, transparent 1px), linear-gradient(90deg, rgba(48,54,61,0.3) 1px, transparent 1px); background-size: 64px 64px; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden bg-[#0d1117]">
    {{-- Subtle grid --}}
    <div class="grid-bg absolute inset-0 opacity-40"></div>

    {{-- Glow --}}
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[400px] bg-gradient-to-r from-indigo-500/10 via-violet-500/10 to-transparent rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10">
        <div class="text-center mb-8 animate-up">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-3 group">
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center transition-transform duration-200 group-hover:scale-105">
                    <svg class="w-5 h-5 text-gray-900" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                    </svg>
                </div>
                <span class="text-xl font-bold tracking-tight text-white">Webnewbiz</span>
            </a>
        </div>

        <div class="bg-[#161b22] rounded-xl border border-[#30363d] p-8 shadow-2xl shadow-black/40 animate-up" style="animation-delay: 0.1s;">
            @if($errors->any())
                <div class="mb-6 p-3 bg-red-500/10 border border-red-500/20 rounded-lg text-red-300 text-sm">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if(session('success'))
                <div class="mb-6 p-3 bg-green-500/10 border border-green-500/20 rounded-lg text-green-300 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </div>

        <p class="text-center text-xs text-[#484f58] mt-8">&copy; {{ date('Y') }} Webnewbiz. All rights reserved.</p>
    </div>
</body>
</html>
