<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;

use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::latest()->get();
        return view('admin.locations.index', compact('locations'));
    }

    public function create()
    {
        return view('admin.locations.form');
    }

    public function store(Request $request)
    {
        Location::create($request->all());
        return redirect()->route('admin.locations.index');
    }

    public function edit(Location $location)
    {
        return view('admin.locations.form', compact('location'));
    }

    public function update(Request $request, Location $location)
    {
        $location->update($request->all());
        return redirect()->route('admin.locations.index');
    }

    public function destroy(Location $location)
    {
        $location->delete();
        return back();
    }
}
