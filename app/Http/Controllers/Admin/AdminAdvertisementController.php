<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use App\Mail\AdApprovedMail;
use App\Mail\AdPublishedMail;
use App\Mail\AdRejectedMail;
use App\Models\AdPricingRule;
use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AdminAdvertisementController extends Controller
{
    // ── List ──────────────────────────────────────────────────────────────

    public function index()
    {
        $pending   = Advertisement::with('owner')->pendingReview()->latest()->get();
        $approved  = Advertisement::with('owner')->where('status', 'approved')->latest()->get();
        $published = Advertisement::with('owner')->where('status', 'published')->latest()->paginate(20);
        $rejected  = Advertisement::with('owner')->where('status', 'rejected')->latest()->paginate(20);

        return view('admin.advertisements.index', compact(
            'pending', 'approved', 'published', 'rejected'
        ));
    }

    // ── Review detail ─────────────────────────────────────────────────────

    public function show(Advertisement $advertisement)
    {
        $advertisement->load('owner', 'car');

        $suggestedCharge = $advertisement->calculateExpectedCharge();
        $pricingRule     = AdPricingRule::for($advertisement->placement, $advertisement->priority);

        return view('admin.advertisements.show', compact(
            'advertisement', 'suggestedCharge', 'pricingRule'
        ));
    }

    // ── Approve ───────────────────────────────────────────────────────────

    public function approve(Request $request, Advertisement $advertisement)
    {
        abort_if($advertisement->status !== 'pending_review', 422, 'This ad is not pending review.');

        $request->validate([
            'charged_amount' => ['required', 'numeric', 'min:0'],
        ]);

        $advertisement->update([
            'status'           => 'approved',
            'charged_amount'   => $request->charged_amount,
            'rejection_reason' => null,
            'reviewed_by'      => Auth::id(),
            'reviewed_at'      => now(),
        ]);

        app(NotificationService::class)->adApproved($advertisement);

        $this->sendMail(
            new AdApprovedMail($advertisement),
            $advertisement->owner->email
        );

        return redirect()
            ->route('admin.advertisements.index')
            ->with('success', "Ad \"{$advertisement->title}\" approved. Payment instructions sent to {$advertisement->owner->name}.");
    }

    // ── Reject ────────────────────────────────────────────────────────────

    public function reject(Request $request, Advertisement $advertisement)
    {
        abort_if($advertisement->status !== 'pending_review', 422, 'This ad is not pending review.');

        $request->validate([
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        $advertisement->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->reason,
            'reviewed_by'      => Auth::id(),
            'reviewed_at'      => now(),
        ]);

        app(NotificationService::class)->adRejected($advertisement);

        $this->sendMail(
            new AdRejectedMail($advertisement),
            $advertisement->owner->email
        );

        return redirect()
            ->route('admin.advertisements.index')
            ->with('success', "Ad \"{$advertisement->title}\" rejected.");
    }

    // ── Confirm payment ───────────────────────────────────────────────────

    public function confirmPayment(Request $request, Advertisement $advertisement)
    {
        abort_if($advertisement->status !== 'approved', 422, 'This ad is not in approved/awaiting-payment state.');

        $request->validate([
            'amount_paid'    => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'in:cash,bank,esewa,other'],
            'payment_note'   => ['nullable', 'string', 'max:1000'],
            'paid_at'        => ['required', 'date', 'before_or_equal:today'],
        ]);

        $today    = now()->toDateString();
        $startsAt = $advertisement->starts_at?->toDateString();

        // ── KEY FIX ────────────────────────────────────────────────────────
        // Only flip is_active to true immediately if the ad's start date has
        // already arrived. If starts_at is in the future, keep is_active false
        // — the daily ads:sync-status command will activate it on the right day.
        $isActiveNow = $startsAt === null || $startsAt <= $today;

        $advertisement->update([
            'status'         => 'published',
            'is_active'      => $isActiveNow,
            'amount_paid'    => $request->amount_paid,
            'payment_method' => $request->payment_method,
            'payment_note'   => $request->payment_note,
            'paid_at'        => $request->paid_at,
        ]);

        app(NotificationService::class)->adPublished($advertisement);

        $this->sendMail(
            new AdPublishedMail($advertisement),
            $advertisement->owner->email
        );

        // Only bust the cache if the ad is actually live right now
        if ($isActiveNow) {
            Cache::forget(HomeController::CACHE_HOME_ADS);
        }

        $message = $isActiveNow
            ? "Payment confirmed. Ad \"{$advertisement->title}\" is now live."
            : "Payment confirmed. Ad \"{$advertisement->title}\" is scheduled — it will go live automatically on {$advertisement->starts_at->format('M d, Y')}.";

        return redirect()
            ->route('admin.advertisements.index')
            ->with('success', $message);
    }

    // ── Private helper ────────────────────────────────────────────────────

    private function sendMail(object $mailable, string $to): void
    {
        try {
            Mail::to($to)->queue($mailable);
        } catch (\Throwable $e) {
            Log::warning("Ad email failed to [{$to}]: " . $e->getMessage());
        }
    }
}