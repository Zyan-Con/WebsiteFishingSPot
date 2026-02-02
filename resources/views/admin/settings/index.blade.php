@extends('admin.layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Settings</h1>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sidebar Menu -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-4">
                <nav class="space-y-2">
                    <a href="#profile" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-user mr-3"></i> Profile
                    </a>
                    <a href="#password" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-lock mr-3"></i> Change Password
                    </a>
                    <a href="#app-settings" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-cog mr-3"></i> App Settings
                    </a>
                    <a href="#system" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-server mr-3"></i> System
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Profile Section -->
            <div id="profile" class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">Profile Information</h2>
                <form action="{{ route('admin.settings.update-profile') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Name</label>
                        <input type="text" name="name" value="{{ $admin->name }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" value="{{ $admin->email }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    </div>

                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Update Profile
                    </button>
                </form>
            </div>

            <!-- Password Section -->
            <div id="password" class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">Change Password</h2>
                <form action="{{ route('admin.settings.update-password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Current Password</label>
                        <input type="password" name="current_password" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">New Password</label>
                        <input type="password" name="new_password" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    </div>

                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Change Password
                    </button>
                </form>
            </div>

            <!-- System Section -->
            <div id="system" class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">System Management</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Clear Cache -->
                    <form action="{{ route('admin.settings.clear-cache') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full px-6 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                            <i class="fas fa-broom mr-2"></i> Clear Cache
                        </button>
                    </form>

                    <!-- Optimize -->
                    <form action="{{ route('admin.settings.optimize') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            <i class="fas fa-rocket mr-2"></i> Optimize App
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection