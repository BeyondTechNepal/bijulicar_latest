<?php

namespace App\Http\Controllers\Evstation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EVStationVerificationController extends Controller
{
    /**
     * Show verification form if allowed
     */
    public function create()
    {
        $user = Auth::user();
        $verification = $user->stationVerification;

        // Only allow form if never submitted or previously rejected
        if ($verification && !$verification->isRejected()) {
            return redirect()->route('verification.pending');
        }

        return view('verification.evstation');
    }

    /**
     * Handle submission or resubmission
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $verification = $user->stationVerification;

        // Prevent resubmission if pending or approved
        if ($verification && !$verification->isRejected()) {
            return redirect()->route('verification.pending');
        }

        // Validation
        $request->validate([
            'station_name' => ['required', 'string', 'max:255'],
            'contact_number' => ['required', 'string', 'max:20'],
            'station_location' => ['required', 'string'],
            'operating_license' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'], // 5MB max
        ]);

        // Store uploaded file securely
        $path = $request->file('operating_license')->store('station-docs', 'private');

        if ($verification) {
            // Remove old file if exists
            if ($verification->license_path && Storage::disk('private')->exists($verification->license_path)) {
                Storage::disk('private')->delete($verification->license_path);
            }

            // Update record
            $verification->update([
                'station_name' => $request->station_name,
                'contact_number' => $request->contact_number,
                'location_details' => $request->station_location,
                'license_path' => $path,
                'status' => 'pending',
                'rejection_reason' => null,
            ]);
        } else {
            // Create new verification
            $user->stationVerification()->create([
                'station_name' => $request->station_name,
                'contact_number' => $request->contact_number,
                'location_details' => $request->station_location,
                'license_path' => $path,
                'status' => 'pending',
            ]);
        }

        return redirect()->route('verification.pending')->with('success', 'Station credentials submitted! Our team will verify your details shortly.');
    }

    /**
     * Show the pending/rejected status page
     */
    public function pending()
    {
        $user = Auth::user();
        $verification = $user->stationVerification;

        // Redirect approved users straight to dashboard
        if ($verification && $verification->status === 'approved') {
            return redirect()->route('dashboard')->with('success', 'Your account is approved.');
        }

        return view('verification.pending', compact('verification'));
    }
}
