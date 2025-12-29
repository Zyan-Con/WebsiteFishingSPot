<?php

namespace App\Http\Controllers;

use App\Models\Spot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpotController extends Controller
{
    public function index()
    {
        $spots = Spot::with('user')->get();
        return response()->json($spots);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:laut,sungai,danau,waduk,tambak',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $spot = Spot::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
        ]);

        return response()->json($spot, 201);
    }

    public function show($id)
    {
        $spot = Spot::with('user')->findOrFail($id);
        return response()->json($spot);
    }

    public function destroy($id)
    {
        $spot = Spot::findOrFail($id);
        
        if ($spot->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $spot->delete();
        return response()->json(['message' => 'Spot deleted']);
    }
}