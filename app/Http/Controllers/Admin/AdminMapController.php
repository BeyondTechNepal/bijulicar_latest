<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewLocation;
use Illuminate\Http\Request;

class AdminMapController extends Controller
{
    /**
     * Display a listing of all locations for the admin.
     */
    public function index()
    {
        // Admins usually need to see everything, including inactive ones
        $locations = NewLocation::with('user')->latest()->paginate(15);
        return view('admin.locations.index', compact('locations'));
    }

    /**
     * Show the form for editing a specific location.
     */
    public function edit($id)
    {
        $location = NewLocation::findOrFail($id);
        return view('admin.locations.edit', compact('location'));
    }

    /**
     * Update the location (e.g., toggle active status or fix coordinates).
     */
    public function update(Request $request, $id)
    {
        $location = NewLocation::findOrFail($id);

        $validated = $request->validate([
            'type' => 'required|string',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'is_active' => 'required|boolean',
        ]);

        $location->update($validated);

        return redirect()->route('admin.locations.index')->with('success', 'Location updated successfully.');
    }

    /**
     * Remove a location from the database.
     */
    public function destroy($id)
    {
        $location = NewLocation::findOrFail($id);
        $location->delete();

        return redirect()->route('admin.locations.index')->with('success', 'Location deleted successfully.');
    }

    /**
     * Quick toggle for activation via AJAX or simple GET.
     */
    public function toggleStatus($id)
    {
        $location = NewLocation::findOrFail($id);
        $location->is_active = !$location->is_active;
        $location->save();

        return back()->with('success', 'Status updated successfully.');
    }

    public function create()
    {
        return view('admin.locations.edit'); // Reusing edit view for creation
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'is_active' => 'required|boolean',
        ]);

        $validated['user_id'] = auth()->id();
        NewLocation::create($validated);

        return redirect()->route('admin.locations.index')->with('success', 'New location added!');
    }
}
