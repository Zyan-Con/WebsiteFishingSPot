@extends('layouts.app')

@section('content')
<div style="min-height: 100vh; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
    {{-- Header Section --}}
    <div style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); box-shadow: 0 4px 6px rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 100;">
        <div style="max-width: 1400px; margin: 0 auto; padding: 24px 32px;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <h1 style="font-size: 32px; font-weight: 800; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 8px;">
                        <i class="fas fa-cloud-sun"></i> Prakiraan Cuaca
                    </h1>
                    <div style="font-size: 14px; color: #6b7280; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-location-dot" style="color: #f59e0b;"></i>
                        <span>{{ $location ?? 'Pekanbaru, Indonesia' }}</span>
                        <span style="margin: 0 8px;">•</span>
                        <i class="fas fa-calendar" style="color: #f59e0b;"></i>
                        <span>{{ \Carbon\Carbon::parse($date ?? now())->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                    </div>
                </div>
                <button onclick="window.location.reload()" style="padding: 12px 24px; background: white; border: 2px solid #e5e7eb; border-radius: 12px; color: #1f2937; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
            </div>
        </div>
    </div>

    <div style="max-width: 1400px; margin: 0 auto; padding: 32px;">
        {{-- Current Weather Hero --}}
        <div style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); border-radius: 32px; padding: 48px; margin-bottom: 32px; box-shadow: 0 20px 50px rgba(245, 158, 11, 0.4); position: relative; overflow: hidden;">
            <div style="position: absolute; top: -100px; right: -100px; width: 300px; height: 300px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="position: absolute; bottom: -80px; left: -80px; width: 250px; height: 250px; background: rgba(255,255,255,0.08); border-radius: 50%;"></div>
            
            <div style="position: relative; z-index: 1; display: grid; grid-template-columns: 1fr auto; gap: 48px; align-items: center;">
                <div>
                    <div style="font-size: 16px; color: rgba(255,255,255,0.9); font-weight: 600; margin-bottom: 16px; text-transform: uppercase; letter-spacing: 1px;">
                        Cuaca Saat Ini
                    </div>
                    <div style="display: flex; align-items: center; gap: 24px; margin-bottom: 24px;">
                        <div style="font-size: 96px; font-weight: 900; color: white; line-height: 1;">
                            {{ isset($weatherData['current']['main']['temp']) ? round($weatherData['current']['main']['temp']) : '28' }}°
                        </div>
                        <div>
                            <div style="font-size: 28px; color: white; font-weight: 700; margin-bottom: 8px;">
                                {{ $weatherData['current']['weather'][0]['description'] ?? 'Cerah' }}
                            </div>
                            <div style="font-size: 16px; color: rgba(255,255,255,0.8);">
                                Terasa seperti {{ isset($weatherData['current']['main']['feels_like']) ? round($weatherData['current']['main']['feels_like']) : '30' }}°C
                            </div>
                        </div>
                    </div>
                    
                    {{-- Weather Details Grid --}}
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 32px;">
                        <div style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); border-radius: 16px; padding: 20px;">
                            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                                <i class="fas fa-droplet" style="font-size: 20px; color: white;"></i>
                                <span style="font-size: 13px; color: rgba(255,255,255,0.8); font-weight: 600;">KELEMBABAN</span>
                            </div>
                            <div style="font-size: 32px; font-weight: 800; color: white;">
                                {{ $weatherData['current']['main']['humidity'] ?? '75' }}%
                            </div>
                        </div>
                        
                        <div style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); border-radius: 16px; padding: 20px;">
                            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                                <i class="fas fa-wind" style="font-size: 20px; color: white;"></i>
                                <span style="font-size: 13px; color: rgba(255,255,255,0.8); font-weight: 600;">ANGIN</span>
                            </div>
                            <div style="font-size: 32px; font-weight: 800; color: white;">
                                {{ isset($weatherData['current']['wind']['speed']) ? round($weatherData['current']['wind']['speed']) : '3' }} m/s
                            </div>
                        </div>
                        
                        <div style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); border-radius: 16px; padding: 20px;">
                            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                                <i class="fas fa-eye" style="font-size: 20px; color: white;"></i>
                                <span style="font-size: 13px; color: rgba(255,255,255,0.8); font-weight: 600;">JARAK PANDANG</span>
                            </div>
                            <div style="font-size: 32px; font-weight: 800; color: white;">
                                10 km
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Weather Icon --}}
                <div style="text-align: center;">
                    @php
                        $icon = $weatherData['current']['weather'][0]['icon'] ?? '01d';
                        $iconUrl = "https://openweathermap.org/img/wn/{$icon}@4x.png";
                    @endphp
                    <img src="{{ $iconUrl }}" alt="Weather Icon" style="width: 200px; height: 200px; filter: drop-shadow(0 10px 30px rgba(0,0,0,0.3));">
                </div>
            </div>
        </div>

        {{-- Hourly Forecast --}}
        <div style="background: white; border-radius: 24px; padding: 32px; margin-bottom: 32px; box-shadow: 0 10px 30px rgba(0,0,0,0.08);">
            <h3 style="font-size: 22px; font-weight: 700; color: #1f2937; margin-bottom: 24px;">
                <i class="fas fa-clock"></i> Prakiraan Per Jam
            </h3>
            <div style="display: flex; gap: 16px; overflow-x: auto; padding: 8px 0;">
                @if(isset($weatherData['hourly']) && count($weatherData['hourly']) > 0)
                    @foreach($weatherData['hourly'] as $hour)
                    <div style="min-width: 120px; background: linear-gradient(135deg, #fef3c7, #fde68a); border-radius: 20px; padding: 24px 20px; text-align: center; transition: all 0.3s;">
                        <div style="font-size: 14px; color: #92400e; font-weight: 600; margin-bottom: 16px;">
                            {{ $hour['time'] }}
                        </div>
                        <img src="https://openweathermap.org/img/wn/{{ $hour['icon'] }}@2x.png" alt="Weather" style="width: 64px; height: 64px; margin: 12px auto;">
                        <div style="font-size: 28px; font-weight: 800; color: #78350f; margin: 12px 0;">
                            {{ $hour['temp'] }}°
                        </div>
                        <div style="font-size: 12px; color: #92400e; margin-top: 8px;">
                            <i class="fas fa-droplet" style="color: #3b82f6;"></i> {{ $hour['humidity'] }}%
                        </div>
                        <div style="font-size: 12px; color: #92400e; margin-top: 4px;">
                            <i class="fas fa-wind" style="color: #6b7280;"></i> {{ $hour['wind_speed'] }} m/s
                        </div>
                    </div>
                    @endforeach
                @else
                    @for($i = 0; $i < 8; $i++)
                    <div style="min-width: 120px; background: linear-gradient(135deg, #fef3c7, #fde68a); border-radius: 20px; padding: 24px 20px; text-align: center;">
                        <div style="font-size: 14px; color: #92400e; font-weight: 600; margin-bottom: 16px;">
                            {{ str_pad($i * 3, 2, '0', STR_PAD_LEFT) }}:00
                        </div>
                        <img src="https://openweathermap.org/img/wn/01d@2x.png" alt="Weather" style="width: 64px; height: 64px; margin: 12px auto;">
                        <div style="font-size: 28px; font-weight: 800; color: #78350f; margin: 12px 0;">
                            {{ 28 + rand(-2, 4) }}°
                        </div>
                        <div style="font-size: 12px; color: #92400e; margin-top: 8px;">
                            <i class="fas fa-droplet" style="color: #3b82f6;"></i> {{ 70 + rand(-10, 15) }}%
                        </div>
                        <div style="font-size: 12px; color: #92400e; margin-top: 4px;">
                            <i class="fas fa-wind" style="color: #6b7280;"></i> {{ rand(2, 6) }} m/s
                        </div>
                    </div>
                    @endfor
                @endif
            </div>
        </div>

        {{-- 5 Day Forecast --}}
        <div style="background: white; border-radius: 24px; padding: 32px; margin-bottom: 32px; box-shadow: 0 10px 30px rgba(0,0,0,0.08);">
            <h3 style="font-size: 22px; font-weight: 700; color: #1f2937; margin-bottom: 24px;">
                <i class="fas fa-calendar-week"></i> Prakiraan 5 Hari
            </h3>
            <div style="display: grid; gap: 16px;">
                @if(isset($weatherData['daily']) && count($weatherData['daily']) > 0)
                    @foreach($weatherData['daily'] as $day)
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 24px; background: linear-gradient(135deg, #f9fafb, #f3f4f6); border-radius: 16px; transition: all 0.3s; cursor: pointer;" onmouseover="this.style.background='linear-gradient(135deg, #fef3c7, #fde68a)'" onmouseout="this.style.background='linear-gradient(135deg, #f9fafb, #f3f4f6)'">
                        <div style="flex: 1;">
                            <div style="font-size: 16px; font-weight: 700; color: #1f2937; margin-bottom: 4px;">
                                {{ \Carbon\Carbon::parse($day['date'])->locale('id')->isoFormat('dddd') }}
                            </div>
                            <div style="font-size: 13px; color: #6b7280;">
                                {{ \Carbon\Carbon::parse($day['date'])->locale('id')->isoFormat('D MMMM YYYY') }}
                            </div>
                        </div>
                        
                        <div style="flex: 1; text-align: center;">
                            <img src="https://openweathermap.org/img/wn/{{ $day['icon'] }}@2x.png" alt="Weather" style="width: 64px; height: 64px;">
                        </div>
                        
                        <div style="flex: 1; text-align: center;">
                            <div style="font-size: 15px; color: #4b5563; font-weight: 600; text-transform: capitalize;">
                                {{ $day['description'] }}
                            </div>
                        </div>
                        
                        <div style="flex: 1; text-align: center;">
                            <div style="display: flex; align-items: center; justify-content: center; gap: 12px;">
                                <div>
                                    <div style="font-size: 11px; color: #6b7280; margin-bottom: 2px;">MAX</div>
                                    <div style="font-size: 24px; font-weight: 800; color: #dc2626;">{{ $day['temp_max'] }}°</div>
                                </div>
                                <div style="width: 1px; height: 40px; background: #e5e7eb;"></div>
                                <div>
                                    <div style="font-size: 11px; color: #6b7280; margin-bottom: 2px;">MIN</div>
                                    <div style="font-size: 24px; font-weight: 800; color: #3b82f6;">{{ $day['temp_min'] }}°</div>
                                </div>
                            </div>
                        </div>
                        
                        <div style="flex: 1; text-align: right;">
                            <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">
                                <i class="fas fa-droplet" style="color: #3b82f6;"></i> {{ $day['humidity'] }}%
                            </div>
                            <div style="font-size: 13px; color: #6b7280;">
                                <i class="fas fa-wind" style="color: #6b7280;"></i> {{ $day['wind_speed'] }} m/s
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    @php
                        $dayNames = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
                        $weatherTypes = [
                            ['icon' => '01d', 'desc' => 'Cerah'],
                            ['icon' => '02d', 'desc' => 'Berawan Sebagian'],
                            ['icon' => '03d', 'desc' => 'Berawan'],
                            ['icon' => '10d', 'desc' => 'Hujan Ringan'],
                            ['icon' => '04d', 'desc' => 'Mendung']
                        ];
                    @endphp
                    @foreach($dayNames as $index => $dayName)
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 24px; background: linear-gradient(135deg, #f9fafb, #f3f4f6); border-radius: 16px; transition: all 0.3s; cursor: pointer;" onmouseover="this.style.background='linear-gradient(135deg, #fef3c7, #fde68a)'" onmouseout="this.style.background='linear-gradient(135deg, #f9fafb, #f3f4f6)'">
                        <div style="flex: 1;">
                            <div style="font-size: 16px; font-weight: 700; color: #1f2937; margin-bottom: 4px;">
                                {{ $dayName }}
                            </div>
                            <div style="font-size: 13px; color: #6b7280;">
                                {{ now()->addDays($index + 1)->locale('id')->isoFormat('D MMMM YYYY') }}
                            </div>
                        </div>
                        
                        <div style="flex: 1; text-align: center;">
                            <img src="https://openweathermap.org/img/wn/{{ $weatherTypes[$index]['icon'] }}@2x.png" alt="Weather" style="width: 64px; height: 64px;">
                        </div>
                        
                        <div style="flex: 1; text-align: center;">
                            <div style="font-size: 15px; color: #4b5563; font-weight: 600;">
                                {{ $weatherTypes[$index]['desc'] }}
                            </div>
                        </div>
                        
                        <div style="flex: 1; text-align: center;">
                            <div style="display: flex; align-items: center; justify-content: center; gap: 12px;">
                                <div>
                                    <div style="font-size: 11px; color: #6b7280; margin-bottom: 2px;">MAX</div>
                                    <div style="font-size: 24px; font-weight: 800; color: #dc2626;">{{ 30 + rand(-2, 4) }}°</div>
                                </div>
                                <div style="width: 1px; height: 40px; background: #e5e7eb;"></div>
                                <div>
                                    <div style="font-size: 11px; color: #6b7280; margin-bottom: 2px;">MIN</div>
                                    <div style="font-size: 24px; font-weight: 800; color: #3b82f6;">{{ 22 + rand(-2, 3) }}°</div>
                                </div>
                            </div>
                        </div>
                        
                        <div style="flex: 1; text-align: right;">
                            <div style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">
                                <i class="fas fa-droplet" style="color: #3b82f6;"></i> {{ 65 + rand(-10, 20) }}%
                            </div>
                            <div style="font-size: 13px; color: #6b7280;">
                                <i class="fas fa-wind" style="color: #6b7280;"></i> {{ rand(2, 8) }} m/s
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- Weather Tips for Fishing --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 24px;">
            <div style="background: white; border-radius: 24px; padding: 32px; box-shadow: 0 10px 30px rgba(0,0,0,0.08);">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px;">
                    <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-fish" style="font-size: 20px; color: white;"></i>
                    </div>
                    <div>
                        <h3 style="font-size: 18px; font-weight: 700; color: #1f2937;">Kondisi Ideal Memancing</h3>
                        <p style="font-size: 13px; color: #6b7280;">Best Fishing Conditions</p>
                    </div>
                </div>
                <div style="display: grid; gap: 12px;">
                    <div style="padding: 16px; background: #d1fae5; border-radius: 12px; border-left: 4px solid #10b981;">
                        <div style="font-size: 14px; color: #065f46; font-weight: 600; margin-bottom: 4px;">
                            <i class="fas fa-check-circle"></i> Cuaca Cerah/Berawan
                        </div>
                        <div style="font-size: 13px; color: #047857; line-height: 1.6;">
                            Kondisi ideal untuk memancing di laut maupun sungai
                        </div>
                    </div>
                    <div style="padding: 16px; background: #fef3c7; border-radius: 12px; border-left: 4px solid #f59e0b;">
                        <div style="font-size: 14px; color: #92400e; font-weight: 600; margin-bottom: 4px;">
                            <i class="fas fa-info-circle"></i> Mendung Ringan
                        </div>
                        <div style="font-size: 13px; color: #78350f; line-height: 1.6;">
                            Tetap bagus, ikan cenderung lebih aktif
                        </div>
                    </div>
                    <div style="padding: 16px; background: #fecaca; border-radius: 12px; border-left: 4px solid #dc2626;">
                        <div style="font-size: 14px; color: #991b1b; font-weight: 600; margin-bottom: 4px;">
                            <i class="fas fa-times-circle"></i> Hujan Lebat/Badai
                        </div>
                        <div style="font-size: 13px; color: #7f1d1d; line-height: 1.6;">
                            Hindari aktivitas memancing demi keselamatan
                        </div>
                    </div>
                </div>
            </div>

            <div style="background: white; border-radius: 24px; padding: 32px; box-shadow: 0 10px 30px rgba(0,0,0,0.08);">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px;">
                    <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-lightbulb" style="font-size: 20px; color: white;"></i>
                    </div>
                    <div>
                        <h3 style="font-size: 18px; font-weight: 700; color: #1f2937;">Tips Berdasarkan Cuaca</h3>
                        <p style="font-size: 13px; color: #6b7280;">Weather-Based Tips</p>
                    </div>
                </div>
                <div style="display: grid; gap: 12px;">
                    <div style="padding: 14px; background: #f9fafb; border-radius: 12px;">
                        <div style="display: flex; align-items: start; gap: 10px;">
                            <i class="fas fa-sun" style="color: #f59e0b; margin-top: 2px;"></i>
                            <div>
                                <div style="font-size: 14px; color: #1f2937; font-weight: 600; margin-bottom: 2px;">Cerah</div>
                                <div style="font-size: 13px; color: #6b7280; line-height: 1.5;">
                                    Pagi/sore hari terbaik, gunakan umpan hidup
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="padding: 14px; background: #f9fafb; border-radius: 12px;">
                        <div style="display: flex; align-items: start; gap: 10px;">
                            <i class="fas fa-cloud" style="color: #6b7280; margin-top: 2px;"></i>
                            <div>
                                <div style="font-size: 14px; color: #1f2937; font-weight: 600; margin-bottom: 2px;">Mendung</div>
                                <div style="font-size: 13px; color: #6b7280; line-height: 1.5;">
                                    Sepanjang hari bagus, ikan lebih berani keluar
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="padding: 14px; background: #f9fafb; border-radius: 12px;">
                        <div style="display: flex; align-items: start; gap: 10px;">
                            <i class="fas fa-cloud-rain" style="color: #3b82f6; margin-top: 2px;"></i>
                            <div>
                                <div style="font-size: 14px; color: #1f2937; font-weight: 600; margin-bottom: 2px;">Gerimis</div>
                                <div style="font-size: 13px; color: #6b7280; line-height: 1.5;">
                                    Sebelum hujan terbaik, setelah hujan juga bagus
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom scrollbar for hourly forecast */
div::-webkit-scrollbar {
    height: 8px;
}

div::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

div::-webkit-scrollbar-thumb {
    background: #f59e0b;
    border-radius: 10px;
}

div::-webkit-scrollbar-thumb:hover {
    background: #d97706;
}
</style>
@endsection