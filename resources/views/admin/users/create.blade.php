@extends('layouts.admin')

@section('title', 'Tambah User')
@section('header', 'Tambah User Baru')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Nama</label>
                <input type="text" name="name" value="{{ old('name') }}" 
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" 
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Password</label>
                <input type="password" name="password" 
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-2">Role</label>
                <select name="role" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
                @error('role')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Simpan
                </button>
                <a href="{{ route('admin.users.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection