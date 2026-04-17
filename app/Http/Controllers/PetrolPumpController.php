<?php

namespace App\Http\Controllers;

use App\Services\OverpassService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PetrolPumpController extends Controller
{
    public function __construct(private OverpassService $overpass) {}

    /**
     * GET /api/petrol-pumps?lat=27.70&lng=85.32&radius=10
     *
     * Returns cached pumps from DB (via OverpassService).
     * Laravel cache adds a 5-minute in-memory layer on top of the DB
     * so repeated identical requests (same tile, many users) cost ~0 DB queries.
     */
    public function nearby(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'lat'    => 'required|numeric|between:-90,90',
            'lng'    => 'required|numeric|between:-180,180',
            'radius' => 'sometimes|numeric|min:1|max:50',
        ]);

        $lat    = (float) $validated['lat'];
        $lng    = (float) $validated['lng'];
        $radius = (float) ($validated['radius'] ?? 10);

        // Cache key rounded to 0.05° grid (~5.5 km) so nearby users share cache
        $cacheKey = sprintf(
            'pumps:%.2f:%.2f:%d',
            round($lat / 0.05) * 0.05,
            round($lng / 0.05) * 0.05,
            (int) $radius
        );

        // 5-minute application cache — avoids DB even for hot spots
        $pumps = Cache::remember($cacheKey, 300, function () use ($lat, $lng, $radius) {
            return $this->overpass->getPumpsNear($lat, $lng, $radius);
        });

        return response()->json([
            'pumps' => $pumps,
            'count' => count($pumps),
        ])->header('Cache-Control', 'public, max-age=300'); // Browser also caches 5 min
    }
}