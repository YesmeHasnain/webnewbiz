@extends('admin.layouts.app')

@section('title', 'Add Server')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.servers.index') }}" class="text-sm text-blue-600 hover:text-blue-700">&larr; Back to Servers</a>
</div>

<div class="max-w-2xl">
    <div class="bg-white dark:bg-dark-100 rounded-xl border border-gray-200 dark:border-gray-800 p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-6">Create New Server</h3>
        <form method="POST" action="{{ route('admin.servers.store') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Server Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-dark-300 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="web-server-01">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Region</label>
                    <select name="region" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-dark-300 dark:text-white focus:ring-2 focus:ring-blue-500">
                        <option value="nyc3">New York 3 (NYC3)</option>
                        <option value="sfo3">San Francisco 3 (SFO3)</option>
                        <option value="ams3">Amsterdam 3 (AMS3)</option>
                        <option value="sgp1">Singapore 1 (SGP1)</option>
                        <option value="lon1">London 1 (LON1)</option>
                        <option value="fra1">Frankfurt 1 (FRA1)</option>
                        <option value="blr1">Bangalore 1 (BLR1)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Size</label>
                    <select name="size" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-dark-300 dark:text-white focus:ring-2 focus:ring-blue-500">
                        <option value="s-2vcpu-4gb">2 vCPU / 4 GB RAM ($24/mo)</option>
                        <option value="s-4vcpu-8gb">4 vCPU / 8 GB RAM ($48/mo)</option>
                        <option value="s-8vcpu-16gb">8 vCPU / 16 GB RAM ($96/mo)</option>
                    </select>
                </div>
            </div>
            <div class="mt-6 flex gap-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition">Create Server</button>
                <a href="{{ route('admin.servers.index') }}" class="bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 text-gray-700 dark:text-gray-300 px-6 py-2 rounded-lg text-sm transition">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
