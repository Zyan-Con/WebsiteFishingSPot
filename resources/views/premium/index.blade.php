<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Premium - Fishing Spot</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(to bottom, #87CEEB 0%, #E0F6FF 50%, #F4E4C1 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Beach Elements */
        body::before {
            content: '🌊';
            position: fixed;
            bottom: 0;
            left: 0;
            font-size: 3rem;
            animation: wave-move 8s ease-in-out infinite;
            opacity: 0.6;
        }

        body::after {
            content: '☀️';
            position: fixed;
            top: 30px;
            right: 40px;
            font-size: 4rem;
            animation: sun-glow 3s ease-in-out infinite;
        }

        @keyframes wave-move {
            0%, 100% { transform: translateX(-20px); }
            50% { transform: translateX(20px); }
        }

        @keyframes sun-glow {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        /* Back Button */
        .back-button {
            position: fixed;
            top: 20px;
            left: 20px;
            background: white;
            padding: 10px 20px;
            border-radius: 50px;
            text-decoration: none;
            color: #2c5aa0;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s;
            z-index: 100;
        }

        .back-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        /* Main Card */
        .premium-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            max-width: 450px;
            width: 100%;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            text-align: center;
        }

        .premium-card h1 {
            color: #2c5aa0;
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .premium-card p {
            color: #666;
            margin-bottom: 30px;
            font-size: 1.1rem;
        }

        .price-box {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            padding: 30px;
            border-radius: 15px;
            color: white;
            margin-bottom: 25px;
        }

        .price {
            font-size: 3rem;
            font-weight: bold;
            margin: 10px 0;
        }

        .price-label {
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .features {
            text-align: left;
            margin: 25px 0;
            padding: 0 20px;
        }

        .features li {
            list-style: none;
            padding: 10px 0;
            color: #555;
            border-bottom: 1px solid #f0f0f0;
        }

        .features li:before {
            content: '✓ ';
            color: #4facfe;
            font-weight: bold;
            margin-right: 10px;
        }

        .btn-subscribe {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-subscribe:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        /* Premium Active */
        .premium-active {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
        }

        .premium-active h2 {
            font-size: 1.8rem;
            margin-bottom: 10px;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            padding: 30px;
            max-width: 400px;
            width: 90%;
            text-align: center;
            position: relative;
        }

        .close-btn {
            position: absolute;
            top: 15px;
            right: 20px;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #999;
        }

        .close-btn:hover {
            color: #333;
        }

        .qr-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 15px;
            margin: 20px 0;
        }

        .qr-box img {
            max-width: 250px;
            width: 100%;
            border-radius: 10px;
            background: white;
            padding: 10px;
        }

        .payment-info {
            margin: 15px 0;
            color: #666;
        }

        .payment-info strong {
            color: #2c5aa0;
            font-size: 1.2rem;
        }

        .timer {
            font-size: 1.5rem;
            color: #667eea;
            font-weight: bold;
            margin: 10px 0;
        }

        .status-box {
            background: #fff3cd;
            padding: 15px;
            border-radius: 10px;
            margin-top: 15px;
        }

        .status-box.success {
            background: #d1e7dd;
        }

        .btn-test {
            background: #6c757d;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 15px;
            width: 100%;
            font-size: 0.9rem;
        }

        .btn-test:hover {
            background: #5a6268;
        }

        .loading {
            padding: 40px;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 600px) {
            .premium-card {
                padding: 25px;
            }
            
            .premium-card h1 {
                font-size: 1.5rem;
            }
            
            .price {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>

<a href="{{ route('dashboard') }}" class="back-button">← Kembali</a>

<div class="premium-card">
    @if($user->is_premium && $user->premium_until && $user->premium_until->isFuture())
        <div class="premium-active">
            <h2>Premium Aktif</h2>
            <p style="color: white; opacity: 0.95; margin: 10px 0;">
                Berlaku hingga:<br>
                <strong style="font-size: 1.3rem;">{{ $user->premium_until->format('d M Y') }}</strong>
            </p>
        </div>
    @else
        <h1>Premium Fishing Spot</h1>
        <p>Akses semua lokasi mancing terbaik</p>

        <div class="price-box">
            <div class="price-label">Harga</div>
            <div class="price">Rp 50.000</div>
            <div class="price-label">per bulan</div>
        </div>

        <ul class="features">
            <li>Akses semua lokasi premium</li>
            <li>Info detail spot mancing</li>
            <li>Tanpa iklan</li>
            <li>Update lokasi baru</li>
        </ul>

        <button class="btn-subscribe" onclick="startPayment()">
            Berlangganan Sekarang
        </button>
    @endif
</div>

<!-- Payment Modal -->
<div id="paymentModal" class="modal">
    <div class="modal-content">
        <button class="close-btn" onclick="closeModal()">&times;</button>
        
        <div id="loading" class="loading">
            <div class="spinner"></div>
            <p>Membuat QRIS...</p>
        </div>

        <div id="qrContent" style="display: none;">
            <h3 style="color: #2c5aa0; margin-bottom: 15px;">Scan QRIS</h3>
            
            <div class="qr-box">
                <img id="qrImage" src="" alt="QR Code">
            </div>

            <div class="payment-info">
                Order: <strong id="orderId">-</strong><br>
                Total: <strong>Rp 50.000</strong>
            </div>

            <div class="timer" id="timer">30:00</div>

            <div class="status-box" id="statusBox">
                <p>Menunggu pembayaran...</p>
            </div>

            <button class="btn-test" onclick="simulatePay()" id="testBtn">
                🧪 Simulasi Bayar (Testing)
            </button>
        </div>
    </div>
</div>

<script>
let orderId = null;
let timerInt = null;
let verifyInt = null;

async function startPayment() {
    document.getElementById('paymentModal').style.display = 'flex';
    document.getElementById('loading').style.display = 'block';
    document.getElementById('qrContent').style.display = 'none';

    try {
        const res = await fetch('{{ route("premium.create") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        const data = await res.json();

        if (data.success) {
            orderId = data.payment.order_id;
            document.getElementById('orderId').textContent = data.payment.order_id;
            document.getElementById('qrImage').src = data.payment.qris_url;
            
            document.getElementById('loading').style.display = 'none';
            document.getElementById('qrContent').style.display = 'block';

            startTimer(new Date(data.payment.expires_at));
            startVerify();
        } else {
            alert(data.message);
            closeModal();
        }
    } catch (error) {
        console.error(error);
        alert('Terjadi kesalahan');
        closeModal();
    }
}

function startTimer(expires) {
    if (timerInt) clearInterval(timerInt);

    timerInt = setInterval(() => {
        const now = new Date().getTime();
        const end = new Date(expires).getTime();
        const diff = end - now;

        if (diff < 0) {
            clearInterval(timerInt);
            document.getElementById('timer').textContent = 'Expired';
            alert('QRIS kadaluarsa');
            closeModal();
            return;
        }

        const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const s = Math.floor((diff % (1000 * 60)) / 1000);
        document.getElementById('timer').textContent = 
            `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
    }, 1000);
}

function startVerify() {
    if (verifyInt) clearInterval(verifyInt);

    verifyInt = setInterval(async () => {
        if (!orderId) return;

        try {
            const res = await fetch(`/premium/verify/${orderId}`);
            const data = await res.json();

            if (data.status === 'paid') {
                clearInterval(verifyInt);
                clearInterval(timerInt);
                showSuccess();
            }
        } catch (error) {
            console.error(error);
        }
    }, 3000);
}

async function simulatePay() {
    if (!orderId) return;

    try {
        const res = await fetch(`/premium/confirm/${orderId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        const data = await res.json();

        if (data.success) {
            showSuccess(data.premium_until);
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error(error);
        alert('Terjadi kesalahan');
    }
}

function showSuccess(until) {
    const box = document.getElementById('statusBox');
    box.className = 'status-box success';
    box.innerHTML = `<strong>✅ Pembayaran Berhasil!</strong><br>Premium aktif hingga: ${until}`;
    document.getElementById('testBtn').style.display = 'none';

    setTimeout(() => {
        location.reload();
    }, 2000);
}

function closeModal() {
    if (timerInt) clearInterval(timerInt);
    if (verifyInt) clearInterval(verifyInt);
    document.getElementById('paymentModal').style.display = 'none';
    orderId = null;
}
</script>

</body>
</html>