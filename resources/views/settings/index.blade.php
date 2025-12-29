@extends('layouts.app')

@section('title', 'Pengaturan')

@section('content')
<style>
    .settings-container {
        padding: 24px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .settings-header {
        margin-bottom: 32px;
    }

    .settings-title {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 8px;
    }

    .settings-subtitle {
        font-size: 14px;
        color: #6b7280;
    }

    .settings-grid {
        display: grid;
        gap: 24px;
    }

    .settings-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid #e5e7eb;
    }

    .card-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
    }

    .card-title {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
    }

    .setting-item {
        padding: 16px 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .setting-item:last-child {
        border-bottom: none;
    }

    .setting-label {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .label-text {
        font-size: 14px;
        font-weight: 500;
        color: #1f2937;
    }

    .label-description {
        font-size: 13px;
        color: #6b7280;
        margin-top: 4px;
    }

    .form-input {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s;
        margin-top: 8px;
    }

    .form-input:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .form-select {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        background: white;
        cursor: pointer;
        transition: all 0.2s;
    }

    .form-select:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    /* Toggle Switch */
    .toggle-switch {
        position: relative;
        width: 48px;
        height: 24px;
        background: #e5e7eb;
        border-radius: 24px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .toggle-switch.active {
        background: #2563eb;
    }

    .toggle-switch::after {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: white;
        top: 2px;
        left: 2px;
        transition: transform 0.3s;
    }

    .toggle-switch.active::after {
        transform: translateX(24px);
    }

    .btn-primary {
        padding: 10px 20px;
        background: #2563eb;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
    }

    .btn-danger {
        padding: 10px 20px;
        background: #dc2626;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-danger:hover {
        background: #b91c1c;
        transform: translateY(-1px);
    }

    .btn-group {
        display: flex;
        gap: 12px;
        margin-top: 20px;
    }

    /* Dark Theme */
    body.dark-theme .settings-container {
        background: #111827;
    }

    body.dark-theme .settings-title {
        color: #f3f4f6;
    }

    body.dark-theme .settings-card {
        background: #1f2937;
    }

    body.dark-theme .card-header {
        border-bottom-color: #374151;
    }

    body.dark-theme .card-title {
        color: #f3f4f6;
    }

    body.dark-theme .setting-item {
        border-bottom-color: #374151;
    }

    body.dark-theme .label-text {
        color: #f3f4f6;
    }

    body.dark-theme .label-description {
        color: #9ca3af;
    }

    body.dark-theme .form-input,
    body.dark-theme .form-select {
        background: #111827;
        border-color: #374151;
        color: #f3f4f6;
    }
</style>

<div class="settings-container">
    <div class="settings-header">
        <h1 class="settings-title">Pengaturan</h1>
        <p class="settings-subtitle">Kelola preferensi dan pengaturan akun Anda</p>
    </div>

    <div class="settings-grid">
        <!-- Profile Settings -->
        <div class="settings-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fas fa-user"></i>
                </div>
                <h2 class="card-title">Profil</h2>
            </div>

            <form action="{{ route('settings.update.profile') }}" method="POST">
                @csrf
                <div class="setting-item">
                    <div class="setting-label">
                        <div>
                            <div class="label-text">Nama</div>
                            <div class="label-description">Nama lengkap Anda</div>
                        </div>
                    </div>
                    <input type="text" name="name" class="form-input" value="{{ Auth::user()->name }}" required>
                </div>

                <div class="setting-item">
                    <div class="setting-label">
                        <div>
                            <div class="label-text">Email</div>
                            <div class="label-description">Alamat email Anda</div>
                        </div>
                    </div>
                    <input type="email" name="email" class="form-input" value="{{ Auth::user()->email }}" required>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Appearance Settings -->
        <div class="settings-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fas fa-palette"></i>
                </div>
                <h2 class="card-title">Tampilan</h2>
            </div>

            <form action="{{ route('settings.update.appearance') }}" method="POST">
                @csrf
                <div class="setting-item">
                    <div class="setting-label">
                        <div>
                            <div class="label-text">Tema</div>
                            <div class="label-description">Pilih tema aplikasi</div>
                        </div>
                    </div>
                    <select name="theme" class="form-select" id="themeSelect">
                        <option value="light" {{ (Auth::user()->theme ?? 'light') == 'light' ? 'selected' : '' }}>Terang</option>
                        <option value="dark" {{ (Auth::user()->theme ?? 'light') == 'dark' ? 'selected' : '' }}>Gelap</option>
                    </select>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Notification Settings -->
        <div class="settings-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <h2 class="card-title">Notifikasi</h2>
            </div>

            <form action="{{ route('settings.update.notifications') }}" method="POST">
                @csrf
                <div class="setting-item">
                    <div class="setting-label">
                        <div>
                            <div class="label-text">Email Notifikasi</div>
                            <div class="label-description">Terima notifikasi melalui email</div>
                        </div>
                        <div class="toggle-switch active" data-toggle="email_notifications">
                            <input type="hidden" name="email_notifications" value="1">
                        </div>
                    </div>
                </div>

                <div class="setting-item">
                    <div class="setting-label">
                        <div>
                            <div class="label-text">Prakiraan Cuaca</div>
                            <div class="label-description">Notifikasi prakiraan cuaca harian</div>
                        </div>
                        <div class="toggle-switch active" data-toggle="weather_notifications">
                            <input type="hidden" name="weather_notifications" value="1">
                        </div>
                    </div>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Account Actions -->
        <div class="settings-card">
            <div class="card-header">
                <div class="card-icon" style="background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h2 class="card-title">Aksi Akun</h2>
            </div>

            <div class="setting-item">
                <div class="setting-label">
                    <div>
                        <div class="label-text">Keluar</div>
                        <div class="label-description">Keluar dari akun Anda</div>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-danger">
                        <i class="fas fa-sign-out-alt"></i>
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Theme selector
    document.getElementById('themeSelect').addEventListener('change', function() {
        if (this.value === 'dark') {
            document.body.classList.add('dark-theme');
        } else {
            document.body.classList.remove('dark-theme');
        }
    });

    // Toggle switches
    document.querySelectorAll('.toggle-switch').forEach(toggle => {
        toggle.addEventListener('click', function() {
            this.classList.toggle('active');
            const input = this.querySelector('input[type="hidden"]');
            input.value = this.classList.contains('active') ? '1' : '0';
        });
    });
</script>
@endpush
@endsection