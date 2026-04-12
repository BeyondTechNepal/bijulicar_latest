<?php

namespace App\Http\Controllers;

use App\Models\EvStationSlot;
use App\Models\GarageAppointment;
use App\Models\NewLocation;
use Illuminate\Http\Request;

class PublicBookingController extends Controller
{
    // ── Garage booking ─────────────────────────────────────────────────

    public function bookGarage(Request $request)
    {
        $request->validate([
            'garage_user_id'      => 'required|exists:users,id',
            'service_description' => 'required|string|max:300',
            'requested_at'        => 'required|date|after:now',
        ]);

        if ((int) $request->garage_user_id === auth()->id()) {
            return response()->json(['message' => 'You cannot book your own garage.'], 422);
        }

        $existing = GarageAppointment::where('garage_user_id', $request->garage_user_id)
            ->where('customer_user_id', auth()->id())
            ->where('status', 'pending')
            ->exists();

        if ($existing) {
            return response()->json(['message' => 'You already have a pending appointment at this garage.'], 422);
        }

        GarageAppointment::create([
            'garage_user_id'      => $request->garage_user_id,
            'customer_user_id'    => auth()->id(),
            'service_description' => $request->service_description,
            'requested_at'        => $request->requested_at,
            'status'              => 'pending',
        ]);

        return response()->json(['message' => 'Appointment request sent!']);
    }

    // ── Cancel garage appointment (Bug 9 fix) ─────────────────────────

    public function cancelAppointment(GarageAppointment $appointment)
    {
        // Only the customer who made it can cancel
        abort_if($appointment->customer_user_id !== auth()->id(), 403);

        // Can only cancel while still pending — once approved, the garage
        // has committed a bay, so the customer must contact them directly
        abort_if($appointment->status !== 'pending', 422, 'Only pending appointments can be cancelled. Please contact the garage directly.');

        $appointment->update(['status' => 'rejected', 'rejection_reason' => 'Cancelled by customer.']);

        return redirect()
            ->route('booking.mine')
            ->with('success', 'Appointment cancelled.');
    }

    // ── EV slot request ────────────────────────────────────────────────

    public function requestSlot(Request $request)
    {
        $request->validate([
            'slot_id' => 'required|exists:ev_station_slots,id',
        ]);

        $slot = EvStationSlot::findOrFail($request->slot_id);

        if ($slot->user_id === auth()->id()) {
            return response()->json(['message' => 'You cannot request your own station slot.'], 422);
        }

        if (!$slot->isAvailable()) {
            return response()->json(['message' => 'This slot is no longer available.'], 422);
        }

        $slot->update([
            'status'      => 'pending',
            'occupied_by' => auth()->id(),
        ]);

        return response()->json(['message' => 'Slot request sent!']);
    }

    // ── Cancel EV slot request (Bug 7 fix) ────────────────────────────

    public function cancelSlot(EvStationSlot $slot)
    {
        // Only the customer who requested it can cancel
        abort_if($slot->occupied_by !== auth()->id(), 403);

        // Can only cancel a pending request — once booked/occupied the
        // station has committed, so the customer must contact them directly
        abort_if($slot->status !== 'pending', 422, 'Only pending slot requests can be cancelled. Please contact the station directly.');

        // Reset slot fully back to available so the station's map turns green
        $slot->update([
            'status'      => 'available',
            'occupied_by' => null,
            'free_at'     => null,
        ]);

        return redirect()
            ->route('booking.mine')
            ->with('success', 'Slot request cancelled. The slot is now available again.');
    }

    // ── My bookings ────────────────────────────────────────────────────

    public function myAppointments()
    {
        $appointments = GarageAppointment::where('customer_user_id', auth()->id())
            ->with('garage')
            ->orderByDesc('requested_at')
            ->get();

        $slotRequests = EvStationSlot::where('occupied_by', auth()->id())
            ->with('station')
            ->orderByDesc('updated_at')
            ->get();

        return view('dashboard.shared.my-bookings', compact('appointments', 'slotRequests'));
    }
}