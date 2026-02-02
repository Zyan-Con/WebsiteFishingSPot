@extends('layouts.app')

@section('title', 'My Locations')

@section('content')
<div class="locations-page">
    <!-- Header Section -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-text">
                <h1 class="page-title">
                    <i class="fas fa-map-marker-alt"></i>
                    My Locations
                </h1>
                <p class="page-subtitle">Kelola spot mancing favorit Anda</p>
            </div>
            <button id="btnOpenModal" class="btn-add">
                <i class="fas fa-plus"></i>
                <span>Tambah Lokasi</span>
            </button>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <button class="tab-btn {{ ($type ?? 'lokasi') === 'lokasi' ? 'active' : '' }}" onclick="filterType('lokasi')">
            <i class="fas fa-map-marker-alt"></i>
            <span>Lokasi</span>
            <span class="count">{{ $locations->where('type', 'lokasi')->count() }}</span>
        </button>
        <button class="tab-btn {{ ($type ?? 'lokasi') === 'rawai' ? 'active' : '' }}" onclick="filterType('rawai')">
            <i class="fas fa-fish"></i>
            <span>Rawai</span>
            <span class="count">{{ $locations->where('type', 'rawai')->count() }}</span>
        </button>
        <button class="tab-btn {{ ($type ?? 'lokasi') === 'tonda' ? 'active' : '' }}" onclick="filterType('tonda')">
            <i class="fas fa-route"></i>
            <span>Tonda</span>
            <span class="count">{{ $locations->where('type', 'tonda')->count() }}</span>
        </button>
    </div>

    <!-- Locations Grid -->
    <div class="locations-grid">
        @forelse($locations as $location)
            <div class="location-card" data-id="{{ $location->id }}">
                <!-- Card Image -->
                <div class="card-image">
                    @if($location->photo)
                        <img src="{{ Storage::url($location->photo) }}" alt="{{ $location->name }}">
                    @else
                        <div class="card-image-placeholder {{ $location->type }}">
                            <i class="fas fa-{{ $location->type === 'lokasi' ? 'map-marker-alt' : ($location->type === 'rawai' ? 'fish' : 'route') }}"></i>
                        </div>
                    @endif
                    
                    <!-- Type Badge -->
                    <div class="type-badge {{ $location->type }}">
                        {{ ucfirst($location->type) }}
                    </div>
                </div>

                <!-- Card Content -->
                <div class="card-content">
                    <!-- Title & Rating -->
                    <div class="card-header">
                        <h3 class="card-title">{{ $location->name }}</h3>
                        @if($location->rating)
                            <div class="card-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $location->rating ? 'filled' : '' }}"></i>
                                @endfor
                            </div>
                        @endif
                    </div>

                    <!-- Description -->
                    @if($location->description)
                        <p class="card-description">{{ Str::limit($location->description, 60) }}</p>
                    @endif

                    <!-- Info Tags -->
                    <div class="card-tags">
                        @if($location->type === 'lokasi')
                            @if($location->fish_types)
                                <span class="tag tag-fish">
                                    <i class="fas fa-fish"></i>
                                    {{ implode(', ', array_slice($location->fish_types, 0, 2)) }}
                                </span>
                            @endif
                            @if($location->depth)
                                <span class="tag tag-depth">
                                    <i class="fas fa-arrows-alt-v"></i>
                                    {{ $location->depth }}m
                                </span>
                            @endif
                            @if($location->difficulty)
                                <span class="tag tag-{{ $location->difficulty }}">
                                    <i class="fas fa-signal"></i>
                                    {{ ucfirst($location->difficulty) }}
                                </span>
                            @endif
                        @elseif($location->type === 'rawai')
                            @if($location->hooks_count)
                                <span class="tag tag-hooks">
                                    <i class="fas fa-link"></i>
                                    {{ $location->hooks_count }} kail
                                </span>
                            @endif
                            @if($location->total_catch)
                                <span class="tag tag-catch">
                                    <i class="fas fa-fish"></i>
                                    {{ $location->total_catch }} ikan ({{ number_format($location->success_rate, 1) }}%)
                                </span>
                            @endif
                            @if($location->rawai_distance)
                                <span class="tag tag-distance">
                                    <i class="fas fa-ruler"></i>
                                    {{ $location->rawai_distance }} km
                                </span>
                            @endif
                        @elseif($location->type === 'tonda')
                            @if($location->distance_km)
                                <span class="tag tag-distance">
                                    <i class="fas fa-route"></i>
                                    {{ $location->distance_km }} km
                                </span>
                            @endif
                            @if($location->duration_minutes)
                                <span class="tag tag-time">
                                    <i class="fas fa-clock"></i>
                                    {{ floor($location->duration_minutes / 60) }}j {{ $location->duration_minutes % 60 }}m
                                </span>
                            @endif
                            @if($location->avg_speed)
                                <span class="tag tag-speed">
                                    <i class="fas fa-tachometer-alt"></i>
                                    {{ $location->avg_speed }} knots
                                </span>
                            @endif
                        @endif
                    </div>

                    <!-- Last Visited -->
                    @if($location->last_visited_at)
                        <div class="card-meta">
                            <i class="far fa-clock"></i>
                            <span>{{ $location->last_visited_at->diffForHumans() }}</span>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="card-actions">
                        <button onclick="viewLocation({{ $location->id }})" class="btn-action btn-view" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="editLocation({{ $location->id }})" class="btn-action btn-edit" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteLocation({{ $location->id }})" class="btn-action btn-delete" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <h3 class="empty-title">Belum Ada Lokasi</h3>
                <p class="empty-text">Mulai simpan spot mancing favorit Anda</p>
                <button id="btnOpenModalEmpty" class="btn-empty">
                    <i class="fas fa-plus"></i>
                    Tambah Lokasi Pertama
                </button>
            </div>
        @endforelse
    </div>
</div>

<style>
/* ==================== PAGE LAYOUT ==================== */
.locations-page {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem;
}

/* ==================== HEADER ==================== */
.page-header {
    margin-bottom: 2rem;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1.5rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.page-title i {
    color: #3b82f6;
}

.page-subtitle {
    color: #6b7280;
    margin-top: 0.5rem;
    font-size: 0.95rem;
}

.btn-add {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    border: none;
    padding: 0.875rem 1.75rem;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.btn-add:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
}

/* ==================== FILTER TABS ==================== */
.filter-tabs {
    display: flex;
    gap: 0.5rem;
    background: white;
    padding: 0.5rem;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    margin-bottom: 2rem;
}

.tab-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.875rem 1.25rem;
    border: none;
    background: transparent;
    color: #6b7280;
    font-weight: 600;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
}

.tab-btn:hover {
    background: #f3f4f6;
    color: #374151;
}

.tab-btn.active {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
}

.tab-btn .count {
    background: rgba(255, 255, 255, 0.2);
    padding: 0.125rem 0.5rem;
    border-radius: 12px;
    font-size: 0.75rem;
}

.tab-btn.active .count {
    background: rgba(255, 255, 255, 0.3);
}

/* ==================== LOCATIONS GRID ==================== */
.locations-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 1.5rem;
}

/* ==================== LOCATION CARD ==================== */
.location-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    animation: fadeInUp 0.4s ease;
}

.location-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Card Image */
.card-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.location-card:hover .card-image img {
    transform: scale(1.05);
}

.card-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 4rem;
    color: white;
}

.card-image-placeholder.lokasi {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
}

.card-image-placeholder.rawai {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
}

.card-image-placeholder.tonda {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

/* Type Badge */
.type-badge {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
    padding: 0.375rem 0.875rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    backdrop-filter: blur(10px);
    color: white;
}

.type-badge.lokasi {
    background: rgba(59, 130, 246, 0.9);
}

.type-badge.rawai {
    background: rgba(139, 92, 246, 0.9);
}

.type-badge.tonda {
    background: rgba(16, 185, 129, 0.9);
}

/* Card Content */
.card-content {
    padding: 1.25rem;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 0.75rem;
    gap: 0.75rem;
}

.card-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
    line-height: 1.4;
}

.card-rating {
    display: flex;
    gap: 0.125rem;
    font-size: 0.875rem;
    flex-shrink: 0;
}

.card-rating .fa-star {
    color: #d1d5db;
    transition: color 0.2s;
}

.card-rating .fa-star.filled {
    color: #fbbf24;
}

.card-description {
    color: #6b7280;
    font-size: 0.875rem;
    line-height: 1.6;
    margin-bottom: 1rem;
}

/* Tags */
.card-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.tag {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.375rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
}

.tag i {
    font-size: 0.7rem;
}

.tag-fish { background: #dbeafe; color: #1e40af; }
.tag-depth { background: #f3f4f6; color: #374151; }
.tag-easy { background: #d1fae5; color: #065f46; }
.tag-medium { background: #fef3c7; color: #92400e; }
.tag-hard { background: #fee2e2; color: #991b1b; }
.tag-hooks { background: #ede9fe; color: #5b21b6; }
.tag-catch { background: #d1fae5; color: #065f46; }
.tag-distance { background: #dbeafe; color: #1e40af; }
.tag-time { background: #f3f4f6; color: #374151; }
.tag-speed { background: #d1fae5; color: #065f46; }

/* Card Meta */
.card-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #9ca3af;
    font-size: 0.8rem;
    margin-bottom: 1rem;
}

/* Card Actions */
.card-actions {
    display: flex;
    gap: 0.5rem;
    padding-top: 1rem;
    border-top: 1px solid #f3f4f6;
}

.btn-action {
    flex: 1;
    padding: 0.625rem;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.875rem;
}

.btn-view {
    background: #eff6ff;
    color: #2563eb;
}

.btn-view:hover {
    background: #dbeafe;
    transform: translateY(-1px);
}

.btn-edit {
    background: #f0fdf4;
    color: #16a34a;
}

.btn-edit:hover {
    background: #dcfce7;
    transform: translateY(-1px);
}

.btn-delete {
    background: #fef2f2;
    color: #dc2626;
}

.btn-delete:hover {
    background: #fee2e2;
    transform: translateY(-1px);
}

/* ==================== EMPTY STATE ==================== */
.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
}

.empty-icon {
    font-size: 5rem;
    color: #e5e7eb;
    margin-bottom: 1.5rem;
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #374151;
    margin-bottom: 0.5rem;
}

.empty-text {
    color: #9ca3af;
    margin-bottom: 2rem;
}

.btn-empty {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    border: none;
    padding: 0.875rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-empty:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
}

/* ==================== RESPONSIVE ==================== */
@media (max-width: 768px) {
    .locations-page {
        padding: 1rem;
    }

    .header-content {
        flex-direction: column;
        align-items: stretch;
    }

    .page-title {
        font-size: 1.5rem;
    }

    .btn-add {
        width: 100%;
        justify-content: center;
    }

    .filter-tabs {
        flex-direction: column;
    }

    .locations-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<!-- INCLUDE MODAL DULU SEBELUM SCRIPT -->
@include('locations.modals.add')

<script>
// ==================== WAIT FOR DOM TO LOAD ====================
document.addEventListener('DOMContentLoaded', function() {
    
    // ==================== MODAL FUNCTIONS ====================
    function openAddModal() {
        const modal = document.getElementById('addLocationModal');
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        } else {
            console.error('Modal tidak ditemukan!');
        }
    }

    window.closeAddModal = function() {
        const modal = document.getElementById('addLocationModal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
            // Reset form
            const form = document.getElementById('addLocationForm');
            if (form) {
                form.reset();
                // Reset photo preview
                const preview = document.getElementById('photoPreview');
                if (preview) {
                    preview.style.display = 'none';
                    preview.innerHTML = '';
                }
                // Reset fish types
                const fishTypesList = document.getElementById('fishTypesList');
                if (fishTypesList) {
                    fishTypesList.innerHTML = '';
                }
            }
        }
    }

    // ==================== BUTTON EVENT LISTENERS ====================
    const btnOpenModal = document.getElementById('btnOpenModal');
    if (btnOpenModal) {
        btnOpenModal.addEventListener('click', openAddModal);
    }

    const btnOpenModalEmpty = document.getElementById('btnOpenModalEmpty');
    if (btnOpenModalEmpty) {
        btnOpenModalEmpty.addEventListener('click', openAddModal);
    }

    // ==================== CLOSE MODAL ON OVERLAY CLICK ====================
    const modalOverlay = document.getElementById('addLocationModal');
    if (modalOverlay) {
        modalOverlay.addEventListener('click', function(e) {
            if (e.target === this) {
                window.closeAddModal();
            }
        });
    }

    // ==================== PREVENT MODAL CLOSE ON CONTAINER CLICK ====================
    const modalContainer = document.querySelector('.modal-container');
    if (modalContainer) {
        modalContainer.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }

    // ==================== CLOSE ON ESC KEY ====================
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('addLocationModal');
            if (modal && modal.style.display === 'flex') {
                window.closeAddModal();
            }
        }
    });

}); // END DOMContentLoaded

// ==================== OTHER FUNCTIONS ====================
function filterType(type) {
    window.location.href = '/locations?type=' + type;
}

function viewLocation(id) {
    window.location.href = '/locations/' + id;
}

function editLocation(id) {
    alert('Edit location: ' + id);
    // TODO: Implement edit functionality
}

function deleteLocation(id) {
    if (confirm('Yakin ingin menghapus lokasi ini?')) {
        fetch(`/locations/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal menghapus lokasi');
        });
    }
}
</script>
@endsection