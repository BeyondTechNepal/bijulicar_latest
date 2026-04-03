<?php

namespace App\Http\Controllers\Garage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GarageVerificationController extends Controller
{
    /**
     * Show the garage verification form.
     * Only allow access if the user has never submitted or was rejected.
     */
    public function create()
    {
        $user = Auth::user();
        $verification = $user->garageVerification;

        // Block access if pending or approved
        if ($verification && !$verification->isRejected()) {
            return redirect()->route('verification.pending');
        }

        return view('verification.garage');
    }

    /**
     * Handle submission of garage verification.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $verification = $user->garageVerification;

        // Prevent resubmission if still pending/approved
        if ($verification && !$verification->isRejected()) {
            return redirect()->route('verification.pending');
        }

        // Validate input
        $validated = $request->validate([
            'garage_name' => 'required|string|max:255',
            'contact' => 'required|string|max:20',
            'garage_location' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'license' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB
        ]);

        // Store uploaded file securely
        $path = $request->file('license')->store('garage-docs', 'private');

        // Prepare data array
        $data = [
            'garage_name' => $validated['garage_name'],
            'contact' => $validated['contact'],
            'garage_location' => $validated['garage_location'],
            'specialization' => $validated['specialization'],
            'license_path' => $path,
            'status' => 'pending',
            'rejection_reason' => null,
        ];

        // Create or update existing record
        if ($verification) {
            // Delete old license file if resubmitting
            if ($verification->license_path) {
                Storage::disk('private')->delete($verification->license_path);
            }
            $verification->update($data);
        } else {
            $user->garageVerification()->create($data);
        }

        return redirect()->route('verification.pending')->with('success', 'Garage credentials submitted! Our team will verify your center shortly.');
    }

    /**
     * Optional: Show pending status separately
     * (useful if you want a dedicated route instead of the Blade handling it)
     */
    public function pending()
    {
        $user = Auth::user();
        $verification = $user->garageVerification;

        // Redirect to dashboard if approved
        if ($verification?->status === 'approved') {
            return redirect()->route('dashboard');
        }

        return view('verification.pending', [
            'verification' => $verification,
        ]);
    }
}
