<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\AdPricingRule;
use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BusinessAdvertisementController extends Controller
{
    /** List all ads belonging to this business user. */
    public function index()
    {
        $ads = Advertisement::where('user_id', Auth::id())->orderByDesc('created_at')->paginate(10);

        return view('dashboard.business.advertisements.index', compact('ads'));
    }

    /** Show the create form — including pricing hints so business knows what to expect. */
    public function create()
    {
        $cars = Auth::user()->listedCars()->where('status', 'available')->get();
        $placements = Advertisement::PLACEMENTS;
        $priorities = Advertisement::PRIORITIES;

        // Pass pricing rules so the create form can show estimated costs via JS
        $pricingRules = AdPricingRule::active()->get()->groupBy('placement')->map(fn($g) => $g->keyBy('priority'))->map(
            fn($tierGroup) => $tierGroup->map(
                fn($rule) => [
                    'price_per_day' => (float) $rule->price_per_day,
                    'min_days' => (int) $rule->min_days,
                    'is_active' => (bool) $rule->is_active,
                ],
            ),
        );

        return view('dashboard.business.advertisements.create', compact('cars', 'placements', 'priorities', 'pricingRules'));
    }

    /** Store a new ad — always starts as pending_review, never active. */
    public function store(Request $request)
    {
        $validPlacements = implode(',', array_keys(Advertisement::PLACEMENTS));

        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:500'],
            'car_id' => ['nullable', 'exists:cars,id'],
            'link_url' => ['nullable', 'url', 'max:255'],
            'placement' => ['required', "in:{$validPlacements}"],
            'priority' => ['required', 'integer', 'in:0,1,2'],
            'starts_at' => ['required', 'date', 'after_or_equal:today'],
            'ends_at' => ['required', 'date', 'after_or_equal:starts_at'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        // Validate against min_days for the chosen placement+priority
        $rule = AdPricingRule::for($data['placement'], (int) $data['priority']);
        if ($rule) {
            $days =
                (int) now()
                    ->parse($data['starts_at'])
                    ->diffInDays(now()->parse($data['ends_at'])) + 1;
            if ($days < $rule->min_days) {
                return back()
                    ->withInput()
                    ->withErrors(['ends_at' => "Minimum booking for this slot is {$rule->min_days} days."]);
            }
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('ad-banners', 'public');
        }

        Advertisement::create([
            'user_id' => Auth::id(),
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'car_id' => $data['car_id'] ?? null,
            'link_url' => $data['link_url'] ?? null,
            'placement' => $data['placement'],
            'priority' => (int) $data['priority'],
            'starts_at' => $data['starts_at'],
            'ends_at' => $data['ends_at'],
            'image' => $imagePath,
            // Always start here — admin review gates everything
            'status' => 'pending_review',
            'is_active' => false,
        ]);

        return redirect()->route('business.advertisements.index')->with('success', 'Your advertisement has been submitted for review. We\'ll email you once it\'s approved.');
    }

    /** Show the edit form — only allowed while still pending review. */
    public function edit(Advertisement $advertisement)
    {
        abort_if($advertisement->user_id != Auth::id(), 403);
        abort_if(!in_array($advertisement->status, ['pending_review', 'rejected']), 403, 'You can only edit ads that are pending review or were rejected.');

        $cars = Auth::user()->listedCars()->where('status', 'available')->get();
        $placements = Advertisement::PLACEMENTS;
        $priorities = Advertisement::PRIORITIES;
        $pricingRules = AdPricingRule::active()->get()->groupBy('placement')->map(fn($g) => $g->keyBy('priority'));

        return view('dashboard.business.advertisements.edit', compact('advertisement', 'cars', 'placements', 'priorities', 'pricingRules'));
    }


    /** Update — resets back to pending_review so admin sees changes. */
    public function update(Request $request, Advertisement $advertisement)
    {
        abort_if($advertisement->user_id != Auth::id(), 403);
        abort_if(!in_array($advertisement->status, ['pending_review', 'rejected']), 403, 'You can only edit ads that are pending review or were rejected.');

        $validPlacements = implode(',', array_keys(Advertisement::PLACEMENTS));

        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:500'],
            'car_id' => ['nullable', 'exists:cars,id'],
            'link_url' => ['nullable', 'url', 'max:255'],
            'placement' => ['required', "in:{$validPlacements}"],
            'priority' => ['required', 'integer', 'in:0,1,2'],
            'starts_at' => ['required', 'date', 'after_or_equal:today'],
            'ends_at' => ['required', 'date', 'after_or_equal:starts_at'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $rule = AdPricingRule::for($data['placement'], (int) $data['priority']);
        if ($rule) {
            $days =
                (int) now()
                    ->parse($data['starts_at'])
                    ->diffInDays(now()->parse($data['ends_at'])) + 1;
            if ($days < $rule->min_days) {
                return back()
                    ->withInput()
                    ->withErrors(['ends_at' => "Minimum booking for this slot is {$rule->min_days} days."]);
            }
        }

        if ($request->hasFile('image')) {
            if ($advertisement->image) {
                Storage::disk('public')->delete($advertisement->image);
            }
            $advertisement->image = $request->file('image')->store('ad-banners', 'public');
        }

        $advertisement->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'car_id' => $data['car_id'] ?? null,
            'link_url' => $data['link_url'] ?? null,
            'placement' => $data['placement'],
            'priority' => (int) $data['priority'],
            'starts_at' => $data['starts_at'],
            'ends_at' => $data['ends_at'],
            'image' => $advertisement->image,
            // Editing a rejected ad re-queues it for review
            'status' => 'pending_review',
            'rejection_reason' => null,
            'is_active' => false,
        ]);

        return redirect()->route('business.advertisements.index')->with('success', 'Advertisement updated and re-submitted for review.');
    }

    /** Delete — only if not yet published. */
    public function destroy(Advertisement $advertisement)
    {
        abort_if($advertisement->user_id != Auth::id(), 403);
        abort_if($advertisement->status == 'published', 403, 'Published ads cannot be deleted.');

        if ($advertisement->image) {
            Storage::disk('public')->delete($advertisement->image);
        }

        $advertisement->delete();

        return redirect()->route('business.advertisements.index')->with('success', 'Advertisement deleted.');
    }
}
