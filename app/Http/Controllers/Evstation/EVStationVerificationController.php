<?php

namespace App\Http\Controllers\Evstation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EVStationVerificationController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $verification = $user->stationVerification;

        // Block access if they have a pending or approved request
        // We only allow access if they have never submitted OR were rejected
        if ($verification && !$verification->isRejected()) {
            return redirect()->route('verification.pending');
        }

        return view('verification.evstation');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $verification = $user->stationVerification;

        if ($verification && !$verification->isRejected()) {
            return redirect()->route('verification.pending');
        }

        $request->validate([
            'station_name' => ['required', 'string', 'max:255'],
            'contact_number' => ['required', 'string', 'max:20'],
            'station_location' => ['required', 'string'], // Address or Coordinates
            'operating_license' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'], // 5MB max
        ]);

        // Secure storage in private disk so public users can't see legal docs
        $path = $request->file('operating_license')->store('station-docs', 'private');

        if ($verification) {
            // Clean up old file if they are resubmitting after a rejection
            Storage::disk('private')->delete($verification->license_path);

            $verification->update([
                'station_name' => $request->station_name,
                'contact_number' => $request->contact_number,
                'location_details' => $request->station_location,
                'license_path' => $path,
                'status' => 'pending',
                'rejection_reason' => null,
            ]);
        } else {
            $user->stationVerification()->create([
                'station_name' => $request->station_name,
                'contact_number' => $request->contact_number,
                'location_details' => $request->station_location,
                'license_path' => $path,
                'status' => 'pending',
            ]);
        }

        return redirect()->route('verification.pending')->with('success', 'Station credentials submitted! Our team will verify your node shortly.');
    }
}
