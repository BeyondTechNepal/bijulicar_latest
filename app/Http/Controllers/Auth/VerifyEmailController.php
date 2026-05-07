<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerifyEmailController extends Controller
{
    /**
     * Handle an email verification link click.
     *
     * This route is intentionally outside the 'auth' middleware group so that
     * the user does not need to be logged in on the clicking browser (Browser B).
     * The signed URL already contains cryptographic proof of identity via the
     * {id} and {hash} parameters — no session is needed.
     *
     * Flow:
     *   1. 'signed' middleware validates the HMAC signature + expiry (automatic).
     *   2. We find the user by {id} from the URL.
     *   3. We validate {hash} == sha1(user email) ourselves.
     *   4. We mark the email verified if not already done.
     *   5. Browser B sees a confirmation page — it is NOT logged in.
     *   6. Browser A (where the user registered and is logged in) is polling
     *      GET /email/verified-status every 4 seconds and auto-advances once
     *      it sees {"verified": true}.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        // ── Step 1: Find the user by {id} from the URL ────────────────────────
        // We deliberately do NOT use $request->user() here. That would require
        // an active session, which is exactly what we are removing the need for.
        $user = User::find($request->route('id'));

        // If the user ID in the URL does not exist in our database at all,
        // the link is stale (user was deleted). Show a clear error.
        if (! $user) {
            return view('auth.email-verified-confirmation', [
                'status'  => 'invalid',
                'message' => 'This verification link is invalid or the account no longer exists.',
            ]);
        }

        // ── Step 2: Validate the {hash} against the user's email ─────────────
        // The signed middleware already verified the HMAC signature on the whole
        // URL (so nobody has tampered with {id} or {hash}). We additionally
        // check that {hash} == sha1(email) so a valid signature for user #5
        // cannot be used to verify user #6's email.
        if (! hash_equals(
            sha1($user->getEmailForVerification()),
            (string) $request->route('hash')
        )) {
            return view('auth.email-verified-confirmation', [
                'status'  => 'invalid',
                'message' => 'This verification link is invalid or has expired.',
            ]);
        }

        // ── Step 3: Mark as verified (idempotent) ─────────────────────────────
        // If the user clicked the link a second time (e.g. double-click, or
        // Browser A also clicks it after Browser B already did), we silently
        // skip the marking and show the same success page. No harm done.
        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        // ── Step 4: Show a confirmation page on Browser B ─────────────────────
        // We do NOT log the user in here and we do NOT redirect them into the
        // dashboard flow. Browser B may belong to someone else entirely (shared
        // computer, phone email client). Logging in silently would be a security
        // issue. Instead we show a simple "verified — go back to your original
        // browser" message.
        //
        // Browser A (logged in, polling /email/verified-status) will detect the
        // change automatically and redirect itself to the doc-submission form.
        return view('auth.email-verified-confirmation', [
            'status'  => 'success',
            'message' => 'Your email has been verified!',
        ]);
    }
}