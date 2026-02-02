<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\FishCatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search by name or email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status (if column exists)
        if ($request->has('status') && $request->status) {
            if ($request->status === 'active') {
                $query->where('is_banned', false);
            } elseif ($request->status === 'banned') {
                $query->where('is_banned', true);
            }
        }

        // Filter by premium status (if column exists)
        if ($request->has('premium') && $request->premium) {
            if ($request->premium === 'yes') {
                $query->where('is_premium', true);
            } elseif ($request->premium === 'no') {
                $query->where('is_premium', false);
            }
        }

        $users = $query->latest()->paginate(20);

        // Statistics
        $stats = [
            'total' => User::count(),
            'active' => User::where('is_banned', false)->count(),
            'banned' => User::where('is_banned', true)->count(),
            'premium' => User::where('is_premium', true)->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'is_premium' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Display the specified user
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        // User statistics
        try {
            $stats = [
                'total_catches' => FishCatch::where('user_id', $id)->count(),
                'total_spots' => 0, // Sesuaikan dengan model Spot jika ada
                'member_since' => $user->created_at->diffForHumans(),
            ];

            $recentCatches = FishCatch::where('user_id', $id)
                ->latest()
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            $stats = [
                'total_catches' => 0,
                'total_spots' => 0,
                'member_since' => $user->created_at->diffForHumans(),
            ];
            $recentCatches = collect();
        }

        return view('admin.users.show', compact('user', 'stats', 'recentCatches'));
    }

    /**
     * Show the form for editing user
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function updateRating(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|numeric|min:0|max:5'
        ]);
        
        $user = User::findOrFail($id);
        $user->rating = $request->rating;
        $user->total_reviews = $user->total_reviews + 1; // increment review count
        $user->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Rating berhasil diperbarui!'
        ]);
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'is_premium' => 'boolean',
            'is_banned' => 'boolean',
        ]);

        // Update password only if provided
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8']);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', "User {$user->name} berhasil diupdate!");
    }

    /**
     * Ban user
     */
    public function ban($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_banned' => true]);

        return back()->with('success', "User {$user->name} berhasil di-ban!");
    }

    /**
     * Unban user
     */
    public function unban($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_banned' => false]);

        return back()->with('success', "User {$user->name} berhasil di-unban!");
    }

    /**
     * Reset user password
     */
    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        
        // Reset to default password
        $newPassword = 'password123';
        $user->update(['password' => Hash::make($newPassword)]);

        return back()->with('success', "Password user {$user->name} direset ke: {$newPassword}");
    }

    /**
     * Remove the specified user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $name = $user->name;
        
        // Delete related data if needed
        // FishCatch::where('user_id', $id)->delete();
        
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', "User {$name} berhasil dihapus!");
    }
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }
} 