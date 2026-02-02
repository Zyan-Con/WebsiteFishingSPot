@extends('layouts.app')

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    .fish-activity-page {
        height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 15px;
        overflow: hidden;
        position: relative;
    }
    
    /* Swimming Fish */
    .fish-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 0;
    }
    
    .fish {
        position: absolute;
        font-size: 24px;
        opacity: 0.2;
        animation: swim linear infinite;
    }
    
    @keyframes swim {
        0%, 100% { transform: translateX(-50px) scaleX(1); }
        50% { transform: translateX(calc(100vw + 50px)) scaleX(-1); }
    }
    
    .container-wrapper {
        max-width: 1400px;
        height: 100%;
        margin: 0 auto;
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    
    /* Header - Compact */
    .header {
        background: white;
        border-radius: 15px;
        padding: 15px 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        animation: slideDown 0.6s ease;
    }
    
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .header h1 {
        font-size: 24px;
        font-weight: 800;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .header h1 i {
        color: #667eea;
        animation: bounce 2s ease-in-out infinite;
    }
    
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }
    
    .header-info {
        display: flex;
        gap: 20px;
        font-size: 13px;
        color: #6b7280;
    }
    
    /* Main Grid */
    .main-grid {
        display: grid;
        grid-template-columns: 2fr 3fr;
        gap: 12px;
        flex: 1;
        min-height: 0;
    }
    
    /* Score Card */
    .score-card {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 20px;
        padding: 30px;
        color: white;
        text-align: center;
        box-shadow: 0 10px 40px rgba(102, 126, 234, 0.4);
        animation: fadeIn 0.8s ease 0.2s backwards;
        position: relative;
        overflow: hidden;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }
    
    .score-card::before {
        content: '';
        position: absolute;
        width: 200px;
        height: 200px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        top: -100px;
        right: -50px;
        animation: float 6s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translate(0, 0); }
        50% { transform: translate(-20px, 20px); }
    }
    
    .score-label {
        font-size: 11px;
        letter-spacing: 2px;
        opacity: 0.9;
        margin-bottom: 10px;
    }
    
    .score-value {
        font-size: 72px;
        font-weight: 900;
        margin: 15px 0;
        animation: pulse 2s ease-in-out infinite;
        position: relative;
        z-index: 1;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    .score-text {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 20px;
    }
    
    .moon-data {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        padding-top: 20px;
        border-top: 2px solid rgba(255,255,255,0.2);
    }
    
    .moon-item {
        font-size: 12px;
        opacity: 0.9;
    }
    
    .moon-value {
        font-size: 18px;
        font-weight: 800;
        margin-top: 5px;
    }
    
    /* Right Grid */
    .right-grid {
        display: grid;
        grid-template-rows: 1fr 1fr;
        gap: 12px;
    }
    
    /* Times Grid */
    .times-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    .time-box {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        animation: fadeIn 0.8s ease backwards;
        transition: all 0.3s;
    }
    
    .time-box:nth-child(1) { animation-delay: 0.3s; }
    .time-box:nth-child(2) { animation-delay: 0.4s; }
    .time-box:nth-child(3) { animation-delay: 0.5s; }
    .time-box:nth-child(4) { animation-delay: 0.6s; }
    
    .time-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    }
    
    .time-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
        font-size: 14px;
        font-weight: 700;
        color: #1f2937;
    }
    
    .time-items {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .time-item {
        padding: 10px;
        border-radius: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        transition: all 0.3s;
    }
    
    .time-item:hover {
        transform: translateX(5px);
    }
    
    .sunrise-bg { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #78350f; }
    .sunset-bg { background: linear-gradient(135deg, #ddd6fe, #c4b5fd); color: #4c1d95; }
    .moonrise-bg { background: linear-gradient(135deg, #e0e7ff, #c7d2fe); color: #1e3a8a; }
    .moonset-bg { background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #1e40af; }
    
    .time-value {
        font-size: 18px;
        font-weight: 900;
    }
    
    /* Periods Grid */
    .periods-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    .period-box {
        background: white;
        border-radius: 15px;
        padding: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        animation: fadeIn 0.8s ease backwards;
    }
    
    .period-box:nth-child(1) { animation-delay: 0.7s; }
    .period-box:nth-child(2) { animation-delay: 0.8s; }
    
    .period-title {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
        font-size: 14px;
        font-weight: 700;
        color: #1f2937;
    }
    
    .period-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .period-item {
        padding: 12px;
        border-radius: 10px;
        border-left: 4px solid;
        transition: all 0.3s;
        cursor: pointer;
    }
    
    .period-item:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .major-period {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        border-color: #f59e0b;
    }
    
    .minor-period {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        border-color: #3b82f6;
    }
    
    .period-time {
        font-size: 16px;
        font-weight: 900;
        margin-bottom: 4px;
    }
    
    .major-period .period-time { color: #78350f; }
    .minor-period .period-time { color: #1e3a8a; }
    
    .period-label {
        font-size: 11px;
        font-weight: 600;
        opacity: 0.8;
    }
    
    /* Responsive */
    @media (max-width: 1200px) {
        .main-grid {
            grid-template-columns: 1fr;
            grid-template-rows: auto 1fr;
        }
        .score-card {
            padding: 20px;
        }
        .score-value {
            font-size: 60px;
        }
    }
</style>

<div class="fish-activity-page">
    <!-- Swimming Fish Background -->
    <div class="fish-bg">
        <div class="fish" style="top: 10%; animation-duration: 20s;">🐟</div>
        <div class="fish" style="top: 30%; animation-duration: 25s; animation-delay: 5s;">🐠</div>
        <div class="fish" style="top: 50%; animation-duration: 22s; animation-delay: 10s;">🐡</div>
        <div class="fish" style="top: 70%; animation-duration: 28s; animation-delay: 3s;">🦈</div>
        <div class="fish" style="top: 85%; animation-duration: 24s; animation-delay: 8s;">🐟</div>
    </div>

    <div class="container-wrapper">
        <!-- Header -->
        <div class="header">
            <h1><i class="fas fa-fish"></i> Aktivitas Ikan</h1>
            <div class="header-info">
                <span><i class="fas fa-map-marker-alt"></i> {{ $lat ?? '-0.947136' }}, {{ $lng ?? '100.417419' }}</span>
                <span><i class="fas fa-calendar"></i> {{ isset($date) ? \Carbon\Carbon::parse($date)->format('d M Y') : date('d M Y') }}</span>
            </div>
        </div>

        <!-- Main Grid -->
        <div class="main-grid">
            <!-- Score Card -->
            <div class="score-card">
                <div class="score-label">SKOR AKTIVITAS</div>
                <div class="score-value">{{ isset($solunarData['rating']) ? round($solunarData['rating'] * 20) : '70' }}</div>
                <div class="score-text">{{ $solunarData['rating_text'] ?? 'Baik' }}</div>
                <div class="moon-data">
                    <div class="moon-item">
                        <div>🌙 Fase Bulan</div>
                        <div class="moon-value">{{ $solunarData['moon_phase'] ?? 'Kuartir' }}</div>
                    </div>
                    <div class="moon-item">
                        <div>✨ Iluminasi</div>
                        <div class="moon-value">{{ $solunarData['moon_illumination'] ?? '50' }}%</div>
                    </div>
                </div>
            </div>

            <!-- Right Grid -->
            <div class="right-grid">
                <!-- Times Grid -->
                <div class="times-grid">
                    <!-- Sun Times -->
                    <div class="time-box">
                        <div class="time-header">
                            <i class="fas fa-sun" style="color: #f59e0b;"></i>
                            Matahari
                        </div>
                        <div class="time-items">
                            <div class="time-item sunrise-bg">
                                <span>☀️ Sunrise</span>
                                <span class="time-value">{{ $solunarData['sunrise'] ?? '06:15' }}</span>
                            </div>
                            <div class="time-item sunset-bg">
                                <span>🌅 Sunset</span>
                                <span class="time-value">{{ $solunarData['sunset'] ?? '18:30' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Moon Times -->
                    <div class="time-box">
                        <div class="time-header">
                            <i class="fas fa-moon" style="color: #6366f1;"></i>
                            Bulan
                        </div>
                        <div class="time-items">
                            <div class="time-item moonrise-bg">
                                <span>🌙 Moonrise</span>
                                <span class="time-value">{{ $solunarData['moonrise'] ?? '19:00' }}</span>
                            </div>
                            <div class="time-item moonset-bg">
                                <span>🌑 Moonset</span>
                                <span class="time-value">{{ $solunarData['moonset'] ?? '07:30' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Major Periods -->
                    <div class="time-box">
                        <div class="time-header">
                            <i class="fas fa-star" style="color: #f59e0b;"></i>
                            Periode Utama
                        </div>
                        <div class="time-items">
                            @if(isset($solunarData['major_periods']) && is_array($solunarData['major_periods']))
                                @foreach(array_slice($solunarData['major_periods'], 0, 2) as $period)
                                <div class="time-item sunrise-bg">
                                    <span>{{ $period['label'] ?? 'Pagi' }}</span>
                                    <span class="time-value">{{ $period['start'] ?? '06:00' }}</span>
                                </div>
                                @endforeach
                            @else
                                <div class="time-item sunrise-bg">
                                    <span>Pagi</span>
                                    <span class="time-value">06:00</span>
                                </div>
                                <div class="time-item sunset-bg">
                                    <span>Sore</span>
                                    <span class="time-value">18:00</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Minor Periods -->
                    <div class="time-box">
                        <div class="time-header">
                            <i class="fas fa-clock" style="color: #3b82f6;"></i>
                            Periode Minor
                        </div>
                        <div class="time-items">
                            @if(isset($solunarData['minor_periods']) && is_array($solunarData['minor_periods']))
                                @foreach(array_slice($solunarData['minor_periods'], 0, 2) as $period)
                                <div class="time-item moonrise-bg">
                                    <span>{{ $period['label'] ?? 'Malam' }}</span>
                                    <span class="time-value">{{ $period['start'] ?? '00:00' }}</span>
                                </div>
                                @endforeach
                            @else
                                <div class="time-item moonrise-bg">
                                    <span>Malam</span>
                                    <span class="time-value">00:00</span>
                                </div>
                                <div class="time-item moonset-bg">
                                    <span>Siang</span>
                                    <span class="time-value">12:00</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate score value
    const scoreValue = document.querySelector('.score-value');
    if (scoreValue) {
        const targetValue = parseInt(scoreValue.textContent);
        let currentValue = 0;
        const duration = 2000;
        const increment = targetValue / (duration / 16);
        
        const animate = () => {
            currentValue += increment;
            if (currentValue < targetValue) {
                scoreValue.textContent = Math.floor(currentValue);
                requestAnimationFrame(animate);
            } else {
                scoreValue.textContent = targetValue;
            }
        };
        
        setTimeout(animate, 500);
    }
    
    // Click effect on cards
    const boxes = document.querySelectorAll('.time-box, .period-box');
    boxes.forEach(box => {
        box.addEventListener('click', function() {
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = '';
            }, 200);
        });
    });
    
    // Hover effect on periods
    const periodItems = document.querySelectorAll('.period-item');
    periodItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.borderLeftWidth = '8px';
        });
        item.addEventListener('mouseleave', function() {
            this.style.borderLeftWidth = '4px';
        });
    });
});
</script>

@endsection