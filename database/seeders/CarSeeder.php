<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\User;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    /**
     * Seed realistic EV/hybrid cars for the Nepal market.
     * Uses the existing seeded seller and business users.
     */
    public function run(): void
    {
        $seller   = User::where('email', 'seller@test.com')->first();
        $business = User::where('email', 'business@test.com')->first();

        $cars = [

            // ── Listed by seller ──────────────────────────────────────
            [
                'seller_id'        => $seller->id,
                'brand'             => 'BYD',
                'model'            => 'Atto 3',
                'year'             => 2023,
                'variant'          => 'Extended Range',
                'drivetrain'       => 'ev',
                'mileage'          => 8200,
                'range_km'         => 480,
                'battery_kwh'      => 60,
                'color'            => 'Surf Blue',
                'condition'        => 'used',
                'price'            => 5500000,
                'price_negotiable' => true,
                'location'         => 'Kathmandu',
                'description'      => 'Single owner, full service history. Comes with home charger.',
                'status'           => 'available',
            ],
            [
                'seller_id'        => $seller->id,
                'brand'             => 'Tesla',
                'model'            => 'Model 3',
                'year'             => 2022,
                'variant'          => 'Standard Range Plus',
                'drivetrain'       => 'ev',
                'mileage'          => 15400,
                'range_km'         => 430,
                'battery_kwh'      => 54,
                'color'            => 'Pearl White',
                'condition'        => 'used',
                'price'            => 8200000,
                'price_negotiable' => false,
                'location'         => 'Lalitpur',
                'description'      => 'Autopilot included. All software updates current.',
                'status'           => 'available',
            ],
            [
                'seller_id'        => $seller->id,
                'brand'             => 'MG',
                'model'            => 'ZS EV',
                'year'             => 2023,
                'variant'          => 'Excite',
                'drivetrain'       => 'ev',
                'mileage'          => 3100,
                'range_km'         => 320,
                'battery_kwh'      => 44,
                'color'            => 'Dynamic Red',
                'condition'        => 'used',
                'price'            => 4800000,
                'price_negotiable' => true,
                'location'         => 'Bhaktapur',
                'description'      => 'Like new. Transferred from corporate fleet. All warranties valid.',
                'status'           => 'available',
            ],
            [
                'seller_id'        => $seller->id,
                'brand'             => 'Toyota',
                'model'            => 'Prius',
                'year'             => 2021,
                'variant'          => 'Z Grade',
                'drivetrain'       => 'hybrid',
                'mileage'          => 32000,
                'range_km'         => null,
                'battery_kwh'      => null,
                'color'            => 'Platinum Silver',
                'condition'        => 'used',
                'price'            => 4200000,
                'price_negotiable' => true,
                'location'         => 'Pokhara',
                'description'      => 'Excellent fuel economy. No accidents.',
                'status'           => 'available',
            ],

            // ── Listed by business ────────────────────────────────────
            [
                'seller_id'        => $business->id,
                'brand'             => 'BYD',
                'model'            => 'Seal',
                'year'             => 2024,
                'variant'          => 'Design',
                'drivetrain'       => 'ev',
                'mileage'          => 0,
                'range_km'         => 570,
                'battery_kwh'      => 82,
                'color'            => 'Aurora White',
                'condition'        => 'new',
                'price'            => 11500000,
                'price_negotiable' => false,
                'location'         => 'Kathmandu',
                'description'      => 'Brand new showroom unit. Full warranty. Test drives available.',
                'status'           => 'available',
            ],
            [
                'seller_id'        => $business->id,
                'brand'             => 'BYD',
                'model'            => 'Dolphin',
                'year'             => 2024,
                'variant'          => null,
                'drivetrain'       => 'ev',
                'mileage'          => 0,
                'range_km'         => 427,
                'battery_kwh'      => 60,
                'color'            => 'Sky Blue',
                'condition'        => 'new',
                'price'            => 4900000,
                'price_negotiable' => false,
                'location'         => 'Kathmandu',
                'description'      => 'Best-selling compact EV in Nepal. Immediate delivery available.',
                'status'           => 'available',
            ],
            [
                'seller_id'        => $business->id,
                'brand'             => 'Kia',
                'model'            => 'EV6',
                'year'             => 2023,
                'variant'          => 'GT-Line AWD',
                'drivetrain'       => 'ev',
                'mileage'          => 0,
                'range_km'         => 484,
                'battery_kwh'      => 77,
                'color'            => 'Moonscape',
                'condition'        => 'new',
                'price'            => 13200000,
                'price_negotiable' => false,
                'location'         => 'Lalitpur',
                'description'      => 'Top of range GT-Line. 800V ultra-fast charging. Limited units.',
                'status'           => 'available',
            ],
            [
                'seller_id'        => $business->id,
                'brand'             => 'Hyundai',
                'model'            => 'Kona Electric',
                'year'             => 2022,
                'variant'          => 'Premium',
                'drivetrain'       => 'ev',
                'mileage'          => 6800,
                'range_km'         => 305,
                'battery_kwh'      => 39,
                'color'            => 'Phantom Black',
                'condition'        => 'certified',
                'price'            => 4600000,
                'price_negotiable' => true,
                'location'         => 'Kathmandu',
                'description'      => 'Certified pre-owned with 1-year warranty extension. Ideal city EV.',
                'status'           => 'available',
            ],

        ];

        foreach ($cars as $car) {
            Car::create($car);
        }
    }
}