<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarExperience;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class AdminCarExperienceController extends Controller
{
    /**
     * Admin posts an experience directly (e.g. sourced from social media).
     * Goes straight to approved — no moderation queue needed for admin posts.
     */
    public function store(Request $request)
    {
        $request->validate([
            'author_name'         => ['required', 'string', 'max:100'],
            'title'               => ['required', 'string', 'max:150'],
            'trip_context'        => ['nullable', 'string', 'max:150'],
            'body'                => ['required', 'string', 'min:30', 'max:3000'],
            'experience_type'     => ['required', 'in:rental,purchase,general'],
            'linked_to_bijulicar' => ['required', 'boolean'],
            'car_id'              => ['nullable', 'required_if:linked_to_bijulicar,true', 'exists:cars,id'],
            'external_car_name'   => ['nullable', 'required_if:linked_to_bijulicar,false', 'string', 'max:100'],
        ], [
            'author_name.required'          => 'Please enter the author\'s name.',
            'car_id.required_if'            => 'Please select a car from BijuliCar.',
            'external_car_name.required_if' => 'Please enter the car name.',
            'body.min'                      => 'Experience must be at least 30 characters.',
        ]);

        $carId           = null;
        $externalCarName = null;

        if ($request->boolean('linked_to_bijulicar')) {
            $car             = \App\Models\Car::findOrFail($request->car_id);
            $carId           = $car->id;
            $externalCarName = $car->displayName();
        } else {
            $externalCarName = $request->external_car_name;
        }

        CarExperience::create([
            'user_id'          => auth()->id(),
            'author_name'      => $request->author_name,
            'car_id'           => $carId,
            'external_car_name' => $externalCarName,
            'title'            => $request->title,
            'trip_context'     => $request->trip_context,
            'body'             => $request->body,
            'experience_type'  => $request->experience_type,
            'status'           => 'approved',
            'approved_at'      => now(),
        ]);

        return redirect()
            ->route('admin.car_experiences.index')
            ->with('success', 'Experience posted and published successfully.');
    }

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