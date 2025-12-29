{{-- Wave Modal Overlay --}}
<div class="modal-overlay" id="waveModalOverlay"></div>

{{-- Wave Popup --}}
<div class="fish-popup" id="wavePopup">
    {{-- Loading Overlay --}}
    <div class="loading-overlay" id="waveLoadingOverlay">
        <div class="loading-spinner"></div>
        <div style="font-size: 14px; color: #6b7280;">Memuat data gelombang...</div>
    </div>

    {{-- Popup Header --}}
    <div style="background: linear-gradient(135deg, #0891b2 0%, #0e7490 100%); padding: 24px; color: white; position: relative;">
        <h2 style="font-size: 28px; font-weight: 700; margin-bottom: 4px;">
            <i class="fas fa-water"></i> Prakiraan Gelombang
        </h2>
        <div style="font-size: 14px; opacity: 0.9; display: flex; align-items: center; gap: 6px;">
            <i class="fas fa-location-dot"></i>
            <span id="waveLocationName">Pekanbaru, Indonesia</span>
        </div>
        <button onclick="closeWaveModal()" style="position: absolute; top: 20px; right: 20px; width: 40px; height: 40px; background: rgba(255,255,255,0.2); border: none; border-radius: 10px; color: white; font-size: 20px; cursor: pointer;">
            <i class="fas fa-times"></i>
        </button>
    </div>

    {{-- Popup Content --}}
    <div style="background: white; max-height: calc(85vh - 100px); overflow-y: auto;">
        
        {{-- Week Days Selector --}}
        <div id="waveWeekDays" style="display: flex; padding: 20px; gap: 12px; overflow-x: auto; border-bottom: 1px solid #e5e7eb;"></div>

        {{-- Current Wave Status --}}
        <div style="padding: 40px 20px; text-align: center; border-bottom: 1px solid #e5e7eb;">
            <div style="width: 180px; height: 180px; margin: 0 auto 20px; position: relative; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #0891b2, #0e7490); border-radius: 50%;">
                <div style="width: 85%; height: 85%; background: white; border-radius: 50%; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                    <div id="waveCurrentHeight" style="font-size: 56px; font-weight: 700; color: #0891b2;">--</div>
                    <div style="font-size: 14px; color: #6b7280; font-weight: 600;">meter</div>
                </div>
            </div>
            <div id="waveStatus" style="font-size: 22px; font-weight: 700; color: #1f2937; margin-bottom: 8px;">
                Kondisi gelombang sedang
            </div>
            <div style="font-size: 14px; color: #6b7280;">
                <i class="fas fa-wind"></i> Arah: <span id="waveDirection">Barat Daya</span>
            </div>
        </div>

        {{-- Wave Height Chart --}}
        <div style="padding: 30px 20px; background: #f9fafb;">
            <h3 style="font-size: 16px; font-weight: 700; color: #1f2937; margin-bottom: 16px;">
                <i class="fas fa-chart-line"></i> Tinggi Gelombang 24 Jam
            </h3>
            <div style="position: relative; height: 250px; margin-bottom: 20px;">
                <canvas id="waveChart"></canvas>
            </div>
            <div style="display: flex; justify-content: space-between; margin-top: 12px; font-size: 12px; color: #6b7280; font-weight: 600;">
                <span>00:00</span><span>06:00</span><span>12:00</span><span>18:00</span><span>24:00</span>
            </div>
        </div>

        {{-- Wave Details Grid --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; padding: 20px;">
            <div style="background: white; padding: 20px; border-radius: 12px; border: 2px solid #e5e7eb;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                    <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #0891b2, #0e7490); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 20px;">
                        <i class="fas fa-ruler-vertical"></i>
                    </div>
                    <div>
                        <div style="font-size: 12px; color: #6b7280; font-weight: 600;">TINGGI MAX</div>
                        <div id="waveMaxHeight" style="font-size: 20px; font-weight: 700; color: #1f2937;">2.5m</div>
                    </div>
                </div>
            </div>
            
            <div style="background: white; padding: 20px; border-radius: 12px; border: 2px solid #e5e7eb;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                    <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #0891b2, #0e7490); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 20px;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <div style="font-size: 12px; color: #6b7280; font-weight: 600;">PERIODE</div>
                        <div id="wavePeriod" style="font-size: 20px; font-weight: 700; color: #1f2937;">8s</div>
                    </div>
                </div>
            </div>
            
            <div style="background: white; padding: 20px; border-radius: 12px; border: 2px solid #e5e7eb;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                    <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #0891b2, #0e7490); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 20px;">
                        <i class="fas fa-wind"></i>
                    </div>
                    <div>
                        <div style="font-size: 12px; color: #6b7280; font-weight: 600;">KECEPATAN ANGIN</div>
                        <div id="windSpeed" style="font-size: 20px; font-weight: 700; color: #1f2937;">15 km/h</div>
                    </div>
                </div>
            </div>
            
            <div style="background: white; padding: 20px; border-radius: 12px; border: 2px solid #e5e7eb;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                    <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #0891b2, #0e7490); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 20px;">
                        <i class="fas fa-compass"></i>
                    </div>
                    <div>
                        <div style="font-size: 12px; color: #6b7280; font-weight: 600;">ARAH ANGIN</div>
                        <div id="windDirection" style="font-size: 20px; font-weight: 700; color: #1f2937;">225°</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Safety Warning --}}
        <div id="waveSafetyWarning" style="margin: 20px; padding: 20px; background: linear-gradient(135deg, #fef3c7, #fde68a); border-radius: 12px; border-left: 4px solid #f59e0b;">
            <div style="display: flex; align-items: start; gap: 12px;">
                <i class="fas fa-exclamation-triangle" style="color: #f59e0b; font-size: 24px;"></i>
                <div>
                    <div style="font-size: 16px; font-weight: 700; color: #92400e; margin-bottom: 4px;">Perhatian!</div>
                    <div style="font-size: 14px; color: #78350f;">Gelombang sedang hingga tinggi. Waspadai saat berlayar.</div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
let waveChart = null;

function openWaveModal() {
    const overlay = document.getElementById('waveModalOverlay');
    const popup = document.getElementById('wavePopup');
    const loading = document.getElementById('waveLoadingOverlay');
    
    overlay.classList.add('active');
    popup.classList.add('active');
    loading.style.display = 'flex';
    
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

function loadWaveData() {
    const lat = -0.947136;
    const lon = 100.417419;
    
    fetch(`/api/forecast/wave-data?lat=${lat}&lon=${lon}`)
        .then(response => response.json())
        .then(data => {
            const loading = document.getElementById('waveLoadingOverlay');
            loading.style.display = 'none';
            
            if (data.success) {
                const current = data.current;
                
                populateWeekDays('waveWeekDays');
                updateWaveStatus(current.height);
                createWaveChart(data.hourly);
                
                document.getElementById('waveMaxHeight').textContent = data.maxHeight.toFixed(1) + 'm';
                document.getElementById('wavePeriod').textContent = current.period + 's';
                document.getElementById('windSpeed').textContent = Math.round(current.windSpeed) + ' km/h';
                document.getElementById('windDirection').textContent = current.windDirection + '°';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('waveLoadingOverlay').style.display = 'none';
        });
}

function updateWaveStatus(height) {
    document.getElementById('waveCurrentHeight').textContent = height.toFixed(1);
    
    let status = 'Kondisi gelombang tenang';
    if (height >= 2.5) status = 'Kondisi gelombang tinggi';
    else if (height >= 1.5) status = 'Kondisi gelombang sedang';
    
    document.getElementById('waveStatus').textContent = status;
}

function createWaveChart() {
    const ctx = document.getElementById('waveChart').getContext('2d');
    
    if (waveChart) {
        waveChart.destroy();
    }
    
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
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 3,
                    ticks: { callback: v => v + 'm' },
                    grid: { color: '#f3f4f6' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
}

function updateWaveDetails() {
    document.getElementById('waveMaxHeight').textContent = '2.5m';
    document.getElementById('wavePeriod').textContent = '8s';
    document.getElementById('windSpeed').textContent = '15 km/h';
    document.getElementById('windDirection').textContent = '225° SW';
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('waveModalOverlay')?.addEventListener('click', closeWaveModal);
});
</script>