<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * BUG 1 FIX — Cross-browser verification polling endpoint
 *
 * The verify-email waiting page (auth/verify-email.blade.php) polls this
 * endpoint every 4 seconds. When the user verifies their email in a different
 * browser or tab, this endpoint detects it and returns {"verified": true},
 * allowing the blade's JS to automatically redirect Browser A to the next
 * onboarding step without the user having to do anything.
 *
 * Route: GET /email/verified-status   (name: verification.check)
 * Middleware: auth, throttle:20,1
 */
class EmailVerificationStatusController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        // Re-read the user fresh from the DB so we always reflect the latest
        // email_verified_at value, even if it was set by a different browser.
        $request->user()->refresh();

        return response()->json([
            'verified' => $request->user()->hasVerifiedEmail(),
        ]);
    }
}