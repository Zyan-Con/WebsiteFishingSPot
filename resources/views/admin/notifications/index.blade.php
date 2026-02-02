@extends('layouts.admin')

@section('title', 'Kelola Notifikasi')

@section('header', 'Kelola Notifikasi')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Notifikasi yang Dikirim</h2>
        <p class="text-gray-600 mt-1">Kelola notifikasi yang dikirim ke user</p>
    </div>
    <a href="{{ route('admin.notifications.create') }}" 
       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition flex items-center gap-2">
        <i class="fas fa-plus"></i>
        Buat Notifikasi Baru
    </a>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-bell text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Total</p>
                <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-broadcast-tower text-green-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Broadcast</p>
                <p class="text-2xl font-bold">{{ $stats['broadcast'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-user text-purple-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Personal</p>
                <p class="text-2xl font-bold">{{ $stats['personal'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-envelope text-orange-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Belum Dibaca</p>
                <p class="text-2xl font-bold">{{ $stats['unread'] }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Notifications Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Judul</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tipe</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Target</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Waktu</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($notifications as $notif)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $notif->title }}</p>
                            <p class="text-sm text-gray-500 mt-1">{{ Str::limit($notif->message, 60) }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $colors = [
                                'info' => 'bg-blue-100 text-blue-700',
                                'success' => 'bg-green-100 text-green-700',
                                'warning' => 'bg-yellow-100 text-yellow-700',
                                'danger' => 'bg-red-100 text-red-700',
                            ];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $colors[$notif->type] }}">
                            {{ ucfirst($notif->type) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($notif->user_id)
                            <span class="text-sm text-gray-600">
                                <i class="fas fa-user mr-1"></i>
                                {{ $notif->user->name }}
                            </span>
                        @else
                            <span class="text-sm font-semibold text-green-600">
                                <i class="fas fa-broadcast-tower mr-1"></i>
                                Semua User
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $notif->created_at->diffForHumans() }}
                    </td>
                    <td class="px-6 py-4">
                        <form action="{{ route('admin.notifications.destroy', $notif->id) }}" method="POST" 
                              onsubmit="return confirm('Hapus notifikasi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-bell-slash text-4xl mb-3"></i>
                        <p>Belum ada notifikasi yang dikirim</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="px-6 py-4 border-t">
        {{ $notifications->links() }}
    </div>
</div>
@endsection