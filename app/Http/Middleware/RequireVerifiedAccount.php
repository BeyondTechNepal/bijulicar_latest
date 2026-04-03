<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequireVerifiedAccount
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Buyers skip verification entirely
        if ($user->hasRole('buyer')) {
            return $next($request);
        }

        if ($user->hasRole('seller')) {
            $verification = $user->sellerVerification;

            // Never submitted the form → send them to the verification form
            if (!$verification) {
                return redirect()->route('seller.verify.create')->with('info', 'Please complete your seller verification to continue.');
            }

            // Submitted but pending or rejected → show the waiting/status screen
            if (!$verification->isApproved()) {
                return redirect()->route('verification.pending');
            }
        }

        if ($user->hasRole('business')) {
            $verification = $user->businessVerification;

            // Never submitted the form
            if (!$verification) {
                return redirect()->route('business.verify.create')->with('info', 'Please complete your business verification to continue.');
            }

            // Submitted but not yet approved
            if (!$verification->isApproved()) {
                return redirect()->route('verification.pending');
            }
        }

        if ($user->hasRole('ev-station')) {
            $verification = $user->stationVerification;

            // 1. No data submitted yet
            if (!$verification) {
                // Prevent redirect if already on the create page
                if (!request()->routeIs('station.verify.create')) {
                    return redirect()->route('station.verify.create')->with('info', 'Please register your EV Station details to go live.');
                }
            }

            // 2. Data submitted but waiting for approval
            if ($verification && !$verification->isApproved()) {
                // CRITICAL: Only redirect if they AREN'T already on the pending page
                if (!request()->routeIs('verification.pending')) {
                    return redirect()->route('verification.pending');
                }
            }
        }

        // --- Garage ---
        if ($user->hasRole('garage')) {
            $verification = $user->garageVerification;

            // 1. No data submitted yet
            if (!$verification) {
                if (!request()->routeIs('garage.verify.create')) {
                    return redirect()->route('garage.verify.create')
                        ->with('info', 'Please submit your Garage workshop credentials to continue.');
                }
            }

            // 2. Data submitted but waiting for approval
            if ($verification && !$verification->isApproved()) {
                if (!request()->routeIs('verification.pending')) {
                    return redirect()->route('verification.pending');
                }
            }
        }

        // if ($user->hasRole('garage')) {
        //     $verification = $user->garageVerification;

        //     // 1. No data submitted yet
        //     if (! $verification) {
        //         return redirect()->route('garage.verify.create')
        //             ->with('info', 'Please submit your Garage workshop credentials.');
        //     }

        //     // 2. Data submitted but waiting for Admin/Mentor approval
        //     if (! $verification->isApproved()) {
        //         return redirect()->route('verification.pending');
        //     }
        // }

        return $next($request);
    }
}
