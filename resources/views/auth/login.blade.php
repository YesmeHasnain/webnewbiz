@extends('layouts.guest')
@section('title', 'Login')

@section('content')
    <h2 class="text-xl font-bold text-white mb-1">Sign in to Webnewbiz</h2>
    <p class="text-[#8b949e] text-sm mb-6">Welcome back. Enter your credentials to continue.</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        @if(request('redirect'))
            <input type="hidden" name="redirect" value="{{ request('redirect') }}">
        @endif
        <div class="mb-4">
            <label class="block text-sm font-medium text-[#e6edf3] mb-1.5">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                class="w-full px-3.5 py-2.5 bg-[#0d1117] border border-[#30363d] rounded-lg text-[#e6edf3] placeholder-[#484f58] focus:outline-none focus:ring-2 focus:ring-[#58a6ff]/40 focus:border-[#58a6ff] transition-all text-sm"
                placeholder="you@example.com">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-[#e6edf3] mb-1.5">Password</label>
            <input type="password" name="password" required
                class="w-full px-3.5 py-2.5 bg-[#0d1117] border border-[#30363d] rounded-lg text-[#e6edf3] placeholder-[#484f58] focus:outline-none focus:ring-2 focus:ring-[#58a6ff]/40 focus:border-[#58a6ff] transition-all text-sm"
                placeholder="Enter your password">
        </div>

        <div class="flex items-center justify-between mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="remember" class="rounded border-[#30363d] bg-[#0d1117] text-[#58a6ff] focus:ring-[#58a6ff]/40">
                <span class="ml-2 text-sm text-[#8b949e]">Remember me</span>
            </label>
            <a href="{{ route('password.request') }}" class="text-sm text-[#58a6ff] hover:underline">Forgot password?</a>
        </div>

        <button type="submit" class="w-full bg-white hover:bg-gray-100 text-gray-900 font-semibold py-2.5 px-4 rounded-lg transition-colors text-sm">
            Sign in
        </button>
    </form>

    <p class="text-center text-sm text-[#8b949e] mt-6">
        Don't have an account?
        <a href="{{ route('register', request('prompt') ? ['prompt' => request('prompt')] : []) }}" class="text-[#58a6ff] hover:underline font-medium">Create one free</a>
    </p>
@endsection
