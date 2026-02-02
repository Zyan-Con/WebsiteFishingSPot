@extends('layouts.app')

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    .tide-page {
        height: 100vh;
        background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    
    /* Animated Waves Background */
    .waves-bg {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 200px;
        pointer-events: none;
        z-index: 0;
        opacity: 0.3;
    }
    
    .wave {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 200%;
        height: 100%;
        background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1200 120'%3E%3Cpath d='M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z' fill='rgba(255,255,255,0.3)'/%3E%3C/svg%3E") repeat-x;
        animation: wave linear infinite;
    }
    
    .wave:nth-child(1) {
        animation-duration: 15s;
        bottom: 0;
    }
    
    .wave:nth-child(2) {
        animation-duration: 20s;
        bottom: 10px;
        opacity: 0.5;
    }
    
    @keyframes wave {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    
    /* Sticky Header */
    .tide-header {
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
        background: linear-gradient(135deg, #06b6d4, #0891b2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .header-left h1 i {
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
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
        color: #06b6d4;
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
        border-color: #06b6d4;
        color: #06b6d4;
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
    
    /* Tide Extremes Grid */
    .extremes-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        height: 28%;
    }
    
    .extreme-card {
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 10px 40px rgba(6, 182, 212, 0.35);
        position: relative;
        overflow: hidden;
        animation: fadeIn 0.8s ease backwards;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .extreme-card:nth-child(1) { animation-delay: 0.1s; }
    .extreme-card:nth-child(2) { animation-delay: 0.2s; }
    .extreme-card:nth-child(3) { animation-delay: 0.3s; }
    .extreme-card:nth-child(4) { animation-delay: 0.4s; }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .extreme-card.high {
        background: linear-gradient(135deg, #06b6d4, #0891b2);
    }
    
    .extreme-card.low {
        background: linear-gradient(135deg, #0891b2, #0e7490);
    }
    
    .extreme-card::before {
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
    
    .extreme-content {
        position: relative;
        z-index: 1;
    }
    
    .extreme-type {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }
    
    .type-label {
        font-size: 10px;
        color: rgba(255,255,255,0.85);
        font-weight: 700;
        letter-spacing: 1.5px;
    }
    
    .type-icon {
        font-size: 20px;
        color: white;
    }
    
    .extreme-time {
        font-size: 32px;
        font-weight: 900;
        color: white;
        margin: 8px 0;
    }
    
    .extreme-height {
        display: flex;
        align-items: baseline;
        gap: 6px;
    }
    
    .height-value {
        font-size: 24px;
        font-weight: 800;
        color: white;
    }
    
    .height-unit {
        font-size: 12px;
        color: rgba(255,255,255,0.85);
        font-weight: 600;
    }
    
    /* Bottom Section */
    .bottom-section {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 12px;
        flex: 1;
        min-height: 0;
    }
    
    /* Chart Card */
    .chart-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        animation: fadeIn 0.8s ease 0.5s backwards;
        display: flex;
        flex-direction: column;
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
        font-size: 11px;
        color: #6b7280;
    }
    
    .chart-container {
        position: relative;
        flex: 1;
        min-height: 0;
    }
    
    /* Info Cards */
    .info-cards {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    
    .info-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        animation: fadeIn 0.8s ease backwards;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .info-card:nth-child(1) { animation-delay: 0.6s; }
    .info-card:nth-child(2) { animation-delay: 0.7s; }
    
    .card-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
        flex-shrink: 0;
    }
    
    .card-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: white;
    }
    
    .icon-fishing { background: linear-gradient(135deg, #06b6d4, #0891b2); }
    .icon-info { background: linear-gradient(135deg, #0891b2, #0e7490); }
    
    .card-title h3 {
        font-size: 14px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 2px;
    }
    
    .card-title p {
        font-size: 10px;
        color: #6b7280;
    }
    
    .card-content {
        flex: 1;
        overflow-y: auto;
    }
    
    .card-content::-webkit-scrollbar {
        width: 4px;
    }
    
    .card-content::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 4px;
    }
    
    .recommendation-box {
        padding: 12px;
        background: linear-gradient(135deg, #ecfeff, #cffafe);
        border-radius: 10px;
        border-left: 3px solid #06b6d4;
        margin-bottom: 10px;
    }
    
    .rec-title {
        font-size: 10px;
        color: #0e7490;
        font-weight: 700;
        margin-bottom: 4px;
        letter-spacing: 0.5px;
    }
    
    .rec-text {
        font-size: 11px;
        color: #0c4a6e;
        line-height: 1.5;
    }
    
    .tips-box {
        padding: 12px;
        background: #f9fafb;
        border-radius: 10px;
    }
    
    .tips-title {
        font-size: 11px;
        color: #374151;
        font-weight: 700;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .tips-title i {
        color: #f59e0b;
    }
    
    .tips-list {
        margin: 0;
        padding-left: 18px;
    }
    
    .tips-list li {
        font-size: 11px;
        color: #4b5563;
        line-height: 1.6;
        margin-bottom: 4px;
    }
    
    .info-item {
        padding: 12px;
        background: #f9fafb;
        border-radius: 10px;
        margin-bottom: 8px;
        transition: all 0.3s;
        cursor: pointer;
    }
    
    .info-item:hover {
        background: #f3f4f6;
        transform: translateX(3px);
    }
    
    .info-label {
        font-size: 10px;
        color: #6b7280;
        margin-bottom: 4px;
    }
    
    .info-value {
        font-size: 13px;
        color: #1f2937;
        font-weight: 700;
    }
    
    /* Responsive */
    @media (max-width: 1400px) {
        .extremes-grid {
            grid-template-columns: repeat(2, 1fr);
            height: auto;
        }
        .bottom-section {
            grid-template-columns: 1fr;
        }
    }
    
    @media (max-width: 768px) {
        .extremes-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="tide-page">
    {{-- Animated Waves Background --}}
    <div class="waves-bg">
        <div class="wave"></div>
        <div class="wave"></div>
    </div>

    {{-- Sticky Header --}}
    <div class="tide-header">
        <div class="header-content">
            <div class="header-left">
                <h1><i class="fas fa-water"></i> Prakiraan Pasang Surut</h1>
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
        {{-- Tide Extremes --}}
        <div class="extremes-grid">
            @if(isset($tideData['extremes']))
                @foreach($tideData['extremes'] as $extreme)
                <div class="extreme-card {{ $extreme['type'] }}">
                    <div class="extreme-content">
                        <div class="extreme-type">
                            <div class="type-label">
                                {{ $extreme['type'] == 'high' ? 'PASANG TINGGI' : 'PASANG RENDAH' }}
                            </div>
                            <div class="type-icon">
                                <i class="fas fa-arrow-{{ $extreme['type'] == 'high' ? 'up' : 'down' }}"></i>
                            </div>
                        </div>
                        <div class="extreme-time" data-animate="time">{{ $extreme['time'] }}</div>
                        <div class="extreme-height">
                            <span class="height-value">{{ $extreme['height'] }}</span>
                            <span class="height-unit">meter</span>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="extreme-card high">
                    <div class="extreme-content">
                        <div class="extreme-type">
                            <div class="type-label">PASANG TINGGI</div>
                            <div class="type-icon"><i class="fas fa-arrow-up"></i></div>
                        </div>
                        <div class="extreme-time">05:30</div>
                        <div class="extreme-height">
                            <span class="height-value">1.8</span>
                            <span class="height-unit">meter</span>
                        </div>
                    </div>
                </div>
                <div class="extreme-card low">
                    <div class="extreme-content">
                        <div class="extreme-type">
                            <div class="type-label">PASANG RENDAH</div>
                            <div class="type-icon"><i class="fas fa-arrow-down"></i></div>
                        </div>
                        <div class="extreme-time">11:45</div>
                        <div class="extreme-height">
                            <span class="height-value">0.4</span>
                            <span class="height-unit">meter</span>
                        </div>
                    </div>
                </div>
                <div class="extreme-card high">
                    <div class="extreme-content">
                        <div class="extreme-type">
                            <div class="type-label">PASANG TINGGI</div>
                            <div class="type-icon"><i class="fas fa-arrow-up"></i></div>
                        </div>
                        <div class="extreme-time">18:15</div>
                        <div class="extreme-height">
                            <span class="height-value">1.9</span>
                            <span class="height-unit">meter</span>
                        </div>
                    </div>
                </div>
                <div class="extreme-card low">
                    <div class="extreme-content">
                        <div class="extreme-type">
                            <div class="type-label">PASANG RENDAH</div>
                            <div class="type-icon"><i class="fas fa-arrow-down"></i></div>
                        </div>
                        <div class="extreme-time">23:50</div>
                        <div class="extreme-height">
                            <span class="height-value">0.3</span>
                            <span class="height-unit">meter</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Bottom Section --}}
        <div class="bottom-section">
            {{-- Chart Card --}}
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">
                        <i class="fas fa-chart-line"></i> Grafik Pasang Surut 24 Jam
                    </h3>
                    <p class="chart-subtitle">Pantau pergerakan air laut sepanjang hari</p>
                </div>
                <div class="chart-container">
                    <canvas id="tideChart"></canvas>
                </div>
            </div>

            {{-- Info Cards --}}
            <div class="info-cards">
                {{-- Fishing Recommendations --}}
                <div class="info-card">
                    <div class="card-header">
                        <div class="card-icon icon-fishing">
                            <i class="fas fa-fish"></i>
                        </div>
                        <div class="card-title">
                            <h3>Rekomendasi Memancing</h3>
                            <p>Kondisi pasang surut</p>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="recommendation-box">
                            <div class="rec-title">WAKTU TERBAIK</div>
                            <div class="rec-text">
                                1-2 jam sebelum dan sesudah pasang tinggi ideal untuk memancing
                            </div>
                        </div>
                        <div class="tips-box">
                            <div class="tips-title">
                                <i class="fas fa-lightbulb"></i> Tips:
                            </div>
                            <ul class="tips-list">
                                <li>Ikan aktif saat air bergerak</li>
                                <li>Pasang tinggi bawa ikan ke pantai</li>
                                <li>Surut ideal untuk muara sungai</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Tide Information --}}
                <div class="info-card">
                    <div class="card-header">
                        <div class="card-icon icon-info">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div class="card-title">
                            <h3>Informasi Pasang Surut</h3>
                            <p>Fakta penting</p>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="info-item">
                            <div class="info-label">Siklus Pasang Surut</div>
                            <div class="info-value">2x setiap ~24 jam 50 menit</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Penyebab Utama</div>
                            <div class="info-value">Gravitasi Bulan & Matahari</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Variasi Tinggi</div>
                            <div class="info-value">Tergantung lokasi & fase bulan</div>
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
    createTideChart();
    
    // Click effect on cards
    const cards = document.querySelectorAll('.extreme-card, .info-item');
    cards.forEach(card => {
        card.addEventListener('click', function() {
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 200);
        });
    });
});

function createTideChart() {
    const ctx = document.getElementById('tideChart');
    if (!ctx) return;
    
    // Generate 24-hour tide data
    const hours = [];
    const tideHeights = [];
    
    for (let i = 0; i < 24; i++) {
        hours.push(i.toString().padStart(2, '0') + ':00');
        // Simulate tide pattern (2 cycles per day)
        const angle = (i / 24) * 4 * Math.PI;
        const height = 1.1 + Math.sin(angle) * 0.7;
        tideHeights.push(height.toFixed(2));
    }
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: hours,
            datasets: [{
                label: 'Tinggi Air (meter)',
                data: tideHeights,
                borderColor: '#06b6d4',
                backgroundColor: 'rgba(6, 182, 212, 0.15)',
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointBackgroundColor: '#06b6d4',
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
                    max: 2.5,
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

@endsection