<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use App\Http\Requests\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified,
     * then send them straight to their document-verification form.
     * (Step 2 of the onboarding flow: email ✔ → doc verification → admin approval)
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $user = $request->user();

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        // Step 2 of 3: go fill in the document verification form
        $docRoute = match (true) {
            $user->hasRole('seller')     => route('seller.verify.create'),
            $user->hasRole('business')   => route('business.verify.create'),
            $user->hasRole('ev-station') => route('station.verify.create'),
            $user->hasRole('garage')     => route('garage.verify.create'),
            default                      => route('buyer.verify.create'),   // buyer + fallback
        };

        return redirect($docRoute)->with('success', 'Email verified! Now complete your account verification.');
    }
}