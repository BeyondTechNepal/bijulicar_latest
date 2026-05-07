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

        // Step 1: email not verified yet — wait on the verify-email page.
        // Browser A sits here and polls /email/verified-status every 4 seconds.
        // When Browser B clicks the verification link (no login needed there),
        // the poll detects it and auto-advances Browser A. No intended() juggling
        // needed because the verify link no longer requires auth middleware.
        if (! $user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        // Step 2: doc verification form not submitted yet
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

        // Step 3: docs submitted but admin has not approved yet
        if (! $verification->isApproved()) {
            return redirect()->route('verification.pending');
        }

        // Fully onboarded
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