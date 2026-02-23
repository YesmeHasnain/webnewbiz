@extends('layouts.guest')
@section('title', 'Register')

@section('content')
    <h2 class="text-2xl font-bold text-gray-900 mb-2">Create your account</h2>
    <p class="text-gray-500 mb-6">Start building websites with AI in seconds</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required autofocus
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="John Doe">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="you@example.com">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
            <input type="password" name="password" required
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Min 8 characters">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirm Password</label>
            <input type="password" name="password_confirmation" required
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Repeat your password">
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-lg transition">
            Create Account
        </button>
    </form>

    <p class="text-center text-sm text-gray-500 mt-6">
        Already have an account?
        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-medium">Sign in</a>
    </p>
@endsection
