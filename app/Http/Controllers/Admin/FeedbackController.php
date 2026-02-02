<?php
// app/Http/Controllers/Admin/FeedbackController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::with('user')
            ->latest()
            ->paginate(20);
        
        $totalCount = Feedback::count();
        $unreadCount = Feedback::where('is_read_by_admin', false)->count();
        $repliedCount = Feedback::whereNotNull('admin_reply')->count();
        $pendingCount = Feedback::whereNull('admin_reply')->count();
        
        return view('admin.feedback.index', compact(
            'feedbacks',
            'totalCount',
            'unreadCount',
            'repliedCount',
            'pendingCount'
        ));
    }
    
    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string'
        ]);
        
        $feedback = Feedback::findOrFail($id);
        $feedback->update([
            'admin_reply' => $request->reply,
            'admin_id' => auth('admin')->id(),
            'replied_at' => now(),
            'is_read_by_admin' => true,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Balasan berhasil dikirim'
        ]);
    }
    
    public function markAsRead($id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->update(['is_read_by_admin' => true]);
        
        return response()->json([
            'success' => true
        ]);
    }
    
    public function destroy($id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Feedback berhasil dihapus'
        ]);
    }
} 