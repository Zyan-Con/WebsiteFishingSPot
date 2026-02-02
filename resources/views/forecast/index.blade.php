@extends('layouts.app')

@section('content')
<div style="min-height: 100vh; background: linear-gradient(to bottom, #f0f9ff, #e0f2fe);">
    {{-- Header Navigation --}}
    <div style="background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 100;">
        <div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                <div>
                    <h1 style="font-size: 28px; font-weight: 700; color: #1f2937; margin-bottom: 4px;">
                        <i class="fas fa-chart-line"></i> Prakiraan
                    </h1>
                    <div style="font-size: 14px; color: #6b7280; display: flex; align-items: center; gap: 6px;">
                        <i class="fas fa-location-dot"></i>
                        <span id="locationName">Pekanbaru, Indonesia</span>
                    </div>
                </div>
                <button onclick="window.history.back()" style="padding: 10px 20px; background: #f3f4f6; border: none; border-radius: 8px; color: #1f2937; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-arrow-left"></i> Kembali
                </button>
            </div>
            
            {{-- Tab Navigation --}}
            <div style="display: flex; gap: 8px; overflow-x: auto; padding-bottom: 8px;">
                <button class="forecast-tab active" data-tab="activity" onclick="switchTab('activity')">
                    <i class="fas fa-fish"></i> Aktivitas Ikan
                </button>
                <button class="forecast-tab" data-tab="tide" onclick="switchTab('tide')">
                    <i class="fas fa-chart-line"></i> Pasang Surut
                </button>
                <button class="forecast-tab" data-tab="wave" onclick="switchTab('wave')">
                    <i class="fas fa-water"></i> Gelombang
                </button>
                <button class="forecast-tab" data-tab="weather" onclick="switchTab('weather')">
                    <i class="fas fa-cloud-sun"></i> Cuaca
                </button>
            </div>
        </div>
    </div>

    {{-- Content Container --}}
    <div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
        
        {{-- Activity Tab --}}
        <div id="activityTab" class="tab-content active">
            {{-- Week Days Selector --}}
            <div style="background: white; border-radius: 16px; padding: 20px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div id="activityWeekDays" style="display: flex; gap: 12px; overflow-x: auto;"></div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr; md:grid-template-columns: 1fr 1fr; gap: 20px;">
                {{-- Activity Gauge --}}
                <div style="background: white; border-radius: 16px; padding: 40px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); text-align: center;">
                    <h3 style="font-size: 18px; font-weight: 700; color: #1f2937; margin-bottom: 30px;">
                        <i class="fas fa-tachometer-alt"></i> Tingkat Aktivitas
                    </h3>
                    <div style="width: 200px; height: 200px; margin: 0 auto 20px; position: relative;">
                        <div id="activityGaugeCircle" style="width: 100%; height: 100%; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: conic-gradient(#3b82f6 calc(var(--score, 0) * 3.6deg), #e5e7eb 0deg);">
                            <div style="width: 85%; height: 85%; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 64px; font-weight: 700; color: #3b82f6;" id="activityScore">--</div>
                        </div>
                    </div>
                    <div id="activityLevel" style="font-size: 22px; font-weight: 700; color: #1f2937; margin-bottom: 8px;">Memuat data...</div>
                    <div style="font-size: 14px; color: #6b7280;">Waktu mancing terbaik hari ini</div>
                </div>

                {{-- Activity Chart --}}
                <div style="background: white; border-radius: 16px; padding: 30px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <h3 style="font-size: 18px; font-weight: 700; color: #1f2937; margin-bottom: 20px;">
                        <i class="fas fa-chart-area"></i> Grafik Aktivitas 24 Jam
                    </h3>
                    <div style="position: relative; height: 250px;">
                        <canvas id="activityChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Fishing Times --}}
            <div style="display: grid; grid-template-columns: 1fr; md:grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
                <div style="background: white; border-radius: 16px; padding: 30px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <h3 style="font-size: 18px; font-weight: 700; color: #1f2937; margin-bottom: 20px;">
                        <i class="fas fa-star" style="color: #f59e0b;"></i> Waktu Utama
                    </h3>
                    <div id="majorTimes" style="display: flex; flex-direction: column; gap: 16px;">
                        <!-- Populated by JS -->
                    </div>
                </div>
                <div style="background: white; border-radius: 16px; padding: 30px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <h3 style="font-size: 18px; font-weight: 700; color: #1f2937; margin-bottom: 20px;">
                        <i class="fas fa-clock" style="color: #3b82f6;"></i> Waktu Minor
                    </h3>
                    <div id="minorTimes" style="display: flex; flex-direction: column; gap: 16px;">
                        <!-- Populated by JS -->
                    </div>
                </div>
            </div>
        </div>

        {{-- Tide Tab --}}
        <div id="tideTab" class="tab-content">
            <div style="background: white; border-radius: 16px; padding: 20px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div id="tideWeekDays" style="display: flex; gap: 12px; overflow-x: auto;"></div>
            </div>

            <div style="display: grid; gap: 20px;">
                {{-- Tide Chart --}}
                <div style="background: white; border-radius: 16px; padding: 30px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <h3 style="font-size: 18px; font-weight: 700; color: #1f2937; margin-bottom: 20px;">
                        <i class="fas fa-water"></i> Grafik Pasang Surut
                    </h3>
                    <div style="position: relative; height: 300px;">
                        <canvas id="tideChart"></canvas>
                    </div>
                </div>

                {{-- Tide Times --}}
                <div style="display: grid; grid-template-columns: 1fr; md:grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div style="background: white; border-radius: 16px; padding: 30px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                        <h3 style="font-size: 18px; font-weight: 700; color: #1f2937; margin-bottom: 20px;">
                            <i class="fas fa-arrow-up" style="color: #3b82f6;"></i> Waktu Pasang
                        </h3>
                        <div id="highTideTimes"></div>
                    </div>
                    <div style="background: white; border-radius: 16px; padding: 30px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                        <h3 style="font-size: 18px; font-weight: 700; color: #1f2937; margin-bottom: 20px;">
                            <i class="fas fa-arrow-down" style="color: #ef4444;"></i> Waktu Surut
                        </h3>
                        <div id="lowTideTimes"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Wave Tab --}}
        <div id="waveTab" class="tab-content">
            <div style="background: white; border-radius: 16px; padding: 20px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div id="waveWeekDays" style="display: flex; gap: 12px; overflow-x: auto;"></div>
            </div>

            <div style="display: grid; gap: 20px;">
                {{-- Current Wave Status --}}
                <div style="background: white; border-radius: 16px; padding: 40px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); text-align: center;">
                    <div style="width: 200px; height: 200px; margin: 0 auto 20px; position: relative; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #0891b2, #0e7490); border-radius: 50%;">
                        <div style="width: 85%; height: 85%; background: white; border-radius: 50%; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                            <div id="waveCurrentHeight" style="font-size: 64px; font-weight: 700; color: #0891b2;">--</div>
                            <div style="font-size: 14px; color: #6b7280; font-weight: 600;">meter</div>
                        </div>
                    </div>
                    <div id="waveStatus" style="font-size: 22px; font-weight: 700; color: #1f2937; margin-bottom: 8px;">Kondisi gelombang sedang</div>
                    <div style="font-size: 14px; color: #6b7280;">
                        <i class="fas fa-wind"></i> Arah: <span id="waveDirection">Barat Daya</span>
                    </div>
                </div>

                {{-- Wave Chart --}}
                <div style="background: white; border-radius: 16px; padding: 30px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <h3 style="font-size: 18px; font-weight: 700; color: #1f2937; margin-bottom: 20px;">
                        <i class="fas fa-chart-line"></i> Tinggi Gelombang 24 Jam
                    </h3>
                    <div style="position: relative; height: 300px;">
                        <canvas id="waveChart"></canvas>
                    </div>
                </div>

                {{-- Wave Details --}}
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                    <div style="background: white; border-radius: 16px; padding: 25px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #0891b2, #0e7490); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px;">
                                <i class="fas fa-ruler-vertical"></i>
                            </div>
                            <div>
                                <div style="font-size: 12px; color: #6b7280; font-weight: 600;">TINGGI MAX</div>
                                <div id="waveMaxHeight" style="font-size: 24px; font-weight: 700; color: #1f2937;">2.5m</div>
                            </div>
                        </div>
                    </div>
                    <div style="background: white; border-radius: 16px; padding: 25px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #0891b2, #0e7490); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px;">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <div style="font-size: 12px; color: #6b7280; font-weight: 600;">PERIODE</div>
                                <div id="wavePeriod" style="font-size: 24px; font-weight: 700; color: #1f2937;">8s</div>
                            </div>
                        </div>
                    </div>
                    <div style="background: white; border-radius: 16px; padding: 25px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #0891b2, #0e7490); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px;">
                                <i class="fas fa-wind"></i>
                            </div>
                            <div>
                                <div style="font-size: 12px; color: #6b7280; font-weight: 600;">KECEPATAN ANGIN</div>
                                <div id="windSpeed" style="font-size: 24px; font-weight: 700; color: #1f2937;">15 km/h</div>
                            </div>
                        </div>
                    </div>
                    <div style="background: white; border-radius: 16px; padding: 25px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #0891b2, #0e7490); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px;">
                                <i class="fas fa-compass"></i>
                            </div>
                            <div>
                                <div style="font-size: 12px; color: #6b7280; font-weight: 600;">ARAH ANGIN</div>
                                <div id="windDirection" style="font-size: 24px; font-weight: 700; color: #1f2937;">225°</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Weather Tab --}}
        <div id="weatherTab" class="tab-content">
            <div style="display: grid; gap: 20px;">
                {{-- Current Weather --}}
                <div style="background: linear-gradient(135deg, #fef3c7, #fde68a); border-radius: 16px; padding: 50px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); text-align: center;">
                    <div id="weatherIcon" style="font-size: 100px; margin-bottom: 20px;">☀️</div>
                    <div id="weatherTemp" style="font-size: 72px; font-weight: 700; color: #1f2937;">28°</div>
                    <div id="weatherDesc" style="font-size: 24px; color: #6b7280; margin-top: 12px; font-weight: 600;">Cerah</div>
                    <div style="display: flex; justify-content: center; gap: 32px; margin-top: 24px; font-size: 16px;">
                        <div>
                            <i class="fas fa-temperature-high" style="color: #f59e0b;"></i>
                            <span id="weatherFeelsLike">Terasa 30°</span>
                        </div>
                        <div>
                            <i class="fas fa-droplet" style="color: #3b82f6;"></i>
                            <span id="weatherHumidity">65%</span>
                        </div>
                    </div>
                </div>

                {{-- Weather Details --}}
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                    <div style="background: white; border-radius: 16px; padding: 25px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #3b82f6, #2563eb); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px;">
                                <i class="fas fa-wind"></i>
                            </div>
                            <div>
                                <div style="font-size: 12px; color: #6b7280; font-weight: 600;">ANGIN</div>
                                <div id="weatherWind" style="font-size: 20px; font-weight: 700; color: #1f2937;">12 km/h</div>
                            </div>
                        </div>
                    </div>
                    <div style="background: white; border-radius: 16px; padding: 25px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #8b5cf6, #7c3aed); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px;">
                                <i class="fas fa-gauge"></i>
                            </div>
                            <div>
                                <div style="font-size: 12px; color: #6b7280; font-weight: 600;">TEKANAN</div>
                                <div id="weatherPressure" style="font-size: 20px; font-weight: 700; color: #1f2937;">1013 mb</div>
                            </div>
                        </div>
                    </div>
                    <div style="background: white; border-radius: 16px; padding: 25px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #06b6d4, #0891b2); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px;">
                                <i class="fas fa-eye"></i>
                            </div>
                            <div>
                                <div style="font-size: 12px; color: #6b7280; font-weight: 600;">JARAK PANDANG</div>
                                <div id="weatherVisibility" style="font-size: 20px; font-weight: 700; color: #1f2937;">10 km</div>
                            </div>
                        </div>
                    </div>
                    <div style="background: white; border-radius: 16px; padding: 25px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px;">
                                <i class="fas fa-cloud"></i>
                            </div>
                            <div>
                                <div style="font-size: 12px; color: #6b7280; font-weight: 600;">AWAN</div>
                                <div id="weatherClouds" style="font-size: 20px; font-weight: 700; color: #1f2937;">20%</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Temperature Chart --}}
                <div style="background: white; border-radius: 16px; padding: 30px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <h3 style="font-size: 18px; font-weight: 700; color: #1f2937; margin-bottom: 20px;">
                        <i class="fas fa-chart-line"></i> Suhu 24 Jam
                    </h3>
                    <div style="position: relative; height: 300px;">
                        <canvas id="weatherTempChart"></canvas>
                    </div>
                </div>

                {{-- 5-Day Forecast --}}
                <div style="background: white; border-radius: 16px; padding: 30px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <h3 style="font-size: 18px; font-weight: 700; color: #1f2937; margin-bottom: 20px;">
                        <i class="fas fa-calendar-week"></i> Prakiraan 5 Hari
                    </h3>
                    <div id="weatherDaily"></div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
.forecast-tab {
    padding: 12px 24px;
    background: transparent;
    border: none;
    border-radius: 10px;
    color: #6b7280;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.forecast-tab:hover {
    background: #f3f4f6;
    color: #1f2937;
}

.forecast-tab.active {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
}

.tab-content {
    display: none;
    animation: fadeIn 0.3s ease;
}

.tab-content.active {
    display: block;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.day-selector-btn {
    min-width: 80px;
    padding: 12px 16px;
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    color: #6b7280;
}

.day-selector-btn:hover {
    border-color: #3b82f6;
    transform: translateY(-2px);
}

.day-selector-btn.active {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    border-color: #3b82f6;
    color: white;
}

.time-block {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: #f9fafb;
    border-radius: 12px;
}

.time-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
}
</style>

<script>
let activityChart = null;
let tideChart = null;
let waveChart = null;
let weatherTempChart = null;

// Tab Switching
function switchTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Remove active from all tab buttons
    document.querySelectorAll('.forecast-tab').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected tab
    document.getElementById(tabName + 'Tab').classList.add('active');
    document.querySelector(`[data-tab="${tabName}"]`).classList.add('active');
    
    // Load data for selected tab
    if (tabName === 'activity') loadActivityData();
    else if (tabName === 'tide') loadTideData();
    else if (tabName === 'wave') loadWaveData();
    else if (tabName === 'weather') loadWeatherData();
}

// Populate Week Days
function populateWeekDays(containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;
    
    const days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
    const today = new Date();
    
    container.innerHTML = '';
    for (let i = 0; i < 7; i++) {
        const date = new Date(today);
        date.setDate(today.getDate() + i);
        
        const btn = document.createElement('button');
        btn.className = 'day-selector-btn' + (i === 0 ? ' active' : '');
        btn.innerHTML = `
            <div style="font-size: 12px; opacity: 0.8;">${days[date.getDay()]}</div>
            <div style="font-size: 18px; font-weight: 700; margin-top: 4px;">${date.getDate()}</div>
        `;
        container.appendChild(btn);
    }
}

// Activity Tab Functions
function loadActivityData() {
    populateWeekDays('activityWeekDays');
    
    const score = 65;
    document.getElementById('activityScore').textContent = score;
    document.getElementById('activityGaugeCircle').style.setProperty('--score', score);
    
    let level = 'Aktivitas sangat rendah';
    if (score >= 80) level = 'Aktivitas sangat tinggi';
    else if (score >= 60) level = 'Aktivitas tinggi';
    else if (score >= 40) level = 'Aktivitas sedang';
    else if (score >= 20) level = 'Aktivitas rendah';
    
    document.getElementById('activityLevel').textContent = level;
    
    createActivityChart();
    populateFishingTimes();
}

function createActivityChart() {
    const ctx = document.getElementById('activityChart').getContext('2d');
    
    if (activityChart) activityChart.destroy();
    
    activityChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['00', '02', '04', '06', '08', '10', '12', '14', '16', '18', '20', '22', '24'],
            datasets: [{
                label: 'Aktivitas',
                data: [30, 25, 35, 60, 75, 70, 50, 45, 40, 55, 80, 65, 30],
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, max: 100, grid: { color: '#f3f4f6' } },
                x: { grid: { display: false } }
            }
        }
    });
}

function populateFishingTimes() {
    document.getElementById('majorTimes').innerHTML = `
        <div class="time-block">
            <div class="time-icon"><i class="fas fa-sun"></i></div>
            <div>
                <div style="font-size: 18px; font-weight: 600;">06:15 - 08:15</div>
                <div style="font-size: 13px; color: #6b7280;">Pagi hari</div>
            </div>
        </div>
        <div class="time-block">
            <div class="time-icon" style="background: linear-gradient(135deg, #6366f1, #4f46e5);"><i class="fas fa-moon"></i></div>
            <div>
                <div style="font-size: 18px; font-weight: 600;">18:30 - 20:30</div>
                <div style="font-size: 13px; color: #6b7280;">Sore hari</div>
            </div>
        </div>
    `;
    
    document.getElementById('minorTimes').innerHTML = `
        <div class="time-block">
            <div class="time-icon" style="background: linear-gradient(135deg, #6b7280, #4b5563);"><i class="fas fa-cloud-sun"></i></div>
            <div>
                <div style="font-size: 18px; font-weight: 600;">12:00 - 13:00</div>
                <div style="font-size: 13px; color: #6b7280;">Siang hari</div>
            </div>
        </div>
        <div class="time-block">
            <div class="time-icon" style="background: linear-gradient(135deg, #6b7280, #4b5563);"><i class="fas fa-star"></i></div>
            <div>
                <div style="font-size: 18px; font-weight: 600;">00:15 - 01:15</div>
                <div style="font-size: 13px; color: #6b7280;">Tengah malam</div>
            </div>
        </div>
    `;
}

// Tide Tab Functions
function loadTideData() {
    populateWeekDays('tideWeekDays');
    createTideChart();
    populateTideTimes();
}

function createTideChart() {
    const ctx = document.getElementById('tideChart').getContext('2d');
    
    if (tideChart) tideChart.destroy();
    
    tideChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['00', '02', '04', '06', '08', '10', '12', '14', '16', '18', '20', '22', '24'],
            datasets: [{
                label: 'Tinggi (m)',
                data: [1.2, 1.8, 2.1, 1.9, 1.3, 0.8, 0.5, 0.7, 1.2, 1.8, 2.2, 2.0, 1.5],
                borderColor: '#0891b2',
                backgroundColor: 'rgba(8, 145, 178, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { 
                    beginAtZero: true, 
                    max: 2.5,
                    ticks: { callback: v => v + 'm' },
                    grid: { color: '#f3f4f6' } 
                },
                x: { grid: { display: false } }
            }
        }
    });
}

function populateTideTimes() {
    document.getElementById('highTideTimes').innerHTML = `
        <div class="time-block">
            <div class="time-icon" style="background: linear-gradient(135deg, #3b82f6, #2563eb);"><i class="fas fa-arrow-up"></i></div>
            <div>
                <div style="font-size: 18px; font-weight: 600;">04:30</div>
                <div style="font-size: 13px; color: #6b7280;">Tinggi: 2.1m</div>
            </div>
        </div>
        <div class="time-block">
            <div class="time-icon" style="background: linear-gradient(135deg, #3b82f6, #2563eb);"><i class="fas fa-arrow-up"></i></div>
            <div>
                <div style="font-size: 18px; font-weight: 600;">16:45</div>
                <div style="font-size: 13px; color: #6b7280;">Tinggi: 2.2m</div>
            </div>
        </div>
    `;
    
    document.getElementById('lowTideTimes').innerHTML = `
        <div class="time-block">
            <div class="time-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626);"><i class="fas fa-arrow-down"></i></div>
            <div>
                <div style="font-size: 18px; font-weight: 600;">10:15</div>
                <div style="font-size: 13px; color: #6b7280;">Tinggi: 0.5m</div>
            </div>
        </div>
        <div class="time-block">
            <div class="time-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626);"><i class="fas fa-arrow-down"></i></div>
            <div>
                <div style="font-size: 18px; font-weight: 600;">22:30</div>
                <div style="font-size: 13px; color: #6b7280;">Tinggi: 0.7m</div>
            </div>
        </div>
    `;
}

// Wave Tab Functions
function loadWaveData() {
    populateWeekDays('waveWeekDays');
    
    const height = 1.8;
    document.getElementById('waveCurrentHeight').textContent = height.toFixed(1);
    
    let status = 'Kondisi gelombang tenang';
    if (height >= 2.5) status = 'Kondisi gelombang tinggi';
    else if (height >= 1.5) status = 'Kondisi gelombang sedang';
    
    document.getElementById('waveStatus').textContent = status;
    
    createWaveChart();
}

function createWaveChart() {
    const ctx = document.getElementById('waveChart').getContext('2d');
    
    if (waveChart) waveChart.destroy();
    
    waveChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['00', '02', '04', '06', '08', '10', '12', '14', '16', '18', '20', '22', '24'],
            datasets: [{
                label: 'Tinggi (m)',
                data: [1.2, 1.4, 1.6, 1.8, 2.0, 2.2, 2.5, 2.3, 2.0, 1.7, 1.5, 1.3, 1.2],
                borderColor: '#0891b2',
                backgroundColor: 'rgba(8, 145, 178, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { 
                    beginAtZero: true, 
                    max: 3,
                    ticks: { callback: v => v + 'm' },
                    grid: { color: '#f3f4f6' } 
                },
                x: { grid: { display: false } }
            }
        }
    });
}

// Weather Tab Functions
function loadWeatherData() {
    createWeatherTempChart();
    populateWeatherDaily();
}

function createWeatherTempChart() {
    const ctx = document.getElementById('weatherTempChart').getContext('2d');
    
    if (weatherTempChart) weatherTempChart.destroy();
    
    weatherTempChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['00', '02', '04', '06', '08', '10', '12', '14', '16', '18', '20', '22', '24'],
            datasets: [{
                label: 'Suhu (°C)',
                data: [24, 23, 22, 23, 25, 27, 29, 31, 32, 30, 28, 26, 25],
                borderColor: '#f59e0b',
                backgroundColor: 'rgba(245, 158, 11, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { 
                    ticks: { callback: v => v + '°C' },
                    grid: { color: '#f3f4f6' } 
                },
                x: { grid: { display: false } }
            }
        }
    });
}

function populateWeatherDaily() {
    const daily = document.getElementById('weatherDaily');
    const days = ['Besok', 'Jumat', 'Sabtu', 'Minggu', 'Senin'];
    const highs = [32, 31, 30, 29, 31];
    const lows = [24, 23, 24, 23, 24];
    const icons = ['☀️', '⛅', '🌤️', '⛅', '☀️'];
    const descs = ['Cerah', 'Berawan sebagian', 'Cerah berawan', 'Berawan', 'Cerah'];
    
    daily.innerHTML = days.map((day, i) => `
        <div style="display: flex; align-items: center; justify-content: space-between; padding: 20px; background: #f9fafb; border-radius: 12px; margin-bottom: 12px;">
            <div style="flex: 1;">
                <div style="font-size: 18px; font-weight: 700; color: #1f2937;">${day}</div>
                <div style="font-size: 13px; color: #6b7280;">${descs[i]}</div>
            </div>
            <div style="font-size: 48px; margin: 0 24px;">${icons[i]}</div>
            <div style="display: flex; gap: 12px; align-items: center;">
                <div style="font-size: 24px; font-weight: 700; color: #1f2937;">${highs[i]}°</div>
                <div style="font-size: 18px; color: #6b7280;">${lows[i]}°</div>
            </div>
        </div>
    `).join('');
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    loadActivityData();
});
</script>

@endsection