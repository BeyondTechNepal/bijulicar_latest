<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AccountApprovedMail;
use App\Mail\AccountRejectedMail;
use App\Models\BusinessVerification;
use App\Models\SellerVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AdminVerificationController extends Controller
{
    /** List all verifications — pending first, then reviewed history. */
    public function index()
    {
        $sellersPending    = SellerVerification::with('user')
            ->where('status', 'pending')
            ->latest()
            ->get();

        $businessesPending = BusinessVerification::with('user')
            ->where('status', 'pending')
            ->latest()
            ->get();

        $sellersAll        = SellerVerification::with('user')
            ->whereIn('status', ['approved', 'rejected'])
            ->latest()
            ->get();

        $businessesAll     = BusinessVerification::with('user')
            ->whereIn('status', ['approved', 'rejected'])
            ->latest()
            ->get();

        return view('admin.verifications.index', compact(
            'sellersPending',
            'businessesPending',
            'sellersAll',
            'businessesAll'
        ));
    }

    // ── Seller ────────────────────────────────────────────────────────

    public function approveSeller(SellerVerification $verification)
    {
        $verification->update([
            'status'           => 'approved',
            'rejection_reason' => null,
        ]);

        $this->sendMail(new AccountApprovedMail($verification->user), $verification->user->email);

        return back()->with('success', "{$verification->user->name} has been approved.");
    }

    public function rejectSeller(Request $request, SellerVerification $verification)
    {
        $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $verification->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->reason,
        ]);

        $this->sendMail(
            new AccountRejectedMail($verification->user, $request->reason),
            $verification->user->email
        );

        return back()->with('success', "{$verification->user->name} has been rejected.");
    }

    // ── Business ──────────────────────────────────────────────────────

    public function approveBusiness(BusinessVerification $verification)
    {
        $verification->update([
            'status'           => 'approved',
            'rejection_reason' => null,
        ]);

        $this->sendMail(new AccountApprovedMail($verification->user), $verification->user->email);

        return back()->with('success', "{$verification->user->name} has been approved.");
    }

    public function rejectBusiness(Request $request, BusinessVerification $verification)
    {
        $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $verification->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->reason,
        ]);

        $this->sendMail(
            new AccountRejectedMail($verification->user, $request->reason),
            $verification->user->email
        );

        return back()->with('success', "{$verification->user->name} has been rejected.");
    }

    // ── Secure document viewer ────────────────────────────────────────

    public function viewDocument(string $type, int $id)
    {
        $record = $type === 'seller'
            ? SellerVerification::findOrFail($id)
            : BusinessVerification::findOrFail($id);

        $path = $type === 'seller'
            ? $record->national_id_path
            : $record->registration_doc_path;

        abort_unless(Storage::disk('private')->exists($path), 404);

        return Storage::disk('private')->response($path);
    }

    // ── Private helper ────────────────────────────────────────────────

    /**
     * Send mail safely — approval/rejection always succeeds even if
     * the mail server is unreachable or credentials aren't set yet.
     * Failures are logged silently so you can debug without crashing.
     */
    private function sendMail(object $mailable, string $to): void
    {
        try {
            Mail::to($to)->send($mailable);
        } catch (\Throwable $e) {
            Log::warning("Verification email failed to [{$to}]: " . $e->getMessage());
        }
    }
}