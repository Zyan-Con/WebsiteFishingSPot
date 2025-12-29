@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
    /* ==================================================================================
       SECTION 1: FORECAST POPUP STYLES (4 POPUP)
       - Weather Forecast Popup
       - Waves Forecast Popup  
       - Tide Forecast Popup
       - Fish Activity Popup
       ================================================================================== */

    /* --- Popup Overlay (Backdrop) --- */
    .popup-overlay-forecast {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0,0,0,0.6);
        backdrop-filter: blur(4px);
        z-index: 10000;
        display: none;
    }

    .popup-overlay-forecast.show {
        display: block !important;
    }

    /* --- Popup Container --- */
    .forecast-popup {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 90%;
        max-width: 900px;
        max-height: 85vh;
        background: white;
        border-radius: 20px;
        box-shadow: 0 25px 80px rgba(0, 0, 0, 0.5);
        z-index: 10001;
        overflow: hidden;
        display: none;
    }

    .forecast-popup.show {
        display: block !important;
        animation: popupSlideUp 0.3s ease-out;
    }

    /* --- Popup Animation --- */
    @keyframes popupSlideUp {
        from {
            opacity: 0;
            transform: translate(-50%, -40%);
        }
        to {
            opacity: 1;
            transform: translate(-50%, -50%);
        }
    }

    /* --- Popup Header Styles --- */
    .popup-header-custom {
        padding: 24px;
        color: white;
        position: relative;
    }

    /* Gradient untuk Weather Forecast */
    .weather-gradient {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    }

    /* Gradient untuk Waves Forecast */
    .waves-gradient {
        background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
    }

    /* Gradient untuk Tide Forecast */
    .tide-gradient {
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
    }

    /* Gradient untuk Fish Activity */
    .fish-gradient {
        background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
    }

    /* --- Popup Title & Subtitle --- */
    .popup-title-custom {
        font-size: 28px;
        font-weight: 700;
        margin: 0 0 8px 0;
    }

    .popup-subtitle-custom {
        font-size: 14px;
        opacity: 0.9;
    }

    /* --- Close Button --- */
    .popup-close-custom {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 40px;
        height: 40px;
        background: rgba(255,255,255,0.2);
        border: none;
        border-radius: 10px;
        color: white;
        font-size: 20px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .popup-close-custom:hover {
        background: rgba(255,255,255,0.3);
        transform: scale(1.1);
    }

    /* --- Popup Content Area --- */
    .popup-content-custom {
        padding: 60px 40px;
        text-align: center;
        max-height: calc(85vh - 120px);
        overflow-y: auto;
    }

    /* --- Placeholder Content --- */
    .popup-placeholder {
        color: #9ca3af;
        font-size: 16px;
        line-height: 2;
    }

    .popup-icon-large {
        font-size: 80px;
        opacity: 0.2;
        margin-bottom: 30px;
    }

    /* ==================================================================================
       SECTION 2: MAP CONTAINER & CONTROLS
       - Main map container
       - Map control buttons (zoom, location, layer)
       - Bottom info bar
       - Floating action button (FAB)
       ================================================================================== */

    /* --- Map Container --- */
    .map-container {
        position: fixed;
        top: 60px;
        left: 280px;
        right: 0;
        bottom: 0;
        width: calc(100vw - 280px);
        height: calc(100vh - 60px);
        overflow: hidden;
    }

    #map {
        width: 100%;
        height: 100%;
        z-index: 1;
    }

    /* --- Map Control Buttons (Right Side) --- */
    .map-controls {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        display: flex;
        flex-direction: column;
        gap: 8px;
        z-index: 1000;
    }

    .map-control-btn {
        width: 44px;
        height: 44px;
        background: white;
        border: none;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: #4b5563;
        transition: all 0.2s;
    }

    .map-control-btn:hover {
        background: #f3f4f6;
        transform: scale(1.05);
    }

    .map-control-btn.active {
        background: #2563eb;
        color: white;
    }

    /* --- Bottom Information Bar --- */
    .bottom-bar {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 60px;
        background: white;
        border-top: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        padding: 0 24px;
        gap: 24px;
        z-index: 1000;
    }

    /* --- Map Legend --- */
    .map-legend {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #6b7280;
    }

    .legend-icon {
        width: 20px;
        height: 20px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* --- Map Info (Coordinates, Zoom, Layer) --- */
    .map-info {
        display: flex;
        gap: 24px;
        margin-left: auto;
        font-size: 13px;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #6b7280;
    }

    /* --- Floating Action Button (Add Location) --- */
    .fab {
        position: absolute;
        bottom: 80px;
        right: 24px;
        width: 56px;
        height: 56px;
        background: #2563eb;
        border-radius: 50%;
        border: none;
        color: white;
        font-size: 24px;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
        transition: all 0.2s;
        z-index: 1000;
    }

    .fab:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.5);
    }

    /* --- Custom Marker Icon --- */
    .custom-marker-icon {
        background: #2563eb;
        width: 30px;
        height: 30px;
        border-radius: 50% 50% 50% 0;
        transform: rotate(-45deg);
        border: 3px solid white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
    }

    .custom-marker-icon::after {
        content: '';
        width: 10px;
        height: 10px;
        background: white;
        border-radius: 50%;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    /* ==================================================================================
       SECTION 3: LOCATION MANAGEMENT MODAL
       - Modal overlay & container
       - Modal header with tabs
       - Form untuk add location
       - Location list display
       ================================================================================== */

    /* --- Modal Overlay (Backdrop) --- */
    .modal-overlay {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        background: rgba(0,0,0,0.6) !important;
        backdrop-filter: blur(4px);
        z-index: 9998 !important;
        display: none;
        animation: fadeIn 0.2s;
    }

    .modal-overlay.show {
        display: block !important;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* --- Modal Container --- */
    .location-modal {
        position: fixed !important;
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) scale(1) !important;
        width: 90%;
        max-width: 600px;
        max-height: 80vh;
        background: white !important;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3) !important;
        z-index: 9999 !important;
        display: none;
        overflow: hidden;
        animation: slideUp 0.3s;
    }

    .location-modal.show {
        display: block !important;
    }

    @keyframes slideUp {
        from { 
            opacity: 0;
            transform: translate(-50%, -40%) scale(1);
        }
        to { 
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }
    }

    /* --- Modal Header --- */
    .modal-header {
        padding: 24px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        position: relative;
    }

    .modal-title {
        font-size: 20px;
        font-weight: 700;
        margin: 0 0 6px 0;
    }

    .modal-subtitle {
        font-size: 13px;
        opacity: 0.9;
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
        transform: scale(1.1);
    }

    /* --- Modal Tabs (Lokasi, Rawai, Tonda) --- */
    .modal-tabs {
        display: flex;
        padding: 0 24px;
        background: white;
        border-bottom: 2px solid #f3f4f6;
        gap: 4px;
    }

    .tab-btn {
        padding: 12px 16px;
        background: none;
        border: none;
        color: #6b7280;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        border-bottom: 3px solid transparent;
        margin-bottom: -2px;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .tab-btn:hover {
        color: #667eea;
    }

    .tab-btn.active {
        color: #667eea;
        border-bottom-color: #667eea;
    }

    /* --- Modal Body --- */
    .modal-body {
        padding: 24px;
        max-height: 50vh;
        overflow-y: auto;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    /* --- Empty State (No Data) --- */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
    }

    .empty-icon {
        font-size: 48px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 16px;
    }

    .empty-title {
        font-size: 16px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 6px;
    }

    .empty-text {
        font-size: 13px;
        color: #6b7280;
    }

    /* --- Add Location Form --- */
    .add-location-form {
        display: none;
    }

    .add-location-form.active {
        display: block;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }

    .form-label .required {
        color: #ef4444;
    }

    .form-input {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s;
        box-sizing: border-box;
    }

    .form-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        resize: vertical;
        min-height: 80px;
        transition: all 0.2s;
        box-sizing: border-box;
    }

    .form-textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .coord-group {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    /* --- Modal Footer (Buttons) --- */
    .modal-footer {
        padding: 20px 24px;
        border-top: 1px solid #f3f4f6;
        display: flex;
        gap: 12px;
        background: #fafafa;
    }

    .btn-cancel {
        flex: 1;
        padding: 12px 16px;
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        color: #4b5563;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-cancel:hover {
        border-color: #9ca3af;
    }

    .btn-save {
        flex: 2;
        padding: 12px 16px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 10px;
        color: white;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    /* --- Location List Display --- */
    .location-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .location-item {
        padding: 16px;
        background: #f9fafb;
        border-radius: 12px;
        border: 2px solid transparent;
        cursor: pointer;
        transition: all 0.2s;
    }

    .location-item:hover {
        border-color: #667eea;
        background: white;
    }

    .location-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 8px;
    }

    .location-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
        flex-shrink: 0;
    }

    .location-info {
        flex: 1;
        min-width: 0;
    }

    .location-name {
        font-size: 15px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 2px;
    }

    .location-coords {
        font-size: 12px;
        color: #6b7280;
    }

    .location-actions {
        display: flex;
        gap: 8px;
        flex-shrink: 0;
    }

    .btn-view, .btn-delete {
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        font-size: 14px;
    }

    .btn-view {
        background: #dbeafe;
        color: #2563eb;
    }

    .btn-view:hover {
        background: #2563eb;
        color: white;
    }

    .btn-delete {
        background: #fee2e2;
        color: #ef4444;
    }

    .btn-delete:hover {
        background: #ef4444;
        color: white;
    }

    .location-description {
        margin: 8px 0 0 52px;
        font-size: 13px;
        color: #6b7280;
        line-height: 1.5;
    }

    /* ==================================================================================
       SECTION 4: FISH ACTIVITY POPUP
       - Main popup container
       - Loading overlay
       - Day selector
       - Activity gauge
       - Chart display
       - Time slots (Major/Minor)
       ================================================================================== */

    .fish-popup {
        position: fixed !important;
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) scale(1) !important;
        width: 90%;
        max-width: 900px;
        max-height: 85vh;
        background: white;
        border-radius: 20px;
        box-shadow: 0 25px 80px rgba(0, 0, 0, 0.5);
        z-index: 10000;
        overflow: hidden;
        animation: slideUpFish 0.3s;
        display: none;
    }

    .fish-popup.show {
        display: block !important;
    }

    @keyframes slideUpFish {
        from {
            opacity: 0;
            transform: translate(-50%, -40%) scale(1);
        }
        to {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }
    }

    /* --- Loading Overlay --- */
    .loading-overlay {
        display: none;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.95);
        z-index: 10001;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 16px;
    }

    .loading-overlay.show {
        display: flex !important;
    }

    .loading-spinner {
        width: 50px;
        height: 50px;
        border: 4px solid #e5e7eb;
        border-top-color: #2563eb;
        border-radius: 50%;
        animation: spinFish 0.8s linear infinite;
    }

    @keyframes spinFish {
        to { transform: rotate(360deg); }
    }

    /* --- Day Card Selector --- */
    .day-card {
        flex: 1;
        min-width: 100px;
        padding: 12px;
        background: #f9fafb;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .day-card:hover {
        border-color: #2563eb;
        background: #eff6ff;
    }

    .day-card.active {
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        color: white;
        border-color: #2563eb;
    }

    /* --- Time Slot Display --- */
    .time-slot {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        background: #f9fafb;
        border-radius: 8px;
        margin-bottom: 8px;
    }

    .time-icon {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 16px;
    }

    /* ==================================================================================
       SECTION 5: TIDE POPUP SPECIFIC STYLES
       - Tide-specific loading
       - Day selector for tide
       - Tide status display
       - Tide type badges (high/low)
       - Tide table
       ================================================================================== */

    /* Day Selector Styles */
    .tide-day-item {
        flex-shrink: 0;
        width: 70px;
        padding: 12px 8px;
        text-align: center;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s;
        border: 2px solid #e5e7eb;
        background: white;
    }

    .tide-day-item:hover {
        border-color: #0ea5e9;
        transform: translateY(-2px);
    }

    .tide-day-item.active {
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
        border-color: #0ea5e9;
        color: white;
    }

    .tide-day-name {
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 4px;
    }

    .tide-day-date {
        font-size: 18px;
        font-weight: 700;
    }

    /* Tide Time Items */
    .tide-time-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .tide-time-item:last-child {
        border-bottom: none;
    }

    .tide-time {
        font-size: 16px;
        font-weight: 700;
        color: #1f2937;
    }

    .tide-height {
        font-size: 14px;
        font-weight: 600;
        color: #6b7280;
    }

    /* Table Styles */
    #tideTable tbody tr {
        border-bottom: 1px solid #f3f4f6;
        transition: background 0.2s;
    }

    #tideTable tbody tr:hover {
        background: #f9fafb;
    }

    #tideTable tbody td {
        padding: 12px;
        font-size: 14px;
        color: #1f2937;
    }

    .tide-type-high {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        background: #d1fae5;
        color: #059669;
        border-radius: 6px;
        font-weight: 600;
        font-size: 12px;
    }

    .tide-type-low {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        background: #fee2e2;
        color: #dc2626;
        border-radius: 6px;
        font-weight: 600;
        font-size: 12px;
    }

    /* ==================================================================================
       SECTION 6: RESPONSIVE DESIGN
       - Mobile adjustments for all popups
       - Touch-friendly sizing
       ================================================================================== */

    @media (max-width: 768px) {
        .map-container {
            left: 0;
            width: 100vw;
        }

        .location-modal, .fish-popup {
            width: 95%;
            max-height: 90vh;
        }

        .coord-group {
            grid-template-columns: 1fr;
        }

        .modal-footer {
            flex-direction: column;
        }

        .btn-cancel, .btn-save {
            flex: 1;
        }

        .fish-popup {
            width: 100% !important;
            height: 100% !important;
            max-height: 100% !important;
            border-radius: 0 !important;
        }
    }
</style>

<!-- ==================================================================================
     HTML STRUCTURE - MAP CONTAINER
     ================================================================================== -->
<div class="map-container">
    <div id="map"></div>

    <!-- Map Control Buttons -->
    <div class="map-controls">
        <button class="map-control-btn" id="myLocationBtn" title="Lokasi Saya">
            <i class="fas fa-location-crosshairs"></i>
        </button>
        <button class="map-control-btn" id="layerBtn" title="Ganti Layer">
            <i class="fas fa-layer-group"></i>
        </button>
        <button class="map-control-btn" id="zoomInBtn" title="Zoom In">
            <i class="fas fa-plus"></i>
        </button>
        <button class="map-control-btn" id="zoomOutBtn" title="Zoom Out">
            <i class="fas fa-minus"></i>
        </button>
    </div>

    <!-- Floating Action Button (Add Location) -->
    <button class="fab" id="addLocationBtn" title="Tambah Lokasi">
        <i class="fas fa-plus"></i>
    </button>

    <!-- Bottom Information Bar -->
    <div class="bottom-bar">
        <div class="map-legend">
            <div class="legend-icon" style="background: #3b82f6;">
                <i class="fas fa-map-pin" style="color: white; font-size: 12px;"></i>
            </div>
            <span>Penanda lokasi mancing</span>
        </div>
        
        <div class="map-info">
            <div class="info-item">
                <i class="fas fa-layer-group"></i>
                <span id="currentLayer">OpenStreetMap</span>
            </div>
            <div class="info-item">
                <i class="fas fa-search-plus"></i>
                <span id="currentZoom">Zoom: 13</span>
            </div>
            <div class="info-item">
                <i class="fas fa-location-dot"></i>
                <span id="currentCoords">-0.9471, 100.4172</span>
            </div>
        </div>
    </div>
</div>

<!-- Modal Overlays -->
<div class="modal-overlay" id="modalOverlay"></div>
<div class="modal-overlay" id="fishModalOverlay"></div>

<!-- ==================================================================================
     LOCATION MANAGEMENT MODAL
     Purpose: Add, view, and manage fishing locations (Lokasi, Rawai, Tonda)
     ================================================================================== -->
<div class="location-modal" id="locationModal">
    <!-- Modal Header -->
    <div class="modal-header">
        <h2 class="modal-title">Kelola Lokasi</h2>
        <p class="modal-subtitle">Kelola lokasi, rawai, dan tonda Anda</p>
        <button class="modal-close" id="closeModalBtn">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Modal Tabs Navigation -->
    <div class="modal-tabs">
        <button class="tab-btn active" data-tab="lokasi">
            <i class="fas fa-map-marker-alt"></i> Lokasi
        </button>
        <button class="tab-btn" data-tab="rawai">
            <i class="fas fa-fish"></i> Pancing Rawai
        </button>
        <button class="tab-btn" data-tab="tonda">
            <i class="fas fa-route"></i> Pancing Tonda
        </button>
    </div>

    <!-- Modal Body Content -->
    <div class="modal-body">
        <!-- Tab: Lokasi -->
        <div id="tab-lokasi" class="tab-content active">
            <div id="locationList" class="location-list" style="display: none;"></div>
            <div id="emptyStateLokasi" class="empty-state">
                <div class="empty-icon"><i class="fas fa-map-marker-alt"></i></div>
                <div class="empty-title">Belum ada lokasi tersimpan</div>
                <div class="empty-text">Mulai simpan lokasi favorit Anda</div>
            </div>
        </div>

        <!-- Form: Add Location -->
        <div id="form-lokasi" class="add-location-form">
            <div class="form-group">
                <label class="form-label">Nama Lokasi <span class="required">*</span></label>
                <input type="text" class="form-input" id="locationName" placeholder="Contoh: Pantai Air Manis">
            </div>
            <div class="form-group">
                <label class="form-label">Koordinat <span class="required">*</span></label>
                <div class="coord-group">
                    <input type="text" class="form-input" id="locationLat" placeholder="Latitude" readonly>
                    <input type="text" class="form-input" id="locationLng" placeholder="Longitude" readonly>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-textarea" id="locationDesc" placeholder="Tambahkan catatan tentang lokasi ini (opsional)..."></textarea>
            </div>
        </div>

        <!-- Tab: Rawai (Empty State) -->
        <div id="tab-rawai" class="tab-content">
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-fish"></i></div>
                <div class="empty-title">Belum ada rawai tersimpan</div>
                <div class="empty-text">Catat lokasi pancing rawai Anda</div>
            </div>
        </div>

        <!-- Tab: Tonda (Empty State) -->
        <div id="tab-tonda" class="tab-content">
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-route"></i></div>
                <div class="empty-title">Belum ada tonda tersimpan</div>
                <div class="empty-text">Rekam jalur pancing tonda</div>
            </div>
        </div>
    </div>

    <!-- Modal Footer (Action Buttons) -->
    <div class="modal-footer">
        <button class="btn-cancel" id="btnCancel">
            <i class="fas fa-times"></i> Batal
        </button>
        <button class="btn-save" id="btnSave">
            <i class="fas fa-plus"></i> <span id="btnSaveText">Tambah Lokasi Baru</span>
        </button>
    </div>
</div>

<!-- ==================================================================================
     FISH ACTIVITY POPUP
     Purpose: Display fish activity forecast with hourly chart and best fishing times
     ================================================================================== -->
<div class="fish-popup" id="fishPopup">
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="fishLoadingOverlay">
        <div class="loading-spinner"></div>
        <div style="font-size: 14px; color: #6b7280;">Loading forecast data...</div>
    </div>

    <!-- Popup Header -->
    <div style="background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%); padding: 24px; color: white; position: relative;">
        <h2 style="font-size: 28px; font-weight: 700; margin-bottom: 4px;">Fish Activity</h2>
        <div style="font-size: 14px; opacity: 0.9; display: flex; align-items: center; gap: 6px;">
            <i class="fas fa-location-dot"></i>
            <span id="fishLocationName">Padang, Indonesia</span>
        </div>
        <button onclick="closeFishPopup()" style="position: absolute; top: 20px; right: 20px; width: 40px; height: 40px; background: rgba(255,255,255,0.2); border: none; border-radius: 10px; color: white; font-size: 20px; cursor: pointer;">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Popup Content -->
    <div style="background: white; max-height: calc(85vh - 100px); overflow-y: auto;">
        <!-- Week Days Selector -->
        <div id="fishWeekDays" style="display: flex; padding: 20px; gap: 12px; overflow-x: auto; border-bottom: 1px solid #e5e7eb;"></div>

        <!-- Activity Gauge & Score -->
        <div style="padding: 40px 20px; text-align: center;">
            <div style="width: 180px; height: 180px; margin: 0 auto 20px; position: relative;">
                <div id="fishGaugeCircle" style="width: 100%; height: 100%; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <div id="fishGaugeScore" style="width: 85%; height: 85%; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 56px; font-weight: 700; color: #1e40af;">14</div>
                </div>
            </div>
            <div id="fishActivityLevel" style="font-size: 22px; font-weight: 700; color: #1f2937; margin-bottom: 8px;">Very low fish activity</div>
            <div style="font-size: 14px; color: #6b7280;">Best fishing times today</div>
        </div>

        <!-- Activity Chart -->
        <div style="padding: 30px 20px; background: #f9fafb;">
            <div style="position: relative; height: 180px; margin-bottom: 20px;">
                <canvas id="fishActivityChart"></canvas>
            </div>
            <div style="display: flex; justify-content: space-between; margin-top: 12px; font-size: 12px; color: #6b7280; font-weight: 600;">
                <span>04:00</span><span>08:00</span><span>12:00</span><span>16:00</span><span>20:00</span>
            </div>
        </div>

        <!-- Major & Minor Fishing Times -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; padding: 20px;">
            <div style="background: white; padding: 20px; border-radius: 12px; border: 2px solid #e5e7eb;">
                <h3 style="font-size: 14px; text-transform: uppercase; color: #6b7280; margin-bottom: 12px; font-weight: 700;">MAJOR TIMES</h3>
                <div id="fishMajorTimes"></div>
            </div>
            <div style="background: white; padding: 20px; border-radius: 12px; border: 2px solid #e5e7eb;">
                <h3 style="font-size: 14px; text-transform: uppercase; color: #6b7280; margin-bottom: 12px; font-weight: 700;">MINOR TIMES</h3>
                <div id="fishMinorTimes"></div>
            </div>
        </div>
    </div>
</div>

<!-- ==================================================================================
     TIDE POPUP
     Purpose: Display tide forecast with high/low tides, chart, and 7-day table
     ================================================================================== -->
<div class="fish-popup" id="tidePopup" style="display: none;">
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="tideLoadingOverlay">
        <div class="loading-spinner"></div>
        <div style="font-size: 14px; color: #6b7280;">Loading tide data...</div>
    </div>

    <!-- Popup Header -->
    <div style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); padding: 24px; color: white; position: relative;">
        <h2 style="font-size: 28px; font-weight: 700; margin-bottom: 4px;">
            <i class="fas fa-water"></i> Tide Forecast
        </h2>
        <div style="font-size: 14px; opacity: 0.9; display: flex; align-items: center; gap: 6px;">
            <i class="fas fa-location-dot"></i>
            <span id="tideLocationName">Padang, Indonesia</span>
        </div>
        <button onclick="closeTidePopup()" style="position: absolute; top: 20px; right: 20px; width: 40px; height: 40px; background: rgba(255,255,255,0.2); border: none; border-radius: 10px; color: white; font-size: 20px; cursor: pointer; transition: all 0.2s;">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Popup Content -->
    <div style="background: white; max-height: calc(85vh - 100px); overflow-y: auto;">
        
        <!-- Week Days Selector -->
        <div id="tideWeekDays" style="display: flex; padding: 20px; gap: 12px; overflow-x: auto; border-bottom: 1px solid #e5e7eb;">
            <!-- Populated by JavaScript -->
        </div>

        <!-- Current Tide Status with Circular Progress -->
        <div style="padding: 40px 20px; text-align: center; border-bottom: 1px solid #e5e7eb;">
            <div style="width: 180px; height: 180px; margin: 0 auto 20px; position: relative;">
                <svg style="width: 100%; height: 100%; transform: rotate(-90deg);">
                    <circle cx="90" cy="90" r="80" fill="none" stroke="#e5e7eb" stroke-width="12"></circle>
                    <circle id="tideProgressCircle" cx="90" cy="90" r="80" fill="none" stroke="#0ea5e9" stroke-width="12" stroke-dasharray="502.4" stroke-dashoffset="251.2" stroke-linecap="round" style="transition: stroke-dashoffset 0.5s ease;"></circle>
                </svg>
                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
                    <div id="tideCurrentHeight" style="font-size: 56px; font-weight: 700; color: #0284c7;">-0.3</div>
                    <div style="font-size: 14px; color: #6b7280; font-weight: 600;">meters</div>
                </div>
            </div>
            <div id="tideStatus" style="font-size: 22px; font-weight: 700; color: #1f2937; margin-bottom: 8px;">
                <i class="fas fa-arrow-up"></i> Tide is Rising
            </div>
            <div style="font-size: 14px; color: #6b7280;">Next high tide in 6h 55min</div>
        </div>

        <!-- Tide Chart -->
        <div style="padding: 30px 20px; background: #f9fafb;">
            <div style="position: relative; height: 250px; margin-bottom: 20px;">
                <canvas id="tideChart"></canvas>
            </div>
            <div style="display: flex; justify-content: space-between; margin-top: 12px; font-size: 12px; color: #6b7280; font-weight: 600;">
                <span>00:00</span><span>06:00</span><span>12:00</span><span>18:00</span><span>24:00</span>
            </div>
        </div>

        <!-- High & Low Tides Times -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; padding: 20px;">
            <div style="background: white; padding: 20px; border-radius: 12px; border: 2px solid #e5e7eb;">
                <h3 style="font-size: 14px; text-transform: uppercase; color: #6b7280; margin-bottom: 12px; font-weight: 700;">
                    <i class="fas fa-arrow-up" style="color: #059669;"></i> HIGH TIDES
                </h3>
                <div id="tideHighTimes">
                    <!-- Populated by JavaScript -->
                </div>
            </div>
            <div style="background: white; padding: 20px; border-radius: 12px; border: 2px solid #e5e7eb;">
                <h3 style="font-size: 14px; text-transform: uppercase; color: #6b7280; margin-bottom: 12px; font-weight: 700;">
                    <i class="fas fa-arrow-down" style="color: #dc2626;"></i> LOW TIDES
                </h3>
                <div id="tideLowTimes">
                    <!-- Populated by JavaScript -->
                </div>
            </div>
        </div>

        <!-- 7-Day Tide Forecast Table -->
        <div style="padding: 20px; background: white;">
            <h3 style="font-size: 16px; font-weight: 700; color: #1f2937; margin-bottom: 16px;">
                <i class="fas fa-calendar-days"></i> 7-Day Forecast
            </h3>
            <div style="overflow-x: auto;">
                <table id="tideTable" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                            <th style="padding: 12px; text-align: left; font-size: 12px; color: #6b7280; font-weight: 700;">DATE</th>
                            <th style="padding: 12px; text-align: left; font-size: 12px; color: #6b7280; font-weight: 700;">TIME</th>
                            <th style="padding: 12px; text-align: left; font-size: 12px; color: #6b7280; font-weight: 700;">TYPE</th>
                            <th style="padding: 12px; text-align: right; font-size: 12px; color: #6b7280; font-weight: 700;">HEIGHT</th>
                        </tr>
                    </thead>
                    <tbody id="tideTableBody">
                        <!-- Populated by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- ==================================================================================
     JAVASCRIPT SECTION
     ================================================================================== -->
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
/* ==================================================================================
   TIDE POPUP FUNCTIONS
   Purpose: Handle tide forecast display, chart rendering, and data management
   ================================================================================== */

let tideChart = null;
let selectedTideDate = new Date();
let tideData = null;

/**
 * Open Tide Popup
 * @param {number} lat - Latitude
 * @param {number} lng - Longitude  
 * @param {string} locationName - Location name to display
 */
function openTidePopup(lat, lng, locationName = 'Padang, Indonesia') {
    document.getElementById('tidePopup').style.display = 'block';
    document.getElementById('tideLocationName').textContent = locationName;
    document.getElementById('tideLoadingOverlay').style.display = 'flex';
    
    loadTideData(lat, lng);
}

/**
 * Close Tide Popup
 */
function closeTidePopup() {
    document.getElementById('tidePopup').style.display = 'none';
}

/**
 * Load Tide Data from API
 * @param {number} lat - Latitude
 * @param {number} lng - Longitude
 */
async function loadTideData(lat, lng) {
    try {
        const response = await fetch(`/api/tide?lat=${lat}&lng=${lng}`);
        const result = await response.json();
        
        if (result.success && result.data) {
            tideData = result.data;
            
            // Hide loading overlay
            document.getElementById('tideLoadingOverlay').style.display = 'none';
            
            // Render all components
            renderTideWeekDays();
            updateTideDisplay();
            createTideChart();
            populateTideTable();
        } else {
            showTideError('Gagal memuat data pasang surut');
        }
    } catch (error) {
        console.error('Error loading tide data:', error);
        showTideError('Terjadi kesalahan saat memuat data');
    }
}

/**
 * Render Week Days Selector
 */
function renderTideWeekDays() {
    const container = document.getElementById('tideWeekDays');
    container.innerHTML = '';
    
    const startDate = new Date();
    const dayNames = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];
    
    for (let i = 0; i < 7; i++) {
        const date = new Date(startDate);
        date.setDate(startDate.getDate() + i);
        
        const isActive = i === 0;
        const dayDiv = document.createElement('div');
        dayDiv.className = 'tide-day-item' + (isActive ? ' active' : '');
        dayDiv.onclick = () => selectTideDay(date, dayDiv);
        
        dayDiv.innerHTML = `
            <div class="tide-day-name">${dayNames[date.getDay()]}</div>
            <div class="tide-day-date">${date.getDate()}</div>
        `;
        
        container.appendChild(dayDiv);
    }
}

/**
 * Select a specific day for tide display
 * @param {Date} date - Selected date
 * @param {HTMLElement} element - Clicked element
 */
function selectTideDay(date, element) {
    selectedTideDate = date;
    
    // Update active state
    document.querySelectorAll('.tide-day-item').forEach(el => el.classList.remove('active'));
    element.classList.add('active');
    
    // Update display
    updateTideDisplay();
    createTideChart();
}

/**
 * Update Tide Display (current status, high/low tides)
 */
function updateTideDisplay() {
    if (!tideData || !tideData.data) return;
    
    const now = new Date();
    const tides = tideData.data;
    
    // Find current/upcoming tides
    const upcomingTides = tides.filter(t => new Date(t.time) > now);
    const nextHigh = upcomingTides.find(t => t.type === 'high');
    const nextLow = upcomingTides.find(t => t.type === 'low');
    
    // Determine if tide is rising or falling
    const isRising = nextHigh && (!nextLow || new Date(nextHigh.time) < new Date(nextLow.time));
    
    // Update status display
    const statusEl = document.getElementById('tideStatus');
    if (isRising) {
        statusEl.innerHTML = '<i class="fas fa-arrow-up" style="color: #059669;"></i> Tide is Rising';
    } else {
        statusEl.innerHTML = '<i class="fas fa-arrow-down" style="color: #dc2626;"></i> Tide is Falling';
    }
    
    // Update current height (interpolate between tides)
    const prevTide = tides.filter(t => new Date(t.time) <= now).pop();
    const nextTide = upcomingTides[0];
    
    if (prevTide && nextTide) {
        const prevTime = new Date(prevTide.time).getTime();
        const nextTime = new Date(nextTide.time).getTime();
        const progress = (now.getTime() - prevTime) / (nextTime - prevTime);
        const currentHeight = prevTide.height + (nextTide.height - prevTide.height) * progress;
        
        document.getElementById('tideCurrentHeight').textContent = currentHeight.toFixed(2);
        
        // Update circular progress indicator
        const maxHeight = Math.max(...tides.map(t => t.height));
        const minHeight = Math.min(...tides.map(t => t.height));
        const heightProgress = (currentHeight - minHeight) / (maxHeight - minHeight);
        const circumference = 502.4;
        const offset = circumference - (heightProgress * circumference);
        document.getElementById('tideProgressCircle').style.strokeDashoffset = offset;
    }
    
    // Get tides for selected day
    const selectedDayStart = new Date(selectedTideDate);
    selectedDayStart.setHours(0, 0, 0, 0);
    const selectedDayEnd = new Date(selectedTideDate);
    selectedDayEnd.setHours(23, 59, 59, 999);
    
    const dayTides = tides.filter(t => {
        const tideTime = new Date(t.time);
        return tideTime >= selectedDayStart && tideTime <= selectedDayEnd;
    });
    
    // Update high tides list
    const highTides = dayTides.filter(t => t.type === 'high');
    const highTimesEl = document.getElementById('tideHighTimes');
    highTimesEl.innerHTML = highTides.length ? highTides.map(t => `
        <div class="tide-time-item">
            <div class="tide-time">${new Date(t.time).toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'})}</div>
            <div class="tide-height">${t.height.toFixed(2)}m</div>
        </div>
    `).join('') : '<div style="color: #9ca3af; font-size: 14px;">No high tides</div>';
    
    // Update low tides list
    const lowTides = dayTides.filter(t => t.type === 'low');
    const lowTimesEl = document.getElementById('tideLowTimes');
    lowTimesEl.innerHTML = lowTides.length ? lowTides.map(t => `
        <div class="tide-time-item">
            <div class="tide-time">${new Date(t.time).toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'})}</div>
            <div class="tide-height">${t.height.toFixed(2)}m</div>
        </div>
    `).join('') : '<div style="color: #9ca3af; font-size: 14px;">No low tides</div>';
}

/**
 * Create Tide Chart using Chart.js
 */
function createTideChart() {
    if (!tideData || !tideData.data) return;
    
    const ctx = document.getElementById('tideChart');
    if (!ctx) return;
    
    // Destroy existing chart
    if (tideChart) {
        tideChart.destroy();
    }
    
    // Filter data for selected day
    const selectedDayStart = new Date(selectedTideDate);
    selectedDayStart.setHours(0, 0, 0, 0);
    const selectedDayEnd = new Date(selectedTideDate);
    selectedDayEnd.setHours(23, 59, 59, 999);
    
    const dayTides = tideData.data.filter(t => {
        const tideTime = new Date(t.time);
        return tideTime >= selectedDayStart && tideTime <= selectedDayEnd;
    });
    
    // Create interpolated data points for smooth curve
    const chartData = [];
    for (let hour = 0; hour <= 24; hour++) {
        const time = new Date(selectedDayStart);
        time.setHours(hour);
        
        // Find surrounding tide points
        const prevTide = dayTides.filter(t => new Date(t.time) <= time).pop();
        const nextTide = dayTides.find(t => new Date(t.time) > time);
        
        if (prevTide && nextTide) {
            const prevTime = new Date(prevTide.time).getTime();
            const nextTime = new Date(nextTide.time).getTime();
            const progress = (time.getTime() - prevTime) / (nextTime - prevTime);
            const height = prevTide.height + (nextTide.height - prevTide.height) * progress;
            
            chartData.push({ x: hour, y: height });
        } else if (prevTide) {
            chartData.push({ x: hour, y: prevTide.height });
        } else if (nextTide) {
            chartData.push({ x: hour, y: nextTide.height });
        }
    }
    
    // Create chart
    tideChart = new Chart(ctx, {
        type: 'line',
        data: {
            datasets: [{
                data: chartData,
                borderColor: '#0ea5e9',
                backgroundColor: (context) => {
                    const ctx = context.chart.ctx;
                    const gradient = ctx.createLinearGradient(0, 0, 0, 250);
                    gradient.addColorStop(0, 'rgba(14, 165, 233, 0.3)');
                    gradient.addColorStop(1, 'rgba(14, 165, 233, 0)');
                    return gradient;
                },
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointRadius: 0,
                pointHoverRadius: 6,
                pointBackgroundColor: '#0ea5e9',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (context) => `${context.parsed.y.toFixed(2)}m`
                    }
                }
            },
            scales: {
                x: {
                    type: 'linear',
                    min: 0,
                    max: 24,
                    ticks: { display: false },
                    grid: { display: false },
                    border: { display: false }
                },
                y: {
                    ticks: { display: false },
                    grid: { color: '#f3f4f6' },
                    border: { display: false }
                }
            }
        }
    });
}

/**
 * Populate 7-Day Tide Table
 */
function populateTideTable() {
    if (!tideData || !tideData.data) return;
    
    const tbody = document.getElementById('tideTableBody');
    tbody.innerHTML = '';
    
    tideData.data.forEach(tide => {
        const time = new Date(tide.time);
        const row = tbody.insertRow();
        
        row.innerHTML = `
            <td style="padding: 12px; font-size: 14px;">
                ${time.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })}
            </td>
            <td style="padding: 12px; font-size: 14px; font-weight: 600;">
                ${time.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}
            </td>
            <td style="padding: 12px;">
                <span class="tide-type-${tide.type}">
                    <i class="fas fa-arrow-${tide.type === 'high' ? 'up' : 'down'}"></i>
                    ${tide.type === 'high' ? 'High' : 'Low'}
                </span>
            </td>
            <td style="padding: 12px; font-size: 16px; font-weight: 700; text-align: right; color: #0284c7;">
                ${tide.height.toFixed(2)}m
            </td>
        `;
    });
}

/**
 * Show Error Message
 * @param {string} message - Error message to display
 */
function showTideError(message) {
    document.getElementById('tideLoadingOverlay').innerHTML = `
        <div style="text-align: center;">
            <i class="fas fa-exclamation-circle" style="font-size: 48px; color: #dc2626; margin-bottom: 16px;"></i>
            <div style="font-size: 16px; color: #1f2937; font-weight: 600;">${message}</div>
        </div>
    `;
}

/* ==================================================================================
   MAP & LOCATION MANAGEMENT FUNCTIONS
   ================================================================================== */

/* ==================================================================================
   MAP & LOCATION MANAGEMENT FUNCTIONS
   Purpose: Handle map initialization, user location, layer switching, and location CRUD
   ================================================================================== */

let map;
let markers = [];
let userMarker = null;
let tempMarker = null;
let currentLayer = 'street';
let currentTab = 'lokasi';
let isAddingLocation = false;
let savedLocations = [];
let tempMarkerLatLng = null;

/**
 * Tile Layer Definitions
 * Available map styles: street, satellite, dark mode
 */
const tileLayers = {
    street: L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }),
    satellite: L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: '© Esri',
        maxZoom: 19
    }),
    dark: L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '© CartoDB',
        maxZoom: 19
    })
};

/**
 * Initialize Leaflet Map
 * Sets up map with default location (Padang), event listeners, and user location tracking
 */
function initMap() {
    // Default location: Padang, Indonesia
    const defaultLocation = [-0.9471, 100.4172];

    // Create map instance
    map = L.map('map', {
        center: defaultLocation,
        zoom: 13,
        zoomControl: false,
        attributionControl: true
    });

    // Add default street layer
    tileLayers.street.addTo(map);

    // Fix map rendering issues
    setTimeout(() => map.invalidateSize(), 100);

    // Update coordinates display on map move
    map.on('move', () => {
        const center = map.getCenter();
        document.getElementById('currentCoords').textContent = 
            `${center.lat.toFixed(4)}, ${center.lng.toFixed(4)}`;
    });

    // Update zoom level display
    map.on('zoomend', () => {
        document.getElementById('currentZoom').textContent = `Zoom: ${map.getZoom()}`;
    });

    // Get and display user's current location
    getUserLocation();
}

/**
 * Get User's Current Location
 * Uses browser Geolocation API to get and display user's position on map
 */
function getUserLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const userLocation = [position.coords.latitude, position.coords.longitude];
                map.setView(userLocation, 15);

                // Remove existing user marker if any
                if (userMarker) {
                    map.removeLayer(userMarker);
                }

                // Create custom icon for user location
                const userIcon = L.divIcon({
                    className: 'custom-user-marker',
                    html: '<div style="background: #4285F4; width: 16px; height: 16px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"></div>',
                    iconSize: [22, 22],
                    iconAnchor: [11, 11]
                });

                // Add user marker to map
                userMarker = L.marker(userLocation, {
                    icon: userIcon,
                    title: 'Lokasi Anda'
                }).addTo(map);

                userMarker.bindPopup('<b>Lokasi Anda</b>').openPopup();
            },
            (error) => console.log('Error getting location:', error)
        );
    }
}

/**
 * Switch Map Layer
 * Cycles between street, satellite, and dark mode layers
 */
function switchLayer() {
    // Remove all layers first
    Object.values(tileLayers).forEach(layer => map.removeLayer(layer));

    // Switch to next layer
    if (currentLayer === 'street') {
        tileLayers.satellite.addTo(map);
        currentLayer = 'satellite';
        document.getElementById('currentLayer').textContent = 'Satellite';
    } else if (currentLayer === 'satellite') {
        tileLayers.dark.addTo(map);
        currentLayer = 'dark';
        document.getElementById('currentLayer').textContent = 'Dark Mode';
    } else {
        tileLayers.street.addTo(map);
        currentLayer = 'street';
        document.getElementById('currentLayer').textContent = 'OpenStreetMap';
    }
}

/**
 * Open Location Management Modal
 * Displays modal with list of saved locations
 */
function openLocationModal() {
    document.getElementById('locationModal').classList.add('show');
    document.getElementById('modalOverlay').classList.add('show');
    renderLocationList();
}

/**
 * Close Location Management Modal
 * Hides modal and cancels any ongoing location addition
 */
function closeLocationModal() {
    document.getElementById('locationModal').classList.remove('show');
    document.getElementById('modalOverlay').classList.remove('show');
    cancelAddLocation();
}

/**
 * Switch Between Tabs
 * @param {string} tabName - Name of tab to switch to (lokasi, rawai, tonda)
 */
function switchTab(tabName) {
    // Remove active class from all tabs
    document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    
    // Activate selected tab
    document.getElementById('tab-' + tabName).classList.add('active');
    event.target.classList.add('active');
    
    currentTab = tabName;
    cancelAddLocation();
}

/**
 * Start Adding New Location
 * Displays form and creates draggable marker on map
 */
function startAddLocation() {
    isAddingLocation = true;
    
    // Hide list, show form
    document.getElementById('locationList').style.display = 'none';
    document.getElementById('emptyStateLokasi').style.display = 'none';
    document.getElementById('form-lokasi').classList.add('active');
    
    // Update button text
    document.getElementById('btnSaveText').textContent = 'Simpan Lokasi';
    document.getElementById('btnSave').innerHTML = '<i class="fas fa-save"></i> <span>Simpan Lokasi</span>';
    
    // Get map center as default location
    const center = map.getCenter();
    tempMarkerLatLng = {lat: center.lat, lng: center.lng};
    
    // Create custom marker icon
    const customIcon = L.divIcon({
        className: 'custom-marker-icon',
        html: '<div class="custom-marker-icon"></div>',
        iconSize: [30, 30],
        iconAnchor: [15, 30],
        popupAnchor: [0, -30]
    });

    // Remove existing temp marker if any
    if (tempMarker) {
        map.removeLayer(tempMarker);
    }

    // Create draggable temporary marker
    tempMarker = L.marker([center.lat, center.lng], {
        icon: customIcon,
        draggable: true,
        title: 'Seret untuk mengatur posisi'
    }).addTo(map);

    // Set initial coordinates in form
    document.getElementById('locationLat').value = center.lat.toFixed(6);
    document.getElementById('locationLng').value = center.lng.toFixed(6);

    // Update coordinates when marker is dragged
    tempMarker.on('dragend', function(e) {
        const pos = e.target.getLatLng();
        tempMarkerLatLng = {lat: pos.lat, lng: pos.lng};
        document.getElementById('locationLat').value = pos.lat.toFixed(6);
        document.getElementById('locationLng').value = pos.lng.toFixed(6);
    });

    // Show instruction popup
    tempMarker.bindPopup('<b>Seret marker untuk mengatur posisi</b><br><small>Lalu isi form dan simpan</small>').openPopup();
    
    // Close modal to allow map interaction
    closeLocationModal();
}

/**
 * Cancel Adding Location
 * Removes temporary marker and resets form
 */
function cancelAddLocation() {
    isAddingLocation = false;
    
    // Remove temporary marker from map
    if (tempMarker) {
        map.removeLayer(tempMarker);
        tempMarker = null;
    }
    
    tempMarkerLatLng = null;
    
    // Hide form and show location list
    document.getElementById('form-lokasi').classList.remove('active');
    renderLocationList();
    
    // Clear form inputs
    document.getElementById('locationName').value = '';
    document.getElementById('locationDesc').value = '';
    document.getElementById('locationLat').value = '';
    document.getElementById('locationLng').value = '';
    
    // Reset button text
    document.getElementById('btnSaveText').textContent = 'Tambah Lokasi Baru';
    document.getElementById('btnSave').innerHTML = '<i class="fas fa-plus"></i> <span>Tambah Lokasi Baru</span>';
}

/**
 * Save Location
 * Validates and saves location to array, adds permanent marker to map
 */
function saveLocation() {
    // If not in adding mode, start adding
    if (!isAddingLocation) {
        startAddLocation();
        return;
    }

    // Get form values
    const name = document.getElementById('locationName').value.trim();
    const lat = tempMarkerLatLng ? tempMarkerLatLng.lat : parseFloat(document.getElementById('locationLat').value);
    const lng = tempMarkerLatLng ? tempMarkerLatLng.lng : parseFloat(document.getElementById('locationLng').value);
    const desc = document.getElementById('locationDesc').value.trim();

    // Validate name is required
    if (!name) {
        alert('Nama lokasi harus diisi!');
        return;
    }

    // Create location object
    const location = {
        id: Date.now(),
        name: name,
        lat: lat,
        lng: lng,
        description: desc,
        createdAt: new Date().toISOString()
    };

    // Add to saved locations array
    savedLocations.push(location);
    
    // Create custom marker icon
    const customIcon = L.divIcon({
        className: 'custom-marker-icon',
        html: '<div class="custom-marker-icon"></div>',
        iconSize: [30, 30],
        iconAnchor: [15, 30],
        popupAnchor: [0, -30]
    });

    // Add permanent marker to map
    const marker = L.marker([lat, lng], {
        icon: customIcon,
        title: name
    }).addTo(map);

    // Create popup content
    const popupContent = `
        <div style="padding: 8px;">
            <h3 style="margin: 0 0 8px 0; font-size: 15px; color: #1f2937;">${name}</h3>
            <p style="margin: 0 0 4px 0; font-size: 12px; color: #6b7280;">
                <i class="fas fa-map-marker-alt"></i> ${lat.toFixed(6)}, ${lng.toFixed(6)}
            </p>
            ${desc ? `<p style="margin: 4px 0 0 0; font-size: 12px; color: #6b7280;">${desc}</p>` : ''}
        </div>
    `;

    marker.bindPopup(popupContent);
    
    // Store marker reference with location ID
    markers.push({ id: location.id, marker: marker });

    // Remove temporary marker
    if (tempMarker) {
        map.removeLayer(tempMarker);
        tempMarker = null;
    }

    // Reset form and show location list
    cancelAddLocation();
    openLocationModal();
    
    alert('✅ Lokasi berhasil disimpan!');
}

/**
 * Render Location List
 * Displays all saved locations in the modal
 */
function renderLocationList() {
    const listContainer = document.getElementById('locationList');
    const emptyState = document.getElementById('emptyStateLokasi');

    // Show empty state if no locations
    if (savedLocations.length === 0) {
        listContainer.style.display = 'none';
        emptyState.style.display = 'block';
    } else {
        listContainer.style.display = 'flex';
        emptyState.style.display = 'none';
        
        // Render location items
        listContainer.innerHTML = savedLocations.map(loc => `
            <div class="location-item">
                <div class="location-header">
                    <div class="location-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="location-info">
                        <div class="location-name">${loc.name}</div>
                        <div class="location-coords">${loc.lat.toFixed(6)}, ${loc.lng.toFixed(6)}</div>
                    </div>
                    <div class="location-actions">
                        <button class="btn-view" onclick="viewLocation(${loc.id})" title="Lihat di peta">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn-delete" onclick="deleteLocation(${loc.id})" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                ${loc.description ? `<div class="location-description">${loc.description}</div>` : ''}
            </div>
        `).join('');
    }
}

/**
 * View Location on Map
 * @param {number} id - Location ID to view
 */
function viewLocation(id) {
    const location = savedLocations.find(loc => loc.id === id);
    if (location) {
        // Center map on location
        map.setView([location.lat, location.lng], 16);
        closeLocationModal();
        
        // Open marker popup
        const markerObj = markers.find(m => m.id === id);
        if (markerObj) {
            markerObj.marker.openPopup();
        }
    }
}

/**
 * Delete Location
 * @param {number} id - Location ID to delete
 */
function deleteLocation(id) {
    if (!confirm('Hapus lokasi ini?')) return;
    
    // Remove from saved locations array
    savedLocations = savedLocations.filter(loc => loc.id !== id);
    
    // Remove marker from map
    const markerObj = markers.find(m => m.id === id);
    if (markerObj) {
        map.removeLayer(markerObj.marker);
        markers = markers.filter(m => m.id !== id);
    }
    
    // Refresh location list
    renderLocationList();
}

/* ==================================================================================
   FISH ACTIVITY POPUP FUNCTIONS
   Purpose: Display fish activity forecast with charts and best fishing times
   ================================================================================== */

let fishForecastData = [];
let fishSelectedDay = 0;
let fishActivityChart = null;

/**
 * Open Fish Activity Popup
 * Initializes and displays fish activity forecast
 */
function openFishPopup() {
    document.getElementById('fishPopup').classList.add('show');
    document.getElementById('fishModalOverlay').classList.add('show');
    loadFishForecastData();
}

/**
 * Close Fish Activity Popup
 */
function closeFishPopup() {
    document.getElementById('fishPopup').classList.remove('show');
    document.getElementById('fishModalOverlay').classList.remove('show');
}

/**
 * Show Loading Overlay
 */
function showFishLoading() {
    document.getElementById('fishLoadingOverlay').classList.add('show');
}

/**
 * Hide Loading Overlay
 */
function hideFishLoading() {
    document.getElementById('fishLoadingOverlay').classList.remove('show');
}

/**
 * Load Fish Forecast Data
 * Fetches or generates fish activity data for 7 days
 */
async function loadFishForecastData() {
    showFishLoading();
    
    try {
        const center = map.getCenter();
        
        // Generate mock data for demo (replace with real API call)
        fishForecastData = Array.from({length: 7}, (_, i) => {
            const date = new Date();
            date.setDate(date.getDate() + i);
            return {
                date: date,
                score: Math.floor(Math.random() * 40) + 60, // Score 60-100
                level: {text: i === 0 ? 'Good fish activity' : 'Moderate fish activity'},
                hourlyData: Array.from({length: 24}, () => Math.floor(Math.random() * 50) + 30),
                majorTimes: [
                    {start: '06:00', end: '08:30', duration: '2h 30m'},
                    {start: '18:00', end: '20:00', duration: '2h'}
                ],
                minorTimes: [
                    {start: '00:00', end: '01:00', duration: '1h'},
                    {start: '12:00', end: '13:30', duration: '1h 30m'}
                ]
            };
        });
        
        // Update location name
        document.getElementById('fishLocationName').textContent = 'Padang, Indonesia';
        
        // Render UI components
        renderFishWeekDays();
        displayFishDayForecast(0);
        
    } catch (error) {
        console.error('Error loading fish forecast:', error);
        alert('Failed to load fish activity data. Please try again.');
    } finally {
        hideFishLoading();
    }
}

/**
 * Render Week Days Selector
 * Creates day cards for 7-day forecast
 */
function renderFishWeekDays() {
    const container = document.getElementById('fishWeekDays');
    const dayNames = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];
    
    container.innerHTML = fishForecastData.map((day, index) => {
        const isToday = index === 0;
        return `
            <div class="day-card ${index === fishSelectedDay ? 'active' : ''}" onclick="selectFishDay(${index})">
                <div style="font-size: 24px; font-weight: 700; margin-bottom: 4px;">${day.date.getDate()}</div>
                <div style="font-size: 12px; text-transform: uppercase; font-weight: 600; opacity: 0.8;">${isToday ? 'TODAY' : dayNames[day.date.getDay()]}</div>
            </div>
        `;
    }).join('');
}

/**
 * Select Fish Activity Day
 * @param {number} index - Day index (0-6)
 */
function selectFishDay(index) {
    fishSelectedDay = index;
    renderFishWeekDays();
    displayFishDayForecast(index);
}

/**
 * Display Fish Day Forecast
 * @param {number} index - Day index to display
 */
function displayFishDayForecast(index) {
    const day = fishForecastData[index];
    
    // Update activity gauge (circular progress)
    const gaugeCircle = document.getElementById('fishGaugeCircle');
    gaugeCircle.style.background = `conic-gradient(
        #3b82f6 0deg,
        #3b82f6 ${day.score * 3.6}deg,
        #e5e7eb ${day.score * 3.6}deg
    )`;
    document.getElementById('fishGaugeScore').textContent = day.score;
    
    // Update activity level text
    document.getElementById('fishActivityLevel').textContent = day.level.text;
    
    // Draw hourly activity chart
    drawFishActivityChart(day.hourlyData);
    
    // Render major/minor fishing times
    renderFishTimes(day);
}

/**
 * Draw Fish Activity Chart
 * @param {Array} hourlyData - Array of 24 hourly activity values
 */
function drawFishActivityChart(hourlyData) {
    const canvas = document.getElementById('fishActivityChart');
    const ctx = canvas.getContext('2d');
    
    // Set canvas size for retina display
    canvas.width = canvas.offsetWidth * 2;
    canvas.height = canvas.offsetHeight * 2;
    ctx.scale(2, 2);
    
    const width = canvas.offsetWidth;
    const height = canvas.offsetHeight;
    const padding = 20;
    
    // Clear canvas
    ctx.clearRect(0, 0, width, height);
    
    // Draw horizontal grid lines
    ctx.strokeStyle = '#e5e7eb';
    ctx.lineWidth = 1;
    ctx.setLineDash([5, 5]);
    
    [0.25, 0.5, 0.75].forEach(ratio => {
        const y = padding + (height - 2 * padding) * ratio;
        ctx.beginPath();
        ctx.moveTo(padding, y);
        ctx.lineTo(width - padding, y);
        ctx.stroke();
    });
    
    // Draw activity line
    ctx.strokeStyle = '#3b82f6';
    ctx.lineWidth = 3;
    ctx.setLineDash([]);
    ctx.beginPath();
    
    const pointSpacing = (width - 2 * padding) / (hourlyData.length - 1);
    
    hourlyData.forEach((value, i) => {
        const x = padding + i * pointSpacing;
        const y = padding + (height - 2 * padding) * (1 - value / 100);
        
        if (i === 0) {
            ctx.moveTo(x, y);
        } else {
            ctx.lineTo(x, y);
        }
    });
    
    ctx.stroke();
    
    // Draw current hour marker
    const currentHour = new Date().getHours();
    const markerX = padding + currentHour * pointSpacing;
    const markerY = padding + (height - 2 * padding) * (1 - hourlyData[currentHour] / 100);
    
    ctx.fillStyle = '#ef4444';
    ctx.beginPath();
    ctx.arc(markerX, markerY, 6, 0, Math.PI * 2);
    ctx.fill();
}

/**
 * Render Fish Times (Major and Minor)
 * @param {Object} day - Day data containing major and minor times
 */
function renderFishTimes(day) {
    // Render major fishing times
    document.getElementById('fishMajorTimes').innerHTML = day.majorTimes.map(time => `
        <div class="time-slot">
            <div class="time-icon">
                <i class="fas fa-sun"></i>
            </div>
            <div>
                <div style="font-size: 16px; font-weight: 700; color: #1f2937;">${time.start} - ${time.end}</div>
                <div style="font-size: 12px; color: #6b7280;">${time.duration}</div>
            </div>
        </div>
    `).join('');
    
    // Render minor fishing times
    document.getElementById('fishMinorTimes').innerHTML = day.minorTimes.map(time => `
        <div class="time-slot">
            <div class="time-icon">
                <i class="fas fa-moon"></i>
            </div>
            <div>
                <div style="font-size: 16px; font-weight: 700; color: #1f2937;">${time.start} - ${time.end}</div>
                <div style="font-size: 12px; color: #6b7280;">${time.duration}</div>
            </div>
        </div>
    `).join('');
}

/* ==================================================================================
   EVENT LISTENERS & INITIALIZATION
   Purpose: Setup all event listeners and initialize map on page load
   ================================================================================== */

// Map control event listeners
document.getElementById('myLocationBtn').addEventListener('click', getUserLocation);
document.getElementById('layerBtn').addEventListener('click', switchLayer);
document.getElementById('zoomInBtn').addEventListener('click', () => map.zoomIn());
document.getElementById('zoomOutBtn').addEventListener('click', () => map.zoomOut());

// FAB button to add location
document.getElementById('addLocationBtn').addEventListener('click', () => {
    openLocationModal();
    setTimeout(startAddLocation, 100);
});

// Modal close event listeners
document.getElementById('closeModalBtn').addEventListener('click', closeLocationModal);
document.getElementById('modalOverlay').addEventListener('click', closeLocationModal);
document.getElementById('fishModalOverlay').addEventListener('click', closeFishPopup);

// Modal action buttons
document.getElementById('btnCancel').addEventListener('click', () => {
    if (isAddingLocation) {
        cancelAddLocation();
        openLocationModal();
    } else {
        closeLocationModal();
    }
});

document.getElementById('btnSave').addEventListener('click', saveLocation);

// Tab switching event listeners
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        switchTab(this.dataset.tab);
    });
});

// Initialize map on page load
window.addEventListener('load', () => setTimeout(initMap, 200));

// Make functions globally accessible
window.openLocationModal = openLocationModal;
window.closeLocationModal = closeLocationModal;
window.viewLocation = viewLocation;
window.deleteLocation = deleteLocation;
window.openFishPopup = openFishPopup;
window.closeFishPopup = closeFishPopup;
window.selectFishDay = selectFishDay;
window.openTidePopup = openTidePopup;
window.closeTidePopup = closeTidePopup;
</script>
@endpush
@endsection  