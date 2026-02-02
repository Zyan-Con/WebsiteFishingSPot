@extends('layouts.admin')

@section('title', 'Detail User - ' . $user->name)

@section('header', 'Detail User')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke List Users
    </a>
</div>

<!-- Header Profile Card -->
<div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg shadow-lg p-8 mb-6 text-white">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-6">
            <!-- Avatar -->
            <div class="w-24 h-24 bg-white/20 backdrop-blur rounded-full flex items-center justify-center text-4xl font-bold border-4 border-white/30">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            
            <!-- User Info -->
            <div>
                <h1 class="text-3xl font-bold mb-2">{{ $user->name }}</h1>
                <p class="text-blue-100 mb-3">{{ $user->email }}</p>
                
                <div class="flex gap-2">
                    @if($user->is_banned)
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-500 text-white">
                            <i class="fas fa-ban mr-1"></i> Banned
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-500 text-white">
                            <i class="fas fa-check-circle mr-1"></i> Active
                        </span>
                    @endif
                    
                    @if($user->is_premium)
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-400 text-yellow-900">
                            <i class="fas fa-crown mr-1"></i> Premium Member
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="flex flex-col gap-2">
            @if($user->is_banned)
                <form action="{{ route('admin.users.unban', $user->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-semibold transition w-full">
                        <i class="fas fa-unlock mr-2"></i> Unban
                    </button>
                </form>
            @else
                <form action="{{ route('admin.users.ban', $user->id) }}" method="POST" onsubmit="return confirm('Ban user ini?')">
                    @csrf
                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg font-semibold transition w-full">
                        <i class="fas fa-ban mr-2"></i> Ban User
                    </button>
                </form>
            @endif

            <form action="{{ route('admin.users.reset-password', $user->id) }}" method="POST" onsubmit="return confirm('Reset password?')">
                @csrf
                <button type="submit" class="bg-white/20 hover:bg-white/30 backdrop-blur text-white px-4 py-2 rounded-lg font-semibold transition w-full">
                    <i class="fas fa-key mr-2"></i> Reset Password
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Stats Overview Cards -->
<div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Catches</p>
                <p class="text-3xl font-bold text-gray-800">{{ $userStats['total_catches'] }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-fish text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Fishing Spots</p>
                <p class="text-3xl font-bold text-gray-800">{{ $userStats['total_spots'] }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-map-marker-alt text-purple-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Weight</p>
                <p class="text-3xl font-bold text-gray-800">{{ number_format($catchStats['total_weight'], 1) }}</p>
                <p class="text-xs text-gray-500">kg</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-weight text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Member Since</p>
                <p class="text-lg font-bold text-gray-800">{{ $user->created_at->format('M Y') }}</p>
                <p class="text-xs text-gray-500">{{ $userStats['member_since'] }}</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-calendar text-yellow-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Last Active</p>
                <p class="text-sm font-bold text-gray-800">{{ $userStats['last_login'] }}</p>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-clock text-red-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- TAB Navigation -->
<div class="bg-white rounded-lg shadow mb-6">
    <div class="border-b" x-data="{ activeTab: 'overview' }">
        <nav class="flex">
            <button @click="activeTab = 'overview'" 
                    :class="activeTab === 'overview' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500 hover:text-gray-700'"
                    class="px-6 py-4 font-semibold transition">
                <i class="fas fa-th-large mr-2"></i> Overview
            </button>
            <button @click="activeTab = 'catches'" 
                    :class="activeTab === 'catches' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500 hover:text-gray-700'"
                    class="px-6 py-4 font-semibold transition">
                <i class="fas fa-fish mr-2"></i> Catches ({{ $catchStats['total'] }})
            </button>
            <button @click="activeTab = 'spots'" 
                    :class="activeTab === 'spots' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500 hover:text-gray-700'"
                    class="px-6 py-4 font-semibold transition">
                <i class="fas fa-map-marker-alt mr-2"></i> Fishing Spots ({{ $userStats['total_spots'] }})
            </button>
            <button @click="activeTab = 'activity'" 
                    :class="activeTab === 'activity' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500 hover:text-gray-700'"
                    class="px-6 py-4 font-semibold transition">
                <i class="fas fa-history mr-2"></i> Activity Log
            </button>
        </nav>

        <!-- TAB CONTENT -->
        <div class="p-6">
            
            <!-- TAB 1: OVERVIEW -->
            <div x-show="activeTab === 'overview'" x-transition>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    
                    <!-- User Details -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">
                            <i class="fas fa-user text-blue-600 mr-2"></i>
                            User Information
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-2 border-b">
                                <span class="text-gray-600">User ID</span>
                                <span class="font-semibold text-gray-800">#{{ $user->id }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b">
                                <span class="text-gray-600">Full Name</span>
                                <span class="font-semibold text-gray-800">{{ $user->name }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b">
                                <span class="text-gray-600">Email</span>
                                <span class="font-semibold text-gray-800">{{ $user->email }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b">
                                <span class="text-gray-600">Phone</span>
                                <span class="font-semibold text-gray-800">{{ $user->phone ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b">
                                <span class="text-gray-600">Location</span>
                                <span class="font-semibold text-gray-800">{{ $user->location ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b">
                                <span class="text-gray-600">Joined Date</span>
                                <span class="font-semibold text-gray-800">{{ $user->created_at->format('d M Y, H:i') }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b">
                                <span class="text-gray-600">Last Update</span>
                                <span class="font-semibold text-gray-800">{{ $user->updated_at->format('d M Y, H:i') }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-gray-600">Account Status</span>
                                @if($user->is_banned)
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                        <i class="fas fa-ban mr-1"></i> Banned
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                        <i class="fas fa-check-circle mr-1"></i> Active
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Catch Statistics -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">
                            <i class="fas fa-chart-bar text-green-600 mr-2"></i>
                            Catch Statistics
                        </h3>
                        
                        <!-- Stats Grid -->
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="bg-white rounded-lg p-4 text-center">
                                <p class="text-sm text-gray-500 mb-1">Total Catches</p>
                                <p class="text-3xl font-bold text-green-600">{{ $catchStats['total'] }}</p>
                            </div>
                            <div class="bg-white rounded-lg p-4 text-center">
                                <p class="text-sm text-gray-500 mb-1">This Month</p>
                                <p class="text-3xl font-bold text-blue-600">{{ $catchStats['this_month'] }}</p>
                            </div>
                            <div class="bg-white rounded-lg p-4 text-center">
                                <p class="text-sm text-gray-500 mb-1">Total Weight</p>
                                <p class="text-3xl font-bold text-purple-600">{{ number_format($catchStats['total_weight'], 1) }}</p>
                                <p class="text-xs text-gray-500">kg</p>
                            </div>
                            <div class="bg-white rounded-lg p-4 text-center">
                                <p class="text-sm text-gray-500 mb-1">Avg Weight</p>
                                <p class="text-3xl font-bold text-orange-600">{{ number_format($catchStats['avg_weight'], 1) }}</p>
                                <p class="text-xs text-gray-500">kg</p>
                            </div>
                        </div>

                        <!-- Biggest Catch -->
                        @if($catchStats['biggest_catch'])
                            <div class="bg-gradient-to-r from-yellow-400 to-orange-500 rounded-lg p-4 text-white">
                                <p class="text-sm opacity-90 mb-2">🏆 Biggest Catch</p>
                                <p class="text-2xl font-bold">{{ $catchStats['biggest_catch']->fish_type }}</p>
                                <p class="text-lg">{{ $catchStats['biggest_catch']->weight }} kg</p>
                                <p class="text-xs opacity-75 mt-2">{{ $catchStats['biggest_catch']->created_at->format('d M Y') }}</p>
                            </div>
                        @endif

                        <!-- Chart Catches by Type -->
                        <div class="mt-6">
                            <canvas id="catchTypeChart" height="200"></canvas>
                        </div>
                    </div>

                    <!-- Recent Catches -->
                    <div class="lg:col-span-2 bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">
                            <i class="fas fa-clock text-blue-600 mr-2"></i>
                            Recent Catches
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @forelse($recentCatches as $catch)
                                <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition p-4">
                                    <div class="flex items-start justify-between mb-3">
                                        <div>
                                            <h4 class="font-bold text-gray-800">{{ $catch->fish_type }}</h4>
                                            <p class="text-2xl font-bold text-green-600">{{ $catch->weight }} kg</p>
                                        </div>
                                        <i class="fas fa-fish text-3xl text-gray-300"></i>
                                    </div>
                                    <div class="text-sm text-gray-600 space-y-1">
                                        <p><i class="fas fa-map-marker-alt text-red-500 mr-2"></i>{{ Str::limit($catch->location, 20) }}</p>
                                        <p><i class="fas fa-calendar text-blue-500 mr-2"></i>{{ $catch->created_at->format('d M Y') }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-3 text-center py-8 text-gray-500">
                                    <i class="fas fa-fish-fins text-4xl mb-2"></i>
                                    <p>Belum ada catch</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>

            <!-- TAB 2: ALL CATCHES -->
            <div x-show="activeTab === 'catches'" x-transition>
                <div class="mb-4">
                    <h3 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-fish text-green-600 mr-2"></i>
                        All Catches ({{ $catchStats['total'] }} total)
                    </h3>
                </div>

                <!-- Monthly Chart -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <h4 class="font-semibold text-gray-800 mb-4">Catch Timeline (Last 6 Months)</h4>
                    <canvas id="monthlyChart" height="80"></canvas>
                </div>

                <!-- Catches Table -->
                <div class="bg-white rounded-lg overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Fish Type</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Weight</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Location</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Weather</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($catches as $catch)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-fish text-green-600"></i>
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-800">{{ $catch->fish_type }}</p>
                                                @if($catch->length)
                                                    <p class="text-xs text-gray-500">Length: {{ $catch->length }} cm</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-2xl font-bold text-green-600">{{ $catch->weight }}</span>
                                        <span class="text-sm text-gray-500">kg</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-gray-800">
                                            <i class="fas fa-map-marker-alt text-red-500 mr-1"></i>
                                            {{ $catch->location }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-gray-600">
                                            <i class="fas fa-cloud-sun text-yellow-500 mr-1"></i>
                                            {{ $catch->weather ?? 'N/A' }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $catch->created_at->format('d M Y, H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        <i class="fas fa-fish-fins text-5xl mb-3"></i>
                                        <p>Belum ada catch yang diupload</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    <div class="px-6 py-4 border-t">
                        {{ $catches->links() }}
                    </div>
                </div>
            </div>

            <!-- TAB 3: FISHING SPOTS -->
            <div x-show="activeTab === 'spots'" x-transition>
                <div class="mb-4">
                    <h3 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-map-marker-alt text-purple-600 mr-2"></i>
                        Fishing Spots ({{ $userStats['total_spots'] }} total)
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($spots as $spot)
                        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h4 class="font-bold text-lg text-gray-800">{{ $spot->name }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">
                                        <i class="fas fa-map-marker-alt text-red-500 mr-1"></i>
                                        {{ $spot->location }}
                                    </p>
                                </div>
                                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-fish text-purple-600 text-xl"></i>
                                </div>
                            </div>
                            
                            @if($spot->description)
                                <p class="text-sm text-gray-600 mb-3">{{ Str::limit($spot->description, 80) }}</p>
                            @endif
                            
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span>
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ $spot->created_at->format('d M Y') }}
                                </span>
                                @if($spot->rating)
                                    <span class="text-yellow-500">
                                        <i class="fas fa-star mr-1"></i>
                                        {{ $spot->rating }}/5
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 text-center py-12 text-gray-500">
                            <i class="fas fa-map-marked-alt text-6xl mb-4"></i>
                            <p class="text-xl font-semibold">Belum ada fishing spot</p>
                            <p class="text-sm mt-2">User belum menambahkan lokasi mancing</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- TAB 4: ACTIVITY LOG -->
            <div x-show="activeTab === 'activity'" x-transition>
                <div class="mb-4">
                    <h3 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-history text-blue-600 mr-2"></i>
                        Activity Timeline
                    </h3>
                </div>
                <div class="space-y-4">
                    @forelse($activities as $activity)
                        <div class="flex items-start gap-4 bg-white rounded-lg shadow p-4 hover:shadow-md transition">
                            <div class="w-12 h-12 bg-{{ $activity['color'] }}-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas {{ $activity['icon'] }} text-{{ $activity['color'] }}-600 text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-800">{{ $activity['title'] }}</h4>
                                <p class="text-sm text-gray-600 mt-1">{{ $activity['description'] }}</p>
                                <p class="text-xs text-gray-400 mt-2">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ $activity['time']->diffForHumans() }} 
                                    ({{ $activity['time']->format('d M Y, H:i') }})
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-500">
                            <i class="fas fa-clipboard-list text-6xl mb-4"></i>
                            <p class="text-xl font-semibold">Belum ada aktivitas</p>
                        </div>
                    @endforelse
                </div>
            </div>
    </div>
</div>
</div>
<!-- Chart.js Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart: Catches by Type (Pie Chart)
const typeCtx = document.getElementById('catchTypeChart').getContext('2d');
new Chart(typeCtx, {
    type: 'doughnut',
    data: {
        labels: @json($catchesByType->pluck('fish_type')),
        datasets: [{
            data: @json($catchesByType->pluck('total')),
            backgroundColor: [
                '#10B981', '#3B82F6', '#F59E0B', '#EF4444', '#8B5CF6',
                '#EC4899', '#14B8A6', '#F97316', '#6366F1', '#84CC16'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Chart: Monthly Catches (Line Chart)
const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
new Chart(monthlyCtx, {
    type: 'line',
    data: {
        labels: @json($monthlyCatches->map(fn($c) => date('M Y', mktotime(0, 0, 0, $c->month, 1, $c->year)))),
        datasets: [{
            label: 'Catches',
            data: @json($monthlyCatches->pluck('total')),
            borderColor: '#10B981',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            tension: 0.4,
            fill: true,
            pointRadius: 5,
            pointBackgroundColor: '#10B981',
            pointBorderColor: '#fff',
            pointBorderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});
</script>
<!-- Alpine.js for Tabs -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection