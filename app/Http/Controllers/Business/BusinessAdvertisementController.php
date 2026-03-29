<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BusinessAdvertisementController extends Controller
{
    /** List all ads belonging to this business user. */
    public function index()
    {
        $ads = Advertisement::where('user_id', Auth::id())->latest()->paginate(10);

        return view('dashboard.business.advertisements.index', compact('ads'));
    }

    /** Show the create form. */
    public function create()
    {
        // Only cars listed by this business user can be linked to an ad
        $cars = Auth::user()->listedCars()->where('status', 'available')->get();

        return view('dashboard.business.advertisements.create', compact('cars'));
    }

    /** Store a new ad. */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:500'],
            'car_id' => ['nullable', 'exists:cars,id'],
            'link_url' => ['nullable', 'url', 'max:255'],
            'placement' => ['required', 'in:home,marketplace,sidebar'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['boolean'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('ad-banners', 'public');
        }

        Advertisement::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'car_id' => $request->car_id,
            'link_url' => $request->link_url,
            'placement' => $request->placement,
            'starts_at' => $request->starts_at,
            'ends_at' => $request->ends_at,
            'is_active' => $request->boolean('is_active', true),
            'image' => $imagePath,
        ]);

        return redirect()->route('business.advertisements.index')->with('success', 'Advertisement created successfully.');
    }

    /** Show the edit form. */
    public function edit(Advertisement $advertisement)
    {
        abort_if($advertisement->user_id != Auth::id(), 403);

        $cars = Auth::user()->listedCars()->where('status', 'available')->get();

        return view('dashboard.business.advertisements.edit', compact('advertisement', 'cars'));
    }

    /** Update an existing ad. */
    public function update(Request $request, Advertisement $advertisement)
    {
        abort_if($advertisement->user_id != Auth::id(), 403);

        $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:500'],
            'car_id' => ['nullable', 'exists:cars,id'],
            'link_url' => ['nullable', 'url', 'max:255'],
            'placement' => ['required', 'in:home,marketplace,sidebar'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['boolean'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        // Replace image only if a new one is uploaded
        if ($request->hasFile('image')) {
            if ($advertisement->image) {
                Storage::disk('public')->delete($advertisement->image);
            }
            $advertisement->image = $request->file('image')->store('ad-banners', 'public');
        }

        $advertisement->update([
            'title' => $request->title,
            'description' => $request->description,
            'car_id' => $request->car_id,
            'link_url' => $request->link_url,
            'placement' => $request->placement,
            'starts_at' => $request->starts_at,
            'ends_at' => $request->ends_at,
            'is_active' => $request->boolean('is_active'),
            'image' => $advertisement->image,
        ]);

        return redirect()->route('business.advertisements.index')->with('success', 'Advertisement updated successfully.');
    }

    /** Delete an ad and its image. */
    public function destroy(Advertisement $advertisement)
    {
        abort_if($advertisement->user_id != Auth::id(), 403);

        if ($advertisement->image) {
            Storage::disk('public')->delete($advertisement->image);
        }

        $advertisement->delete();

        return redirect()->route('business.advertisements.index')->with('success', 'Advertisement deleted.');
    }
}
