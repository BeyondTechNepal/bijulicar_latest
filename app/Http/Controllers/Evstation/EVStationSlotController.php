<?php

namespace App\Http\Controllers\Evstation;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use App\Mail\SlotRequestApprovedMail;
use App\Mail\SlotRequestRejectedMail;
use App\Models\EvStationSlot;
use App\Models\NewLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EVStationSlotController extends Controller
{
    /**
     * Show the slot management dashboard.
     */
    public function index()
    {
        $user     = auth()->user();
        $location = NewLocation::where('user_id', $user->id)->first();
        $slots    = EvStationSlot::where('user_id', $user->id)
                        ->orderBy('slot_number')
                        ->with('occupant')
                        ->get();

        return view('dashboard.station.slots.index', compact('location', 'slots'));
    }

    /**
     * Save total slot count and sync slot rows.
     */
    public function configure(Request $request)
    {
        $request->validate([
            'total_slots' => 'required|integer|min:1|max:50',
        ]);

        $user       = auth()->user();
        $totalSlots = (int) $request->total_slots;

        NewLocation::where('user_id', $user->id)
            ->update(['total_slots' => $totalSlots]);

        $existing = EvStationSlot::where('user_id', $user->id)
                        ->pluck('slot_number')
                        ->toArray();

        for ($i = 1; $i <= $totalSlots; $i++) {
            if (!in_array($i, $existing)) {
                EvStationSlot::create([
                    'user_id'     => $user->id,
                    'slot_number' => $i,
                    'status'      => 'available',
                ]);
            }
        }

        EvStationSlot::where('user_id', $user->id)
            ->where('slot_number', '>', $totalSlots)
            ->delete();

        return back()->with('success', "Station configured with {$totalSlots} charging ports.");
    }

    /**
     * Manually update a slot's status from the dashboard toggle.
     * Station owner can still manually mark slots occupied/available.
     */
    public function updateSlot(Request $request, EvStationSlot $slot)
    {
        abort_unless($slot->user_id == auth()->id(), 403);

        $request->validate([
            'status'  => 'required|in:available,occupied,booked',
            'free_at' => 'nullable|date|after:now',
        ]);

        $slot->update([
            'status'      => $request->status,
            'free_at'     => $request->status === 'occupied' ? $request->free_at : null,
            'occupied_by' => $request->status === 'available' ? null : $slot->occupied_by,
        ]);

        return back()->with('success', "Slot #{$slot->slot_number} updated.");
    }

    /**
     * Station approves a PENDING customer request.
     * This is the moment the slot becomes truly "occupied" — map turns red.
     * Customer receives a confirmation email.
     */
    public function approveRequest(Request $request, EvStationSlot $slot)
    {
        abort_unless($slot->user_id == auth()->id(), 403);
        abort_unless($slot->isPending(), 422, 'Only pending requests can be approved.');

        $request->validate([
            'free_at' => 'nullable|date|after:now',
        ]);

        $customer = $slot->occupant;
        abort_unless($customer, 404, 'No customer linked to this slot.');

        // Mark as BOOKED — customer confirmed, but vehicle hasn't arrived yet.
        // Station manually marks occupied when vehicle physically arrives.
        $slot->update([
            'status'  => 'booked',
            'free_at' => $request->free_at,
        ]);
        app(NotificationService::class)->slotApproved($slot, $customer);

        Mail::to($customer->email)->send(
            new SlotRequestApprovedMail($slot, $customer)
        );

        return back()->with('success', "Slot #{$slot->slot_number} booked and confirmation email sent to {$customer->name}.");
    }

    /**
     * Station rejects a PENDING customer request.
     * Slot returns to available (green on map). Customer notified by email.
     */
    public function rejectRequest(Request $request, EvStationSlot $slot)
    {
        abort_unless($slot->user_id == auth()->id(), 403);
        abort_unless($slot->isPending(), 422, 'Only pending requests can be rejected.');

        $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $customer = $slot->occupant;
        abort_unless($customer, 404, 'No customer linked to this slot.');

        // Reset slot fully back to available — map turns green again
        $slot->update([
            'status'      => 'available',
            'free_at'     => null,
            'occupied_by' => null,
        ]);
        app(NotificationService::class)->slotRejected($slot, $customer, $request->rejection_reason ?? '');

        Mail::to($customer->email)->send(
            new SlotRequestRejectedMail($slot, $customer, $request->rejection_reason ?? '')
        );

        return back()->with('success', "Request rejected and slot #{$slot->slot_number} is available again.");
    }
}