@extends('layouts.app')

@section('title', 'Tambah Data Penangkapan')

@section('content')
<style>
    .form-container {
        max-width: 600px;
        margin: 16px auto;
        padding: 0 12px;
    }

    .form-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        max-height: calc(100vh - 120px);
        overflow-y: auto;
        overflow-x: hidden;
    }

    /* Custom Scrollbar */
    .form-card::-webkit-scrollbar {
        width: 8px;
    }

    .form-card::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .form-card::-webkit-scrollbar-thumb {
        background: #667eea;
        border-radius: 10px;
    }

    .form-card::-webkit-scrollbar-thumb:hover {
        background: #5568d3;
    }

    .form-header {
        margin-bottom: 16px;
    }

    .form-title {
        font-size: 18px;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 2px 0;
    }

    .form-subtitle {
        font-size: 12px;
        color: #6b7280;
        margin: 0;
    }

    .form-group {
        margin-bottom: 12px;
    }

    .form-label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 4px;
    }

    .required {
        color: #ef4444;
    }

    .form-input, .form-select, .form-textarea {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        font-size: 13px;
        transition: border 0.2s;
    }

    .form-input:focus, .form-select:focus, .form-textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.08);
    }

    .form-textarea {
        resize: vertical;
        min-height: 60px;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    .form-row-3 {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 10px;
    }

    .form-hint {
        font-size: 10px;
        color: #9ca3af;
        margin-top: 2px;
    }

    .section {
        background: #f9fafb;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 12px;
    }

    .section-title {
        font-size: 11px;
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .photo-upload {
        border: 1px dashed #d1d5db;
        border-radius: 6px;
        padding: 12px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        background: #fafafa;
    }

    .photo-upload:hover {
        border-color: #667eea;
        background: #f3f4f6;
    }

    .photo-upload-icon {
        font-size: 24px;
        color: #9ca3af;
        margin-bottom: 4px;
    }

    .photo-upload-text {
        margin: 0;
        color: #6b7280;
        font-size: 12px;
        font-weight: 500;
    }

    .photo-preview {
        display: none;
        margin-top: 8px;
        border-radius: 6px;
        overflow: hidden;
        max-height: 150px;
    }

    .photo-preview img {
        width: 100%;
        height: auto;
        object-fit: cover;
    }

    .form-actions {
        display: flex;
        gap: 8px;
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid #f3f4f6;
        position: sticky;
        bottom: 0;
        background: white;
        z-index: 10;
    }

    .btn {
        flex: 1;
        padding: 10px 16px;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-decoration: none;
    }

    .btn-cancel {
        background: white;
        border: 1px solid #e5e7eb;
        color: #4b5563;
    }

    .btn-cancel:hover {
        border-color: #9ca3af;
        background: #f9fafb;
    }

    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
    }

    .btn-location {
        padding: 6px 10px;
        background: #667eea;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 11px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.2s;
        font-weight: 500;
        margin-top: 6px;
    }

    .btn-location:hover {
        background: #5568d3;
        transform: scale(1.02);
    }

    /* Mobile Responsive */
    @media (max-width: 640px) {
        .form-container {
            margin: 8px auto;
            padding: 0 8px;
        }

        .form-card {
            padding: 16px;
            border-radius: 8px;
            max-height: calc(100vh - 80px);
        }

        .form-title {
            font-size: 16px;
        }

        .form-subtitle {
            font-size: 11px;
        }

        .form-row, .form-row-3 {
            grid-template-columns: 1fr;
            gap: 8px;
        }

        .form-group {
            margin-bottom: 10px;
        }

        .section {
            padding: 10px;
            margin-bottom: 10px;
        }

        .form-input, .form-select, .form-textarea {
            font-size: 14px; /* Lebih besar di mobile untuk kemudahan ketik */
            padding: 10px 12px;
        }

        .btn {
            padding: 12px 16px;
            font-size: 14px;
        }

        .form-actions {
            margin-top: 12px;
            padding-top: 12px;
            padding-bottom: 8px;
        }

        .photo-preview {
            max-height: 200px;
        }
    }

    /* Extra small devices */
    @media (max-width: 360px) {
        .form-card {
            padding: 12px;
        }

        .form-title {
            font-size: 15px;
        }

        .btn {
            padding: 10px 12px;
            font-size: 13px;
            gap: 4px;
        }
    }

    /* Tablet */
    @media (min-width: 641px) and (max-width: 1024px) {
        .form-container {
            max-width: 550px;
        }
    }
</style>

<div class="form-container">
    <div class="form-card">
        <div class="form-header">
            <h1 class="form-title">🎣 Tambah Tangkapan</h1>
            <p class="form-subtitle">Catat hasil tangkapan Anda</p>
        </div>

        <form action="{{ route('catches.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Data Ikan -->
            <div class="form-row-3">
                <div class="form-group">
                    <label class="form-label">Jenis Ikan <span class="required">*</span></label>
                    <input type="text" name="fish_species" class="form-input" 
                           placeholder="Tuna, Kakap..." 
                           value="{{ old('fish_species') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Jumlah <span class="required">*</span></label>
                    <input type="number" name="quantity" class="form-input" 
                           placeholder="Ekor" 
                           value="{{ old('quantity', 1) }}" min="1" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Berat (kg) <span class="required">*</span></label>
                    <input type="number" name="weight" class="form-input" 
                           placeholder="2.5" 
                           value="{{ old('weight') }}" step="0.01" min="0" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal <span class="required">*</span></label>
                    <input type="date" name="catch_date" class="form-input" 
                           value="{{ old('catch_date', date('Y-m-d')) }}" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Waktu</label>
                    <input type="time" name="catch_time" class="form-input" 
                           value="{{ old('catch_time') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Metode</label>
                    <select name="fishing_method" class="form-select">
                        <option value="">Pilih</option>
                        <option value="Rawai">Rawai</option>
                        <option value="Tonda">Tonda</option>
                        <option value="Jaring">Jaring</option>
                        <option value="Bubu">Bubu</option>
                    </select>
                </div>
            </div>

            <!-- Lokasi -->
            <div class="section">
                <div class="section-title">
                    <i class="fas fa-map-marker-alt"></i> Lokasi
                </div>
                
                <div class="form-group" style="margin-bottom: 8px;">
                    <input type="text" name="location_name" id="locationName" class="form-input" 
                           placeholder="Nama lokasi" 
                           value="{{ old('location_name') }}">
                </div>

                <div class="form-row">
                    <input type="text" name="latitude" id="latitude" class="form-input" 
                           placeholder="Latitude" value="{{ old('latitude') }}">
                    <input type="text" name="longitude" id="longitude" class="form-input" 
                           placeholder="Longitude" value="{{ old('longitude') }}">
                </div>

                <button type="button" class="btn-location" onclick="getCurrentLocation(event)">
                    <i class="fas fa-crosshairs"></i> Lokasi Saat Ini
                </button>
            </div>

            <!-- Info Tambahan -->
            <div class="section">
                <div class="section-title">
                    <i class="fas fa-info-circle"></i> Info Lainnya
                </div>

                <div class="form-row" style="margin-bottom: 8px;">
                    <select name="weather" class="form-select">
                        <option value="">Cuaca</option>
                        <option value="Cerah">☀️ Cerah</option>
                        <option value="Berawan">⛅ Berawan</option>
                        <option value="Mendung">☁️ Mendung</option>
                        <option value="Hujan">🌧️ Hujan</option>
                    </select>
                    <input type="number" name="water_temp" class="form-input" 
                           placeholder="Suhu air (°C)" 
                           value="{{ old('water_temp') }}" step="0.1">
                </div>

                <textarea name="notes" class="form-textarea" 
                          placeholder="Catatan...">{{ old('notes') }}</textarea>
            </div>

            <!-- Foto -->
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-camera"></i> Foto
                </label>
                <div class="photo-upload" onclick="document.getElementById('photoInput').click()">
                    <div class="photo-upload-icon">
                        <i class="fas fa-image"></i>
                    </div>
                    <p class="photo-upload-text">Klik untuk upload</p>
                    <p class="form-hint">Maks 5MB</p>
                </div>
                <input type="file" id="photoInput" name="photo" accept="image/*" 
                       style="display: none;" onchange="previewPhoto(event)">
                
                <div id="photoPreview" class="photo-preview">
                    <img id="previewImg" src="" alt="Preview">
                </div>
            </div>

            <!-- Actions -->
            <div class="form-actions">
                <a href="{{ route('catches.index') }}" class="btn btn-cancel">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="submit" class="btn btn-submit">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewPhoto(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('photoPreview').style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
}

function getCurrentLocation(e) {
    if (navigator.geolocation) {
        const btn = e && e.target ? e.target.closest('button') : document.querySelector('.btn-location');
        const originalHTML = btn ? btn.innerHTML : '';
        if (btn) {
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengambil...';
            btn.disabled = true;
        }

        navigator.geolocation.getCurrentPosition(
            (position) => {
                document.getElementById('latitude').value = position.coords.latitude.toFixed(6);
                document.getElementById('longitude').value = position.coords.longitude.toFixed(6);
                
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${position.coords.latitude}&lon=${position.coords.longitude}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.display_name) {
                            const parts = data.display_name.split(',');
                            document.getElementById('locationName').value = parts.slice(0, 2).join(',').trim();
                        }
                    })
                    .catch(() => {})
                    .finally(() => {
                        if (btn) {
                            btn.innerHTML = originalHTML;
                            btn.disabled = false;
                        }
                    });
                
                alert('✅ Lokasi berhasil diambil!');
            },
            () => {
                alert('⚠️ Tidak dapat mengambil lokasi');
                btn.innerHTML = originalHTML;
                btn.disabled = false;
            }
        );
    } else {
        alert('⚠️ Browser tidak mendukung geolocation');
    }
}
</script>
@endsection