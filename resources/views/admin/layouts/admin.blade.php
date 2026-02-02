<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Fishing App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Sidebar -->
    <div class="flex h-screen">
        <aside class="w-64 bg-blue-800 text-white flex flex-col">
            <div class="p-6 text-center border-b border-blue-700">
                <h1 class="text-2xl font-bold">🎣 Fishing App</h1>
                <p class="text-sm text-blue-200 mt-1">Admin Panel</p>
            </div>

            <nav class="flex-1 p-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition
                          {{ request()->routeIs('admin.dashboard') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-home mr-3"></i> Dashboard
                </a>
                    <!-- PASTIKAN INI ADA -->
                <!-- <a href="{{ route('admin.notifications.index') }}" 
                class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition
                        {{ request()->routeIs('admin.notifications.*') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-bell mr-3"></i> Notifications
                </a> -->
                                    <!-- TAMBAHKAN INI -->
                <a href="{{ route('admin.notifications.index') }}" 
                class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition
                        {{ request()->routeIs('admin.notifications.*') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-bell mr-3"></i> Notifikasi
                </a> 
                 <!-- ✅ FEEDBACK MENU BARU -->
                    <a href="{{ route('admin.feedback.index') }}" 
                    class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition
                            {{ request()->routeIs('admin.feedback.*') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-comment-dots mr-3"></i> 
                        User Feedback
                        @php
                            $unreadFeedbacks = \App\Models\Feedback::where('is_read_by_admin', false)->count();
                        @endphp
                        @if($unreadFeedbacks > 0)
                            <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                                {{ $unreadFeedbacks }}
                            </span>
                        @endif
                    </a>
                <a href="{{ route(name: 'admin.users.index') }}" 
                class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition
                        {{ request()->routeIs('admin.users.*') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-users mr-3"></i> Users
                </a>
                <a href="#" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-fish mr-3"></i> Catches
                </a>
                <a href="#" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-trophy mr-3"></i> Tournaments
                </a>
                <a href="{{ route('admin.settings.index') }}" 
                class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition
                        {{ request()->routeIs('admin.settings.*') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-cog mr-3"></i> Settings
                </a>
            </nav>

            <div class="p-4 border-t border-blue-700">
                <div class="flex items-center mb-3">
                    <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="ml-3">
                        <p class="font-semibold">{{ auth()->guard('admin')->user()->name }}</p>
                        <p class="text-xs text-blue-200">Administrator</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" 
                            class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg transition">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <!-- Header -->
            <header class="bg-white shadow-sm p-6 border-b">
                <h2 class="text-2xl font-bold text-gray-800">@yield('header', 'Dashboard')</h2>
            </header>

            <!-- Content -->
            <div class="p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>