<?php

namespace App\Http\Controllers;

use App\Models\NewLocation;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index()
    {
        // Only show active/verified locations on the public map
        $locations = NewLocation::where('is_active', true)->latest()->get();
        return view('frontend.pages.map_location', compact('locations'));
    }

    public function getLocations()
    {
        $locations = NewLocation::where('is_active', true)->get();
        return response()->json($locations);
    }
}
