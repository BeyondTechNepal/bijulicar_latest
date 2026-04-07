<?php

namespace App\Http\Controllers;

use App\Models\EvStationSlot;
use App\Models\GarageAppointment;
use App\Models\GarageBay;
use App\Models\NewLocation;

class MapController extends Controller
{
    public function index()
    {
        $locations = NewLocation::where('is_active', true)->latest()->get();
        return view('frontend.pages.map_location', compact('locations'));
    }

    public function getLocations()
    {
        $locations = NewLocation::where('is_active', true)
            ->with('user')
            ->get();

        $enriched = $locations->map(function ($loc) {

            $base = [
                'id'              => $loc->id,
                'user_id'         => $loc->user_id,
                'type'            => $loc->type,
                'address'         => $loc->address,
                'latitude'        => $loc->latitude,
                'longitude'       => $loc->longitude,
                'name'            => $loc->user->name ?? 'Unknown',
                'total_slots'     => $loc->total_slots,
                'accepts_walkins' => $loc->accepts_walkins,
            ];

            if ($loc->type === 'ev-station') {
                $slots = EvStationSlot::where('user_id', $loc->user_id)
                    ->orderBy('slot_number')
                    ->get(['id', 'slot_number', 'status', 'free_at']);

                // Only truly 'available' slots count as open on the map.
                // 'pending' slots (requested but not yet approved) do NOT
                // reduce the green count — they are still awaiting approval.
                $available = $slots->where('status', 'available')->count();
                $pending   = $slots->where('status', 'pending')->count();
                $occupied  = $slots->where('status', 'occupied')->count();

                $nextFree = $slots
                    ->where('status', 'occupied')
                    ->whereNotNull('free_at')
                    ->sortBy('free_at')
                    ->first();

                $base['slots']           = $slots->values();
                $base['available_slots'] = $available;
                $base['pending_slots']   = $pending;
                $base['occupied_slots']  = $occupied;
                $base['next_free_at']    = $nextFree?->free_at?->toDateTimeString();

            } elseif ($loc->type === 'garage') {
                $bays = GarageBay::where('user_id', $loc->user_id)
                    ->orderBy('bay_number')
                    ->get(['id', 'bay_number', 'status', 'estimated_finish_at']);

                $freeBays    = $bays->where('status', 'available')->count();
                $occupiedBays = $bays->where('status', 'occupied')->count();

                $nextFinish = $bays
                    ->where('status', 'occupied')
                    ->whereNotNull('estimated_finish_at')
                    ->sortBy('estimated_finish_at')
                    ->first();

                $base['bays']          = $bays->values();
                $base['free_bays']     = $freeBays;
                $base['busy_bays']     = $occupiedBays;
                $base['next_finish_at'] = $nextFinish?->estimated_finish_at?->toDateTimeString();
            }

            return $base;
        });

        return response()->json($enriched);
    }
}