<?php

namespace App\Http\Controllers;

use App\Models\PremiumPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PremiumController extends Controller
{
    // Show premium page
    public function index()
    {
        $user = Auth::user();
        return view('premium.index', compact('user'));
    }

    // Create payment order
    public function createPayment(Request $request)
    {
        $user = Auth::user();
        
        // Check if user already has active premium
        if ($user->is_premium && $user->premium_until && $user->premium_until->isFuture()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah memiliki premium aktif'
            ], 400);
        }

        // Check if there's pending payment
        $pendingPayment = PremiumPayment::where('user_id', $user->id)
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->first();

        if ($pendingPayment) {
            return response()->json([
                'success' => true,
                'payment' => $pendingPayment
            ]);
        }

        // Create new payment
        $orderId = 'PREM-' . strtoupper(Str::random(10));
        $amount = 50000; // Rp 50.000 untuk 1 bulan

        $payment = PremiumPayment::create([
            'user_id' => $user->id,
            'order_id' => $orderId,
            'amount' => $amount,
            'status' => 'pending',
            'qris_url' => $this->generateQRIS($orderId, $amount),
            'expires_at' => now()->addMinutes(30), // QRIS valid 30 menit
        ]);

        return response()->json([
            'success' => true,
            'payment' => $payment
        ]);
    }

    // Simulate payment verification
    public function verifyPayment(Request $request, $orderId)
    {
        $payment = PremiumPayment::where('order_id', $orderId)->firstOrFail();

        if ($payment->status === 'paid') {
            return response()->json([
                'success' => true,
                'status' => 'paid',
                'message' => 'Pembayaran sudah berhasil'
            ]);
        }

        if ($payment->isExpired()) {
            $payment->update(['status' => 'expired']);
            return response()->json([
                'success' => false,
                'status' => 'expired',
                'message' => 'QRIS telah kadaluarsa'
            ]);
        }

        // SIMULASI: Dalam production, ini akan check ke payment gateway
        return response()->json([
            'success' => true,
            'status' => 'pending',
            'message' => 'Menunggu pembayaran'
        ]);
    }

    // Manual confirm payment (untuk testing)
    public function confirmPayment(Request $request, $orderId)
    {
        $payment = PremiumPayment::where('order_id', $orderId)->firstOrFail();

        if ($payment->status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran sudah dikonfirmasi'
            ]);
        }

        // Update payment status
        $payment->update([
            'status' => 'paid',
            'paid_at' => now()
        ]);

        // Activate premium for user
        $user = $payment->user;
        $premiumUntil = $user->is_premium && $user->premium_until && $user->premium_until->isFuture()
            ? $user->premium_until->addMonth()
            : now()->addMonth();

        $user->update([
            'is_premium' => true,
            'premium_until' => $premiumUntil
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Premium berhasil diaktifkan!',
            'premium_until' => $premiumUntil->format('d M Y')
        ]);
    }

    // Generate QRIS (simulasi)
    private function generateQRIS($orderId, $amount)
    {
        // Dalam production, ini akan hit API payment gateway (Midtrans, Xendit, dll)
        // Untuk demo, kita generate QR code sederhana
        $qrData = "Order: {$orderId}, Amount: Rp " . number_format($amount, 0, ',', '.');
        return "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($qrData);
    }
}