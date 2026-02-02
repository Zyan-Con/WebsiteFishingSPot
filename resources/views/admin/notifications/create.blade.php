@extends('layouts.admin')

@section('title', 'Buat Notifikasi Baru')

@section('header', 'Buat Notifikasi Baru')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.notifications.store') }}" method="POST">
            @csrf

            <!-- Type -->
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">
                    Tipe Notifikasi <span class="text-red-500">*</span>
                </label>
                <select name="type" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="info">Info (Biru)</option>
                    <option value="success">Success (Hijau)</option>
                    <option value="warning">Warning (Kuning)</option>
                    <option value="danger">Danger (Merah)</option>
                </select>
                @error('type')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Title -->
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">
                    Judul <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       placeholder="Contoh: Update Sistem"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Message -->
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">
                    Pesan <span class="text-red-500">*</span>
                </label>
                <textarea name="message" rows="5" required
                          placeholder="Tulis pesan notifikasi..."
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('message') }}</textarea>
                @error('message')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action URL (Optional) -->
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">
                    Link Aksi (Opsional)
                </label>
                <input type="url" name="action_url" value="{{ old('action_url') }}"
                       placeholder="https://example.com"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <p class="text-sm text-gray-500 mt-1">Link yang akan muncul di notifikasi</p>
            </div>

            <!-- Target -->
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">
                    Kirim Ke <span class="text-red-500">*</span>
                </label>
                <div class="space-y-3">
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="target" value="all" checked
                               class="w-4 h-4 text-blue-600"
                               onclick="document.getElementById('userSelect').classList.add('hidden')">
                        <span class="ml-3 font-medium">Semua User (Broadcast)</span>
                    </label>
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="target" value="specific"
                               class="w-4 h-4 text-blue-600"
                               onclick="document.getElementById('userSelect').classList.remove('hidden')">
                        <span class="ml-3 font-medium">User Tertentu</span>
                    </label>
                </div>
            </div>

            <!-- User Selection (Hidden by default) -->
            <div id="userSelect" class="mb-6 hidden">
                <label class="block text-gray-700 font-semibold mb-2">
                    Pilih User <span class="text-red-500">*</span>
                </label>
                <select name="user_ids[]" multiple size="8"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                <p class="text-sm text-gray-500 mt-1">
                    Tahan Ctrl (Windows) atau Cmd (Mac) untuk pilih banyak user
                </p>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Kirim Notifikasi
                </button>
                <a href="{{ route('admin.notifications.index') }}"
                   class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection