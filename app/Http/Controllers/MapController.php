<?php

namespace App\Http\Controllers;
use App\Models\Location;

use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index()
    {
        $locations = Location::latest()->get();
        return view('frontend.pages.map_location', compact('locations'));
    }

    public function getLocations()
    {
        $locations = Location::all();
        return response()->json($locations);
    }
}
