<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Show verify-email page, or skip ahead if the user is already past this step.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        $user = $request->user();

        // Step 1 not done — show the verify email page
        if (!$user->hasVerifiedEmail()) {
            return view('auth.verify-email');
        }

        // Step 1 done — check if step 2 (doc verification) is also done
        $verification = $user->verification();

        if (!$verification) {
            // Email verified but no doc form submitted yet → send to doc form
            return redirect(match (true) {
                $user->hasRole('seller')     => route('seller.verify.create'),
                $user->hasRole('business')   => route('business.verify.create'),
                $user->hasRole('ev-station') => route('station.verify.create'),
                $user->hasRole('garage')     => route('garage.verify.create'),
                default                      => route('buyer.verify.create'),
            });
        }

        if (!$verification->isApproved()) {
            // Doc form submitted but admin hasn't approved yet
            return redirect()->route('verification.pending');
        }

        // Fully approved — go to dashboard
        return redirect()->intended(route('dashboard', absolute: false));
    }
}