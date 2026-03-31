<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SellerVerificationController extends Controller
{
    // Show the form
    public function create()
    {
        $user = Auth::user();

        // Already submitted — show status page instead
        if ($user->sellerVerification) {
            return redirect()->route('verification.pending');
        }

        return view('verification.seller');
    }

    // Handle form submission
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->sellerVerification) {
            return redirect()->route('verification.pending');
        }

        $request->validate([
            'full_name'   => ['required', 'string', 'max:255'],
            'contact'     => ['required', 'string', 'max:20'],
            'national_id' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ]);

        // Store on private disk
        $path = $request->file('national_id')
            ->store('national-ids', 'private');

        $user->sellerVerification()->create([
            'full_name'        => $request->full_name,
            'contact'          => $request->contact,
            'national_id_path' => $path,
        ]);

        return redirect()->route('verification.pending')
            ->with('success', 'Details submitted! We will review and notify you by email.');
    }
}