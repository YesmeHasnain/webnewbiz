@extends('layouts.app')
@section('title', 'Settings')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Settings</h1>
        <p class="text-slate-500 mt-1">Manage your account and preferences</p>
    </div>

    <div class="max-w-2xl space-y-6">
        {{-- Profile --}}
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Profile Information</h2>
            <form method="POST" action="{{ route('settings.profile') }}">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                        <input type="email" value="{{ $user->email }}" disabled
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-lg bg-slate-50 text-slate-500 cursor-not-allowed">
                        <p class="text-xs text-slate-400 mt-1">Email cannot be changed.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            placeholder="Optional">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Company</label>
                        <input type="text" name="company" value="{{ old('company', $user->company) }}"
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            placeholder="Optional">
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" class="bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-semibold px-6 py-2.5 rounded-lg transition-all shadow-sm shadow-indigo-500/20">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        {{-- Password --}}
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Change Password</h2>
            <form method="POST" action="{{ route('settings.password') }}">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Current Password</label>
                        <input type="password" name="current_password" required
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">New Password</label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            placeholder="Min 8 characters">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Confirm New Password</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" class="bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-semibold px-6 py-2.5 rounded-lg transition-all shadow-sm shadow-indigo-500/20">
                        Update Password
                    </button>
                </div>
            </form>
        </div>

        {{-- Plan Info --}}
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Current Plan</h2>
            @if($plan)
                <div class="flex items-center justify-between p-4 bg-indigo-50 border border-indigo-200 rounded-lg">
                    <div>
                        <p class="font-semibold text-slate-900">{{ $plan->name }}</p>
                        <p class="text-sm text-slate-600 mt-0.5">{{ $plan->max_websites }} websites, {{ $plan->storage_gb }}GB storage</p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-slate-900">${{ intval($plan->price) }}</p>
                        <p class="text-xs text-slate-500">per {{ $plan->billing_cycle }}</p>
                    </div>
                </div>
            @else
                <p class="text-sm text-slate-500">No active plan. <a href="{{ url('/pricing') }}" class="text-indigo-600 hover:text-indigo-700">View plans</a></p>
            @endif
        </div>

        {{-- Account Info --}}
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Account</h2>
            <dl class="space-y-3">
                <div class="flex justify-between">
                    <dt class="text-sm text-slate-500">Member since</dt>
                    <dd class="text-sm font-medium text-slate-900">{{ $user->created_at->format('M d, Y') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-slate-500">Last login</dt>
                    <dd class="text-sm font-medium text-slate-900">{{ $user->last_login_at?->format('M d, Y g:i A') ?? 'N/A' }}</dd>
                </div>
            </dl>
        </div>
    </div>
@endsection
