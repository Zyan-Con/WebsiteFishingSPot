<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    // User: Lihat semua feedback (bisa return JSON atau view)
    public function index(Request $request)
    {
        $feedbacks = Feedback::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        // Jika request dari AJAX, return JSON
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($feedbacks);
        }
        
        // Jika request biasa, return view
        return view('feedback.index', compact('feedbacks'));
    }

    // User: Kirim feedback baru
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        Feedback::create([
            'user_id' => auth()->id(),
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'pending',
            'is_read_by_admin' => false,
            'is_read_by_user' => true, // ⭐ TAMBAHKAN INI
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Feedback berhasil dikirim!'
        ]);
    }

    // User: Tandai sebagai dibaca
    public function markAsRead($id)
    {
        $feedback = Feedback::where('user_id', auth()->id())->findOrFail($id);
        $feedback->update(['is_read_by_user' => true]);

        return response()->json(['success' => true]);
    }

    // Get unread count untuk user
    public function getUnreadCount()
    {
        $count = Feedback::where('user_id', auth()->id())
            ->where('is_read_by_user', false)
            ->whereNotNull('admin_reply') // ⭐ UBAH INI - cek ada admin_reply
            ->count();

        return response()->json(['count' => $count]);
    }
} 