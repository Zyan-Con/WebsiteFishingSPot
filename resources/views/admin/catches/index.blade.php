@extends('admin.layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Fish Catches Management</h1>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-500 text-sm">Total Catches</div>
            <div class="text-3xl font-bold text-blue-600">{{ number_format($stats['total']) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-500 text-sm">Total Quantity</div>
            <div class="text-3xl font-bold text-green-600">{{ number_format($stats['total_quantity']) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-500 text-sm">Total Weight</div>
            <div class="text-3xl font-bold text-purple-600">{{ number_format($stats['total_weight'], 2) }} kg</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-500 text-sm">Today</div>
            <div class="text-3xl font-bold text-orange-600">{{ number_format($stats['today']) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-500 text-sm">This Month</div>
            <div class="text-3xl font-bold text-indigo-600">{{ number_format($stats['this_month']) }}</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" action="{{ route('admin.catches.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- Search -->
            <div>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search fish type, location..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Filter by User -->
            <div>
                <select name="user_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter by Fish Type -->
            <div>
                <select name="fish_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">All Fish Types</option>
                    @foreach($fishTypes as $type)
                        <option value="{{ $type }}" {{ request('fish_type') == $type ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Date From -->
            <div>
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Date To -->
            <div>
                <input type="date" name="date_to" value="{{ request('date_to') }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Buttons -->
            <div class="md:col-span-5 flex gap-2">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
                <a href="{{ route('admin.catches.index') }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                    <i class="fas fa-redo mr-2"></i> Reset
                </a>
                <a href="{{ route('admin.catches.export', request()->all()) }}" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 ml-auto">
                    <i class="fas fa-download mr-2"></i> Export CSV
                </a>
                <a href="{{ route('admin.catches.statistics') }}" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                    <i class="fas fa-chart-bar mr-2"></i> Statistics
                </a>
            </div>
        </form>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Catches Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fish Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Weight</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Length</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Caught At</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($catches as $catch)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $catch->user->name ?? 'Unknown' }}</div>
                                    <div class="text-sm text-gray-500">{{ $catch->user->email ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $catch->fish_type }}</div>
                            @if($catch->fishing_method)
                                <div class="text-sm text-gray-500">{{ $catch->fishing_method }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($catch->weight, 2) }} kg
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($catch->length, 2) }} cm
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $catch->quantity }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $catch->location_name ?? '-' }}</div>
                            @if($catch->weather)
                                <div class="text-xs text-gray-500">
                                    <i class="fas fa-cloud mr-1"></i>{{ $catch->weather }}
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $catch->caught_at->format('d M Y') }}</div>
                            <div class="text-sm text-gray-500">{{ $catch->catch_time ?? $catch->caught_at->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.catches.show', $catch->id) }}" 
                               class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <form action="{{ route('admin.catches.destroy', $catch->id) }}" 
                                  method="POST" 
                                  class="inline"
                                  onsubmit="return confirm('Are you sure you want to delete this catch?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-fish text-4xl mb-2"></i>
                            <p>No catches found</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $catches->links() }}
    </div>
</div>
@endsection