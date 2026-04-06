<?php

namespace App\Http\Controllers;

use App\Models\EvStationSlot;
use App\Models\GarageAppointment;
use App\Models\NewLocation;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index()
    {
        $locations = NewLocation::where('is_active', true)->latest()->get();
        return view('frontend.pages.map_location', compact('locations'));
    }

    /**
     * JSON endpoint consumed by the map JS.
     * Returns each location enriched with live slot / appointment data.
     */
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

                $available = $slots->where('status', 'available')->count();
                $occupied  = $slots->where('status', 'occupied')->count();

                // Earliest free-by time among occupied slots
                $nextFree = $slots
                    ->where('status', 'occupied')
                    ->whereNotNull('free_at')
                    ->sortBy('free_at')
                    ->first();

                $base['slots']           = $slots->values();
                $base['available_slots'] = $available;
                $base['occupied_slots']  = $occupied;
                $base['next_free_at']    = $nextFree?->free_at?->toDateTimeString();

            } elseif ($loc->type === 'garage') {
                $pending  = GarageAppointment::where('garage_user_id', $loc->user_id)
                                ->where('status', 'pending')->count();
                $approved = GarageAppointment::where('garage_user_id', $loc->user_id)
                                ->where('status', 'approved')->count();

                // How many bays are currently busy
                $busyBays = min($approved, $loc->total_slots);
                $freeBays = max(0, $loc->total_slots - $busyBays);

                // Earliest estimated finish among approved appointments
                $nextFinish = GarageAppointment::where('garage_user_id', $loc->user_id)
                    ->where('status', 'approved')
                    ->whereNotNull('estimated_finish_at')
                    ->orderBy('estimated_finish_at')
                    ->value('estimated_finish_at');

                $base['free_bays']        = $freeBays;
                $base['busy_bays']        = $busyBays;
                $base['pending_requests'] = $pending;
                $base['next_finish_at']   = $nextFinish;
            }

            return $base;
        });

        return response()->json($enriched);
    }
}