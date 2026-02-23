@extends('layouts.guest')
@section('title', 'Login')

@section('content')
    <h2 class="text-2xl font-bold text-gray-900 mb-2">Welcome back</h2>
    <p class="text-gray-500 mb-6">Sign in to your account to continue</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="you@example.com">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
            <input type="password" name="password" required
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Enter your password">
        </div>

        <div class="flex items-center justify-between mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <span class="ml-2 text-sm text-gray-600">Remember me</span>
            </label>
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-lg transition">
            Sign In
        </button>
    </form>

    <p class="text-center text-sm text-gray-500 mt-6">
        Don't have an account?
        <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 font-medium">Create one free</a>
    </p>
@endsection
