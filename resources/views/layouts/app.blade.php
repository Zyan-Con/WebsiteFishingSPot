<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Laravel') }}</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* ==================================================================================
           POPUP MODAL STYLES - Universal untuk semua forecast pop-ups
           ================================================================================== */

        /* Overlay Background */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 9998;
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modal-overlay.active {
            display: block;
            animation: fadeIn 0.3s forwards;
        }

        /* Fish/Tide/Wave/Weather Popup Container */
        .fish-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.7);
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            z-index: 9999;
            display: none;
            opacity: 0;
            overflow: hidden;
        }

        .fish-popup.active {
            display: block;
            animation: popupSlideIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        /* Loading Overlay inside Popup */
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.95);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 10;
            gap: 16px;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #e5e7eb;
            border-top-color: #3b82f6;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        /* Close Button Hover Effect */
        .fish-popup button[onclick*="close"]:hover {
            background: rgba(255, 255, 255, 0.3) !important;
            transform: rotate(90deg);
            transition: all 0.3s ease;
        }

        /* Scrollbar Styling */
        .fish-popup > div:nth-child(2) {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f1f5f9;
        }

        .fish-popup > div:nth-child(2)::-webkit-scrollbar {
            width: 8px;
        }

        .fish-popup > div:nth-child(2)::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .fish-popup > div:nth-child(2)::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .fish-popup > div:nth-child(2)::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Day Selector Buttons */
        .day-selector-btn {
            min-width: 80px;
            padding: 12px 16px;
            background: #f3f4f6;
            border: 2px solid transparent;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
        }

        .day-selector-btn:hover {
            background: #e5e7eb;
            transform: translateY(-2px);
        }

        .day-selector-btn.active {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border-color: #1d4ed8;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

        /* Gauge Circle Animation */
        #fishGaugeCircle {
            background: conic-gradient(
                #3b82f6 0deg,
                #3b82f6 calc(var(--score, 14) * 3.6deg),
                #e5e7eb calc(var(--score, 14) * 3.6deg)
            );
            transition: background 0.6s ease;
        }

        /* Time Block Styling */
        .time-block {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .time-block:last-child {
            border-bottom: none;
        }

        .time-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
        }

        /* Tide Table Styling */
        #tideTable tbody tr {
            border-bottom: 1px solid #f3f4f6;
            transition: background 0.2s;
        }

        #tideTable tbody tr:hover {
            background: #f9fafb;
        }

        #tideTable tbody td {
            padding: 14px 12px;
        }

        /* Badge Styling */
        .tide-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .tide-badge.high {
            background: #d1fae5;
            color: #065f46;
        }

        .tide-badge.low {
            background: #fee2e2;
            color: #991b1b;
        }

        /* ==================================================================================
           ANIMATIONS
           ================================================================================== */

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes popupSlideIn {
            0% {
                opacity: 0;
                transform: translate(-50%, -50%) scale(0.7);
            }
            100% {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1);
            }
        }

        @keyframes popupSlideOut {
            0% {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1);
            }
            100% {
                opacity: 0;
                transform: translate(-50%, -50%) scale(0.7);
            }
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Close Animation Class */
        .fish-popup.closing {
            animation: popupSlideOut 0.3s ease forwards;
        }

        .modal-overlay.closing {
            animation: fadeOut 0.3s ease forwards;
        }

        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }

        /* ==================================================================================
           RESPONSIVE DESIGN
           ================================================================================== */

        @media (max-width: 768px) {
            .fish-popup {
                width: 95%;
                max-height: 95vh;
            }
            
            .day-selector-btn {
                min-width: 70px;
                padding: 10px 12px;
            }
            
            #fishGaugeScore, #tideCurrentHeight {
                font-size: 42px !important;
            }
        }

        /* ==================================================================================
           LAYOUT UTAMA - JANGAN DIHAPUS!
           ================================================================================== */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            width: 100%;
            overflow: hidden;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        #app {
            width: 100vw;
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .container {
            display: flex;
            height: 100vh;
            width: 100vw;
            overflow: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            min-width: 280px;
            background: #fff;
            border-right: 1px solid #e5e7eb;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 100;
        }

        .logo {
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }

        .logo-text {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
        }

        .badge-new {
            background: #10b981;
            color: white;
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 12px;
            font-weight: 600;
            margin-left: auto;
        }

        /* CTA Banner */
        .cta-banner {
            margin: 16px;
            padding: 20px;
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            border-radius: 12px;
            color: #78350f;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .cta-banner:hover {
            transform: translateY(-2px);
        }

        .cta-icon {
            font-size: 24px;
            margin-bottom: 8px;
        }

        .cta-title {
            font-weight: 600;
            font-size: 15px;
            margin-bottom: 4px;
        }

        .cta-subtitle {
            font-size: 13px;
            opacity: 0.9;
        }

        /* Menu Section */
        .menu-section {
            padding: 8px 0;
        }

        .menu-label {
            padding: 12px 20px 8px;
            font-size: 11px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .menu-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #4b5563;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
            text-decoration: none;
        }

        .menu-item:hover {
            background: #f3f4f6;
            color: #1f2937;
        }

        .menu-item.active {
            background: #eff6ff;
            color: #2563eb;
            font-weight: 500;
        }

        .menu-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: #2563eb;
        }

        .menu-icon {
            width: 20px;
            text-align: center;
            font-size: 18px;
        }

        .menu-text {
            flex: 1;
            font-size: 14px;
        }

        .menu-count {
            background: #e5e7eb;
            color: #6b7280;
            font-size: 12px;
            padding: 2px 8px;
            border-radius: 10px;
            font-weight: 500;
        }

        .menu-count.active {
            background: #dbeafe;
            color: #2563eb;
        }

        /* User Profile */
        .user-profile {
            margin-top: auto;
            padding: 16px 20px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .user-profile:hover {
            background: #f9fafb;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 16px;
        }

        .user-info {
            flex: 1;
        }

        .user-name {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
        }

        .user-status {
            font-size: 12px;
            color: #10b981;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            margin-left: 280px;
            height: 100vh;
            overflow: hidden;
        }

        /* Top Bar */
        .top-bar {
            height: 60px;
            min-height: 60px;
            background: white;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            padding: 0 24px;
            gap: 16px;
            position: fixed;
            top: 0;
            left: 280px;
            right: 0;
            z-index: 50;
        }

        .search-box {
            flex: 1;
            max-width: 400px;
            position: relative;
        }

        .search-box input {
            width: 100%;
            padding: 10px 16px 10px 40px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s;
        }

        .search-box input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }

        /* Search Results Dropdown */
        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #e5e7eb;
            border-top: none;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            max-height: 300px;
            overflow-y: auto;
            display: none;
            z-index: 1000;
            margin-top: 4px;
        }

        .search-results.show {
            display: block;
        }

        .search-result-item {
            padding: 12px 16px;
            cursor: pointer;
            transition: background 0.2s;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .search-result-item:last-child {
            border-bottom: none;
        }

        .search-result-item:hover {
            background: #f9fafb;
        }

        .search-result-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
            flex-shrink: 0;
        }

        .search-result-content {
            flex: 1;
            min-width: 0;
        }

        .search-result-title {
            font-size: 14px;
            color: #1f2937;
            font-weight: 500;
            margin-bottom: 2px;
        }

        .search-result-subtitle {
            font-size: 12px;
            color: #6b7280;
        }

        .search-no-results {
            padding: 20px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }

        .top-bar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-left: auto;
        }

        .icon-button {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            border: none;
            background: #f3f4f6;
            color: #4b5563;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            position: relative;
            text-decoration: none;
        }

        .icon-button:hover {
            background: #e5e7eb;
        }

        .notification-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 8px;
            height: 8px;
            background: #ef4444;
            border-radius: 50%;
            border: 2px solid white;
        }

        /* Content Area */
        .content-area {
            flex: 1;
            position: relative;
            overflow: hidden;
            background: #f9fafb;
            margin-top: 60px;
        }

        /* CTA Banner Link */
        .cta-banner-link {
            text-decoration: none;
            display: block;
        }

        .animated-banner {
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .animated-banner::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .animated-banner:hover::before {
            left: 100%;
        }

        .animated-banner:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
        }

        .animated-banner:active {
            transform: scale(0.98);
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -280px;
                transition: left 0.3s;
                z-index: 100;
            }

            .sidebar.open {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .top-bar {
                left: 0;
            }

            .search-box {
                max-width: none;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div id="app">
        <div class="container">
            <!-- Sidebar -->
            <div class="sidebar">
                <div class="logo">
                    <div class="logo-icon">
                        <i class="fas fa-anchor"></i>
                    </div>
                    <div class="logo-text">{{ config('app.name', 'FishingApp') }}</div>
                    <span class="badge-new">Baru</span>
                </div>

                <!-- CTA Banner -->
                <a href="{{ route('premium.index') }}" class="cta-banner-link">
                    <div class="cta-banner animated-banner">
                        <div class="cta-icon">🌟</div>
                        <div class="cta-title">Mulai uji coba gratis</div>
                        <div class="cta-subtitle">7 hari</div>
                    </div>
                </a>

                <!-- Menu -->
                <div class="menu-section">
                    <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home menu-icon"></i>
                        <span class="menu-text">Dashboard</span>
                    </a>
                    <a href="{{ route('locations.index') }}" class="menu-item {{ request()->routeIs('locations.*') ? 'active' : '' }}">
                        <i class="fas fa-location-dot menu-icon"></i>
                        <span class="menu-text">Lokasi</span>
                        <span class="menu-count {{ request()->routeIs('locations.*') ? 'active' : '' }}" id="locationCount">0</span>
                    </a>
                    <a href="{{ route('catches.index') }}" class="menu-item {{ request()->routeIs('catches.*') ? 'active' : '' }}">
                        <i class="fas fa-fish menu-icon"></i>
                        <span class="menu-text">Tangkapan</span>
                        <span class="menu-count {{ request()->routeIs('catches.*') ? 'active' : '' }}">0</span>
                    </a>
                </div>

                <!-- Prakiraan Menu -->
                <div class="menu-section">
                    <div class="menu-label">Prakiraan</div>
                    
                    <a href="javascript:void(0)" onclick="openActivityModal()" class="menu-item {{ request()->routeIs('forecast.activity') ? 'active' : '' }}">
                        <i class="fas fa-calendar-days menu-icon"></i>
                        <span class="menu-text">Aktivitas ikan</span>
                    </a>
                    
                    <a href="javascript:void(0)" onclick="openTideModal()" class="menu-item {{ request()->routeIs('forecast.tide') ? 'active' : '' }}">
                        <i class="fas fa-chart-line menu-icon"></i>
                        <span class="menu-text">Pasang</span>
                    </a>
                    
                    <a href="javascript:void(0)" onclick="openWaveModal()" class="menu-item {{ request()->routeIs('forecast.wave') ? 'active' : '' }}">
                        <i class="fas fa-water menu-icon"></i>
                        <span class="menu-text">Gelombang</span>
                    </a>
                    
                    <a href="javascript:void(0)" onclick="openWeatherModal()" class="menu-item {{ request()->routeIs('forecast.weather') ? 'active' : '' }}">
                        <i class="fas fa-cloud-sun menu-icon"></i>
                        <span class="menu-text">Cuaca</span>
                    </a>
                </div>

                <!-- User Profile -->
                <div class="user-profile">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
                    </div>
                    <div class="user-info">
                        <div class="user-name">{{ Auth::user()->name ?? 'User' }}</div>
                        <div class="user-status">● Online</div>
                    </div>
                    <i class="fas fa-ellipsis-vertical" style="color: #9ca3af;"></i>
                </div>
            </div>

            <!-- Main Content -->
            <div class="main-content">
                <!-- Top Bar -->
                <div class="top-bar">
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="searchInput" placeholder="Cari lokasi, tangkapan, atau prakiraan..." autocomplete="off">
                        <div class="search-results" id="searchResults"></div>
                    </div>
                    <div class="top-bar-actions">
                        <button class="icon-button">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge"></span>
                        </button>
                        <a href="{{ route('settings') }}" class="icon-button">
                            <i class="fas fa-cog"></i>
                        </a>
                    </div>
                </div>

                <!-- Content Area -->
                <div class="content-area">
                    @yield('content')
                </div>
            </div>
        </div>

        <!-- ====== SEMUA MODAL TARUH DI SINI (HANYA SEKALI!) ====== -->
        @include('forecast.activity-modal')
        @include('forecast.tide-modal')
        @include('forecast.wave-modal')
        @include('forecast.weather-modal')
    </div>

    <!-- Leaflet JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    @stack('scripts')
  <script>
        // ===== MODAL FUNCTIONS =====
        function openActivityModal() {
            console.log('Opening activity modal...');
            const overlay = document.getElementById('activityModalOverlay');
            const popup = document.getElementById('fishPopup');
            const loading = document.getElementById('fishLoadingOverlay');
            
            if (!overlay || !popup) {
                console.error('Activity modal elements not found!');
                return;
            }
            
            overlay.classList.add('active');
            popup.classList.add('active');
            if (loading) loading.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            
            loadFishActivityData();
        }

        function closeActivityModal() {
            const overlay = document.getElementById('activityModalOverlay');
            const popup = document.getElementById('fishPopup');
            overlay.classList.add('closing');
            popup.classList.add('closing');
            setTimeout(() => {
                overlay.classList.remove('active', 'closing');
                popup.classList.remove('active', 'closing');
                document.body.style.overflow = '';
            }, 300);
        }

        function openTideModal() {
            console.log('Opening tide modal...');
            const overlay = document.getElementById('tideModalOverlay');
            const popup = document.getElementById('tidePopup');
            const loading = document.getElementById('tideLoadingOverlay');
            
            if (!overlay || !popup) {
                console.error('Tide modal elements not found!');
                return;
            }
            
            overlay.classList.add('active');
            popup.classList.add('active');
            if (loading) loading.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            
            loadTideData();
        }

        function closeTideModal() {
            const overlay = document.getElementById('tideModalOverlay');
            const popup = document.getElementById('tidePopup');
            overlay.classList.add('closing');
            popup.classList.add('closing');
            setTimeout(() => {
                overlay.classList.remove('active', 'closing');
                popup.classList.remove('active', 'closing');
                document.body.style.overflow = '';
            }, 300);
        }

        function openWaveModal() {
            console.log('Opening wave modal...');
            const overlay = document.getElementById('waveModalOverlay');
            const popup = document.getElementById('wavePopup');
            const loading = document.getElementById('waveLoadingOverlay');
            
            if (!overlay || !popup) {
                console.error('Wave modal elements not found!');
                return;
            }
            
            overlay.classList.add('active');
            popup.classList.add('active');
            if (loading) loading.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            
            loadWaveData();
        }

        function closeWaveModal() {
            const overlay = document.getElementById('waveModalOverlay');
            const popup = document.getElementById('wavePopup');
            overlay.classList.add('closing');
            popup.classList.add('closing');
            setTimeout(() => {
                overlay.classList.remove('active', 'closing');
                popup.classList.remove('active', 'closing');
                document.body.style.overflow = '';
            }, 300);
        }

        function openWeatherModal() {
            console.log('Opening weather modal...');
            const overlay = document.getElementById('weatherModalOverlay');
            const popup = document.getElementById('weatherPopup');
            const loading = document.getElementById('weatherLoadingOverlay');
            
            if (!overlay || !popup) {
                console.error('Weather modal elements not found!');
                return;
            }
            
            overlay.classList.add('active');
            popup.classList.add('active');
            if (loading) loading.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            
            loadWeatherData();
        }

        function closeWeatherModal() {
            const overlay = document.getElementById('weatherModalOverlay');
            const popup = document.getElementById('weatherPopup');
            overlay.classList.add('closing');
            popup.classList.add('closing');
            setTimeout(() => {
                overlay.classList.remove('active', 'closing');
                popup.classList.remove('active', 'closing');
                document.body.style.overflow = '';
            }, 300);
        }

        // Close modals on overlay click
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('activityModalOverlay')?.addEventListener('click', closeActivityModal);
            document.getElementById('tideModalOverlay')?.addEventListener('click', closeTideModal);
            document.getElementById('waveModalOverlay')?.addEventListener('click', closeWaveModal);
            document.getElementById('weatherModalOverlay')?.addEventListener('click', closeWeatherModal);
        });

        // ===== SEARCH FUNCTIONALITY =====
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');
        let searchTimeout;

        searchInput?.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            const query = e.target.value.trim();
            if (query.length < 2) {
                searchResults.classList.remove('show');
                return;
            }
            searchTimeout = setTimeout(() => performSearch(query), 300);
        });

        function performSearch(query) {
            // Implementation here
        }

        // Close search on outside click
        document.addEventListener('click', function(e) {
            if (!searchInput?.contains(e.target) && !searchResults?.contains(e.target)) {
                searchResults?.classList.remove('show');
            }
        });
    </script>