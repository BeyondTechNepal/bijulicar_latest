<?php

namespace App\Http\Controllers;

use App\Models\EvStationSlot;
use App\Models\GarageAppointment;
use App\Models\NewLocation;
use Illuminate\Http\Request;

class PublicBookingController extends Controller
{
    // ── Garage booking ─────────────────────────────────────────────────

    /**
     * Any authenticated user submits a garage appointment request.
     * garage_user_id comes from the location's user_id.
     */
    public function bookGarage(Request $request)
    {
        $request->validate([
            'garage_user_id'      => 'required|exists:users,id',
            'service_description' => 'required|string|max:300',
            'requested_at'        => 'required|date|after:now',
        ]);

        // Prevent booking your own garage
        if ((int) $request->garage_user_id === auth()->id()) {
            return back()->with('error', 'You cannot book your own garage.');
        }

        // Prevent duplicate pending booking at same garage
        $existing = GarageAppointment::where('garage_user_id', $request->garage_user_id)
            ->where('customer_user_id', auth()->id())
            ->where('status', 'pending')
            ->exists();

        if ($existing) {
            return back()->with('error', 'You already have a pending appointment at this garage.');
        }

        GarageAppointment::create([
            'garage_user_id'      => $request->garage_user_id,
            'customer_user_id'    => auth()->id(),
            'service_description' => $request->service_description,
            'requested_at'        => $request->requested_at,
            'status'              => 'pending',
        ]);

        return back()->with('success', 'Appointment request sent! The garage will confirm shortly.');
    }

    // ── EV slot request ────────────────────────────────────────────────

    /**
     * Any authenticated user requests a specific charging slot.
     */
    public function requestSlot(Request $request)
    {
        $request->validate([
            'slot_id' => 'required|exists:ev_station_slots,id',
        ]);

        $slot = EvStationSlot::findOrFail($request->slot_id);

        // Prevent requesting your own station's slot
        if ($slot->user_id === auth()->id()) {
            return back()->with('error', 'You cannot request your own station slot.');
        }

        if ($slot->isOccupied()) {
            return back()->with('error', 'This slot is currently occupied. Please try another.');
        }

        // Mark slot as occupied by this customer (pending station approval)
        $slot->update([
            'status'      => 'occupied',
            'occupied_by' => auth()->id(),
        ]);

        return back()->with('success', 'Slot request sent! The station will confirm and you will receive an email.');
    }

    // ── My bookings ────────────────────────────────────────────────────

    /**
     * Show the authenticated user's own appointment history (all roles).
     */
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