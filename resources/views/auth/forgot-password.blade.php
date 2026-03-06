@extends('layouts.guest')
@section('title', 'Forgot Password')

@section('content')
    <h2 class="text-xl font-bold text-white mb-1">Forgot password?</h2>
    <p class="text-[#8b949e] text-sm mb-6">Enter your email and we'll send you a reset link.</p>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-5">
            <label class="block text-sm font-medium text-[#e6edf3] mb-1.5">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                class="w-full px-3.5 py-2.5 bg-[#0d1117] border border-[#30363d] rounded-lg text-[#e6edf3] placeholder-[#484f58] focus:outline-none focus:ring-2 focus:ring-[#58a6ff]/40 focus:border-[#58a6ff] transition-all text-sm"
                placeholder="you@example.com">
        </div>

        <button type="submit" class="w-full bg-white hover:bg-gray-100 text-gray-900 font-semibold py-2.5 px-4 rounded-lg transition-colors text-sm">
            Send reset link
        </button>
    </form>

    <p class="text-center text-sm text-[#8b949e] mt-6">
        Remember your password?
        <a href="{{ route('login') }}" class="text-[#58a6ff] hover:underline font-medium">Sign in</a>
    </p>
@endsection
