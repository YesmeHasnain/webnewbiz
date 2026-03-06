@extends('layouts.guest')
@section('title', 'Register')

@section('content')
    <h2 class="text-xl font-bold text-white mb-1">Create your account</h2>
    <p class="text-[#8b949e] text-sm mb-6">Start building websites with AI in seconds.</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf
        @if(request('prompt'))
            <input type="hidden" name="prompt" value="{{ request('prompt') }}">
        @endif
        <div class="mb-4">
            <label class="block text-sm font-medium text-[#e6edf3] mb-1.5">Full Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required autofocus
                class="w-full px-3.5 py-2.5 bg-[#0d1117] border border-[#30363d] rounded-lg text-[#e6edf3] placeholder-[#484f58] focus:outline-none focus:ring-2 focus:ring-[#58a6ff]/40 focus:border-[#58a6ff] transition-all text-sm"
                placeholder="John Doe">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-[#e6edf3] mb-1.5">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                class="w-full px-3.5 py-2.5 bg-[#0d1117] border border-[#30363d] rounded-lg text-[#e6edf3] placeholder-[#484f58] focus:outline-none focus:ring-2 focus:ring-[#58a6ff]/40 focus:border-[#58a6ff] transition-all text-sm"
                placeholder="you@example.com">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-[#e6edf3] mb-1.5">Password</label>
            <input type="password" name="password" required
                class="w-full px-3.5 py-2.5 bg-[#0d1117] border border-[#30363d] rounded-lg text-[#e6edf3] placeholder-[#484f58] focus:outline-none focus:ring-2 focus:ring-[#58a6ff]/40 focus:border-[#58a6ff] transition-all text-sm"
                placeholder="Min 8 characters">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-[#e6edf3] mb-1.5">Confirm Password</label>
            <input type="password" name="password_confirmation" required
                class="w-full px-3.5 py-2.5 bg-[#0d1117] border border-[#30363d] rounded-lg text-[#e6edf3] placeholder-[#484f58] focus:outline-none focus:ring-2 focus:ring-[#58a6ff]/40 focus:border-[#58a6ff] transition-all text-sm"
                placeholder="Repeat your password">
        </div>

        <button type="submit" class="w-full bg-white hover:bg-gray-100 text-gray-900 font-semibold py-2.5 px-4 rounded-lg transition-colors text-sm">
            Create account
        </button>
    </form>

    <p class="text-center text-sm text-[#8b949e] mt-6">
        Already have an account?
        <a href="{{ route('login', request('prompt') ? ['prompt' => request('prompt')] : []) }}" class="text-[#58a6ff] hover:underline font-medium">Sign in</a>
    </p>
@endsection
