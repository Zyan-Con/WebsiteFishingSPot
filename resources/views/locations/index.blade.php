@extends('layouts.app')

@section('title', 'Lokasi')

@section('content')
<script>
    // Auto redirect ke dashboard dan buka modal
    window.addEventListener('load', function() {
        if (typeof window.openLocationModal === 'function') {
            // Jika sudah di dashboard
            window.openLocationModal();
        } else {
            // Redirect ke dashboard
            window.location.href = "{{ route('dashboard') }}?openModal=true";
        }
    });
</script>

<div style="display: flex; align-items: center; justify-content: center; height: 100vh; flex-direction: column; gap: 16px;">
    <div style="width: 48px; height: 48px; border: 4px solid #e5e7eb; border-top-color: #2563eb; border-radius: 50%; animation: spin 1s linear infinite;"></div>
    <p style="color: #6b7280; font-size: 14px;">Membuka lokasi...</p>
</div>

<style>
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>
@endsection