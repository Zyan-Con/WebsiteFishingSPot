@extends('layouts.app')

@section('title', 'Edit Tangkapan')

@section('content')
<style>
    .edit-container {
        position: fixed;
        top: 60px;
        left: 280px;
        right: 0;
        bottom: 0;
        width: calc(100vw - 280px);
        height: calc(100vh - 60px);
        overflow-y: auto;
        background: #f9fafb;
        padding: 24px;
    }

    .edit-card {
        max-width: 900px;
        margin: 0 auto;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    .edit-header {
        padding: 24px;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }

    .edit-title {
        font-size: 24px;
        font-weight: 700;
        margin: 0 0 8px 0;
    }

    .edit-subtitle {
        font-size: 14px;
        opacity: 0.9;
        margin: 0;
    }

    .edit-body {
        padding: 32px;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-label .required {
        color: #ef4444;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.2s;
        font-family: inherit;
    }

    .form-control:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-control.error {
        border-color: #ef4444;
    }

    .error-message {
        color: #ef4444;
        font-size: 12px;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    /* Photo Section */
    .photo-section {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
        background: #fafafa;
    }

    .photo-preview-container {
        position: relative;
        margin-bottom: 16px;
    }

    .photo-preview-wrapper {
        width: 100%;
        max-height: 400px;
        border-radius: 12px;
        overflow: hidden;
        background: #000;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .photo-preview {
        max-width: 100%;
        max-height: 400px;
        object-fit: contain;
    }

    .no-photo-placeholder {
        width: 100%;
        height: 300px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #e5e7eb 0%, #f3f4f6 100%);
        border-radius: 12px;
        color: #6b7280;
    }

    .no-photo-placeholder i {
        font-size: 64px;
        margin-bottom: 16px;
    }

    /* Photo Edit Toolbar */
    .photo-toolbar {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 12px;
    }

    .toolbar-btn {
        padding: 10px 16px;
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 500;
        color: #374151;
        transition: all 0.2s;
    }

    .toolbar-btn:hover {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
        transform: translateY(-2px);
    }

    .toolbar-btn i {
        font-size: 14px;
    }

    .toolbar-btn.upload-btn {
        background: #dbeafe;
        border-color: #3b82f6;
        color: #2563eb;
    }

    .toolbar-btn.upload-btn:hover {
        background: #3b82f6;
        color: white;
    }

    .toolbar-btn.remove-btn {
        background: #fee2e2;
        border-color: #ef4444;
        color: #ef4444;
    }

    .toolbar-btn.remove-btn:hover {
        background: #ef4444;
        color: white;
    }

    .row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .row-3 {
        grid-template-columns: repeat(3, 1fr);
    }

    .map-container {
        width: 100%;
        height: 300px;
        border-radius: 12px;
        border: 2px solid #e5e7eb;
        overflow: hidden;
        margin-top: 12px;
    }

    .btn-group {
        display: flex;
        gap: 12px;
        padding: 24px 32px;
        background: #fafafa;
        border-top: 1px solid #e5e7eb;
    }

    .btn {
        flex: 1;
        padding: 14px 20px;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-cancel {
        background: #f3f4f6;
        color: #6b7280;
    }

    .btn-cancel:hover {
        background: #e5e7eb;
        transform: translateY(-2px);
    }

    .btn-submit {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.4);
    }

    .location-btn {
        margin-top: 8px;
        padding: 10px 16px;
        background: #dbeafe;
        color: #2563eb;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }

    .location-btn:hover {
        background: #2563eb;
        color: white;
    }

    @media (max-width: 768px) {
        .edit-container {
            left: 0;
            width: 100vw;
            padding: 16px;
        }

        .row {
            grid-template-columns: 1fr;
        }

        .edit-body {
            padding: 20px;
        }

        .photo-toolbar {
            justify-content: center;
        }
    }
</style>

<div class="edit-container">
    <div class="edit-card">
        <div class="edit-header">
            <h1 class="edit-title">✏️ Edit Tangkapan</h1>
            <p class="edit-subtitle">Perbarui informasi tangkapan Anda</p>
        </div>

        <form action="{{ route('catches.update', $catch->id) }}" method="POST" enctype="multipart/form-data" id="editForm">
            @csrf
            @method('PUT')

            <div class="edit-body">
                <!-- Photo Section -->
                <div class="form-group">
                    <label class="form-label">Foto Tangkapan</label>
                    <div class="photo-section">
                        <!-- Toolbar -->
                        <div class="photo-toolbar">
                            <button type="button" class="toolbar-btn upload-btn" onclick="document.getElementById('photoInput').click()">
                                <i class="fas fa-camera"></i> Upload Foto Baru
                            </button>
                            @if($catch->photo)
                            <button type="button" class="toolbar-btn" onclick="rotatePhoto('left')">
                                <i class="fas fa-undo"></i> Putar Kiri
                            </button>
                            <button type="button" class="toolbar-btn" onclick="rotatePhoto('right')">
                                <i class="fas fa-redo"></i> Putar Kanan
                            </button>
                            <button type="button" class="toolbar-btn" onclick="flipPhoto('horizontal')">
                                <i class="fas fa-arrows-alt-h"></i> Flip Horizontal
                            </button>
                            <button type="button" class="toolbar-btn" onclick="flipPhoto('vertical')">
                                <i class="fas fa-arrows-alt-v"></i> Flip Vertikal
                            </button>
                            <button type="button" class="toolbar-btn remove-btn" onclick="removePhoto()">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                            @endif
                        </div>

                        <!-- Preview -->
                        <div class="photo-preview-container">
                            <div class="photo-preview-wrapper" id="photoPreviewWrapper">
                                @if($catch->photo)
                                <img src="{{ asset('storage/' . $catch->photo) }}" alt="Preview" class="photo-preview" id="photoPreview">
                                @else
                                <div class="no-photo-placeholder">
                                    <i class="fas fa-image"></i>
                                    <p>Belum ada foto</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <input type="file" id="photoInput" name="photo" accept="image/*" style="display: none;" onchange="handlePhotoUpload(event)">
                        <input type="hidden" id="photoEdited" name="photo_edited" value="0">
                        <input type="hidden" id="photoRotation" name="photo_rotation" value="0">
                        <input type="hidden" id="photoFlipH" name="photo_flip_h" value="1">
                        <input type="hidden" id="photoFlipV" name="photo_flip_v" value="1">
                    </div>
                    @error('photo')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Fish Type -->
                <div class="form-group">
                    <label class="form-label">Jenis Ikan <span class="required">*</span></label>
                    <input type="text" name="fish_type" class="form-control @error('fish_type') error @enderror" 
                           value="{{ old('fish_type', $catch->fish_type) }}" placeholder="Contoh: Tuna, Kakap, Kerapu" required>
                    @error('fish_type')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Weight, Length, Quantity -->
                <div class="row row-3">
                    <div class="form-group">
                        <label class="form-label">Berat (kg)</label>
                        <input type="number" step="0.1" name="weight" class="form-control @error('weight') error @enderror" 
                               value="{{ old('weight', $catch->weight) }}" placeholder="0.0">
                        @error('weight')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Panjang (cm)</label>
                        <input type="number" step="0.1" name="length" class="form-control @error('length') error @enderror" 
                               value="{{ old('length', $catch->length) }}" placeholder="0.0">
                        @error('length')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Jumlah (ekor)</label>
                        <input type="number" name="quantity" class="form-control @error('quantity') error @enderror" 
                               value="{{ old('quantity', $catch->quantity ?? 1) }}" placeholder="1" min="1">
                        @error('quantity')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Location -->
                <div class="form-group">
                    <label class="form-label">Lokasi Tangkapan <span class="required">*</span></label>
                    <input type="text" name="location" id="locationInput" class="form-control @error('location') error @enderror" 
                           value="{{ old('location', $catch->location) }}" placeholder="Contoh: Pantai Ancol, Jakarta" required>
                    <button type="button" class="location-btn" onclick="getCurrentLocation()">
                        <i class="fas fa-map-marker-alt"></i> Gunakan Lokasi Saat Ini
                    </button>
                    @error('location')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="map-container" id="editMap"></div>
                    <input type="hidden" name="latitude" id="latitudeInput" value="{{ old('latitude', $catch->latitude) }}">
                    <input type="hidden" name="longitude" id="longitudeInput" value="{{ old('longitude', $catch->longitude) }}">
                </div>

                <!-- Caught At -->
                <div class="form-group">
                    <label class="form-label">Waktu Tangkapan <span class="required">*</span></label>
                    <input type="datetime-local" name="caught_at" class="form-control @error('caught_at') error @enderror" 
                           value="{{ old('caught_at', $catch->caught_at ? date('Y-m-d\TH:i', strtotime($catch->caught_at)) : '') }}" required>
                    @error('caught_at')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Fishing Method & Weather -->
                <div class="row">
                    <div class="form-group">
                        <label class="form-label">Metode Penangkapan</label>
                        <select name="fishing_method" class="form-control @error('fishing_method') error @enderror">
                            <option value="">Pilih metode...</option>
                            <option value="Pancing" {{ old('fishing_method', $catch->fishing_method) == 'Pancing' ? 'selected' : '' }}>Pancing</option>
                            <option value="Jaring" {{ old('fishing_method', $catch->fishing_method) == 'Jaring' ? 'selected' : '' }}>Jaring</option>
                            <option value="Trawl" {{ old('fishing_method', $catch->fishing_method) == 'Trawl' ? 'selected' : '' }}>Trawl</option>
                            <option value="Tombak" {{ old('fishing_method', $catch->fishing_method) == 'Tombak' ? 'selected' : '' }}>Tombak</option>
                            <option value="Lainnya" {{ old('fishing_method', $catch->fishing_method) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Cuaca</label>
                        <select name="weather" class="form-control @error('weather') error @enderror">
                            <option value="">Pilih cuaca...</option>
                            <option value="Cerah" {{ old('weather', $catch->weather) == 'Cerah' ? 'selected' : '' }}>☀️ Cerah</option>
                            <option value="Berawan" {{ old('weather', $catch->weather) == 'Berawan' ? 'selected' : '' }}>⛅ Berawan</option>
                            <option value="Hujan" {{ old('weather', $catch->weather) == 'Hujan' ? 'selected' : '' }}>🌧️ Hujan</option>
                            <option value="Badai" {{ old('weather', $catch->weather) == 'Badai' ? 'selected' : '' }}>⛈️ Badai</option>
                        </select>
                    </div>
                </div>

                <!-- Water Temperature -->
                <div class="form-group">
                    <label class="form-label">Suhu Air (°C)</label>
                    <input type="number" step="0.1" name="water_temp" class="form-control @error('water_temp') error @enderror" 
                           value="{{ old('water_temp', $catch->water_temp) }}" placeholder="Contoh: 28.5">
                </div>

                <!-- Notes -->
                <div class="form-group">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-control @error('notes') error @enderror" 
                              placeholder="Tambahkan catatan tambahan...">{{ old('notes', $catch->notes) }}</textarea>
                </div>
            </div>

            <div class="btn-group">
                <a href="{{ route('catches.index') }}" class="btn btn-cancel">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="submit" class="btn btn-submit">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let map;
    let marker;
    let currentRotation = 0;
    let currentFlipH = 1;
    let currentFlipV = 1;
    let photoEdited = false;
    let originalPhoto = null;

    // Initialize map
    function initMap() {
        const lat = parseFloat(document.getElementById('latitudeInput').value) || -6.2088;
        const lng = parseFloat(document.getElementById('longitudeInput').value) || 106.8456;

        map = L.map('editMap').setView([lat, lng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        const markerIcon = L.divIcon({
            html: '<div style="background: #3b82f6; width: 30px; height: 30px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"></div>',
            iconSize: [30, 30],
            iconAnchor: [15, 30]
        });

        marker = L.marker([lat, lng], { 
            icon: markerIcon,
            draggable: true 
        }).addTo(map);

        marker.on('dragend', function(e) {
            const position = marker.getLatLng();
            document.getElementById('latitudeInput').value = position.lat;
            document.getElementById('longitudeInput').value = position.lng;
            reverseGeocode(position.lat, position.lng);
        });

        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            document.getElementById('latitudeInput').value = e.latlng.lat;
            document.getElementById('longitudeInput').value = e.latlng.lng;
            reverseGeocode(e.latlng.lat, e.latlng.lng);
        });

        setTimeout(() => map.invalidateSize(), 100);
    }

    // Get current location
    function getCurrentLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                document.getElementById('latitudeInput').value = lat;
                document.getElementById('longitudeInput').value = lng;
                
                if (map && marker) {
                    map.setView([lat, lng], 15);
                    marker.setLatLng([lat, lng]);
                }
                
                reverseGeocode(lat, lng);
            }, function(error) {
                alert('Tidak dapat mengakses lokasi. Pastikan GPS aktif.');
            });
        }
    }

    // Reverse geocode
    function reverseGeocode(lat, lng) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(response => response.json())
            .then(data => {
                if (data.display_name) {
                    document.getElementById('locationInput').value = data.display_name;
                }
            });
    }

    // Handle photo upload
    function handlePhotoUpload(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const wrapper = document.getElementById('photoPreviewWrapper');
                wrapper.innerHTML = `<img src="${e.target.result}" alt="Preview" class="photo-preview" id="photoPreview">`;
                
                // Reset transformations
                currentRotation = 0;
                currentFlipH = 1;
                currentFlipV = 1;
                photoEdited = false;
                originalPhoto = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }

    // Rotate photo
    function rotatePhoto(direction) {
        const img = document.getElementById('photoPreview');
        if (!img) return;

        if (direction === 'left') {
            currentRotation -= 90;
        } else {
            currentRotation += 90;
        }
        
        applyTransform();
        photoEdited = true;
        document.getElementById('photoEdited').value = '1';
        document.getElementById('photoRotation').value = currentRotation;
    }

    // Flip photo
    function flipPhoto(direction) {
        const img = document.getElementById('photoPreview');
        if (!img) return;

        if (direction === 'horizontal') {
            currentFlipH *= -1;
        } else {
            currentFlipV *= -1;
        }
        
        applyTransform();
        photoEdited = true;
        document.getElementById('photoEdited').value = '1';
        document.getElementById('photoFlipH').value = currentFlipH;
        document.getElementById('photoFlipV').value = currentFlipV;
    }

    // Apply transformation
    function applyTransform() {
        const img = document.getElementById('photoPreview');
        if (img) {
            img.style.transform = `rotate(${currentRotation}deg) scale(${currentFlipH}, ${currentFlipV})`;
            img.style.transition = 'transform 0.3s ease';
        }
    }

    // Remove photo
    function removePhoto() {
        if (confirm('Yakin ingin menghapus foto?')) {
            const wrapper = document.getElementById('photoPreviewWrapper');
            wrapper.innerHTML = `
                <div class="no-photo-placeholder">
                    <i class="fas fa-image"></i>
                    <p>Belum ada foto</p>
                </div>
            `;
            document.getElementById('photoInput').value = '';
            currentRotation = 0;
            currentFlipH = 1;
            currentFlipV = 1;
            photoEdited = false;
        }
    }

    // Handle form submission with photo editing
    document.getElementById('editForm').addEventListener('submit', async function(e) {
        if (photoEdited && document.getElementById('photoPreview')) {
            e.preventDefault();
            
            const img = document.getElementById('photoPreview');
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');

            // Calculate dimensions
            const isRotated = Math.abs(currentRotation % 180) === 90;
            canvas.width = isRotated ? img.naturalHeight : img.naturalWidth;
            canvas.height = isRotated ? img.naturalWidth : img.naturalHeight;

            // Apply transformations
            ctx.translate(canvas.width / 2, canvas.height / 2);
            ctx.rotate((currentRotation * Math.PI) / 180);
            ctx.scale(currentFlipH, currentFlipV);
            ctx.drawImage(img, -img.naturalWidth / 2, -img.naturalHeight / 2, img.naturalWidth, img.naturalHeight);

            // Convert to blob and submit
            canvas.toBlob((blob) => {
                const formData = new FormData(this);
                formData.set('photo', blob, 'edited-photo.jpg');
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(response => {
                    if (response.ok) {
                        window.location.href = "{{ route('catches.index') }}";
                    }
                });
            }, 'image/jpeg', 0.9);
        }
    });

    // Initialize on load
    document.addEventListener('DOMContentLoaded', function() {
        initMap();
    });
</script>
@endpush
@endsection