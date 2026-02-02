@extends('layouts.admin')

@section('title', 'Dashboard')

@section('header', 'Dashboard')

@section('content')
<div class="mb-6">
    <h2 class="text-3xl font-bold text-gray-800">Dashboard Admin</h2>
    <p class="text-gray-600 mt-2">
        Selamat datang kembali, <span class="font-semibold text-blue-600">{{ auth()->guard('admin')->user()->name }}</span>! 👋
    </p>
    <div class="flex items-center gap-4 mt-2 text-sm text-gray-500">
        <span><i class="fas fa-calendar mr-2"></i>{{ now()->isoFormat('dddd, D MMMM YYYY') }}</span>
        <span><i class="fas fa-envelope mr-2"></i>{{ auth()->guard('admin')->user()->email }}</span>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <!-- Total Users -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm">Total Users</p>
                <p class="text-3xl font-bold mt-2">{{ number_format($stats['total_users']) }}</p>
                <p class="text-blue-100 text-xs mt-2">
                    <i class="fas fa-user-plus mr-1"></i>
                    +{{ $stats['new_users_today'] }} hari ini
                </p>
            </div>
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                <i class="fas fa-users text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Catches -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm">Total Catches</p>
                <p class="text-3xl font-bold mt-2">{{ number_format($stats['total_catches']) }}</p>
                <p class="text-green-100 text-xs mt-2">
                    <i class="fas fa-fish mr-1"></i>
                    Semua tangkapan
                </p>
            </div>
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                <i class="fas fa-fish text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Spots -->
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm">Total Spots</p>
                <p class="text-3xl font-bold mt-2">{{ number_format($stats['total_spots']) }}</p>
                <p class="text-purple-100 text-xs mt-2">
                    <i class="fas fa-map-marker-alt mr-1"></i>
                    Lokasi mancing
                </p>
            </div>
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                <i class="fas fa-map-marker-alt text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- New Users Today -->
    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm">User Baru</p>
                <p class="text-3xl font-bold mt-2">{{ number_format($stats['new_users_today']) }}</p>
                <p class="text-orange-100 text-xs mt-2">
                    <i class="fas fa-clock mr-1"></i>
                    Hari ini
                </p>
            </div>
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                <i class="fas fa-user-plus text-3xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Chart & Tables Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    
    <!-- User Growth Chart -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-chart-line text-blue-600 mr-2"></i>
            Pertumbuhan User (7 Hari)
        </h3>
        <canvas id="userGrowthChart" height="250"></canvas>
    </div>

    <!-- Recent Users -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-users text-green-600 mr-2"></i>
            User Terbaru
        </h3>
        <div class="space-y-3">
            @forelse($recentUsers as $user)
                <div class="flex items-center justify-between py-3 border-b border-gray-100 hover:bg-gray-50 px-2 rounded transition">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="ml-3">
                            <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>
                    <span class="text-xs text-gray-400">
                        <i class="fas fa-clock mr-1"></i>
                        {{ $user->created_at->diffForHumans() }}
                    </span>
                </div>
            @empty
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-user-slash text-4xl mb-3"></i>
                    <p>Belum ada user</p>
                </div>
            @endforelse
        </div>
        <a href="#" class="block text-center text-blue-600 hover:text-blue-700 font-semibold mt-4 transition">
            Lihat Semua User →
        </a>
    </div>
</div>

<!-- Recent Catches -->
<div class="bg-white rounded-xl shadow-lg p-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">
        <i class="fas fa-fish text-green-600 mr-2"></i>
        Tangkapan Terbaru
    </h3>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">User</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jenis Ikan</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Berat</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Lokasi</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Waktu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($recentCatches as $catch)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-sm">
                                    {{ strtoupper(substr($catch->user->name ?? 'U', 0, 1)) }}
                                </div>
                                <span class="ml-3 font-medium text-gray-800">{{ $catch->user->name ?? 'Unknown' }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">
                                {{ $catch->fish_type }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap font-semibold text-gray-700">{{ $catch->weight }} kg</td>
                        <td class="px-4 py-4 whitespace-nowrap text-gray-600">
                            <i class="fas fa-map-marker-alt text-red-500 mr-1"></i>
                            {{ $catch->location }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $catch->created_at->diffForHumans() }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center">
                            <div class="text-gray-400">
                                <i class="fas fa-fish text-5xl mb-3"></i>
                                <p>Belum ada tangkapan</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('userGrowthChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($userGrowth->pluck('date')->map(fn($date) => \Carbon\Carbon::parse($date)->format('d M'))),
            datasets: [{
                label: 'User Baru',
                data: @json($userGrowth->pluck('total')),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 5,
                pointBackgroundColor: 'rgb(59, 130, 246)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 13
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>
@endsection

