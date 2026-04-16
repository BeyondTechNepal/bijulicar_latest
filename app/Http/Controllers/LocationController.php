<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewLocation;

class LocationController extends Controller
{
    /**
     * Determine the role-specific config for the current user.
     * Returns: [ type, indexRoute, accent ]
     */
    private function roleConfig(): array
    {
        $user = auth()->user();

        return match (true) {
            $user->hasRole('ev-station') => ['ev-station',  'station.location.index',  'amber'],
            $user->hasRole('garage')     => ['garage',      'garage.location.index',   'amber'],
            $user->hasRole('seller')     => ['seller',      'seller.location.index',   'green'],
            $user->hasRole('business')   => ['business',    'business.location.index', 'purple'],
            default                      => ['unknown',     'home',                    'slate'],
        };
    }

    /**
     * Determine whether this role requires admin approval before going live.
     * ev-station and garage: needs approval (is_active = false initially).
     * seller and business: goes live immediately (is_active = true).
     */
    private function requiresApproval(): bool
    {
        $user = auth()->user();
        return $user->hasRole('ev-station') || $user->hasRole('garage');
    }

    /**
     * Show the user's map location (index page).
     */
    public function index()
    {
        $user     = auth()->user();
        $location = NewLocation::where('user_id', $user->id)->first();

        if ($user->hasRole('ev-station')) {
            return view('dashboard.station.map_location.index', compact('location'));
        }
        if ($user->hasRole('garage')) {
            return view('dashboard.garage.map_location.index', compact('location'));
        }
        if ($user->hasRole('seller')) {
            return view('dashboard.seller.map_location.index', compact('location'));
        }
        if ($user->hasRole('business')) {
            return view('dashboard.business.map_location.index', compact('location'));
        }

        return redirect()->route('home');
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
        if ($user->hasRole('garage')) {
            return view('dashboard.garage.map_location.create');
        }
        if ($user->hasRole('seller')) {
            return view('dashboard.seller.map_location.create');
        }
        if ($user->hasRole('business')) {
            return view('dashboard.business.map_location.create');
        }

        return redirect()->route('home');
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

        $user               = auth()->user();
        [$type, $indexRoute] = $this->roleConfig();
        $needsApproval      = $this->requiresApproval();

        NewLocation::create([
            'user_id'   => $user->id,
            'type'      => $type,
            'address'   => $request->address,
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
            'is_active' => ! $needsApproval, // seller/business go live immediately
        ]);

        $message = $needsApproval
            ? 'Location saved! It will appear on the map once your account is verified.'
            : 'Location saved! Your pin is now live on the map.';

        return redirect()->route($indexRoute)->with('success', $message);
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
        if ($user->hasRole('garage')) {
            return view('dashboard.garage.map_location.edit', compact('location'));
        }
        if ($user->hasRole('seller')) {
            return view('dashboard.seller.map_location.edit', compact('location'));
        }
        if ($user->hasRole('business')) {
            return view('dashboard.business.map_location.edit', compact('location'));
        }

        return redirect()->route('home');
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

        $user                = auth()->user();
        [$type, $indexRoute] = $this->roleConfig();

        $existing = NewLocation::where('user_id', $user->id)->first();

        NewLocation::updateOrCreate(
            ['user_id' => $user->id],
            [
                'type'      => $type,
                'address'   => $request->address,
                'latitude'  => $request->latitude,
                'longitude' => $request->longitude,
                // Keep is_active as-is for ev/garage (admin controls it).
                // For seller/business, ensure it stays true on update.
                'is_active' => $this->requiresApproval()
                    ? ($existing?->is_active ?? false)
                    : true,
            ]
        );

        return redirect()->route($indexRoute)->with('success', 'Location updated successfully.');
    }

    /**
     * Delete the user's location.
     */
    public function destroy()
    {
        $user                = auth()->user();
        [, $indexRoute]      = $this->roleConfig();

        NewLocation::where('user_id', $user->id)->delete();

        return redirect()->route($indexRoute)->with('success', 'Location removed successfully.');
    }
}
