<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // ✅ UNTUK AJAX REQUEST (yang dipakai JavaScript)
    public function index(Request $request)
    {
        $userId = auth()->id();
        
        // Ambil notifikasi untuk user ini + broadcast
        $notifications = AdminNotification::forUser($userId)
            ->with('admin')
            ->latest()
            ->get()
            ->map(function($notif) {
                return [
                    'id' => $notif->id,
                    'title' => $notif->title,
                    'message' => $notif->message,
                    'type' => $notif->type,
                    'icon' => $this->getIcon($notif->type),
                    'link' => $notif->action_url,
                    'is_read' => $notif->is_read,
                    'created_at' => $notif->created_at->toISOString(),
                ];
            });
        
        // Hitung unread (hanya yang personal, bukan broadcast)
        $unreadCount = AdminNotification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();
        
        // ✅ Return format yang diharapkan JavaScript
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }
    
    // Helper untuk icon berdasarkan type
    private function getIcon($type)
    {
        return match($type) {
            'info' => 'fa-info-circle',
            'success' => 'fa-check-circle',
            'warning' => 'fa-exclamation-triangle',
            'danger' => 'fa-exclamation-circle',
            default => 'fa-bell'
        };
    }
    
    public function markAsRead($id)
    {
        $notification = AdminNotification::forUser(auth()->id())
            ->findOrFail($id);
        
        // Hanya update jika personal notification (punya user_id)
        if ($notification->user_id !== null) {
            $notification->update([
                'is_read' => true,
                'read_at' => now()
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Notifikasi ditandai sudah dibaca'
        ]);
    }
    
    public function markAllAsRead()
    {
        // Hanya update notifikasi personal (yang punya user_id)
        AdminNotification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi ditandai sudah dibaca'
        ]);
    }
    
    public function delete($id)
    {
        // Hanya bisa hapus notifikasi personal
        $notification = AdminNotification::where('user_id', auth()->id())
            ->findOrFail($id);
            
        $notification->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil dihapus'
        ]);
    }
}