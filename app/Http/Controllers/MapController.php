<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Cache;
use App\Models\EvStationSlot;
use App\Models\GarageAppointment;
use App\Models\GarageBay;
use App\Models\Car;
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
    // Cache for 60 seconds — slot/bay availability is real-time-ish,
    // so don't cache too long. Adjust to taste.
    $enriched = Cache::remember('map_locations', 60, function () {

        // Load ALL related data in bulk (3 queries total, not N+2)
        $locations = NewLocation::where('is_active', true)
            ->with('user')
            ->get();

        $userIds = $locations->pluck('user_id')->unique()->values();

        // One query for ALL EV slots across all locations
        $allSlots = EvStationSlot::whereIn('user_id', $userIds)
            ->orderBy('slot_number')
            ->get(['id', 'user_id', 'slot_number', 'status', 'free_at'])
            ->groupBy('user_id');

        // One query for ALL garage bays across all locations
        $allBays = GarageBay::whereIn('user_id', $userIds)
            ->orderBy('bay_number')
            ->get(['id', 'user_id', 'bay_number', 'status', 'estimated_finish_at'])
            ->groupBy('user_id');

        // One query for ALL car listing counts (seller + business share same logic)
        $carCounts = Car::whereIn('seller_id', $userIds)
            ->where('status', 'available')
            ->selectRaw('seller_id, COUNT(*) as count')
            ->groupBy('seller_id')
            ->pluck('count', 'seller_id');

        return $locations->map(function ($loc) use ($allSlots, $allBays, $carCounts) {
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
                $slots     = $allSlots->get($loc->user_id, collect());
                $nextFree  = $slots->where('status', 'occupied')
                                   ->whereNotNull('free_at')
                                   ->sortBy('free_at')
                                   ->first();

                $base['slots']           = $slots->values();
                $base['available_slots'] = $slots->where('status', 'available')->count();
                $base['pending_slots']   = $slots->where('status', 'pending')->count();
                $base['booked_slots']    = $slots->where('status', 'booked')->count();
                $base['occupied_slots']  = $slots->where('status', 'occupied')->count();
                $base['next_free_at']    = $nextFree?->free_at?->toDateTimeString();

            } elseif ($loc->type === 'garage') {
                $bays       = $allBays->get($loc->user_id, collect());
                $nextFinish = $bays->where('status', 'occupied')
                                   ->whereNotNull('estimated_finish_at')
                                   ->sortBy('estimated_finish_at')
                                   ->first();

                $base['bays']           = $bays->values();
                $base['free_bays']      = $bays->where('status', 'available')->count();
                $base['busy_bays']      = $bays->where('status', 'occupied')->count();
                $base['next_finish_at'] = $nextFinish?->estimated_finish_at?->toDateTimeString();

            } elseif (in_array($loc->type, ['seller', 'business'])) {
                $base['listing_count'] = $carCounts->get($loc->user_id, 0);
                $base['profile_url']   = $loc->type === 'seller'
                    ? route('marketplace') . '?seller_id=' . $loc->user_id
                    : route('businesses.show', $loc->user_id);
            }

            return $base;
        });
    });

    return response()->json($enriched);
}
}