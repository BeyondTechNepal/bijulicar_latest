<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CompareController extends Controller
{
    public function index(Request $request)
    {
        // IDs passed as ?cars[]=1&cars[]=2&cars[]=3
        $ids = collect($request->input('cars', []))
            ->filter()
            ->unique()
            ->take(3)
            ->values();

        $selected = $ids->isNotEmpty()
            ? Car::with(['seller', 'primaryImage'])
                ->whereIn('id', $ids)
                ->get()
                ->sortBy(fn($c) => $ids->search($c->id))
                ->values()
            : collect();

        // All available cars for picker — include primary_image path for thumbnail
        $allCars = Car::where('status', 'available')
            ->with('primaryImage')
            ->select('id', 'brand', 'model', 'year', 'variant', 'drivetrain', 'price', 'condition', 'primary_image', 'location')
            ->orderBy('brand')->orderBy('model')
            ->get();

        return view('frontend.pages.compare_cars', compact('selected', 'allCars'));
    }
}