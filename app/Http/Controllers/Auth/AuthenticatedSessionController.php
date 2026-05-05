<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        // ── BUG 2 FIX ─────────────────────────────────────────────────────────
        // When the user clicks the verification link in Browser B (where they are
        // not logged in), Laravel's 'auth' middleware intercepts the request and
        // stores the full signed URL via redirect()->intended() before sending them
        // to /login. After they log in here, that intended URL must be honoured
        // BEFORE our own early-return redirect logic runs — otherwise we return
        // redirect()->route('verification.notice') on line 28 and the stored signed
        // URL is silently discarded, leaving the user stuck on the waiting page.
        //
        // We detect this by checking whether a pending intended URL exists in the
        // session and whether it is a verification link. If it is, we let
        // redirect()->intended() replay it so VerifyEmailController can run normally.
        // ──────────────────────────────────────────────────────────────────────
        $intendedUrl = $request->session()->get('url.intended', '');

        if ($intendedUrl && str_contains($intendedUrl, '/verify-email/')) {
            // The user was trying to hit their verification link — restore that
            // journey. VerifyEmailController will mark the email as verified and
            // then forward them to the correct doc-submission form.
            return redirect()->intended(route('dashboard', absolute: false));
        }

        // Step 1: email not verified yet
        if (! $user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        // Step 2: doc verification not submitted yet
        $verification = $user->verification();

        if (! $verification) {
            return redirect(match (true) {
                $user->hasRole('seller')     => route('seller.verify.create'),
                $user->hasRole('business')   => route('business.verify.create'),
                $user->hasRole('ev-station') => route('station.verify.create'),
                $user->hasRole('garage')     => route('garage.verify.create'),
                default                      => route('buyer.verify.create'),
            });
        }

        // Step 3: doc submitted but pending/rejected admin review
        if (! $verification->isApproved()) {
            return redirect()->route('verification.pending');
        }

        // Fully onboarded — go to dashboard
        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}