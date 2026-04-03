<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AccountApprovedMail;
use App\Mail\AccountRejectedMail;
use App\Models\BusinessVerification;
use App\Models\SellerVerification;
use App\Models\StationVerification;
use App\Models\GarageVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AdminVerificationController extends Controller
{
    /** List all verifications — pending first, then reviewed history. */
    public function index()
    {
        $sellersPending = SellerVerification::with('user')->where('status', 'pending')->latest()->get();

        $businessesPending = BusinessVerification::with('user')->where('status', 'pending')->latest()->get();

        $evStationsPending = StationVerification::with('user')->where('status', 'pending')->latest()->get();

        $garagePending = GarageVerification::with('user')->where('status', 'pending')->latest()->get();

        $sellersAll = SellerVerification::with('user')
            ->whereIn('status', ['approved', 'rejected'])
            ->latest()
            ->get();

        $businessesAll = BusinessVerification::with('user')
            ->whereIn('status', ['approved', 'rejected'])
            ->latest()
            ->get();

        $evStationsAll = StationVerification::with('user')
            ->whereIn('status', ['approved', 'rejected'])
            ->latest()
            ->get();

        $garageAll = GarageVerification::with('user')
            ->whereIn('status', ['approved', 'rejected'])
            ->latest()
            ->get();

        return view('admin.verifications.index', compact('sellersPending', 'businessesPending', 'evStationsPending', 'garagePending', 'sellersAll', 'businessesAll', 'evStationsAll', 'garageAll'));
    }

    // ── Seller ────────────────────────────────────────────────────────

    public function approveSeller(SellerVerification $verification)
    {
        $verification->update([
            'status' => 'approved',
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
            'status' => 'rejected',
            'rejection_reason' => $request->reason,
        ]);

        $this->sendMail(new AccountRejectedMail($verification->user, $request->reason), $verification->user->email);

        return back()->with('success', "{$verification->user->name} has been rejected.");
    }

    // ── Business ──────────────────────────────────────────────────────

    public function approveBusiness(BusinessVerification $verification)
    {
        $verification->update([
            'status' => 'approved',
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
            'status' => 'rejected',
            'rejection_reason' => $request->reason,
        ]);

        $this->sendMail(new AccountRejectedMail($verification->user, $request->reason), $verification->user->email);

        return back()->with('success', "{$verification->user->name} has been rejected.");
    }

    // ── EV Station ───────────────────────────────────────────────────

    public function approveEV(StationVerification $verification)
    {
        $verification->update([
            'status' => 'approved',
            'rejection_reason' => null,
        ]);

        // Send the same approval mail pattern you used for others
        $this->sendMail(new AccountApprovedMail($verification->user), $verification->user->email);

        return back()->with('success', "EV Station '{$verification->station_name}' has been approved.");
    }

    public function rejectEV(Request $request, StationVerification $verification)
    {
        $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $verification->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason,
        ]);

        $this->sendMail(new AccountRejectedMail($verification->user, $request->reason), $verification->user->email);

        return back()->with('success', "EV Station '{$verification->station_name}' has been rejected.");
    }

    /**
     * Approve a garage verification request.
     */
    public function approveGarage(GarageVerification $verification)
    {
        $verification->update([
            'status' => 'approved',
            'rejection_reason' => null,
        ]);

        // Clear rejection reason and notify the user
        $this->sendMail(
            new AccountApprovedMail($verification->user), 
            $verification->user->email
        );

        return back()->with('success', "Garage '{$verification->garage_name}' has been approved successfully.");
    }

    /**
     * Reject a garage verification request with a reason.
     */
    public function rejectGarage(Request $request, GarageVerification $verification)
    {
        $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $verification->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason,
        ]);

        // Send the rejection mail with the specific reason provided by the admin
        $this->sendMail(
            new AccountRejectedMail($verification->user, $request->reason), 
            $verification->user->email
        );

        return back()->with('success', "Garage '{$verification->garage_name}' has been rejected.");
    }



    // ── Secure document viewer ────────────────────────────────────────

    public function viewDocument($type, $id)
    {
        // 1. Find the record based on type
        $record = match ($type) {
            'seller' => SellerVerification::findOrFail($id),
            'business' => BusinessVerification::findOrFail($id),
            'ev_station' => StationVerification::findOrFail($id), // Ensure this model exists
            default => abort(404),
        };

        // 2. Get the specific path column for each type
        $path = match ($type) {
            'seller' => $record->national_id_path,
            'business' => $record->registration_doc_path,
            'ev_station' => $record->license_path, // Double check this column name in your EV table!
            default => null,
        };

        // 3. Use your existing Storage logic
        abort_unless($path && Storage::disk('private')->exists($path), 404);

        return response()->file(Storage::disk('private')->path($path));
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
