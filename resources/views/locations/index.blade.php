@extends('layouts.app')

@section('title', 'My Locations')

@section('content')
<div class="locations-page">
    <div class="page-header">
        <div class="header-content">
            <div class="header-text">
                <h1 class="page-title">
                    <i class="fas fa-map-marker-alt"></i>
                    My Locations
                </h1>
                <p class="page-subtitle">Kelola spot mancing favorit Anda</p>
            </div>
            <button class="btn-add" id="btnAddLocation">
                <i class="fas fa-plus"></i>
                <span>Tambah Lokasi</span>
            </button>
        </div>
    </div>

    <div class="filter-tabs">
        <button class="tab-btn {{ ($type ?? 'lokasi') === 'lokasi' ? 'active' : '' }}" onclick="window.location.href='/locations?type=lokasi'">
            <i class="fas fa-map-marker-alt"></i>
            <span>Lokasi</span>
            <span class="count">{{ $counts['lokasi'] ?? 0 }}</span>
        </button>
        <button class="tab-btn {{ ($type ?? 'lokasi') === 'rawai' ? 'active' : '' }}" onclick="window.location.href='/locations?type=rawai'">
            <i class="fas fa-fish"></i>
            <span>Rawai</span>
            <span class="count">{{ $counts['rawai'] ?? 0 }}</span>
        </button>
        <button class="tab-btn {{ ($type ?? 'lokasi') === 'tonda' ? 'active' : '' }}" onclick="window.location.href='/locations?type=tonda'">
            <i class="fas fa-route"></i>
            <span>Tonda</span>
            <span class="count">{{ $counts['tonda'] ?? 0 }}</span>
        </button>
    </div>

    <div class="locations-grid">
        @forelse($locations as $location)
            <div class="location-card">
                <div class="card-image">
                    @if($location->photo)
                        <img src="{{ Storage::url($location->photo) }}" alt="{{ $location->name }}">
                    @else
                        <div class="card-image-placeholder {{ $location->type }}">
                            <i class="fas fa-{{ $location->type === 'lokasi' ? 'map-marker-alt' : ($location->type === 'rawai' ? 'fish' : 'route') }}"></i>
                        </div>
                    @endif
                    <div class="type-badge {{ $location->type }}">
                        {{ ucfirst($location->type) }}
                    </div>
                </div>
                <div class="card-content">
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
                                    {{ $location->total_catch }} ikan 
                                    @if($location->success_rate)
                                        ({{ number_format($location->success_rate, 1) }}%)
                                    @endif
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
                    
                    <div class="card-actions">
                        <button onclick="deleteLocation({{ $location->id }})" class="btn-action btn-delete">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <h3 class="empty-title">Belum Ada {{ ucfirst($type) }}</h3>
                <p class="empty-text">Mulai simpan spot mancing favorit Anda</p>
                <button class="btn-empty" id="btnAddLocationEmpty">
                    <i class="fas fa-plus"></i>
                    Tambah {{ ucfirst($type) }} Pertama
                </button>
            </div>
        @endforelse
    </div>
</div>

<!-- MODAL -->
<div id="modalAddLocation" class="modal" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>
                    <i class="fas fa-map-marker-alt"></i>
                    Tambah Lokasi Baru
                </h3>
                <button type="button" class="modal-close" id="btnCloseModal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="formAddLocation" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <!-- Type -->
                    <div class="form-group">
                        <label class="form-label">Tipe Lokasi *</label>
                        <div class="type-selector">
                            <label class="type-option active">
                                <input type="radio" name="type" value="lokasi" checked>
                                <div class="type-card">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>Lokasi</span>
                                </div>
                            </label>
                            <label class="type-option">
                                <input type="radio" name="type" value="rawai">
                                <div class="type-card">
                                    <i class="fas fa-fish"></i>
                                    <span>Rawai</span>
                                </div>
                            </label>
                            <label class="type-option">
                                <input type="radio" name="type" value="tonda">
                                <div class="type-card">
                                    <i class="fas fa-route"></i>
                                    <span>Tonda</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Name -->
                    <div class="form-group">
                        <label class="form-label">Nama Lokasi *</label>
                        <input type="text" name="name" class="form-input" placeholder="Contoh: Spot Kakap Merah" required>
                    </div>

                    <!-- Coordinates -->
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Latitude *</label>
                            <input type="number" step="0.000001" name="latitude" id="latitude" class="form-input" placeholder="-0.123456" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Longitude *</label>
                            <input type="number" step="0.000001" name="longitude" id="longitude" class="form-input" placeholder="100.123456" required>
                        </div>
                    </div>
                    <button type="button" class="btn-gps" id="btnGetLocation">
                        <i class="fas fa-crosshairs"></i> Gunakan GPS
                    </button>

                    <!-- Description -->
                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-textarea" rows="3" placeholder="Catatan tentang lokasi..."></textarea>
                    </div>

                    <!-- Lokasi Fields -->
                    <div id="lokasiFields" class="type-fields">
                        <div class="form-group">
                            <label class="form-label">Jenis Ikan</label>
                            <input type="text" id="fishTypesInput" class="form-input" placeholder="Contoh: Kakap, Kerapu">
                            <small>Pisahkan dengan koma</small>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Kedalaman (m)</label>
                                <input type="number" step="0.1" name="depth" class="form-input" placeholder="20">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kesulitan</label>
                                <select name="difficulty" class="form-select">
                                    <option value="">Pilih...</option>
                                    <option value="easy">Easy</option>
                                    <option value="medium">Medium</option>
                                    <option value="hard">Hard</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Rawai Fields -->
                    <div id="rawaiFields" class="type-fields" style="display: none;">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Jumlah Kail</label>
                                <input type="number" name="hooks_count" class="form-input" placeholder="50">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Total Tangkapan</label>
                                <input type="number" name="total_catch" class="form-input" placeholder="23">
                            </div>
                        </div>
                    </div>

                    <!-- Tonda Fields -->
                    <div id="tondaFields" class="type-fields" style="display: none;">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Jarak (km)</label>
                                <input type="number" step="0.1" name="distance_km" class="form-input" placeholder="8.5">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Durasi (menit)</label>
                                <input type="number" name="duration_minutes" class="form-input" placeholder="135">
                            </div>
                        </div>
                    </div>

                    <!-- Photo -->
                    <div class="form-group">
                        <label class="form-label">Foto Lokasi</label>
                        <input type="file" name="photo" accept="image/*" class="form-input">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-secondary" id="btnCancel">Batal</button>
                    <button type="submit" class="btn-primary" id="btnSubmit">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Previous CSS remains the same */
.locations-page { max-width: 1400px; margin: 0 auto; padding: 2rem; }
.page-header { margin-bottom: 2rem; }
.header-content { display: flex; justify-content: space-between; align-items: center; gap: 1.5rem; }
.page-title { font-size: 2rem; font-weight: 700; color: #1f2937; margin: 0; display: flex; align-items: center; gap: 0.75rem; }
.page-title i { color: #3b82f6; }
.page-subtitle { color: #6b7280; margin-top: 0.5rem; font-size: 0.95rem; }
.btn-add { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border: none; padding: 0.875rem 1.75rem; border-radius: 12px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); }
.btn-add:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4); }
.filter-tabs { display: flex; gap: 0.5rem; background: white; padding: 0.5rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); margin-bottom: 2rem; }
.tab-btn { flex: 1; display: flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.875rem 1.25rem; border: none; background: transparent; color: #6b7280; font-weight: 600; border-radius: 8px; cursor: pointer; transition: all 0.2s ease; }
.tab-btn:hover { background: #f3f4f6; color: #374151; }
.tab-btn.active { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3); }
.tab-btn .count { background: rgba(255, 255, 255, 0.2); padding: 0.125rem 0.5rem; border-radius: 12px; font-size: 0.75rem; }
.locations-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem; }
.location-card { background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08); transition: all 0.3s ease; }
.location-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12); }
.card-image { position: relative; height: 200px; overflow: hidden; }
.card-image img { width: 100%; height: 100%; object-fit: cover; }
.card-image-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 4rem; color: white; }
.card-image-placeholder.lokasi { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); }
.card-image-placeholder.rawai { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); }
.card-image-placeholder.tonda { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
.type-badge { position: absolute; top: 0.75rem; right: 0.75rem; padding: 0.375rem 0.875rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: white; }
.type-badge.lokasi { background: rgba(59, 130, 246, 0.9); }
.type-badge.rawai { background: rgba(139, 92, 246, 0.9); }
.type-badge.tonda { background: rgba(16, 185, 129, 0.9); }
.card-content { padding: 1.25rem; }
.card-header { display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.75rem; gap: 0.75rem; }
.card-title { font-size: 1.125rem; font-weight: 700; color: #1f2937; margin: 0; }
.card-rating { display: flex; gap: 0.125rem; font-size: 0.875rem; }
.card-rating .fa-star { color: #d1d5db; }
.card-rating .fa-star.filled { color: #fbbf24; }
.card-description { color: #6b7280; font-size: 0.875rem; line-height: 1.6; margin-bottom: 1rem; }
.card-tags { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1rem; }
.tag { display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.375rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 500; }
.tag i { font-size: 0.7rem; }
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
.card-actions { display: flex; gap: 0.5rem; padding-top: 1rem; border-top: 1px solid #f3f4f6; }
.btn-action { flex: 1; padding: 0.625rem; border: none; border-radius: 8px; cursor: pointer; transition: all 0.2s ease; }
.btn-delete { background: #fef2f2; color: #dc2626; }
.btn-delete:hover { background: #fee2e2; transform: translateY(-1px); }
.empty-state { grid-column: 1 / -1; text-align: center; padding: 4rem 2rem; background: white; border-radius: 16px; box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08); }
.empty-icon { font-size: 5rem; color: #e5e7eb; margin-bottom: 1.5rem; }
.empty-title { font-size: 1.5rem; font-weight: 700; color: #374151; margin-bottom: 0.5rem; }
.empty-text { color: #9ca3af; margin-bottom: 2rem; }
.btn-empty { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border: none; padding: 0.875rem 2rem; border-radius: 12px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 0.5rem; }
.btn-empty:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4); }
.modal { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 9999; display: flex; align-items: center; justify-content: center; padding: 1rem; }
.modal-dialog { width: 100%; max-width: 600px; max-height: 90vh; overflow-y: auto; }
.modal-content { background: white; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
.modal-header { padding: 1.5rem; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; }
.modal-header h3 { margin: 0; font-size: 1.5rem; color: #1f2937; display: flex; align-items: center; gap: 0.5rem; }
.modal-header i { color: #3b82f6; }
.modal-close { background: #f3f4f6; border: none; width: 36px; height: 36px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
.modal-close:hover { background: #e5e7eb; }
.modal-body { padding: 1.5rem; }
.modal-footer { padding: 1.5rem; border-top: 1px solid #e5e7eb; display: flex; gap: 0.75rem; }
.form-group { margin-bottom: 1rem; }
.form-label { display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem; font-size: 0.875rem; }
.form-input, .form-textarea, .form-select { width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 0.875rem; }
.form-input:focus, .form-textarea:focus, .form-select:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
.type-selector { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; }
.type-option input { display: none; }
.type-card { padding: 1rem; border: 2px solid #e5e7eb; border-radius: 8px; text-align: center; cursor: pointer; transition: all 0.2s; }
.type-card i { font-size: 1.5rem; color: #6b7280; margin-bottom: 0.5rem; display: block; }
.type-card span { font-size: 0.875rem; font-weight: 600; color: #6b7280; }
.type-option.active .type-card { border-color: #3b82f6; background: #eff6ff; }
.type-option.active .type-card i, .type-option.active .type-card span { color: #3b82f6; }
.btn-gps { width: 100%; background: #f3f4f6; border: none; padding: 0.625rem; border-radius: 8px; cursor: pointer; margin-bottom: 1rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
.btn-gps:hover { background: #e5e7eb; }
.btn-primary, .btn-secondary { flex: 1; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
.btn-primary { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; }
.btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(59,130,246,0.3); }
.btn-secondary { background: #f3f4f6; color: #374151; }
.btn-secondary:hover { background: #e5e7eb; }
@media (max-width: 768px) {
    .locations-page { padding: 1rem; }
    .header-content { flex-direction: column; align-items: stretch; }
    .btn-add { width: 100%; justify-content: center; }
    .type-selector, .form-row { grid-template-columns: 1fr; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalAddLocation');
    const btnAdd = document.getElementById('btnAddLocation');
    const btnAddEmpty = document.getElementById('btnAddLocationEmpty');
    const btnClose = document.getElementById('btnCloseModal');
    const btnCancel = document.getElementById('btnCancel');
    const form = document.getElementById('formAddLocation');

    function openModal() {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.style.display = 'none';
        document.body.style.overflow = '';
        form.reset();
    }

    if (btnAdd) btnAdd.addEventListener('click', openModal);
    if (btnAddEmpty) btnAddEmpty.addEventListener('click', openModal);
    if (btnClose) btnClose.addEventListener('click', closeModal);
    if (btnCancel) btnCancel.addEventListener('click', closeModal);

    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeModal();
    });

    document.querySelectorAll('.type-option').forEach(option => {
        option.addEventListener('click', function() {
            document.querySelectorAll('.type-option').forEach(o => o.classList.remove('active'));
            this.classList.add('active');
            
            const type = this.querySelector('input').value;
            document.getElementById('lokasiFields').style.display = type === 'lokasi' ? 'block' : 'none';
            document.getElementById('rawaiFields').style.display = type === 'rawai' ? 'block' : 'none';
            document.getElementById('tondaFields').style.display = type === 'tonda' ? 'block' : 'none';
        });
    });

    document.getElementById('btnGetLocation')?.addEventListener('click', function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                position => {
                    document.getElementById('latitude').value = position.coords.latitude.toFixed(6);
                    document.getElementById('longitude').value = position.coords.longitude.toFixed(6);
                    alert('✅ Lokasi berhasil didapatkan!');
                },
                error => alert('❌ Gagal mendapatkan lokasi')
            );
        }
    });

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const fishTypes = document.getElementById('fishTypesInput').value;
        if (fishTypes) {
            formData.append('fish_types', JSON.stringify(fishTypes.split(',').map(f => f.trim()).filter(f => f)));
        }
        
        const btn = document.getElementById('btnSubmit');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        btn.disabled = true;
        
        try {
            const response = await fetch('/locations', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                alert('✅ ' + data.message);
                location.reload();
            } else {
                alert('❌ ' + data.message);
            }
        } catch (error) {
            alert('❌ Terjadi kesalahan');
        } finally {
            btn.innerHTML = '<i class="fas fa-save"></i> Simpan';
            btn.disabled = false;
        }
    });
});

function deleteLocation(id) {
    if (confirm('Yakin ingin menghapus?')) {
        fetch(`/locations/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                alert('✅ ' + data.message);
                location.reload();
            }
        });
    }
}
</script>
@endsection