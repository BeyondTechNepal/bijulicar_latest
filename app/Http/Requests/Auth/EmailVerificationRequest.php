<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest as BaseRequest;

class EmailVerificationRequest extends BaseRequest
{
    public function authorize(): bool
    {
        // If not logged in at all, redirect to login rather than 403
        if (! $this->user()) {
            return false; // will be caught below
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
        // If not authenticated, send them to login (they can click the link again after)
        if (! $this->user()) {
            redirect()->route('login')->with('info', 'Please log in first, then click the verification link again.')->send();
            exit;
        }

        // Hash/ID mismatch — the link is invalid or for a different account
        abort(403, 'This verification link is invalid or has expired.');
    }
}