<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Fishing App</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-500 to-blue-700 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-blue-600">🎣 Fishing App</h1>
            <p class="text-gray-500 mt-2">Admin Panel Login</p>
        </div>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf
            
            <!-- Email -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="admin@fishingapp.com"
                    required
                >
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Password</label>
                <input 
                    type="password" 
                    name="password"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="••••••••"
                    required
                >
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center mb-6">
                <input 
                    type="checkbox" 
                    name="remember" 
                    id="remember"
                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                >
                <label for="remember" class="ml-2 text-gray-700">Remember Me</label>
            </div>

            <!-- Submit Button -->
            <button 
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition duration-200 transform hover:scale-105"
            >
                Login
            </button>
        </form>

        <div class="text-center mt-6">
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline text-sm">
                ← Back to User Login
            </a>
        </div>
    </div>
</body>
</html>