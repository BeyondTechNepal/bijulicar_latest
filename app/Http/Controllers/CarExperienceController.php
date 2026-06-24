<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarExperience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarExperienceController extends Controller
{
    /**
     * Return paginated approved experiences as JSON — consumed by the FAB panel
     * via fetch(). Guests and logged-in users both hit this endpoint.
     *
     * Query params:
     *   ?car_id={id}          filter by a BijuliCar car_id
     *   ?search={term}        filter by external_car_name (for non-linked cars)
     *   ?type={rental|purchase|general}
     *   ?page={n}
     */
    public function index(Request $request)
    {
        $query = CarExperience::approved()
            ->with(['user:id,name,profile_photo', 'car:id,brand,model,year,variant'])
            ->withCount('comments')
            ->latest('approved_at');

        // ── Filters ───────────────────────────────────────────────────

        if ($request->filled('car_id')) {
            $query->where('car_id', $request->integer('car_id'));
        }

        if ($request->filled('search')) {
            $term = '%' . $request->search . '%';
            $query->where(function ($q) use ($term) {
                $q->whereHas('car', fn ($cq) =>
                        $cq->where('brand', 'like', $term)
                           ->orWhere('model', 'like', $term)
                    )
                    ->orWhere('external_car_name', 'like', $term);
            });
        }

        if ($request->filled('type')) {
            $query->where('experience_type', $request->type);
        }

        $experiences = $query->paginate(6);

        return response()->json($experiences);
    }

    /**
     * Store a new experience.
     * Only logged-in users can post; no role restriction beyond auth.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'           => ['required', 'string', 'max:150'],
            'trip_context'    => ['nullable', 'string', 'max:150'],
            'body'            => ['required', 'string', 'min:30', 'max:3000'],
            'experience_type' => ['required', 'in:rental,purchase,general'],
            'linked_to_bijulicar' => ['required', 'boolean'],
            // car_id is required only when the user chose to link a BijuliCar listing
            'car_id'          => ['nullable', 'required_if:linked_to_bijulicar,true', 'exists:cars,id'],
            // external_car_name required when NOT linking a BijuliCar listing
            'external_car_name' => ['nullable', 'required_if:linked_to_bijulicar,false', 'string', 'max:100'],
        ], [
            'car_id.required_if'          => 'Please select a car from BijuliCar.',
            'external_car_name.required_if' => 'Please enter the car name.',
            'body.min'                    => 'Your experience must be at least 30 characters.',
        ]);

        $carId           = null;
        $externalCarName = null;

        if ($request->boolean('linked_to_bijulicar')) {
            $car             = Car::findOrFail($request->car_id);
            $carId           = $car->id;
            // Snapshot the name so it survives listing deletion
            $externalCarName = $car->displayName();
        } else {
            $externalCarName = $request->external_car_name;
        }

        CarExperience::create([
            'user_id'          => Auth::id(),
            'car_id'           => $carId,
            'external_car_name' => $externalCarName,
            'title'            => $request->title,
            'trip_context'     => $request->trip_context,
            'body'             => $request->body,
            'experience_type'  => $request->experience_type,
            'status'           => 'pending',
        ]);

        return response()->json([
            'message' => 'Your experience has been submitted and is pending admin approval. Thank you!',
        ], 201);
    }

    /**
     * Return approved experiences for a specific car — consumed by the
     * car detail page via fetch(). Public, no auth needed.
     */
    public function forCar(Car $car)
    {
        $experiences = CarExperience::approved()
            ->forCar($car->id)
            ->with(['user:id,name,profile_photo'])
            ->latest('approved_at')
            ->paginate(5);

        return response()->json($experiences);
    }

    /**
     * Return ALL available BijuliCar listings as a flat [{id, name}] array.
     * Called once when the FAB panel opens; filtering is done client-side
     * using the same makeTypeahead pattern as the marketplace page.
     */
    public function allCars()
    {
        $cars = Car::whereIn('status', ['available', 'upcoming'])
            ->select('id', 'brand', 'model', 'year', 'variant')
            ->orderBy('brand')
            ->orderBy('model')
            ->get()
            ->map(fn ($car) => [
                'id'   => $car->id,
                'name' => $car->displayName(),
            ]);

        return response()->json($cars);
    }

    /**
     * Return the cars list for the FAB "link to BijuliCar" dropdown.
     * Searches by brand/model/year. Returns a lightweight list.
     */
    public function carSearch(Request $request)
    {
        $term = '%' . $request->get('q', '') . '%';

        $cars = Car::whereIn('status', ['available', 'upcoming'])
            ->where(function ($q) use ($term) {
                $q->where('brand', 'like', $term)
                  ->orWhere('model', 'like', $term)
                  ->orWhere('year', 'like', $term);
            })
            ->select('id', 'brand', 'model', 'year', 'variant')
            ->limit(10)
            ->get()
            ->map(fn ($car) => [
                'id'   => $car->id,
                'name' => $car->displayName(),
            ]);

        return response()->json($cars);
    }
}