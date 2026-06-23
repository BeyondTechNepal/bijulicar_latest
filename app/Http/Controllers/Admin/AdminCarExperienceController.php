<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarExperience;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class AdminCarExperienceController extends Controller
{
    /**
     * List all experiences grouped by status —
     * pending first (needs action), then approved and rejected history.
     */
    public function index()
    {
        $pending = CarExperience::with(['user:id,name,email', 'car:id,brand,model,year,variant'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        $approved = CarExperience::with(['user:id,name,email', 'car:id,brand,model,year,variant'])
            ->where('status', 'approved')
            ->latest('approved_at')
            ->paginate(20, ['*'], 'approved_page');

        $rejected = CarExperience::with(['user:id,name,email', 'car:id,brand,model,year,variant'])
            ->where('status', 'rejected')
            ->latest()
            ->paginate(20, ['*'], 'rejected_page');

        return view('admin.car_experiences.index', compact('pending', 'approved', 'rejected'));
    }

    /**
     * Approve a pending experience.
     */
    public function approve(CarExperience $carExperience)
    {
        abort_if(!$carExperience->isPending(), 422, 'Only pending experiences can be approved.');

        $carExperience->update([
            'status'      => 'approved',
            'approved_at' => now(),
            'admin_note'  => null,
        ]);

        // Notify the author
        app(NotificationService::class)->experienceApproved($carExperience);

        return redirect()
            ->route('admin.car_experiences.index')
            ->with('success', "Experience \"{$carExperience->title}\" approved and is now publicly visible.");
    }

    /**
     * Reject a pending experience, storing the admin's reason.
     */
    public function reject(Request $request, CarExperience $carExperience)
    {
        abort_if(!$carExperience->isPending(), 422, 'Only pending experiences can be rejected.');

        $request->validate([
            'admin_note' => ['required', 'string', 'max:500'],
        ], [
            'admin_note.required' => 'Please provide a reason for rejection.',
        ]);

        $carExperience->update([
            'status'      => 'rejected',
            'admin_note'  => $request->admin_note,
            'approved_at' => null,
        ]);

        // Notify the author
        app(NotificationService::class)->experienceRejected($carExperience);

        return redirect()
            ->route('admin.car_experiences.index')
            ->with('success', "Experience \"{$carExperience->title}\" rejected.");
    }

    /**
     * Hard-delete an experience (admin cleanup for spam/abuse).
     * Available on both approved and rejected records.
     */
    public function destroy(CarExperience $carExperience)
    {
        $title = $carExperience->title;
        $carExperience->delete();

        return redirect()
            ->route('admin.car_experiences.index')
            ->with('success', "Experience \"{$title}\" deleted.");
    }
}