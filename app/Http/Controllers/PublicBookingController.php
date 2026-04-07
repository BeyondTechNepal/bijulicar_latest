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

        // Only truly available slots can be requested
        if (!$slot->isAvailable()) {
            return response()->json(['message' => 'This slot is no longer available.'], 422);
        }

        // Set to PENDING — does NOT count as occupied yet.
        // The map still shows this slot as available until the station approves.
        $slot->update([
            'status'      => 'pending',
            'occupied_by' => auth()->id(),
        ]);

        return response()->json(['message' => 'Slot request sent!']);
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