<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Advertisement;

class HomeController extends Controller
{
    public function index()
    {
        // Latest 6 available listings from sellers & businesses
        $recentCars = Car::whereIn('status', ['available', 'upcoming'])
            ->with(['primaryImage', 'seller'])
            ->latest()
            ->take(10)
            ->get();

        // Live counts for the fleet cards
        $evCount      = Car::where('status', 'available')->where('drivetrain', 'ev')->count();
        $hybridCount  = Car::where('status', 'available')->where('drivetrain', 'hybrid')->count();
        $classicCount = Car::where('status', 'available')->whereIn('drivetrain', ['petrol', 'diesel'])->count();

        $homeAds = Advertisement::with('car')
            ->where('placement', 'home')
            ->where('is_active', true)
            ->where(fn($q) => $q->whereNull('starts_at')->orWhereDate('starts_at', '<=', today()))
            ->where(fn($q) => $q->whereNull('ends_at')->orWhereDate('ends_at', '>=', today()))
            ->get();
       

        return view('frontend.pages.home', compact(
            'recentCars',
            'evCount',
            'hybridCount',
            'classicCount',
            'homeAds'
        ));
    }
}