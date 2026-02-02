<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Halaman list notifikasi yang sudah dikirim
    public function index()
    {
        $notifications = AdminNotification::with(['user', 'admin'])
            ->latest()
            ->paginate(20);

        $stats = [
            'total' => AdminNotification::count(),
            'broadcast' => AdminNotification::whereNull('user_id')->count(),
            'personal' => AdminNotification::whereNotNull('user_id')->count(),
            'unread' => AdminNotification::unread()->count(),
        ];

        return view('admin.notifications.index', compact('notifications', 'stats'));
    }

    // Form buat notifikasi baru
    public function create()
    {
        $users = User::select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        return view('admin.notifications.create', compact('users'));
    }

    // Simpan notifikasi baru
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:info,success,warning,danger',
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'action_url' => 'nullable|url',
            'target' => 'required|in:all,specific',
            'user_ids' => 'required_if:target,specific|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $adminId = Auth::guard('admin')->id();

        if ($request->target === 'all') {
            // Broadcast ke semua user
            AdminNotification::create([
                'admin_id' => $adminId,
                'user_id' => null, // NULL = semua user
                'type' => $request->type,
                'title' => $request->title,
                'message' => $request->message,
                'action_url' => $request->action_url,
            ]);

            $message = 'Notifikasi berhasil dikirim ke semua user!';
        } else {
            // Kirim ke user spesifik
            foreach ($request->user_ids as $userId) {
                AdminNotification::create([
                    'admin_id' => $adminId,
                    'user_id' => $userId,
                    'type' => $request->type,
                    'title' => $request->title,
                    'message' => $request->message,
                    'action_url' => $request->action_url,
                ]);
            }

            $count = count($request->user_ids);
            $message = "Notifikasi berhasil dikirim ke {$count} user!";
        }

        return redirect()
            ->route('admin.notifications.index')
            ->with('success', $message);
    }

    // Hapus notifikasi
    public function destroy($id)
    {
        $notification = AdminNotification::findOrFail($id);
        $notification->delete();

        return back()->with('success', 'Notifikasi berhasil dihapus!');
    }
} 