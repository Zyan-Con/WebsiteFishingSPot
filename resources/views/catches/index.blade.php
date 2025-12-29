@extends('layouts.app')

@section('title', 'Tangkapan')

@section('content')
<style>
    .catches-container {
        position: fixed;
        top: 60px;
        left: 280px;
        right: 0;
        bottom: 0;
        width: calc(100vw - 280px);
        height: calc(100vh - 60px);
        overflow: hidden;
        background: #f9fafb;
    }

    /* Header */
    .catches-header {
        padding: 20px 24px;
        background: white;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .page-title {
        font-size: 22px;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 4px 0;
    }

    .page-subtitle {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }

    .btn-add-catch {
        padding: 10px 20px;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border: none;
        border-radius: 8px;
        color: white;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-add-catch:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
    }

    /* Stats Cards */
    .stats-section {
        padding: 16px 24px;
        background: white;
        border-bottom: 1px solid #e5e7eb;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
    }

    .stat-card {
        background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
        padding: 16px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        transition: all 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-bottom: 12px;
    }

    .stat-label {
        font-size: 11px;
        color: #6b7280;
        margin-bottom: 6px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: #1f2937;
    }

    /* Alert Success */
    .alert-success {
        margin: 16px 24px;
        padding: 12px 16px;
        background: #d1fae5;
        border: 1px solid #6ee7b7;
        border-radius: 8px;
        color: #065f46;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 10px;
        animation: slideDown 0.3s;
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Empty State */
    .empty-state {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        padding: 40px;
    }

    .empty-icon {
        font-size: 64px;
        margin-bottom: 16px;
    }

    .empty-state h3 {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 8px;
    }

    .empty-state p {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 20px;
    }

    /* Catches Grid */
    .catches-grid {
        padding: 20px 24px 80px 24px;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 16px;
        overflow-y: auto;
        height: calc(100vh - 220px);
    }

    .catch-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        transition: all 0.2s;
        cursor: pointer;
    }

    .catch-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    }

    .catch-photo {
        width: 100%;
        height: 180px;
        object-fit: cover;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    }

    .catch-photo.no-photo {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        color: white;
    }

    .catch-content {
        padding: 16px;
    }

    .catch-fish-type {
        font-size: 16px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 10px;
    }

    .catch-details {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-bottom: 0;
    }

    .catch-detail-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #6b7280;
    }

    .catch-detail-item i {
        width: 16px;
        color: #3b82f6;
    }

    /* Pagination */
    .pagination-wrapper {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 16px 24px;
        background: white;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: center;
    }

    /* Modal Overlay */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0,0,0,0.6);
        backdrop-filter: blur(4px);
        z-index: 9998;
        display: none;
    }

    .modal-overlay.show {
        display: block;
    }

    /* Catch Modal */
    .catch-modal {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 90%;
        max-width: 500px;
        max-height: 85vh;
        background: white;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        z-index: 9999;
        display: none;
        overflow: hidden;
    }

    .catch-modal.show {
        display: block;
        animation: slideUp 0.3s;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translate(-50%, -40%);
        }
        to {
            opacity: 1;
            transform: translate(-50%, -50%);
        }
    }

    .modal-header {
        padding: 24px;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        position: relative;
    }

    .modal-title {
        font-size: 20px;
        font-weight: 700;
        margin: 0;
    }

    .modal-close {
        position: absolute;
        top: 16px;
        right: 16px;
        width: 36px;
        height: 36px;
        background: rgba(255,255,255,0.2);
        border: none;
        border-radius: 8px;
        color: white;
        font-size: 18px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .modal-close:hover {
        background: rgba(255,255,255,0.3);
    }

    .modal-body {
        padding: 24px;
        max-height: 60vh;
        overflow-y: auto;
    }

    .modal-photo {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 12px;
        margin-bottom: 20px;
    }

    .modal-photo.no-photo {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 64px;
        color: white;
    }

    .detail-section {
        margin-bottom: 20px;
    }

    .detail-label {
        font-size: 11px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .detail-value {
        font-size: 15px;
        color: #1f2937;
        font-weight: 500;
    }

    .detail-map {
        width: 100%;
        height: 200px;
        border-radius: 12px;
        background: #f3f4f6;
        margin-top: 8px;
    }

    .modal-footer {
        padding: 20px 24px;
        border-top: 1px solid #f3f4f6;
        background: #fafafa;
        display: flex;
        gap: 10px;
    }

    .btn-edit-modal, .btn-delete-modal {
        flex: 1;
        padding: 12px 16px;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-decoration: none;
    }

    .btn-edit-modal {
        background: #dbeafe;
        color: #2563eb;
    }

    .btn-edit-modal:hover {
        background: #2563eb;
        color: white;
    }

    .btn-delete-modal {
        background: #fee2e2;
        color: #ef4444;
    }

    .btn-delete-modal:hover {
        background: #ef4444;
        color: white;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .catches-container {
            left: 0;
            width: 100vw;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .catches-grid {
            grid-template-columns: 1fr;
            height: calc(100vh - 280px);
        }

        .catch-modal {
            width: 95%;
        }
    }
</style>

<div class="catches-container">
    <!-- Header -->
    <div class="catches-header">
        <div>
            <h1 class="page-title">🎣 Tangkapan Saya</h1>
            <p class="page-subtitle">Catat semua hasil tangkapan Anda</p>
        </div>
        <a href="{{ route('catches.create') }}" class="btn-add-catch">
            <i class="fas fa-plus"></i> Tambah Tangkapan
        </a>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
    <div class="alert-success">
        <i class="fas fa-check-circle" style="font-size: 18px;"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- Stats Cards -->
    @if(!$catches->isEmpty())
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: #dbeafe;">
                    <i class="fas fa-fish" style="color: #2563eb;"></i>
                </div>
                <div class="stat-label">Total Tangkapan</div>
                <div class="stat-value">{{ $stats['total_catches'] }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #d1fae5;">
                    <i class="fas fa-weight" style="color: #059669;"></i>
                </div>
                <div class="stat-label">Total Berat</div>
                <div class="stat-value">{{ number_format($stats['total_weight'], 1) }} kg</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #fef3c7;">
                    <i class="fas fa-layer-group" style="color: #d97706;"></i>
                </div>
                <div class="stat-label">Jenis Ikan</div>
                <div class="stat-value">{{ $stats['species_count'] }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #e9d5ff;">
                    <i class="fas fa-calendar-check" style="color: #7c3aed;"></i>
                </div>
                <div class="stat-label">Bulan Ini</div>
                <div class="stat-value">{{ $stats['this_month'] }}</div>
            </div>
        </div>
    </div>
    @endif

    <!-- Empty State -->
    @if($catches->isEmpty())
    <div class="empty-state">
        <div class="empty-icon">🐟</div>
        <h3>Belum ada tangkapan</h3>
        <p>Klik tombol di atas untuk mulai mencatat</p>
        <a href="{{ route('catches.create') }}" class="btn-add-catch" style="display: inline-flex;">
            <i class="fas fa-plus"></i> Tambah Tangkapan Pertama
        </a>
    </div>
    @else
    <!-- Catches Grid -->
    <div class="catches-grid">
        @foreach($catches as $catch)
        <div class="catch-card" onclick="showCatchDetail({{ $catch->id }})">
            @if($catch->photo)
            <img src="{{ asset('storage/' . $catch->photo) }}" alt="{{ $catch->fish_type }}" class="catch-photo">
            @else
            <div class="catch-photo no-photo">🐟</div>
            @endif

            <div class="catch-content">
                <div class="catch-fish-type">{{ $catch->fish_type }}</div>
                <div class="catch-details">
                    @if($catch->weight)
                    <div class="catch-detail-item">
                        <i class="fas fa-weight-hanging"></i>
                        <span>{{ number_format($catch->weight, 1) }} kg</span>
                    </div>
                    @endif
                    @if($catch->length)
                    <div class="catch-detail-item">
                        <i class="fas fa-ruler-horizontal"></i>
                        <span>{{ number_format($catch->length, 1) }} cm</span>
                    </div>
                    @endif
                    @if($catch->quantity && $catch->quantity > 1)
                    <div class="catch-detail-item">
                        <i class="fas fa-fish"></i>
                        <span>{{ $catch->quantity }} ekor</span>
                    </div>
                    @endif
                    <div class="catch-detail-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ Str::limit($catch->location, 30) }}</span>
                    </div>
                    <div class="catch-detail-item">
                        <i class="fas fa-calendar"></i>
                        <span>{{ \Carbon\Carbon::parse($catch->caught_at)->format('d M Y, H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($catches->hasPages())
    <div class="pagination-wrapper">
        {{ $catches->links() }}
    </div>
    @endif
    @endif
</div>

<!-- Modal Overlay -->
<div class="modal-overlay" id="modalOverlay" onclick="closeModal()"></div>

<!-- Catch Detail Modal -->
<div class="catch-modal" id="catchModal">
    <div class="modal-header">
        <h2 class="modal-title" id="modalTitle">Detail Tangkapan</h2>
        <button class="modal-close" onclick="closeModal()">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="modal-body" id="modalContent">
        <!-- Content will be loaded by JavaScript -->
    </div>

    <div class="modal-footer">
        <a href="#" id="editLink" class="btn-edit-modal">
            <i class="fas fa-edit"></i> Edit
        </a>
        <form id="deleteForm" method="POST" style="flex: 1; margin: 0;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-delete-modal" style="width: 100%;" onclick="return confirm('Yakin ingin menghapus tangkapan ini?')">
                <i class="fas fa-trash"></i> Hapus
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const catches = @json($catches->items());

    function showCatchDetail(id) {
        const catchData = catches.find(c => c.id === id);
        if (!catchData) return;

        // Update modal title
        document.getElementById('modalTitle').textContent = catchData.fish_type;

        // Build modal content
        let content = '';

        // Photo
        if (catchData.photo) {
            content += `<img src="/storage/${catchData.photo}" alt="${catchData.fish_type}" class="modal-photo">`;
        } else {
            content += `<div class="modal-photo no-photo">🐟</div>`;
        }

        // Details
        content += `<div class="detail-section">`;
        content += `<div class="detail-label">Informasi Tangkapan</div>`;
        
        if (catchData.weight) {
            content += `<div class="detail-value"><i class="fas fa-weight-hanging" style="color: #3b82f6; margin-right: 8px;"></i> Berat: ${catchData.weight} kg</div>`;
        }
        if (catchData.length) {
            content += `<div class="detail-value" style="margin-top: 6px;"><i class="fas fa-ruler-horizontal" style="color: #3b82f6; margin-right: 8px;"></i> Panjang: ${catchData.length} cm</div>`;
        }
        if (catchData.quantity) {
            content += `<div class="detail-value" style="margin-top: 6px;"><i class="fas fa-fish" style="color: #3b82f6; margin-right: 8px;"></i> Jumlah: ${catchData.quantity} ekor</div>`;
        }
        if (catchData.fishing_method) {
            content += `<div class="detail-value" style="margin-top: 6px;"><i class="fas fa-anchor" style="color: #3b82f6; margin-right: 8px;"></i> Metode: ${catchData.fishing_method}</div>`;
        }
        if (catchData.weather) {
            content += `<div class="detail-value" style="margin-top: 6px;"><i class="fas fa-cloud-sun" style="color: #3b82f6; margin-right: 8px;"></i> Cuaca: ${catchData.weather}</div>`;
        }
        if (catchData.water_temp) {
            content += `<div class="detail-value" style="margin-top: 6px;"><i class="fas fa-temperature-high" style="color: #3b82f6; margin-right: 8px;"></i> Suhu Air: ${catchData.water_temp}°C</div>`;
        }
        content += `</div>`;

        // Location
        content += `<div class="detail-section">`;
        content += `<div class="detail-label">Lokasi</div>`;
        content += `<div class="detail-value"><i class="fas fa-map-marker-alt" style="color: #3b82f6; margin-right: 8px;"></i> ${catchData.location}</div>`;
        if (catchData.latitude && catchData.longitude) {
            content += `<div id="detailMap" class="detail-map"></div>`;
        }
        content += `</div>`;

        // Date
        content += `<div class="detail-section">`;
        content += `<div class="detail-label">Waktu Tangkapan</div>`;
        content += `<div class="detail-value"><i class="fas fa-calendar" style="color: #3b82f6; margin-right: 8px;"></i> ${new Date(catchData.caught_at).toLocaleString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        })}</div>`;
        content += `</div>`;

        // Notes
        if (catchData.notes) {
            content += `<div class="detail-section">`;
            content += `<div class="detail-label">Catatan</div>`;
            content += `<div class="detail-value">${catchData.notes}</div>`;
            content += `</div>`;
        }

        document.getElementById('modalContent').innerHTML = content;

        // Update edit link
        document.getElementById('editLink').href = `/catches/${id}/edit`;

        // Update delete form action
        document.getElementById('deleteForm').action = `/catches/${id}`;

        // Show modal
        document.getElementById('modalOverlay').classList.add('show');
        document.getElementById('catchModal').classList.add('show');

        // Initialize map if coordinates exist
        if (catchData.latitude && catchData.longitude) {
            setTimeout(() => {
                initDetailMap(catchData.latitude, catchData.longitude);
            }, 100);
        }
    }

    function closeModal() {
        document.getElementById('modalOverlay').classList.remove('show');
        document.getElementById('catchModal').classList.remove('show');
    }

    function initDetailMap(lat, lng) {
        const mapElement = document.getElementById('detailMap');
        if (!mapElement) return;

        const map = L.map('detailMap', {
            center: [lat, lng],
            zoom: 13,
            zoomControl: false,
            attributionControl: false
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        const markerIcon = L.divIcon({
            html: '<div style="background: #3b82f6; width: 30px; height: 30px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"></div>',
            iconSize: [30, 30],
            iconAnchor: [15, 30]
        });

        L.marker([lat, lng], { icon: markerIcon }).addTo(map);

        setTimeout(() => {
            map.invalidateSize();
        }, 100);
    }

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
</script>
@endpush
@endsection