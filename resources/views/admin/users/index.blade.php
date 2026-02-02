@extends('layouts.admin')

@section('title', 'Kelola Users')

@section('header', 'Kelola Users')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">User Management</h2>
            <p class="text-gray-600 mt-1">Kelola semua user aplikasi</p>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm">Total Users</p>
                <p class="text-3xl font-bold mt-2">{{ number_format($stats['total']) }}</p>
            </div>
            <i class="fas fa-users text-4xl opacity-50"></i>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm">Active</p>
                <p class="text-3xl font-bold mt-2">{{ number_format($stats['active']) }}</p>
            </div>
            <i class="fas fa-check-circle text-4xl opacity-50"></i>
        </div>
    </div>

    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-red-100 text-sm">Banned</p>
                <p class="text-3xl font-bold mt-2">{{ number_format($stats['banned']) }}</p>
            </div>
            <i class="fas fa-ban text-4xl opacity-50"></i>
        </div>
    </div>

    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg shadow p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-yellow-100 text-sm">Premium</p>
                <p class="text-3xl font-bold mt-2">{{ number_format($stats['premium']) }}</p>
            </div>
            <i class="fas fa-crown text-4xl opacity-50"></i>
        </div>
    </div>
</div>

<!-- Search & Filter -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Search -->
        <div class="md:col-span-2">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Cari nama atau email..."
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Status Filter -->
        <div>
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="banned" {{ request('status') === 'banned' ? 'selected' : '' }}>Banned</option>
            </select>
        </div>

        <!-- Buttons -->
        <div class="flex gap-2">
            <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                <i class="fas fa-search mr-2"></i>Cari
            </button>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-semibold transition">
                <i class="fas fa-redo"></i>
            </a>
        </div>
    </form>
</div>

<!-- Users Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">User</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Email</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Joined</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center font-bold text-blue-600">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="ml-3">
                                <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                                @if($user->is_premium)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-crown mr-1"></i> Premium
                                    </span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                    <td class="px-6 py-4">
                        @if($user->is_banned)
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                <i class="fas fa-ban mr-1"></i> Banned
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                <i class="fas fa-check-circle mr-1"></i> Active
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $user->created_at->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <!-- View Detail -->
                            <a href="{{ route('admin.users.show', $user->id) }}" 
                               class="text-blue-600 hover:text-blue-800" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>

                            <!-- Ban/Unban -->
                            @if($user->is_banned)
                                <form action="{{ route('admin.users.unban', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-800" title="Unban User">
                                        <i class="fas fa-unlock"></i>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.users.ban', $user->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Ban user ini?')">
                                    @csrf
                                    <button type="submit" class="text-yellow-600 hover:text-yellow-800" title="Ban User">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                </form>
                            @endif

                            <!-- Delete -->
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Hapus user ini? Data tidak bisa dikembalikan!')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800" title="Delete User">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-users-slash text-5xl mb-3"></i>
                        <p>Tidak ada user ditemukan</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="px-6 py-4 border-t">
        {{ $users->links() }}
    </div>
</div>
@endsection





