<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SellerVerification;
use App\Models\BusinessVerification;
use App\Mail\AccountApprovedMail;
use App\Mail\AccountRejectedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AdminVerificationController extends Controller
{
    // List all pending verifications
    public function index()
    {
        $sellersPending   = SellerVerification::with('user')
            ->where('status', 'pending')->latest()->get();

        $businessesPending = BusinessVerification::with('user')
            ->where('status', 'pending')->latest()->get();

        $sellersAll   = SellerVerification::with('user')
            ->whereIn('status', ['approved', 'rejected'])->latest()->get();

        $businessesAll = BusinessVerification::with('user')
            ->whereIn('status', ['approved', 'rejected'])->latest()->get();

        return view('admin.verifications.index', compact(
            'sellersPending', 'businessesPending',
            'sellersAll', 'businessesAll'
        ));
    }

    // Approve a seller
    public function approveSeller(SellerVerification $verification)
    {
        $verification->update(['status' => 'approved', 'rejection_reason' => null]);
        Mail::to($verification->user->email)->send(new AccountApprovedMail($verification->user));

        return back()->with('success', "{$verification->user->name} approved.");
    }

    // Reject a seller
    public function rejectSeller(Request $request, SellerVerification $verification)
    {
        $request->validate(['reason' => 'required|string|max:500']);

        $verification->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->reason,
        ]);

        Mail::to($verification->user->email)->send(
            new AccountRejectedMail($verification->user, $request->reason)
        );

        return back()->with('success', "{$verification->user->name} rejected.");
    }

    // Approve a business
    public function approveBusiness(BusinessVerification $verification)
    {
        $verification->update(['status' => 'approved', 'rejection_reason' => null]);
        Mail::to($verification->user->email)->send(new AccountApprovedMail($verification->user));

        return back()->with('success', "{$verification->user->name} approved.");
    }

    // Reject a business
    public function rejectBusiness(Request $request, BusinessVerification $verification)
    {
        $request->validate(['reason' => 'required|string|max:500']);

        $verification->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->reason,
        ]);

        Mail::to($verification->user->email)->send(
            new AccountRejectedMail($verification->user, $request->reason)
        );

        return back()->with('success', "{$verification->user->name} rejected.");
    }

    // Securely serve a private document (admin only)
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
}