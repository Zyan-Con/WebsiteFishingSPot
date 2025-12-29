{{-- Activity Modal Overlay --}}
<div class="modal-overlay" id="activityModalOverlay"></div>

{{-- Fish Activity Popup --}}
<div class="fish-popup" id="fishPopup">
    {{-- Loading Overlay --}}
    <div class="loading-overlay" id="fishLoadingOverlay">
        <div class="loading-spinner"></div>
        <div style="font-size: 14px; color: #6b7280;">Memuat data aktivitas ikan...</div>
    </div>

    {{-- Popup Header --}}
    <div style="background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%); padding: 24px; color: white; position: relative;">
        <h2 style="font-size: 28px; font-weight: 700; margin-bottom: 4px;">
            <i class="fas fa-fish"></i> Aktivitas Ikan
        </h2>
        <div style="font-size: 14px; opacity: 0.9; display: flex; align-items: center; gap: 6px;">
            <i class="fas fa-location-dot"></i>
            <span id="fishLocationName">Pekanbaru, Indonesia</span>
        </div>
        <button onclick="closeActivityModal()" style="position: absolute; top: 20px; right: 20px; width: 40px; height: 40px; background: rgba(255,255,255,0.2); border: none; border-radius: 10px; color: white; font-size: 20px; cursor: pointer;">
            <i class="fas fa-times"></i>
        </button>
    </div>

    {{-- Popup Content --}}
    <div style="background: white; max-height: calc(85vh - 100px); overflow-y: auto;">
        {{-- Week Days Selector --}}
        <div id="fishWeekDays" style="display: flex; padding: 20px; gap: 12px; overflow-x: auto; border-bottom: 1px solid #e5e7eb;"></div>

        {{-- Activity Gauge & Score --}}
        <div style="padding: 40px 20px; text-align: center;">
            <div style="width: 180px; height: 180px; margin: 0 auto 20px; position: relative;">
                <div id="fishGaugeCircle" style="width: 100%; height: 100%; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <div id="fishGaugeScore" style="width: 85%; height: 85%; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 56px; font-weight: 700; color: #1e40af;">--</div>
                </div>
            </div>
            <div id="fishActivityLevel" style="font-size: 22px; font-weight: 700; color: #1f2937; margin-bottom: 8px;">Memuat data...</div>
            <div style="font-size: 14px; color: #6b7280;">Waktu mancing terbaik hari ini</div>
        </div>

        {{-- Activity Chart --}}
        <div style="padding: 30px 20px; background: #f9fafb;">
            <div style="position: relative; height: 180px; margin-bottom: 20px;">
                <canvas id="fishActivityChart"></canvas>
            </div>
            <div style="display: flex; justify-content: space-between; margin-top: 12px; font-size: 12px; color: #6b7280; font-weight: 600;">
                <span>00:00</span><span>06:00</span><span>12:00</span><span>18:00</span><span>24:00</span>
            </div>
        </div>

        {{-- Major & Minor Fishing Times --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; padding: 20px;">
            <div style="background: white; padding: 20px; border-radius: 12px; border: 2px solid #e5e7eb;">
                <h3 style="font-size: 14px; text-transform: uppercase; color: #6b7280; margin-bottom: 12px; font-weight: 700;">
                    <i class="fas fa-star" style="color: #f59e0b;"></i> WAKTU UTAMA
                </h3>
                <div id="fishMajorTimes">
                    <div class="time-block">
                        <div class="time-icon"><i class="fas fa-sun"></i></div>
                        <div>
                            <div style="font-size: 16px; font-weight: 600;">--:--</div>
                            <div style="font-size: 12px; color: #6b7280;">Memuat...</div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="background: white; padding: 20px; border-radius: 12px; border: 2px solid #e5e7eb;">
                <h3 style="font-size: 14px; text-transform: uppercase; color: #6b7280; margin-bottom: 12px; font-weight: 700;">
                    <i class="fas fa-clock" style="color: #3b82f6;"></i> WAKTU MINOR
                </h3>
                <div id="fishMinorTimes">
                    <div class="time-block">
                        <div class="time-icon" style="background: linear-gradient(135deg, #6b7280, #4b5563);"><i class="fas fa-moon"></i></div>
                        <div>
                            <div style="font-size: 16px; font-weight: 600;">--:--</div>
                            <div style="font-size: 12px; color: #6b7280;">Memuat...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let activityChart = null;

function openActivityModal() {
    const overlay = document.getElementById('activityModalOverlay');
    const popup = document.getElementById('fishPopup');
    const loading = document.getElementById('fishLoadingOverlay');
    
    // Show modal with animation
    overlay.classList.add('active');
    popup.classList.add('active');
    loading.style.display = 'flex';
    
    // Prevent body scroll
    document.body.style.overflow = 'hidden';
    
    // Load fish activity data
    loadFishActivityData();
}

function closeActivityModal() {
    const overlay = document.getElementById('activityModalOverlay');
    const popup = document.getElementById('fishPopup');
    
    // Add closing animation
    overlay.classList.add('closing');
    popup.classList.add('closing');
    
    setTimeout(() => {
        overlay.classList.remove('active', 'closing');
        popup.classList.remove('active', 'closing');
        document.body.style.overflow = '';
    }, 300);
}

function loadFishActivityData() {
    // Simulate API call - replace with actual API
    setTimeout(() => {
        const loading = document.getElementById('fishLoadingOverlay');
        loading.style.display = 'none';
        
        // Populate week days
        populateWeekDays('fishWeekDays');
        
        // Set activity score (example: 65)
        const score = 65;
        updateFishScore(score);
        
        // Create activity chart
        createFishActivityChart();
        
        // Populate fishing times
        populateFishingTimes();
    }, 1500);
}

function populateWeekDays(containerId) {
    const container = document.getElementById(containerId);
    const days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
    const today = new Date();
    
    container.innerHTML = '';
    for (let i = 0; i < 7; i++) {
        const date = new Date(today);
        date.setDate(today.getDate() + i);
        
        const btn = document.createElement('button');
        btn.className = 'day-selector-btn' + (i === 0 ? ' active' : '');
        btn.innerHTML = `
            <div style="font-size: 12px; color: inherit; opacity: 0.8;">${days[date.getDay()]}</div>
            <div style="font-size: 18px; font-weight: 700; margin: 4px 0;">${date.getDate()}</div>
        `;
        container.appendChild(btn);
    }
}

function updateFishScore(score) {
    document.getElementById('fishGaugeScore').textContent = score;
    document.getElementById('fishGaugeCircle').style.setProperty('--score', score);
    
    let level = 'Aktivitas sangat rendah';
    if (score >= 80) level = 'Aktivitas sangat tinggi';
    else if (score >= 60) level = 'Aktivitas tinggi';
    else if (score >= 40) level = 'Aktivitas sedang';
    else if (score >= 20) level = 'Aktivitas rendah';
    
    document.getElementById('fishActivityLevel').textContent = level;
}

function createFishActivityChart() {
    const ctx = document.getElementById('fishActivityChart').getContext('2d');
    
    if (activityChart) {
        activityChart.destroy();
    }
    
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
                pointRadius: 4,
                pointHoverRadius: 6
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
                    max: 100,
                    ticks: { display: false },
                    grid: { color: '#f3f4f6' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
}

function populateFishingTimes() {
    document.getElementById('fishMajorTimes').innerHTML = `
        <div class="time-block">
            <div class="time-icon"><i class="fas fa-sun"></i></div>
            <div>
                <div style="font-size: 16px; font-weight: 600;">06:15 - 08:15</div>
                <div style="font-size: 12px; color: #6b7280;">Pagi hari</div>
            </div>
        </div>
        <div class="time-block">
            <div class="time-icon"><i class="fas fa-moon"></i></div>
            <div>
                <div style="font-size: 16px; font-weight: 600;">18:30 - 20:30</div>
                <div style="font-size: 12px; color: #6b7280;">Sore hari</div>
            </div>
        </div>
    `;
    
    document.getElementById('fishMinorTimes').innerHTML = `
        <div class="time-block">
            <div class="time-icon" style="background: linear-gradient(135deg, #6b7280, #4b5563);"><i class="fas fa-cloud-sun"></i></div>
            <div>
                <div style="font-size: 16px; font-weight: 600;">12:00 - 13:00</div>
                <div style="font-size: 12px; color: #6b7280;">Siang hari</div>
            </div>
        </div>
        <div class="time-block">
            <div class="time-icon" style="background: linear-gradient(135deg, #6b7280, #4b5563);"><i class="fas fa-star"></i></div>
            <div>
                <div style="font-size: 16px; font-weight: 600;">00:15 - 01:15</div>
                <div style="font-size: 12px; color: #6b7280;">Tengah malam</div>
            </div>
        </div>
    `;
}

// Close on overlay click
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('activityModalOverlay')?.addEventListener('click', closeActivityModal);
});
</script>