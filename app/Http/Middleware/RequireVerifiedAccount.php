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

        // Buyers skip all of this
        if ($user->hasRole('buyer')) {
            return $next($request);
        }

        if ($user->hasRole('seller')) {
            $verification = $user->sellerVerification;

            // Never submitted the form → send them to the form
            if (!$verification) {
                return redirect()->route('seller.verify.create')
                    ->with('info', 'Please complete your seller verification to continue.');
            }

            // Submitted but not approved yet
            if (!$verification->isApproved()) {
                return redirect()->route('verification.pending');
            }
        }

        if ($user->hasRole('business')) {
            $verification = $user->businessVerification;

            if (!$verification) {
                return redirect()->route('business.verify.create')
                    ->with('info', 'Please complete your business verification to continue.');
            }

            if (!$verification->isApproved()) {
                return redirect()->route('verification.pending');
            }
        }

        return $next($request);
    }
}