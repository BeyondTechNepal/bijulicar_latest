<?php

namespace App\Http\Controllers\Evstation;

use App\Http\Controllers\Controller;
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
     * Sets up slots automatically if none exist yet.
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
     * Save the total slot count for this station.
     * Syncs slot records: creates new ones, removes extras.
     */
    public function configure(Request $request)
    {
        $request->validate([
            'total_slots' => 'required|integer|min:1|max:50',
        ]);

        $user        = auth()->user();
        $totalSlots  = (int) $request->total_slots;

        // Update the location record
        NewLocation::where('user_id', $user->id)
            ->update(['total_slots' => $totalSlots]);

        // Sync slot rows: ensure we have exactly $totalSlots records
        $existing = EvStationSlot::where('user_id', $user->id)
                        ->pluck('slot_number')
                        ->toArray();

        // Create missing slots
        for ($i = 1; $i <= $totalSlots; $i++) {
            if (!in_array($i, $existing)) {
                EvStationSlot::create([
                    'user_id'     => $user->id,
                    'slot_number' => $i,
                    'status'      => 'available',
                ]);
            }
        }

        // Remove slots beyond the new total
        EvStationSlot::where('user_id', $user->id)
            ->where('slot_number', '>', $totalSlots)
            ->delete();

        return back()->with('success', "Station configured with {$totalSlots} charging ports.");
    }

    /**
     * Update a single slot's status and optional free_at time.
     * Called by the toggle buttons on the slot grid.
     */
    public function updateSlot(Request $request, EvStationSlot $slot)
    {
        // Ensure the slot belongs to the authenticated station
        abort_unless($slot->user_id === auth()->id(), 403);

        $request->validate([
            'status'  => 'required|in:available,occupied',
            'free_at' => 'nullable|date|after:now',
        ]);

        $slot->update([
            'status'  => $request->status,
            'free_at' => $request->status === 'occupied' ? $request->free_at : null,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'slot' => $slot]);
        }

        return back()->with('success', "Slot #{$slot->slot_number} updated.");
    }

    /**
     * Station approves a customer's slot request — sends approval email.
     */
    public function approveRequest(Request $request, EvStationSlot $slot)
    {
        abort_unless($slot->user_id === auth()->id(), 403);
        abort_unless($slot->occupant, 404, 'No pending customer on this slot.');

        $customer = $slot->occupant;

        Mail::to($customer->email)->send(
            new SlotRequestApprovedMail($slot, $customer)
        );

        return back()->with('success', "Approval email sent to {$customer->name}.");
    }

    /**
     * Station rejects a customer's slot request — sends rejection email.
     */
    public function rejectRequest(Request $request, EvStationSlot $slot)
    {
        abort_unless($slot->user_id === auth()->id(), 403);
        abort_unless($slot->occupant, 404, 'No pending customer on this slot.');

        $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $customer = $slot->occupant;

        // Free the slot back up
        $slot->update([
            'status'      => 'available',
            'free_at'     => null,
            'occupied_by' => null,
        ]);

        Mail::to($customer->email)->send(
            new SlotRequestRejectedMail($slot, $customer, $request->rejection_reason ?? '')
        );

        return back()->with('success', "Slot #{$slot->slot_number} freed and rejection email sent.");
    }
}