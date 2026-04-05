<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Buyer\BuyerOrderController;
use App\Http\Controllers\Buyer\BuyerPreOrderController;
use App\Http\Controllers\Buyer\BuyerPurchaseController;
use App\Http\Controllers\Buyer\BuyerReviewController;
use App\Http\Controllers\Seller\SellerVerificationController;
use App\Http\Controllers\Seller\SellerCarController;
use App\Http\Controllers\Seller\SellerOrderController;
use App\Http\Controllers\Seller\SellerPreOrderController;
use App\Http\Controllers\BusinessDirectoryController;
use App\Http\Controllers\Business\BusinessVerificationController;
use App\Http\Controllers\Business\BusinessNewsController;
use App\Http\Controllers\Evstation\EVStationVerificationController;
use App\Http\Controllers\Garage\GarageVerificationController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MapController;
use Illuminate\Support\Facades\Route;

// ── Public frontend routes ─────────────────────────────────────────────
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/marketplace', [App\Http\Controllers\MarketplaceController::class, 'index'])->name('marketplace');
Route::get('/marketplace/search', [App\Http\Controllers\MarketplaceController::class, 'search'])->name('marketplace.search');
// Route::get('/news', fn() => view('frontend.pages.news'))->name('news');
Route::get('/news', [App\Http\Controllers\NewsController::class, 'index'])->name('news');
Route::get('/news/{news:slug}', [App\Http\Controllers\NewsController::class, 'show'])->name('news.show');
Route::get('/news-filter', [App\Http\Controllers\NewsFilterController::class, 'filter'])->name('news.filter');
Route::get('/business-news/{news:slug}', [App\Http\Controllers\BusinessNewsPublicController::class, 'show']) ->name('business.news.show');
// Route::get('/map_location', fn() => view('frontend.pages.map_location'))->name('map_location');
// Route::get('/news', [App\Http\Controllers\NewsController::class, 'index'])->name('news');
Route::get('/map_location', [App\Http\Controllers\MapController::class, 'index'])->name('map_location');
Route::get('/loan_calculator', fn() => view('frontend.pages.loan_calculator'))->name('loan_calculator');
Route::get('/contact', [App\Http\Controllers\Frontend\ContactController::class, 'index'])->name('contact');
Route::post('/contact', [App\Http\Controllers\ContactMessageController::class, 'store'])->name('contact.store');
Route::get('/compare_cars', [App\Http\Controllers\CompareController::class, 'index'])->name('compare_cars');
Route::get('/cars/{car}', [App\Http\Controllers\CarController::class, 'show'])->name('cars.show');

// Public business directory
Route::get('/businesses', [BusinessDirectoryController::class, 'index'])->name('businesses.index');
Route::get('/businesses/{id}', [BusinessDirectoryController::class, 'show'])->name('businesses.show')->whereNumber('id');

// ── Verification routes (auth required, no verified.account check here) ──
// These must be OUTSIDE the verified.account middleware so unverified
// sellers/businesses can actually reach the form and the pending page.
Route::middleware(['auth'])->group(function () {

    // Seller verification form
    Route::get('/seller/verify', [SellerVerificationController::class, 'create'])
        ->name('seller.verify.create');
    Route::post('/seller/verify', [SellerVerificationController::class, 'store'])
        ->name('seller.verify.store');

    // Business verification form
    Route::get('/business/verify', [BusinessVerificationController::class, 'create'])
        ->name('business.verify.create');
    Route::post('/business/verify', [BusinessVerificationController::class, 'store'])
        ->name('business.verify.store');

        // 3. EV-Station Verification (New)
    Route::get('/ev-station/verify', [EvstationVerificationController::class, 'create'])->name('station.verify.create');
    Route::post('/ev-station/verify', [EvstationVerificationController::class, 'store'])->name('station.verify.store');

    // 4. Garage Verification
    Route::get('/garage/verify', [GarageVerificationController::class, 'create'])->name('garage.verify.create');
    Route::post('/garage/verify', [GarageVerificationController::class, 'store'])->name('garage.verify.store');

    // Waiting / pending approval screen
    // 4. Shared Pending Approval Screen
    Route::get('/pending-approval', function () {
        $user = auth()->user();
        
        /** * We find the correct verification record based on the User's Role.
         * This replaces the generic $user->verification() call.
         */
        $verification = match(true) {
            $user->hasRole('seller')     => $user->sellerVerification,
            $user->hasRole('business')   => $user->businessVerification,
            $user->hasRole('ev-station') => $user->stationVerification,
            $user->hasRole('garage')     => $user->garageVerification,
            default                      => null
        };

        // If the admin has already clicked "Approve" in the backend
        if ($verification && $verification->isApproved()) {
            return redirect()->route('dashboard');
        }

        // If they landed here but haven't even filled the form yet, send them to the right form
        if (!$verification) {
            return match(true) {
                $user->hasRole('seller')     => redirect()->route('seller.verify.create'),
                $user->hasRole('business')   => redirect()->route('business.verify.create'),
                $user->hasRole('ev-station') => redirect()->route('station.verify.create'),
                $user->hasRole('garage')     => redirect()->route('garage.verify.create'),
                default                      => redirect()->route('dashboard')
            };
        }

        return view('verification.pending', compact('verification'));
    })->name('verification.pending');
});

// ── Dashboard — smart redirect based on role ───────────────────────────
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->hasRole('buyer')) {
        return redirect()->route('buyer.dashboard');
    }
    if ($user->hasRole('seller')) {
        return redirect()->route('seller.dashboard');
    }
    if ($user->hasRole('business')) {
        return redirect()->route('business.dashboard');
    }
    if ($user->hasRole('ev-station')) {
        return redirect()->route('station.dashboard');
    }
    if ($user->hasRole('garage')) {
        return redirect()->route('garage.dashboard');
    }
    return view('dashboard');
})
    ->middleware(['auth'])
    ->name('dashboard');

// ── BUYER routes ───────────────────────────────────────────────────────
// Buyers do not need verification — no verified.account middleware here.
Route::middleware(['auth', 'role:buyer'])
    ->prefix('buyer')
    ->name('buyer.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', fn() => view('dashboard.buyer', ['user' => auth()->user()]))->name('dashboard');

        // Orders
        Route::get('/orders', [BuyerOrderController::class, 'index'])
            ->name('orders.index')
            ->middleware('permission:manage own orders');
        Route::post('/orders', [BuyerOrderController::class, 'store'])
            ->name('orders.store')
            ->middleware('permission:manage own orders');
        Route::get('/orders/{order}', [BuyerOrderController::class, 'show'])
            ->name('orders.show')
            ->middleware('permission:manage own orders');
        Route::patch('/orders/{order}/cancel', [BuyerOrderController::class, 'cancel'])
            ->name('orders.cancel')
            ->middleware('permission:manage own orders');

        // Purchases
        Route::get('/purchases', [BuyerPurchaseController::class, 'index'])
            ->name('purchases.index')
            ->middleware('permission:purchase vehicle');

        // Pre-orders
        Route::get('/preorders', [BuyerPreOrderController::class, 'index'])
            ->name('preorders.index')
            ->middleware('permission:manage own orders');
        Route::get('/preorders/car/{car}', [BuyerPreOrderController::class, 'create'])
            ->name('preorders.create')
            ->middleware('permission:manage own orders');
        Route::post('/preorders', [BuyerPreOrderController::class, 'store'])
            ->name('preorders.store')
            ->middleware('permission:manage own orders');
        Route::get('/preorders/{preOrder}', [BuyerPreOrderController::class, 'show'])
            ->name('preorders.show')
            ->middleware('permission:manage own orders');
        Route::patch('/preorders/{preOrder}/cancel', [BuyerPreOrderController::class, 'cancel'])
            ->name('preorders.cancel')
            ->middleware('permission:manage own orders');

        // Reviews
        Route::get('/reviews', [BuyerReviewController::class, 'index'])
            ->name('reviews.index')
            ->middleware('permission:write reviews');
        Route::get('/reviews/create/{car}', [BuyerReviewController::class, 'create'])
            ->name('reviews.create')
            ->middleware('permission:write reviews');
        Route::post('/reviews', [BuyerReviewController::class, 'store'])
            ->name('reviews.store')
            ->middleware('permission:write reviews');
        Route::get('/reviews/{review}/edit', [BuyerReviewController::class, 'edit'])
            ->name('reviews.edit')
            ->middleware('permission:write reviews');
        Route::patch('/reviews/{review}', [BuyerReviewController::class, 'update'])
            ->name('reviews.update')
            ->middleware('permission:write reviews');
        Route::delete('/reviews/{review}', [BuyerReviewController::class, 'destroy'])
            ->name('reviews.destroy')
            ->middleware('permission:write reviews');
    });

// ── SELLER routes ──────────────────────────────────────────────────────
// verified.account middleware gates entry — unverified sellers are
// redirected to the form or the pending page automatically.
Route::middleware(['auth', 'role:seller', 'verified.account'])
    ->prefix('seller')
    ->name('seller.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', fn() => view('dashboard.seller', ['user' => auth()->user()]))->name('dashboard');

        // Car listings (CRUD)
        Route::get('/cars', [App\Http\Controllers\Seller\SellerCarController::class, 'index'])
            ->name('cars.index')
            ->middleware('permission:manage car listing(seller)');
        Route::get('/cars/create', [App\Http\Controllers\Seller\SellerCarController::class, 'create'])
            ->name('cars.create')
            ->middleware('permission:manage car listing(seller)');
        Route::post('/cars', [App\Http\Controllers\Seller\SellerCarController::class, 'store'])
            ->name('cars.store')
            ->middleware('permission:manage car listing(seller)');
        Route::get('/cars/{car}/edit', [App\Http\Controllers\Seller\SellerCarController::class, 'edit'])
            ->name('cars.edit')
            ->middleware('permission:manage car listing(seller)');
        Route::patch('/cars/{car}', [App\Http\Controllers\Seller\SellerCarController::class, 'update'])
            ->name('cars.update')
            ->middleware('permission:manage car listing(seller)');
        Route::delete('/cars/{car}', [App\Http\Controllers\Seller\SellerCarController::class, 'destroy'])
            ->name('cars.destroy')
            ->middleware('permission:manage car listing(seller)');

        // Orders on seller's listings
        Route::get('/orders', [App\Http\Controllers\Seller\SellerOrderController::class, 'index'])
            ->name('orders.index')
            ->middleware('permission:manage own orders');
        Route::get('/orders/{order}', [App\Http\Controllers\Seller\SellerOrderController::class, 'show'])
            ->name('orders.show')
            ->middleware('permission:manage own orders');
        Route::patch('/orders/{order}/confirm', [App\Http\Controllers\Seller\SellerOrderController::class, 'confirm'])
            ->name('orders.confirm')
            ->middleware('permission:manage own orders');
        Route::patch('/orders/{order}/cancel', [App\Http\Controllers\Seller\SellerOrderController::class, 'cancel'])
            ->name('orders.cancel')
            ->middleware('permission:manage own orders');
        Route::get('/orders/{order}/complete', [App\Http\Controllers\Seller\SellerOrderController::class, 'completeForm'])
            ->name('orders.complete.form')
            ->middleware('permission:manage own orders');
        Route::post('/orders/{order}/complete', [App\Http\Controllers\Seller\SellerOrderController::class, 'complete'])
            ->name('orders.complete')
            ->middleware('permission:manage own orders');

        // Pre-orders
        Route::get('/preorders', [SellerPreOrderController::class, 'index'])
            ->name('preorders.index')
            ->middleware('permission:manage own orders');
        Route::get('/preorders/{preOrder}', [SellerPreOrderController::class, 'show'])
            ->name('preorders.show')
            ->middleware('permission:manage own orders');
        Route::get('/preorders/{preOrder}/confirm-deposit', [SellerPreOrderController::class, 'confirmDepositForm'])
            ->name('preorders.confirm_deposit.form')
            ->middleware('permission:manage own orders');
        Route::patch('/preorders/{preOrder}/confirm-deposit', [SellerPreOrderController::class, 'confirmDeposit'])
            ->name('preorders.confirm_deposit')
            ->middleware('permission:manage own orders');
        Route::post('/preorders/{preOrder}/convert', [SellerPreOrderController::class, 'convert'])
            ->name('preorders.convert')
            ->middleware('permission:manage own orders');
        Route::patch('/preorders/{preOrder}/cancel', [SellerPreOrderController::class, 'cancel'])
            ->name('preorders.cancel')
            ->middleware('permission:manage own orders');
    });

// ── BUSINESS routes ────────────────────────────────────────────────────
// verified.account middleware gates entry here too.
Route::middleware(['auth', 'role:business', 'verified.account'])
    ->prefix('business')
    ->name('business.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', fn() => view('dashboard.business', ['user' => auth()->user()]))->name('dashboard');

        // Car listings (CRUD) — reuses SellerCarController
        Route::get('/cars', [App\Http\Controllers\Seller\SellerCarController::class, 'index'])
            ->name('cars.index')
            ->middleware('permission:browse listings');
        Route::get('/cars/create', [App\Http\Controllers\Seller\SellerCarController::class, 'create'])
            ->name('cars.create')
            ->middleware('permission:browse listings');
        Route::post('/cars', [App\Http\Controllers\Seller\SellerCarController::class, 'store'])
            ->name('cars.store')
            ->middleware('permission:browse listings');
        Route::get('/cars/{car}/edit', [App\Http\Controllers\Seller\SellerCarController::class, 'edit'])
            ->name('cars.edit')
            ->middleware('permission:browse listings');
        Route::patch('/cars/{car}', [App\Http\Controllers\Seller\SellerCarController::class, 'update'])
            ->name('cars.update')
            ->middleware('permission:browse listings');
        Route::delete('/cars/{car}', [App\Http\Controllers\Seller\SellerCarController::class, 'destroy'])
            ->name('cars.destroy')
            ->middleware('permission:browse listings');

        // Orders on business's listings — reuses SellerOrderController
        Route::get('/orders', [App\Http\Controllers\Seller\SellerOrderController::class, 'index'])
            ->name('orders.index')
            ->middleware('permission:manage own orders');
        Route::get('/orders/{order}', [App\Http\Controllers\Seller\SellerOrderController::class, 'show'])
            ->name('orders.show')
            ->middleware('permission:manage own orders');
        Route::patch('/orders/{order}/confirm', [App\Http\Controllers\Seller\SellerOrderController::class, 'confirm'])
            ->name('orders.confirm')
            ->middleware('permission:manage own orders');
        Route::patch('/orders/{order}/cancel', [App\Http\Controllers\Seller\SellerOrderController::class, 'cancel'])
            ->name('orders.cancel')
            ->middleware('permission:manage own orders');
        Route::get('/orders/{order}/complete', [App\Http\Controllers\Seller\SellerOrderController::class, 'completeForm'])
            ->name('orders.complete.form')
            ->middleware('permission:manage own orders');
        Route::post('/orders/{order}/complete', [App\Http\Controllers\Seller\SellerOrderController::class, 'complete'])
            ->name('orders.complete')
            ->middleware('permission:manage own orders');

        // Pre-orders
        Route::get('/preorders', [SellerPreOrderController::class, 'index'])
            ->name('preorders.index')
            ->middleware('permission:manage own orders');
        Route::get('/preorders/{preOrder}', [SellerPreOrderController::class, 'show'])
            ->name('preorders.show')
            ->middleware('permission:manage own orders');
        Route::get('/preorders/{preOrder}/confirm-deposit', [SellerPreOrderController::class, 'confirmDepositForm'])
            ->name('preorders.confirm_deposit.form')
            ->middleware('permission:manage own orders');
        Route::patch('/preorders/{preOrder}/confirm-deposit', [SellerPreOrderController::class, 'confirmDeposit'])
            ->name('preorders.confirm_deposit')
            ->middleware('permission:manage own orders');
        Route::post('/preorders/{preOrder}/convert', [SellerPreOrderController::class, 'convert'])
            ->name('preorders.convert')
            ->middleware('permission:manage own orders');
        Route::patch('/preorders/{preOrder}/cancel', [SellerPreOrderController::class, 'cancel'])
            ->name('preorders.cancel')
            ->middleware('permission:manage own orders');

        // Analytics
        Route::get('/analytics', [App\Http\Controllers\Business\BusinessAnalyticsController::class, 'index'])
            ->name('analytics')
            ->middleware('permission:view business analytics');

         // ── Business News (CRUD) 
        Route::get('/news', [BusinessNewsController::class, 'index'])
            ->name('news.index');
 
        Route::get('/news/create', [BusinessNewsController::class, 'create'])
            ->name('news.create');
 
        Route::post('/news', [BusinessNewsController::class, 'store'])
            ->name('news.store');
 
        Route::get('/news/{news:slug}/edit', [BusinessNewsController::class, 'edit'])
            ->name('news.edit');
 
        Route::patch('/news/{news:slug}', [BusinessNewsController::class, 'update'])
            ->name('news.update');
 
        Route::delete('/news/{news:slug}', [BusinessNewsController::class, 'destroy'])
            ->name('news.destroy');
 

        // Advertisements (CRUD)
        Route::get('/advertisements', [App\Http\Controllers\Business\BusinessAdvertisementController::class, 'index'])
            ->name('advertisements.index')
            ->middleware('permission:create advertisements');
        Route::get('/advertisements/create', [App\Http\Controllers\Business\BusinessAdvertisementController::class, 'create'])
            ->name('advertisements.create')
            ->middleware('permission:create advertisements');
        Route::post('/advertisements', [App\Http\Controllers\Business\BusinessAdvertisementController::class, 'store'])
            ->name('advertisements.store')
            ->middleware('permission:create advertisements');
        Route::get('/advertisements/{advertisement}/edit', [App\Http\Controllers\Business\BusinessAdvertisementController::class, 'edit'])
            ->name('advertisements.edit')
            ->middleware('permission:create advertisements');
        Route::patch('/advertisements/{advertisement}', [App\Http\Controllers\Business\BusinessAdvertisementController::class, 'update'])
            ->name('advertisements.update')
            ->middleware('permission:create advertisements');
        Route::delete('/advertisements/{advertisement}', [App\Http\Controllers\Business\BusinessAdvertisementController::class, 'destroy'])
            ->name('advertisements.destroy')
            ->middleware('permission:create advertisements');
    });

// ── EV STATION routes ──────────────────────────────────────────────────
// Only verified EV Station owners can access these management tools.
Route::middleware(['auth', 'role:ev-station', 'verified.account'])
    ->prefix('ev-station')
    ->name('station.')
    ->group(function () {
        
        // 1. Station Dashboard
        // Shows real-time status of chargers, total energy delivered, and revenue.
        Route::get('/dashboard', function() {
            return view('dashboard.ev-station', ['user' => auth()->user()]);
        })->name('dashboard');

        Route::get('/location', [LocationController::class, 'index'])->name('location.index');
        Route::get('/location/create', [LocationController::class, 'create'])->name('location.create');
        Route::post('/location', [LocationController::class, 'store'])->name('location.store');
        Route::get('/location/edit', [LocationController::class, 'edit'])->name('location.edit');
        Route::put('/location', [LocationController::class, 'update'])->name('location.update');
        Route::delete('/location', [LocationController::class, 'destroy'])->name('location.destroy');
        // 2. Station Profile & Management
        // Update station location, opening hours, and pricing per kWh.
        // Route::get('/manage', [App\Http\Controllers\EVStation\EVSt   ationController::class, 'edit'])
        //     ->name('manage')
        //     ->middleware('permission:manage own stations');
        // Route::patch('/update', [App\Http\Controllers\EVStation\EVStationController::class, 'update'])
        //     ->name('update')
        //     ->middleware('permission:manage own stations');

        // // 3. Charger CRUD (Individual charging points at the station)
        // Route::get('/chargers', [App\Http\Controllers\EVStation\ChargerController::class, 'index'])
        //     ->name('chargers.index')
        //     ->middleware('permission:manage own stations');
        // Route::post('/chargers', [App\Http\Controllers\EVStation\ChargerController::class, 'store'])
        //     ->name('chargers.store')
        //     ->middleware('permission:manage own stations');
        // Route::delete('/chargers/{charger}', [App\Http\Controllers\EVStation\ChargerController::class, 'destroy'])
        //     ->name('chargers.destroy')
        //     ->middleware('permission:manage own stations');

        // // 4. Charging Sessions / Orders
        // // Tracks active and historical charging sessions.
        // Route::get('/sessions', [App\Http\Controllers\EVStation\StationSessionController::class, 'index'])
        //     ->name('sessions.index')
        //     ->middleware('permission:manage own orders');
        // Route::get('/sessions/{session}', [App\Http\Controllers\EVStation\StationSessionController::class, 'show'])
        //     ->name('sessions.show')
        //     ->middleware('permission:manage own orders');
        // Route::patch('/sessions/{session}/terminate', [App\Http\Controllers\EVStation\StationSessionController::class, 'terminate'])
        //     ->name('sessions.terminate')
        //     ->middleware('permission:manage own orders');

        // // 5. Infrastructure Analytics
        // // Energy consumption charts and peak-hour data.
        // Route::get('/analytics', [App\Http\Controllers\EVStation\StationAnalyticsController::class, 'index'])
        //     ->name('analytics')
        //     ->middleware('permission:view business analytics');

        // // 6. Promotions & Advertisements
        // // For stations to promote "Happy Hour" pricing or lounge amenities.
        // Route::resource('promotions', App\Http\Controllers\EVStation\StationPromotionController::class)
        //     ->middleware('permission:create advertisements');
    });

// ── GARAGE routes ──────────────────────────────────────────────────
// Only verified Garage owners can access these management tools.
Route::middleware(['auth', 'role:garage', 'verified.account'])
    ->prefix('garage')
    ->name('garage.')
    ->group(function () {
        
        // 1. Garage Dashboard
        Route::get('/dashboard', function () {
            return view('dashboard.garage', ['user' => auth()->user()]);
        })->name('dashboard');

        // 2. Map Location CRUD
        Route::get('/location', [LocationController::class, 'index'])->name('location.index');
        Route::get('/location/create', [LocationController::class, 'create'])->name('location.create');
        Route::post('/location', [LocationController::class, 'store'])->name('location.store');
        Route::get('/location/edit', [LocationController::class, 'edit'])->name('location.edit');
        Route::put('/location', [LocationController::class, 'update'])->name('location.update');
        Route::delete('/location', [LocationController::class, 'destroy'])->name('location.destroy');
    });

// ── Profile ────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';