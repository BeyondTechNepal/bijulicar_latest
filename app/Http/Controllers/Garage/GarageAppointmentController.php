<?php

namespace App\Http\Controllers\Garage;

use App\Http\Controllers\Controller;
use App\Mail\BookingApprovedMail;
use App\Mail\BookingRejectedMail;
use App\Models\GarageAppointment;
use App\Models\NewLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class GarageAppointmentController extends Controller
{
    /**
     * Show all appointments for this garage owner.
     */
    public function index()
    {
        $user     = auth()->user();
        $location = NewLocation::where('user_id', $user->id)->first();

        $appointments = GarageAppointment::where('garage_user_id', $user->id)
            ->with('customer')
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'completed', 'rejected')")
            ->orderByDesc('requested_at')
            ->get();

        $pending   = $appointments->where('status', 'pending');
        $approved  = $appointments->where('status', 'approved');
        $completed = $appointments->where('status', 'completed');
        $rejected  = $appointments->where('status', 'rejected');

        return view('dashboard.garage.appointments.index',
            compact('location', 'appointments', 'pending', 'approved', 'completed', 'rejected'));
    }

    /**
     * Show a single appointment detail.
     */
    public function show(GarageAppointment $appointment)
    {
        abort_unless($appointment->garage_user_id === auth()->id(), 403);
        $appointment->load('customer');
        return view('dashboard.garage.appointments.show', compact('appointment'));
    }

    /**
     * Approve a pending appointment and notify the customer by email.
     */
    public function approve(Request $request, GarageAppointment $appointment)
    {
        abort_unless($appointment->garage_user_id === auth()->id(), 403);
        abort_unless($appointment->isPending(), 422, 'Only pending appointments can be approved.');

        $request->validate([
            'bay_number'           => 'nullable|integer|min:1|max:50',
            'estimated_finish_at'  => 'nullable|date|after:requested_at',
            'garage_note'          => 'nullable|string|max:500',
        ]);

        $appointment->update([
            'status'              => 'approved',
            'bay_number'          => $request->bay_number,
            'estimated_finish_at' => $request->estimated_finish_at,
            'garage_note'         => $request->garage_note,
        ]);

        Mail::to($appointment->customer->email)->send(
            new BookingApprovedMail($appointment)
        );

        return back()->with('success', "Appointment approved and confirmation email sent to {$appointment->customer->name}.");
    }

    /**
     * Reject a pending appointment and notify the customer by email.
     */
    public function reject(Request $request, GarageAppointment $appointment)
    {
        abort_unless($appointment->garage_user_id === auth()->id(), 403);
        abort_unless($appointment->isPending(), 422, 'Only pending appointments can be rejected.');

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $appointment->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        Mail::to($appointment->customer->email)->send(
            new BookingRejectedMail($appointment)
        );

        return back()->with('success', "Appointment rejected and notification sent to {$appointment->customer->name}.");
    }

    /**
     * Mark an approved appointment as completed.
     */
    public function complete(GarageAppointment $appointment)
    {
        abort_unless($appointment->garage_user_id === auth()->id(), 403);
        abort_unless($appointment->isApproved(), 422, 'Only approved appointments can be marked complete.');

        $appointment->update(['status' => 'completed']);

        return back()->with('success', 'Appointment marked as completed.');
    }

    /**
     * Configure the total number of bays for this garage.
     */
    public function configureBays(Request $request)
    {
        $request->validate([
            'total_slots'     => 'required|integer|min:1|max:30',
            'accepts_walkins' => 'nullable|boolean',
        ]);

        NewLocation::where('user_id', auth()->id())->update([
            'total_slots'     => $request->total_slots,
            'accepts_walkins' => $request->boolean('accepts_walkins', true),
        ]);

        return back()->with('success', 'Garage bay configuration updated.');
    }
}