<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewLocation;

class LocationController extends Controller
{
    /**
     * Show the user's map location (index page).
     * Used by both ev-station and garage dashboards.
     */
    public function index()
    {
        $user     = auth()->user();
        $location = NewLocation::where('user_id', $user->id)->first();

        if ($user->hasRole('ev-station')) {
            return view('dashboard.station.map_location.index', compact('location'));
        }

        return view('dashboard.garage.map_location.index', compact('location'));
    }

    /**
     * Show the create form.
     */
    public function create()
    {
        $user = auth()->user();

        if ($user->hasRole('ev-station')) {
            return view('dashboard.station.map_location.create');
        }

        return view('dashboard.garage.map_location.create');
    }

    /**
     * Store a new location for the authenticated user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'address'   => 'required|string|max:500',
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $user = auth()->user();
        $type = $user->hasRole('ev-station') ? 'ev-station' : 'garage';

        NewLocation::create([
            'user_id'   => $user->id,
            'type'      => $type,
            'address'   => $request->address,
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
            'is_active' => false,
        ]);

        return redirect()->route(
            $user->hasRole('ev-station') ? 'station.location.index' : 'garage.location.index'
        )->with('success', 'Location saved! It will appear on the map once your account is verified.');
    }

    /**
     * Show the edit form.
     */
    public function edit()
    {
        $user     = auth()->user();
        $location = NewLocation::where('user_id', $user->id)->firstOrFail();

        if ($user->hasRole('ev-station')) {
            return view('dashboard.station.map_location.edit', compact('location'));
        }

        return view('dashboard.garage.map_location.edit', compact('location'));
    }

    /**
     * Update the user's existing location.
     */
    public function update(Request $request)
    {
        $request->validate([
            'address'   => 'required|string|max:500',
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $user = auth()->user();
        $type = $user->hasRole('ev-station') ? 'ev-station' : 'garage';

        NewLocation::updateOrCreate(
            ['user_id' => $user->id],
            [
                'type'      => $type,
                'address'   => $request->address,
                'latitude'  => $request->latitude,
                'longitude' => $request->longitude,
            ]
        );

        return redirect()->route(
            $user->hasRole('ev-station') ? 'station.location.index' : 'garage.location.index'
        )->with('success', 'Location updated successfully.');
    }

    /**
     * Delete the user's location.
     */
    public function destroy()
    {
        $user = auth()->user();
        NewLocation::where('user_id', $user->id)->delete();

        return redirect()->route(
            $user->hasRole('ev-station') ? 'station.location.index' : 'garage.location.index'
        )->with('success', 'Location removed successfully.');
    }
}
