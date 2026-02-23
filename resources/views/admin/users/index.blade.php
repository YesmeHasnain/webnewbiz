@extends('admin.layouts.app')

@section('title', 'Users')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Users</h1>
    <p class="text-sm text-gray-500 mt-1">Manage platform users</p>
</div>

<div class="bg-white dark:bg-dark-100 rounded-xl border border-gray-200 dark:border-gray-800 p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-4">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..." class="flex-1 min-w-[200px] px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-dark-300 dark:text-white text-sm focus:ring-2 focus:ring-blue-500">
        <select name="role" class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-dark-300 dark:text-white text-sm">
            <option value="">All Roles</option>
            <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="superadmin" {{ request('role') === 'superadmin' ? 'selected' : '' }}>Super Admin</option>
        </select>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">Filter</button>
    </form>
</div>

<div class="bg-white dark:bg-dark-100 rounded-xl border border-gray-200 dark:border-gray-800 overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 dark:bg-dark-300">
            <tr>
                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">User</th>
                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Role</th>
                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Websites</th>
                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Joined</th>
                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
            @forelse($users as $user)
                <tr class="hover:bg-gray-50 dark:hover:bg-dark-300">
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.users.show', $user) }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">{{ $user->name }}</a>
                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                    </td>
                    <td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full bg-{{ $user->role === 'superadmin' ? 'purple' : ($user->role === 'admin' ? 'blue' : 'gray') }}-100 text-{{ $user->role === 'superadmin' ? 'purple' : ($user->role === 'admin' ? 'blue' : 'gray') }}-700">{{ ucfirst($user->role) }}</span></td>
                    <td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full bg-{{ $user->status === 'active' ? 'green' : 'red' }}-100 text-{{ $user->status === 'active' ? 'green' : 'red' }}-700">{{ ucfirst($user->status) }}</span></td>
                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $user->websites_count }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $user->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.users.show', $user) }}" class="text-blue-600 hover:text-blue-700 text-sm">View</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">No users found.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800">{{ $users->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
