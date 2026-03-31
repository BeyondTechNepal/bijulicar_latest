<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BusinessVerificationController extends Controller
{
    public function create()
    {
        $user         = Auth::user();
        $verification = $user->businessVerification;

        // Block only if pending or already approved — rejected users may resubmit
        if ($verification && ! $verification->isRejected()) {
            return redirect()->route('verification.pending');
        }

        return view('verification.business');
    }

    public function store(Request $request)
    {
        $user         = Auth::user();
        $verification = $user->businessVerification;

        if ($verification && ! $verification->isRejected()) {
            return redirect()->route('verification.pending');
        }

        $request->validate([
            'business_name'    => ['required', 'string', 'max:255'],
            'contact'          => ['required', 'string', 'max:20'],
            'registration_doc' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ]);

        $path = $request->file('registration_doc')
            ->store('business-docs', 'private');

        // If a rejected record exists, update it. Otherwise create fresh.
        if ($verification) {
            Storage::disk('private')->delete($verification->registration_doc_path);

            $verification->update([
                'business_name'         => $request->business_name,
                'contact'               => $request->contact,
                'registration_doc_path' => $path,
                'status'                => 'pending',
                'rejection_reason'      => null,
            ]);
        } else {
            $user->businessVerification()->create([
                'business_name'         => $request->business_name,
                'contact'               => $request->contact,
                'registration_doc_path' => $path,
            ]);
        }

        return redirect()->route('verification.pending')
            ->with('success', 'Details resubmitted! We will review and notify you by email.');
    }
}