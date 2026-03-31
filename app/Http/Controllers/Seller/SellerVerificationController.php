<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SellerVerificationController extends Controller
{
    public function create()
    {
        $user         = Auth::user();
        $verification = $user->sellerVerification;

        // Block only if pending or already approved — rejected users may resubmit
        if ($verification && ! $verification->isRejected()) {
            return redirect()->route('verification.pending');
        }

        return view('verification.seller');
    }

    public function store(Request $request)
    {
        $user         = Auth::user();
        $verification = $user->sellerVerification;

        if ($verification && ! $verification->isRejected()) {
            return redirect()->route('verification.pending');
        }

        $request->validate([
            'full_name'   => ['required', 'string', 'max:255'],
            'contact'     => ['required', 'string', 'max:20'],
            'national_id' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ]);

        $path = $request->file('national_id')
            ->store('national-ids', 'private');

        // If a rejected record exists, update it. Otherwise create fresh.
        if ($verification) {
            // Delete old file to avoid orphaned files on private disk
            Storage::disk('private')->delete($verification->national_id_path);

            $verification->update([
                'full_name'        => $request->full_name,
                'contact'          => $request->contact,
                'national_id_path' => $path,
                'status'           => 'pending',
                'rejection_reason' => null,
            ]);
        } else {
            $user->sellerVerification()->create([
                'full_name'        => $request->full_name,
                'contact'          => $request->contact,
                'national_id_path' => $path,
            ]);
        }

        return redirect()->route('verification.pending')
            ->with('success', 'Details resubmitted! We will review and notify you by email.');
    }
}