<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest as BaseRequest;
use Illuminate\Support\Facades\Auth;

class EmailVerificationRequest extends BaseRequest
{
    public function authorize(): bool
    {
        // If not logged in at all, redirect to login rather than 403
        if (! $this->user()) {
            return false; // caught by failedAuthorization below
        }

        if (! hash_equals((string) $this->user()->getKey(), (string) $this->route('id'))) {
            return false;
        }

        if (! hash_equals(sha1($this->user()->getEmailForVerification()), (string) $this->route('hash'))) {
            return false;
        }

        return true;
    }

    protected function failedAuthorization()
    {
        // ── BUG 3 FIX ─────────────────────────────────────────────────────────
        // Previously, any mismatch between the link's {id} and the currently
        // logged-in user caused a bare 403 error. This happens when Browser B
        // has a completely different account signed in (e.g. a family member's
        // account) and the user clicks their own verification link in that browser.
        //
        // We now distinguish two cases:
        //
        //   Case A — nobody is logged in:
        //     The 'auth' middleware already stores the signed URL as intended() and
        //     redirects to /login automatically. We only reach failedAuthorization
        //     here if auth() somehow returns null after the middleware ran, which
        //     is an edge case. Redirect to login with a helpful message.
        //
        //   Case B — a DIFFERENT account is logged in:
        //     Log out that session cleanly so the correct user can sign in and
        //     retry the link. The signed URL is still valid, so we store it as the
        //     intended URL before logging out, then send them to login. After they
        //     sign in with the matching account, AuthenticatedSessionController
        //     will detect the pending intended URL and replay the verification link.
        // ──────────────────────────────────────────────────────────────────────

        if (! $this->user()) {
            // Case A: not logged in — just go to login
            redirect()
                ->route('login')
                ->with('info', 'Please log in first, then click the verification link again.')
                ->send();
            exit;
        }

        // Case B: wrong account is logged in
        // Store the current full URL (the signed verification link) as intended so
        // AuthenticatedSessionController can replay it after the correct login.
        $this->session()->put('url.intended', $this->fullUrl());

        Auth::logout();
        $this->session()->invalidate();
        $this->session()->regenerateToken();

        redirect()
            ->route('login')
            ->with('info', 'You are signed in with a different account. Please sign in with the account this verification link was sent to.')
            ->send();
        exit;
    }
}