@extends('layouts.app')

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    .wave-page {
        height: 100vh;
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    
    /* Animated Ripples Background */
    .ripples-bg {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 0;
        overflow: hidden;
    }
    
    .ripple {
        position: absolute;
        border-radius: 50%;
        border: 2px solid rgba(255,255,255,0.1);
        animation: ripple-animation 6s ease-out infinite;
    }
    
    .ripple:nth-child(1) {
        width: 200px;
        height: 200px;
        top: 20%;
        left: 10%;
        animation-delay: 0s;
    }
    
    .ripple:nth-child(2) {
        width: 300px;
        height: 300px;
        top: 60%;
        right: 15%;
        animation-delay: 2s;
    }
    
    .ripple:nth-child(3) {
        width: 250px;
        height: 250px;
        bottom: 20%;
        left: 50%;
        animation-delay: 4s;
    }
    
    @keyframes ripple-animation {
        0% {
            transform: scale(0.8);
            opacity: 0;
        }
        50% {
            opacity: 0.3;
        }
        100% {
            transform: scale(2);
            opacity: 0;
        }
    }
    
    /* Sticky Header */
    .wave-header {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        z-index: 1000;
        animation: slideDown 0.6s ease;
        flex-shrink: 0;
    }
    
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .header-content {
        max-width: 1600px;
        margin: 0 auto;
        padding: 16px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .header-left h1 {
        font-size: 24px;
        font-weight: 800;
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .header-left h1 i {
        animation: wave-icon 2s ease-in-out infinite;
    }
    
    @keyframes wave-icon {
        0%, 100% { transform: rotate(0deg); }
        25% { transform: rotate(-10deg); }
        75% { transform: rotate(10deg); }
    }
    
    .header-meta {
        display: flex;
        align-items: center;
        gap: 16px;
        font-size: 12px;
        color: #6b7280;
        font-weight: 500;
    }
    
    .header-meta i {
        color: #3b82f6;
    }
    
    .refresh-btn {
        padding: 10px 20px;
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        color: #1f2937;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
    }
    
    .refresh-btn:hover {
        border-color: #3b82f6;
        color: #3b82f6;
        transform: translateY(-2px);
    }
    
    /* Main Container */
    .main-container {
        max-width: 1600px;
        margin: 0 auto;
        padding: 16px 24px 20px;
        position: relative;
        z-index: 1;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 12px;
        min-height: 0;
    }
    
    /* Conditions Grid */
    .conditions-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        height: 30%;
    }
    
    .condition-card {
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 10px 40px rgba(59, 130, 246, 0.35);
        position: relative;
        overflow: hidden;
        animation: fadeIn 0.8s ease backwards;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .condition-card:nth-child(1) { animation-delay: 0.1s; }
    .condition-card:nth-child(2) { animation-delay: 0.2s; }
    .condition-card:nth-child(3) { animation-delay: 0.3s; }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .condition-card.primary {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
    }
    
    .condition-card.secondary {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
    }
    
    .condition-card.tertiary {
        background: linear-gradient(135deg, #1d4ed8, #1e40af);
    }
    
    .condition-card::before {
        content: '';
        position: absolute;
        top: -30px;
        right: -30px;
        width: 120px;
        height: 120px;
        background: rgba(255,255,255,0.12);
        border-radius: 50%;
        animation: pulse 3s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
    
    .condition-content {
        position: relative;
        z-index: 1;
    }
    
    .condition-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
    }
    
    .condition-icon {
        width: 40px;
        height: 40px;
        background: rgba(255,255,255,0.2);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: white;
    }
    
    .condition-title {
        flex: 1;
    }
    
    .condition-label {
        font-size: 10px;
        color: rgba(255,255,255,0.85);
        font-weight: 700;
        letter-spacing: 1px;
    }
    
    .condition-sublabel {
        font-size: 9px;
        color: rgba(255,255,255,0.65);
    }
    
    .condition-value {
        display: flex;
        align-items: baseline;
        gap: 8px;
        margin: 10px 0;
    }
    
    .value-number {
        font-size: 42px;
        font-weight: 900;
        color: white;
        line-height: 1;
    }
    
    .value-unit {
        font-size: 16px;
        color: rgba(255,255,255,0.85);
        font-weight: 600;
    }
    
    .condition-status {
        padding: 10px;
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(10px);
        border-radius: 10px;
    }
    
    .status-label {
        font-size: 9px;
        color: rgba(255,255,255,0.75);
        margin-bottom: 4px;
    }
    
    .status-value {
        font-size: 13px;
        color: white;
        font-weight: 700;
    }
    
    /* Bottom Section */
    .bottom-section {
        display: grid;
        grid-template-columns: 3fr 2fr;
        gap: 12px;
        flex: 1;
        min-height: 0;
    }
    
    /* Left Column */
    .left-column {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    
    /* Swell Card */
    .swell-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        animation: fadeIn 0.8s ease 0.4s backwards;
        flex-shrink: 0;
    }
    
    .swell-header {
        margin-bottom: 12px;
    }
    
    .swell-title {
        font-size: 16px;
        font-weight: 700;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 4px;
    }
    
    .swell-subtitle {
        font-size: 10px;
        color: #6b7280;
    }
    
    .swell-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }
    
    .swell-item {
        padding: 14px;
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        border-radius: 12px;
        border-left: 3px solid;
        transition: all 0.3s;
        cursor: pointer;
    }
    
    .swell-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .swell-item:nth-child(1) { border-color: #3b82f6; }
    .swell-item:nth-child(2) { border-color: #2563eb; }
    .swell-item:nth-child(3) { border-color: #1d4ed8; }
    
    .swell-label {
        font-size: 9px;
        color: #1e40af;
        font-weight: 700;
        margin-bottom: 8px;
        letter-spacing: 0.5px;
    }
    
    .swell-value {
        display: flex;
        align-items: baseline;
        gap: 6px;
    }
    
    .swell-number {
        font-size: 28px;
        font-weight: 800;
        color: #1e3a8a;
    }
    
    .swell-unit {
        font-size: 13px;
        font-weight: 600;
    }
    
    .swell-item:nth-child(1) .swell-unit { color: #3b82f6; }
    .swell-item:nth-child(2) .swell-unit { color: #2563eb; }
    .swell-item:nth-child(3) .swell-unit { color: #1d4ed8; }
    
    /* Chart Card */
    .chart-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        animation: fadeIn 0.8s ease 0.5s backwards;
        flex: 1;
        display: flex;
        flex-direction: column;
        min-height: 0;
    }
    
    .chart-header {
        margin-bottom: 12px;
        flex-shrink: 0;
    }
    
    .chart-title {
        font-size: 16px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .chart-subtitle {
        font-size: 10px;
        color: #6b7280;
    }
    
    .chart-container {
        position: relative;
        flex: 1;
        min-height: 0;
    }
    
    /* Right Column - Guidelines */
    .guidelines-column {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    
    .guideline-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        animation: fadeIn 0.8s ease backwards;
        flex: 1;
        display: flex;
        flex-direction: column;
        min-height: 0;
    }
    
    .guideline-card:nth-child(1) { animation-delay: 0.6s; }
    .guideline-card:nth-child(2) { animation-delay: 0.7s; }
    
    .guideline-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
        flex-shrink: 0;
    }
    
    .guideline-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: white;
    }
    
    .icon-safety {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }
    
    .icon-tips {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
    }
    
    .guideline-title h3 {
        font-size: 14px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 2px;
    }
    
    .guideline-title p {
        font-size: 10px;
        color: #6b7280;
    }
    
    .guideline-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
        flex: 1;
        overflow-y: auto;
    }
    
    .guideline-list::-webkit-scrollbar {
        width: 4px;
    }
    
    .guideline-list::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 4px;
    }
    
    .guideline-item {
        padding: 12px;
        border-radius: 10px;
        border-left: 3px solid;
        transition: all 0.3s;
        cursor: pointer;
        flex-shrink: 0;
    }
    
    .guideline-item:hover {
        transform: translateX(3px);
    }
    
    .guideline-item.safe {
        background: #fef3c7;
        border-color: #f59e0b;
    }
    
    .guideline-item.caution {
        background: #fed7aa;
        border-color: #ea580c;
    }
    
    .guideline-item.danger {
        background: #fecaca;
        border-color: #dc2626;
    }
    
    .guideline-item.info {
        background: #f9fafb;
        border-color: #3b82f6;
    }
    
    .guideline-item-title {
        font-size: 11px;
        font-weight: 700;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .guideline-item.safe .guideline-item-title { color: #92400e; }
    .guideline-item.caution .guideline-item-title { color: #9a3412; }
    .guideline-item.danger .guideline-item-title { color: #991b1b; }
    .guideline-item.info .guideline-item-title { color: #1e40af; }
    
    .guideline-item-text {
        font-size: 10px;
        line-height: 1.5;
        font-weight: 500;
    }
    
    .guideline-item.safe .guideline-item-text { color: #78350f; }
    .guideline-item.caution .guideline-item-text { color: #7c2d12; }
    .guideline-item.danger .guideline-item-text { color: #7f1d1d; }
    .guideline-item.info .guideline-item-text { color: #4b5563; }
    
    /* Responsive */
    @media (max-width: 1400px) {
        .conditions-grid {
            grid-template-columns: 1fr;
            height: auto;
        }
        .bottom-section {
            grid-template-columns: 1fr;
        }
        .swell-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="wave-page">
    {{-- Animated Ripples Background --}}
    <div class="ripples-bg">
        <div class="ripple"></div>
        <div class="ripple"></div>
        <div class="ripple"></div>
    </div>

    {{-- Sticky Header --}}
    <div class="wave-header">
        <div class="header-content">
            <div class="header-left">
                <h1><i class="fas fa-wave-square"></i> Prakiraan Gelombang</h1>
                <div class="header-meta">
                    <span><i class="fas fa-location-dot"></i> {{ $location ?? 'Pekanbaru, Indonesia' }}</span>
                    <span><i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($date ?? now())->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                </div>
            </div>
            <button class="refresh-btn" onclick="window.location.reload()">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
    </div>

    <div class="main-container">
        {{-- Current Wave Conditions --}}
        <div class="conditions-grid">
            {{-- Wave Height --}}
            <div class="condition-card primary">
                <div class="condition-content">
                    <div class="condition-header">
                        <div class="condition-icon">
                            <i class="fas fa-water"></i>
                        </div>
                        <div class="condition-title">
                            <div class="condition-label">TINGGI GELOMBANG</div>
                            <div class="condition-sublabel">Wave Height</div>
                        </div>
                    </div>
                    <div class="condition-value">
                        <span class="value-number">{{ $waveData['current']['wave_height'] ?? '1.2' }}</span>
                        <span class="value-unit">meter</span>
                    </div>
                    <div class="condition-status">
                        <div class="status-label">Status Kondisi</div>
                        <div class="status-value">
                            @php
                                $height = $waveData['current']['wave_height'] ?? 1.2;
                                if ($height < 0.5) echo 'Tenang';
                                elseif ($height < 1.25) echo 'Rendah';
                                elseif ($height < 2.5) echo 'Sedang';
                                else echo 'Tinggi';
                            @endphp
                        </div>
                    </div>
                </div>
            </div>

            {{-- Wave Period --}}
            <div class="condition-card secondary">
                <div class="condition-content">
                    <div class="condition-header">
                        <div class="condition-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="condition-title">
                            <div class="condition-label">PERIODE GELOMBANG</div>
                            <div class="condition-sublabel">Wave Period</div>
                        </div>
                    </div>
                    <div class="condition-value">
                        <span class="value-number">{{ $waveData['current']['wave_period'] ?? '8' }}</span>
                        <span class="value-unit">detik</span>
                    </div>
                    <div class="condition-status">
                        <div class="status-label">Interval Gelombang</div>
                        <div class="status-value">Waktu antar puncak</div>
                    </div>
                </div>
            </div>

            {{-- Wave Direction --}}
            <div class="condition-card tertiary">
                <div class="condition-content">
                    <div class="condition-header">
                        <div class="condition-icon">
                            <i class="fas fa-compass"></i>
                        </div>
                        <div class="condition-title">
                            <div class="condition-label">ARAH GELOMBANG</div>
                            <div class="condition-sublabel">Wave Direction</div>
                        </div>
                    </div>
                    <div class="condition-value">
                        <span class="value-number">{{ $waveData['current']['wave_direction'] ?? '180' }}</span>
                        <span class="value-unit">°</span>
                    </div>
                    <div class="condition-status">
                        <div class="status-label">Arah Kardinal</div>
                        <div class="status-value">
                            @php
                                $dir = $waveData['current']['wave_direction'] ?? 180;
                                if ($dir >= 337.5 || $dir < 22.5) echo 'Utara';
                                elseif ($dir < 67.5) echo 'Timur Laut';
                                elseif ($dir < 112.5) echo 'Timur';
                                elseif ($dir < 157.5) echo 'Tenggara';
                                elseif ($dir < 202.5) echo 'Selatan';
                                elseif ($dir < 247.5) echo 'Barat Daya';
                                elseif ($dir < 292.5) echo 'Barat';
                                else echo 'Barat Laut';
                            @endphp
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom Section --}}
        <div class="bottom-section">
            {{-- Left Column --}}
            <div class="left-column">
                {{-- Swell Information --}}
                <div class="swell-card">
                    <div class="swell-header">
                        <h3 class="swell-title">
                            <i class="fas fa-wave-square"></i> Informasi Swell
                        </h3>
                        <p class="swell-subtitle">Gelombang laut dalam yang merambat ke pantai</p>
                    </div>
                    <div class="swell-grid">
                        <div class="swell-item">
                            <div class="swell-label">TINGGI SWELL</div>
                            <div class="swell-value">
                                <span class="swell-number">{{ $waveData['current']['swell_height'] ?? '0.8' }}</span>
                                <span class="swell-unit">meter</span>
                            </div>
                        </div>
                        <div class="swell-item">
                            <div class="swell-label">PERIODE SWELL</div>
                            <div class="swell-value">
                                <span class="swell-number">{{ $waveData['current']['swell_period'] ?? '10' }}</span>
                                <span class="swell-unit">detik</span>
                            </div>
                        </div>
                        <div class="swell-item">
                            <div class="swell-label">ARAH SWELL</div>
                            <div class="swell-value">
                                <span class="swell-number">{{ $waveData['current']['swell_direction'] ?? '190' }}</span>
                                <span class="swell-unit">°</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Wave Chart --}}
                <div class="chart-card">
                    <div class="chart-header">
                        <h3 class="chart-title">
                            <i class="fas fa-chart-area"></i> Prakiraan 24 Jam
                        </h3>
                        <p class="chart-subtitle">Prediksi tinggi gelombang</p>
                    </div>
                    <div class="chart-container">
                        <canvas id="waveChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Right Column - Guidelines --}}
            <div class="guidelines-column">
                {{-- Safety Guidelines --}}
                <div class="guideline-card">
                    <div class="guideline-header">
                        <div class="guideline-icon icon-safety">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="guideline-title">
                            <h3>Panduan Keselamatan</h3>
                            <p>Safety Guidelines</p>
                        </div>
                    </div>
                    <div class="guideline-list">
                        <div class="guideline-item safe">
                            <div class="guideline-item-title">
                                <i class="fas fa-check-circle"></i> Gelombang < 1m
                            </div>
                            <div class="guideline-item-text">
                                Aman untuk berenang. Ideal untuk pemula.
                            </div>
                        </div>
                        <div class="guideline-item caution">
                            <div class="guideline-item-title">
                                <i class="fas fa-info-circle"></i> Gelombang 1-2m
                            </div>
                            <div class="guideline-item-text">
                                Hati-hati! Gunakan pelampung.
                            </div>
                        </div>
                        <div class="guideline-item danger">
                            <div class="guideline-item-title">
                                <i class="fas fa-times-circle"></i> Gelombang > 2m
                            </div>
                            <div class="guideline-item-text">
                                Berbahaya! Hindari aktivitas air.
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Fishing Tips --}}
                <div class="guideline-card">
                    <div class="guideline-header">
                        <div class="guideline-icon icon-tips">
                            <i class="fas fa-fish"></i>
                        </div>
                        <div class="guideline-title">
                            <h3>Tips Memancing</h3>
                            <p>Fishing Tips</p>
                        </div>
                    </div>
                    <div class="guideline-list">
                        <div class="guideline-item info">
                            <div class="guideline-item-title">
                                <i class="fas fa-check-circle"></i> Gelombang Sedang
                            </div>
                            <div class="guideline-item-text">
                                1-1.5m ideal. Ikan aktif mencari makan.
                            </div>
                        </div>
                        <div class="guideline-item info">
                            <div class="guideline-item-title">
                                <i class="fas fa-check-circle"></i> Hindari Tinggi
                            </div>
                            <div class="guideline-item-text">
                                Jangan mancing saat gelombang >2m.
                            </div>
                        </div>
                        <div class="guideline-item info">
                            <div class="guideline-item-title">
                                <i class="fas fa-check-circle"></i> Perhatikan Arah
                            </div>
                            <div class="guideline-item-text">
                                Posisi kapal sesuai arah gelombang.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    createWaveChart();
    
    // Click effect on cards
    const cards = document.querySelectorAll('.condition-card, .swell-item, .guideline-item');
    cards.forEach(card => {
        card.addEventListener('click', function() {
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 200);
        });
    });
});

function createWaveChart() {
    const ctx = document.getElementById('waveChart');
    if (!ctx) return;
    
    // Generate 24-hour wave data
    const hours = [];
    const waveHeights = [];
     
    for (let i = 0; <function_calls>
<invoke name="artifacts">
<parameter name="command">update</parameter>
<parameter name="id">wave_page_compact</parameter>
<parameter name="old_str">    for (let i = 0;</parameter>
<parameter name="new_str">    for (let i = 0; i < 24; i++) {
hours.push(i.toString().padStart(2, '0') + ':00');
const baseHeight = 1.2;
const variation = Math.sin((i / 24) * 2 * Math.PI) * 0.4 + Math.random() * 0.2;
waveHeights.push((baseHeight + variation).toFixed(2));
}
new Chart(ctx, {
    type: 'line',
    data: {
        labels: hours,
        datasets: [{
            label: 'Tinggi Gelombang (m)',
            data: waveHeights,
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.15)',
            tension: 0.4,
            fill: true,
            pointRadius: 4,
            pointBackgroundColor: '#3b82f6',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointHoverRadius: 6,
            borderWidth: 2.5
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.85)',
                padding: 12,
                titleFont: { size: 12, weight: 'bold' },
                bodyFont: { size: 11 },
                cornerRadius: 8,
                callbacks: {
                    label: function(context) {
                        return 'Tinggi: ' + context.parsed.y + ' meter';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 3,
                ticks: {
                    callback: function(value) {
                        return value + ' m';
                    },
                    font: { size: 11, weight: '600' },
                    color: '#64748b'
                },
                grid: { 
                    color: '#f1f5f9',
                    lineWidth: 1.5
                }
            },
            x: {
                grid: { display: false },
                ticks: { 
                    font: { size: 10, weight: '600' },
                    color: '#64748b'
                }
            }
        }
    }
});
}
</script>
@endsection</parameter>