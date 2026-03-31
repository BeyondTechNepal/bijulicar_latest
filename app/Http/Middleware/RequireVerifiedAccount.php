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

        if (! $user) {
            return redirect()->route('login');
        }

        // Buyers skip verification entirely
        if ($user->hasRole('buyer')) {
            return $next($request);
        }

        if ($user->hasRole('seller')) {
            $verification = $user->sellerVerification;

            // Never submitted the form → send them to the verification form
            if (! $verification) {
                return redirect()->route('seller.verify.create')
                    ->with('info', 'Please complete your seller verification to continue.');
            }

            // Submitted but pending or rejected → show the waiting/status screen
            if (! $verification->isApproved()) {
                return redirect()->route('verification.pending');
            }
        }

        if ($user->hasRole('business')) {
            $verification = $user->businessVerification;

            // Never submitted the form
            if (! $verification) {
                return redirect()->route('business.verify.create')
                    ->with('info', 'Please complete your business verification to continue.');
            }

            // Submitted but not yet approved
            if (! $verification->isApproved()) {
                return redirect()->route('verification.pending');
            }
        }

        return $next($request);
    }
}