<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FishCatch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CatchController extends Controller
{
    /**
     * Display a listing of catches
     */
    public function index(Request $request)
    {
        $query = FishCatch::with('user');

        // Search by fish type, location, or user
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('fish_type', 'like', "%{$search}%")
                  ->orWhere('location_name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by user
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by fish type
        if ($request->has('fish_type') && $request->fish_type) {
            $query->where('fish_type', $request->fish_type);
        }

        // Filter by fishing method
        if ($request->has('fishing_method') && $request->fishing_method) {
            $query->where('fishing_method', $request->fishing_method);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('caught_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('caught_at', '<=', $request->date_to);
        }

        $catches = $query->latest('caught_at')->paginate(20);

        // Statistics
        $stats = [
            'total' => FishCatch::count(),
            'total_quantity' => FishCatch::sum('quantity'),
            'total_weight' => FishCatch::sum('weight'),
            'today' => FishCatch::whereDate('caught_at', today())->count(),
            'this_month' => FishCatch::whereMonth('caught_at', now()->month)->count(),
        ];

        // Get users for filter dropdown
        $users = User::orderBy('name')->get(['id', 'name']);

        // Get unique fish types for filter
        $fishTypes = FishCatch::distinct()->pluck('fish_type')->filter()->sort()->values();

        // Get unique fishing methods for filter
        $fishingMethods = FishCatch::distinct()->pluck('fishing_method')->filter()->sort()->values();

        return view('admin.catches.index', compact('catches', 'stats', 'users', 'fishTypes', 'fishingMethods'));
    }

    /**
     * Display the specified catch
     */
    public function show($id)
    {
        $catch = FishCatch::with('user')->findOrFail($id);
        
        return view('admin.catches.show', compact('catch'));
    }

    /**
     * Delete a catch
     */
    public function destroy($id)
    {
        $catch = FishCatch::findOrFail($id);
        $fishType = $catch->fish_type;
        
        // Delete photo if exists
        if ($catch->photo && Storage::exists($catch->photo)) {
            Storage::delete($catch->photo);
        }
        
        $catch->delete();

        return redirect()
            ->route('admin.catches.index')
            ->with('success', "Tangkapan ikan {$fishType} berhasil dihapus!");
    }

    /**
     * Bulk delete catches
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'catch_ids' => 'required|array',
            'catch_ids.*' => 'exists:fish_catches,id'
        ]);

        $catches = FishCatch::whereIn('id', $request->catch_ids)->get();
        
        // Delete photos
        foreach ($catches as $catch) {
            if ($catch->photo && Storage::exists($catch->photo)) {
                Storage::delete($catch->photo);
            }
        }

        FishCatch::whereIn('id', $request->catch_ids)->delete();

        return back()->with('success', count($request->catch_ids) . ' tangkapan berhasil dihapus!');
    }

    /**
     * Get catch statistics
     */
    public function statistics()
    {
        $stats = [
            // Total catches per month (last 6 months)
            'monthly' => FishCatch::selectRaw('DATE_FORMAT(caught_at, "%Y-%m") as month, COUNT(*) as count, SUM(weight) as total_weight')
                ->where('caught_at', '>=', now()->subMonths(6))
                ->groupBy('month')
                ->orderBy('month')
                ->get(),

            // Top fish types
            'top_species' => FishCatch::selectRaw('fish_type, COUNT(*) as count, SUM(quantity) as total_quantity, SUM(weight) as total_weight')
                ->groupBy('fish_type')
                ->orderByDesc('count')
                ->limit(10)
                ->get(),

            // Top locations
            'top_locations' => FishCatch::selectRaw('location_name, COUNT(*) as count, SUM(quantity) as total_quantity')
                ->whereNotNull('location_name')
                ->groupBy('location_name')
                ->orderByDesc('count')
                ->limit(10)
                ->get(),

            // Top fishing methods
            'fishing_methods' => FishCatch::selectRaw('fishing_method, COUNT(*) as count')
                ->whereNotNull('fishing_method')
                ->groupBy('fishing_method')
                ->orderByDesc('count')
                ->get(),

            // Top catchers
            'top_users' => User::withCount('fishCatches')
                ->withSum('fishCatches', 'quantity')
                ->withSum('fishCatches', 'weight')
                ->orderByDesc('fish_catches_count')
                ->limit(10)
                ->get(),

            // Weather statistics
            'weather_stats' => FishCatch::selectRaw('weather, COUNT(*) as count')
                ->whereNotNull('weather')
                ->groupBy('weather')
                ->orderByDesc('count')
                ->get(),
        ];

        return view('admin.catches.statistics', compact('stats'));
    }

    /**
     * Export catches to CSV
     */
    public function export(Request $request)
    {
        $query = FishCatch::with('user');

        // Apply same filters as index
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->has('fish_type') && $request->fish_type) {
            $query->where('fish_type', $request->fish_type);
        }
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('caught_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('caught_at', '<=', $request->date_to);
        }

        $catches = $query->latest('caught_at')->get();

        $filename = 'catches_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($catches) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, [
                'ID', 'User', 'Fish Type', 'Weight (kg)', 'Length (cm)', 
                'Quantity', 'Location', 'Caught At', 'Fishing Method', 'Weather'
            ]);

            // Data
            foreach ($catches as $catch) {
                fputcsv($file, [
                    $catch->id,
                    $catch->user->name ?? '-',
                    $catch->fish_type,
                    $catch->weight,
                    $catch->length,
                    $catch->quantity,
                    $catch->location_name ?? $catch->location,
                    $catch->caught_at->format('Y-m-d H:i'),
                    $catch->fishing_method ?? '-',
                    $catch->weather ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}