<?php

namespace App\Http\Controllers\Garage;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use App\Mail\BookingApprovedMail;
use App\Mail\BookingRejectedMail;
use App\Models\GarageAppointment;
use App\Models\GarageBay;
use App\Models\NewLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class GarageAppointmentController extends Controller
{
    /**
     * Show appointments + bay grid.
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

        // Bay grid — auto-create if not yet set up
        $bays = GarageBay::where('user_id', $user->id)
            ->orderBy('bay_number')
            ->with('appointment.customer')
            ->get();

        return view('dashboard.garage.appointments.index',
            compact('location', 'appointments', 'pending', 'approved', 'completed', 'rejected', 'bays'));
    }

    /**
     * Show a single appointment detail.
     */
    public function show(GarageAppointment $appointment)
    {
        abort_unless($appointment->garage_user_id == auth()->id(), 403);
        $appointment->load('customer');
        return view('dashboard.garage.appointments.show', compact('appointment'));
    }

    /**
     * Approve a pending appointment.
     * Also marks the assigned bay as occupied if a bay number was given.
     */
    public function approve(Request $request, GarageAppointment $appointment)
    {
        abort_unless($appointment->garage_user_id == auth()->id(), 403);
        abort_unless($appointment->isPending(), 422, 'Only pending appointments can be approved.');

        $request->validate([
            'bay_number'          => 'nullable|integer|min:1|max:50',
            'estimated_finish_at' => 'nullable|date',
            'garage_note'         => 'nullable|string|max:500',
        ]);

        $appointment->update([
            'status'              => 'approved',
            'bay_number'          => $request->bay_number,
            'estimated_finish_at' => $request->estimated_finish_at,
            'garage_note'         => $request->garage_note,
        ]);

        app(NotificationService::class)->appointmentApproved($appointment);

        // Sync bay status if a bay number was assigned
        if ($request->bay_number) {
            GarageBay::where('user_id', auth()->id())
                ->where('bay_number', $request->bay_number)
                ->update([
                    'status'               => 'occupied',
                    'walkin_customer_name' => null,
                    'service_note'         => $appointment->service_description,
                    'estimated_finish_at'  => $request->estimated_finish_at,
                    'appointment_id'       => $appointment->id,
                ]);
        }

        Mail::to($appointment->customer->email)->send(
            new BookingApprovedMail($appointment)
        );

        return back()->with('success', "Appointment approved and confirmation email sent to {$appointment->customer->name}.");
    }

    /**
     * Reject a pending appointment.
     */
    public function reject(Request $request, GarageAppointment $appointment)
    {
        abort_unless($appointment->garage_user_id == auth()->id(), 403);
        abort_unless($appointment->isPending(), 422, 'Only pending appointments can be rejected.');

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $appointment->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        app(NotificationService::class)->appointmentRejected($appointment);

        Mail::to($appointment->customer->email)->send(
            new BookingRejectedMail($appointment)
        );

        return back()->with('success', "Appointment rejected and notification sent to {$appointment->customer->name}.");
    }

    /**
     * Mark an approved appointment as completed and free the bay.
     */
    public function complete(GarageAppointment $appointment)
    {
        abort_unless($appointment->garage_user_id == auth()->id(), 403);
        abort_unless($appointment->isApproved(), 422, 'Only approved appointments can be marked complete.');

        $appointment->update(['status' => 'completed']);

        // Free the bay that was linked to this appointment
        GarageBay::where('user_id', auth()->id())
            ->where('appointment_id', $appointment->id)
            ->update([
                'status'               => 'available',
                'walkin_customer_name' => null,
                'service_note'         => null,
                'estimated_finish_at'  => null,
                'appointment_id'       => null,
            ]);

        return back()->with('success', 'Appointment completed and bay freed.');
    }

    /**
     * Configure total bays — syncs bay rows just like EV station slots.
     */
    public function configureBays(Request $request)
    {
        $request->validate([
            'total_slots'     => 'required|integer|min:1|max:30',
            'accepts_walkins' => 'nullable|boolean',
        ]);

        $user       = auth()->user();
        $totalBays  = (int) $request->total_slots;

        NewLocation::where('user_id', $user->id)->update([
            'total_slots'     => $totalBays,
            'accepts_walkins' => $request->boolean('accepts_walkins', true),
        ]);

        // Sync bay rows
        $existing = GarageBay::where('user_id', $user->id)
            ->pluck('bay_number')
            ->toArray();

        for ($i = 1; $i <= $totalBays; $i++) {
            if (!in_array($i, $existing)) {
                GarageBay::create([
                    'user_id'    => $user->id,
                    'bay_number' => $i,
                    'status'     => 'available',
                ]);
            }
        }

        // Remove bays beyond the new total (only if they are available)
        GarageBay::where('user_id', $user->id)
            ->where('bay_number', '>', $totalBays)
            ->where('status', 'available')
            ->delete();

        return back()->with('success', "Garage configured with {$totalBays} service bays.");
    }

    /**
     * Manually mark a bay as occupied for a walk-in customer.
     */
    public function walkinOccupy(Request $request, GarageBay $bay)
    {
        abort_unless($bay->user_id == auth()->id(), 403);
        abort_unless($bay->isAvailable(), 422, 'Bay is already occupied.');

        $request->validate([
            'walkin_customer_name' => 'nullable|string|max:100',
            'service_note'         => 'nullable|string|max:300',
            'estimated_finish_at'  => 'nullable|date|after:now',
        ]);

        $bay->update([
            'status'               => 'occupied',
            'walkin_customer_name' => $request->walkin_customer_name ?: 'Walk-in',
            'service_note'         => $request->service_note,
            'estimated_finish_at'  => $request->estimated_finish_at,
            'appointment_id'       => null,
        ]);

        return back()->with('success', "Bay #{$bay->bay_number} marked as occupied.");
    }

    /**
     * Manually free a bay (walk-in done or manual override).
     */
    public function walkinFree(GarageBay $bay)
    {
        abort_unless($bay->user_id == auth()->id(), 403);

        // If this bay was linked to an appointment, mark appointment complete too
        if ($bay->appointment_id) {
            GarageAppointment::where('id', $bay->appointment_id)
                ->where('status', 'approved')
                ->update(['status' => 'completed']);
        }

        $bay->update([
            'status'               => 'available',
            'walkin_customer_name' => null,
            'service_note'         => null,
            'estimated_finish_at'  => null,
            'appointment_id'       => null,
        ]);

        return back()->with('success', "Bay #{$bay->bay_number} is now free.");
    }
}