{{-- Weather Modal Overlay --}}
<div class="modal-overlay" id="weatherModalOverlay"></div>

{{-- Weather Popup --}}
<div class="fish-popup" id="weatherPopup">
    {{-- Loading Overlay --}}
    <div class="loading-overlay" id="weatherLoadingOverlay">
        <div class="loading-spinner"></div>
        <div style="font-size: 14px; color: #6b7280;">Memuat data cuaca...</div>
    </div>

    {{-- Popup Header --}}
    <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); padding: 24px; color: white; position: relative;">
        <h2 style="font-size: 28px; font-weight: 700; margin-bottom: 4px;">
            <i class="fas fa-cloud-sun"></i> Prakiraan Cuaca
        </h2>
        <div style="font-size: 14px; opacity: 0.9; display: flex; align-items: center; gap: 6px;">
            <i class="fas fa-location-dot"></i>
            <span id="weatherLocationName">Pekanbaru, Indonesia</span>
        </div>
        <button onclick="closeWeatherModal()" style="position: absolute; top: 20px; right: 20px; width: 40px; height: 40px; background: rgba(255,255,255,0.2); border: none; border-radius: 10px; color: white; font-size: 20px; cursor: pointer;">
            <i class="fas fa-times"></i>
        </button>
    </div>

    {{-- Popup Content --}}
    <div style="background: white; max-height: calc(85vh - 100px); overflow-y: auto;">
        
        {{-- Current Weather --}}
        <div style="padding: 40px 20px; text-align: center; background: linear-gradient(to bottom, #fef3c7, white); border-bottom: 1px solid #e5e7eb;">
            <div id="weatherIcon" style="font-size: 80px; margin-bottom: 16px;">☀️</div>
            <div id="weatherTemp" style="font-size: 56px; font-weight: 700; color: #1f2937;">28°</div>
            <div id="weatherDesc" style="font-size: 18px; color: #6b7280; margin-top: 8px;">Cerah</div>
            <div style="display: flex; justify-content: center; gap: 24px; margin-top: 20px; font-size: 14px;">
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

        {{-- Weather Details Grid --}}
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; padding: 20px;">
            <div style="background: #f9fafb; padding: 16px; border-radius: 12px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #3b82f6, #2563eb); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white;">
                        <i class="fas fa-wind"></i>
                    </div>
                    <div>
                        <div style="font-size: 12px; color: #6b7280; font-weight: 600;">ANGIN</div>
                        <div id="weatherWind" style="font-size: 16px; font-weight: 700; color: #1f2937;">12 km/h</div>
                    </div>
                </div>
            </div>
            
            <div style="background: #f9fafb; padding: 16px; border-radius: 12px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #8b5cf6, #7c3aed); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white;">
                        <i class="fas fa-gauge"></i>
                    </div>
                    <div>
                        <div style="font-size: 12px; color: #6b7280; font-weight: 600;">TEKANAN</div>
                        <div id="weatherPressure" style="font-size: 16px; font-weight: 700; color: #1f2937;">1013 mb</div>
                    </div>
                </div>
            </div>
            
            <div style="background: #f9fafb; padding: 16px; border-radius: 12px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #06b6d4, #0891b2); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white;">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div>
                        <div style="font-size: 12px; color: #6b7280; font-weight: 600;">JARAK PANDANG</div>
                        <div id="weatherVisibility" style="font-size: 16px; font-weight: 700; color: #1f2937;">10 km</div>
                    </div>
                </div>
            </div>
            
            <div style="background: #f9fafb; padding: 16px; border-radius: 12px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white;">
                        <i class="fas fa-cloud"></i>
                    </div>
                    <div>
                        <div style="font-size: 12px; color: #6b7280; font-weight: 600;">AWAN</div>
                        <div id="weatherClouds" style="font-size: 16px; font-weight: 700; color: #1f2937;">20%</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Temperature Chart --}}
        <div style="padding: 30px 20px; background: #f9fafb;">
            <h3 style="font-size: 16px; font-weight: 700; color: #1f2937; margin-bottom: 16px;">
                <i class="fas fa-chart-line"></i> Suhu 24 Jam
            </h3>
            <div style="position: relative; height: 200px; margin-bottom: 20px;">
                <canvas id="weatherTempChart"></canvas>
            </div>
            <div style="display: flex; justify-content: space-between; margin-top: 12px; font-size: 12px; color: #6b7280; font-weight: 600;">
                <span>00:00</span><span>06:00</span><span>12:00</span><span>18:00</span><span>24:00</span>
            </div>
        </div>

        {{-- Hourly Forecast --}}
        <div style="padding: 20px;">
            <h3 style="font-size: 16px; font-weight: 700; color: #1f2937; margin-bottom: 16px;">
                <i class="fas fa-clock"></i> Prakiraan Per Jam
            </h3>
            <div id="weatherHourly" style="display: flex; gap: 12px; overflow-x: auto; padding-bottom: 10px;">
                <!-- Populated by JavaScript -->
            </div>
        </div>

        {{-- 5-Day Forecast --}}
        <div style="padding: 20px; background: white;">
            <h3 style="font-size: 16px; font-weight: 700; color: #1f2937; margin-bottom: 16px;">
                <i class="fas fa-calendar-week"></i> Prakiraan 5 Hari
            </h3>
            <div id="weatherDaily">
                <!-- Populated by JavaScript -->
            </div>
        </div>

    </div>
</div>

<script>
let weatherTempChart = null;

function openWeatherModal() {
    const overlay = document.getElementById('weatherModalOverlay');
    const popup = document.getElementById('weatherPopup');
    const loading = document.getElementById('weatherLoadingOverlay');
    
    overlay.classList.add('active');
    popup.classList.add('active');
    loading.style.display = 'flex';
    
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

function loadWeatherData() {
    const lat = -0.947136;
    const lon = 100.417419;
    
    fetch(`/api/forecast/weather-data?lat=${lat}&lon=${lon}`)
        .then(response => response.json())
        .then(data => {
            const loading = document.getElementById('weatherLoadingOverlay');
            loading.style.display = 'none';
            
            if (data.success) {
                updateCurrentWeather(data.current);
                createWeatherTempChart();
                populateHourlyForecast();
                populateDailyForecast();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('weatherLoadingOverlay').style.display = 'none';
        });
}

function updateCurrentWeather(data) {
    document.getElementById('weatherTemp').textContent = data.temp + '°';
    document.getElementById('weatherDesc').textContent = data.desc;
    document.getElementById('weatherIcon').textContent = data.icon;
    document.getElementById('weatherFeelsLike').textContent = `Terasa ${data.feelsLike}°`;
    document.getElementById('weatherHumidity').textContent = data.humidity + '%';
    document.getElementById('weatherWind').textContent = data.wind + ' km/h';
    document.getElementById('weatherPressure').textContent = data.pressure + ' mb';
    document.getElementById('weatherVisibility').textContent = data.visibility + ' km';
    document.getElementById('weatherClouds').textContent = data.clouds + '%';
}

function createWeatherTempChart() {
    const ctx = document.getElementById('weatherTempChart').getContext('2d');
    
    if (weatherTempChart) {
        weatherTempChart.destroy();
    }
    
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
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    ticks: { callback: v => v + '°C' },
                    grid: { color: '#f3f4f6' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
}

function populateHourlyForecast() {
    const hourly = document.getElementById('weatherHourly');
    const hours = ['13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
    const temps = [31, 32, 32, 31, 30, 29, 27, 26];
    const icons = ['☀️', '☀️', '⛅', '⛅', '🌤️', '🌤️', '🌙', '🌙'];
    
    hourly.innerHTML = hours.map((hour, i) => `
        <div style="min-width: 80px; background: white; padding: 16px; border-radius: 12px; border: 2px solid #f3f4f6; text-align: center;">
            <div style="font-size: 12px; color: #6b7280; font-weight: 600; margin-bottom: 8px;">${hour}</div>
            <div style="font-size: 32px; margin: 8px 0;">${icons[i]}</div>
            <div style="font-size: 18px; font-weight: 700; color: #1f2937;">${temps[i]}°</div>
        </div>
    `).join('');
}

function populateDailyForecast() {
    const daily = document.getElementById('weatherDaily');
    const days = ['Besok', 'Jumat', 'Sabtu', 'Minggu', 'Senin'];
    const highs = [32, 31, 30, 29, 31];
    const lows = [24, 23, 24, 23, 24];
    const icons = ['☀️', '⛅', '🌤️', '⛅', '☀️'];
    const descs = ['Cerah', 'Berawan sebagian', 'Cerah berawan', 'Berawan', 'Cerah'];
    
    daily.innerHTML = days.map((day, i) => `
        <div style="display: flex; align-items: center; justify-content: space-between; padding: 16px; background: #f9fafb; border-radius: 12px; margin-bottom: 12px;">
            <div style="flex: 1;">
                <div style="font-size: 16px; font-weight: 700; color: #1f2937;">${day}</div>
                <div style="font-size: 12px; color: #6b7280;">${descs[i]}</div>
            </div>
            <div style="font-size: 40px; margin: 0 20px;">${icons[i]}</div>
            <div style="display: flex; gap: 8px; align-items: center;">
                <div style="font-size: 20px; font-weight: 700; color: #1f2937;">${highs[i]}°</div>
                <div style="font-size: 16px; color: #6b7280;">${lows[i]}°</div>
            </div>
        </div>
    `).join('');
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('weatherModalOverlay')?.addEventListener('click', closeWeatherModal);
});
</script>