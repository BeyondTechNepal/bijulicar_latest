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

        // Step 1: email not verified yet
        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        // Step 2: doc verification not submitted yet
        $verification = $user->verification();

        if (!$verification) {
            return redirect(match (true) {
                $user->hasRole('seller')     => route('seller.verify.create'),
                $user->hasRole('business')   => route('business.verify.create'),
                $user->hasRole('ev-station') => route('station.verify.create'),
                $user->hasRole('garage')     => route('garage.verify.create'),
                default                      => route('buyer.verify.create'),
            });
        }

        // Step 3: doc submitted but pending/rejected admin review
        if (!$verification->isApproved()) {
            return redirect()->route('verification.pending');
        }

        // Fully onboarded — go to dashboard
        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}