<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Buyer\BuyerOrderController;
use App\Http\Controllers\Buyer\BuyerPreOrderController;
use App\Http\Controllers\Buyer\BuyerPurchaseController;
use App\Http\Controllers\Buyer\BuyerReviewController;
use App\Http\Controllers\Buyer\BuyerVerificationController;
use App\Http\Controllers\Seller\SellerVerificationController;
use App\Http\Controllers\Seller\SellerCarController;
use App\Http\Controllers\Seller\SellerOrderController;
use App\Http\Controllers\Seller\SellerPreOrderController;
use App\Http\Controllers\BusinessDirectoryController;
use App\Http\Controllers\Business\BusinessVerificationController;
use App\Http\Controllers\Business\BusinessNewsController;
use App\Http\Controllers\Evstation\EVStationVerificationController;
use App\Http\Controllers\Evstation\EVStationSlotController;
use App\Http\Controllers\Garage\GarageVerificationController;
use App\Http\Controllers\Garage\GarageAppointmentController;
use App\Http\Controllers\PublicBookingController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\NewsletterController;
use Illuminate\Support\Facades\Route;

// ── Public frontend routes ─────────────────────────────────────────────
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/marketplace', [App\Http\Controllers\MarketplaceController::class, 'index'])->name('marketplace');
Route::get('/marketplace/search', [App\Http\Controllers\MarketplaceController::class, 'search'])->name('marketplace.search');
Route::get('/news', [App\Http\Controllers\NewsController::class, 'index'])->name('news');
Route::get('/news/{news:slug}', [App\Http\Controllers\NewsController::class, 'show'])->name('news.show');
Route::get('/news-filter', [App\Http\Controllers\NewsFilterController::class, 'filter'])->name('news.filter');
Route::get('/business-news/{news:slug}', [App\Http\Controllers\BusinessNewsPublicController::class, 'show'])->name('business.news.show');
Route::get('/map_location', [App\Http\Controllers\MapController::class, 'index'])->name('map_location');

// Public JSON endpoint — returns all active locations enriched with live slot/bay data
// Consumed by map_location.blade.php JS fetch('/api/map-locations')
Route::get('/api/map-locations', [MapController::class, 'getLocations'])->name('api.map.locations');

Route::get('/loan_calculator', fn() => view('frontend.pages.loan_calculator'))->name('loan_calculator');
Route::get('/contact', [App\Http\Controllers\Frontend\ContactController::class, 'index'])->name('contact');
Route::post('/contact', [App\Http\Controllers\ContactMessageController::class, 'store'])->name('contact.store');
Route::get('/compare_cars', [App\Http\Controllers\CompareController::class, 'index'])->name('compare_cars');
Route::get('/cars/{car}', [App\Http\Controllers\CarController::class, 'show'])->name('cars.show');

// Public business directory
Route::get('/businesses', [BusinessDirectoryController::class, 'index'])->name('businesses.index');
Route::get('/businesses/{id}', [BusinessDirectoryController::class, 'show'])->name('businesses.show')->whereNumber('id');

// newsletter
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
    ->name('newsletter.subscribe');

Route::get('/newsletter/verify/{token}', [NewsletterController::class, 'verify'])
    ->name('newsletter.verify');

// ── Auth-required routes (no verified.account check) ──────────────────
// Verification forms and public booking must be outside verified.account
// middleware so unverified users and all roles can reach them.
Route::middleware(['auth'])->group(function () {

    // Buyer verification
    Route::get('/buyer/verify', [BuyerVerificationController::class, 'create'])->name('buyer.verify.create');
    Route::post('/buyer/verify', [BuyerVerificationController::class, 'store'])->name('buyer.verify.store');

    // Seller verification
    Route::get('/seller/verify', [SellerVerificationController::class, 'create'])->name('seller.verify.create');
    Route::post('/seller/verify', [SellerVerificationController::class, 'store'])->name('seller.verify.store');

    // Business verification
    Route::get('/business/verify', [BusinessVerificationController::class, 'create'])->name('business.verify.create');
    Route::post('/business/verify', [BusinessVerificationController::class, 'store'])->name('business.verify.store');

    // EV Station verification
    Route::get('/ev-station/verify', [EVStationVerificationController::class, 'create'])->name('station.verify.create');
    Route::post('/ev-station/verify', [EVStationVerificationController::class, 'store'])->name('station.verify.store');

    // Garage verification
    Route::get('/garage/verify', [GarageVerificationController::class, 'create'])->name('garage.verify.create');
    Route::post('/garage/verify', [GarageVerificationController::class, 'store'])->name('garage.verify.store');

    // ── Public booking — available to ALL authenticated roles ──────────
    // Any buyer, seller, business, ev-station, or garage user can book

    // Book a garage appointment (submitted from the map popup)
    Route::post('/book/garage', [PublicBookingController::class, 'bookGarage'])->name('booking.garage');

    // Request an EV charging slot (submitted from the map popup)
    Route::post('/book/ev-slot', [PublicBookingController::class, 'requestSlot'])->name('booking.slot');

    // My bookings page — shows the user's garage appointments + EV slot requests
    Route::get('/my-bookings', [PublicBookingController::class, 'myAppointments'])->name('booking.mine');

    // ── Pending approval screen ────────────────────────────────────────
    Route::get('/pending-approval', function () {
        $user = auth()->user();

        $verification = match(true) {
            $user->hasRole('buyer')      => $user->buyerVerification,
            $user->hasRole('seller')     => $user->sellerVerification,
            $user->hasRole('business')   => $user->businessVerification,
            $user->hasRole('ev-station') => $user->stationVerification,
            $user->hasRole('garage')     => $user->garageVerification,
            default                      => null
        };

        if ($verification && $verification->isApproved()) {
            return redirect()->route('dashboard');
        }

        if (!$verification) {
            return match(true) {
                $user->hasRole('buyer')      => redirect()->route('buyer.verify.create'),
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
        $verification = $user->buyerVerification;
        if (!$verification) {
            return redirect()->route('buyer.verify.create')->with('info', 'Please complete your buyer verification to continue.');
        }
        if (!$verification->isApproved()) {
            return redirect()->route('verification.pending');
        }
        return redirect()->route('buyer.dashboard');
    }
    if ($user->hasRole('seller'))     return redirect()->route('seller.dashboard');
    if ($user->hasRole('business'))   return redirect()->route('business.dashboard');
    if ($user->hasRole('ev-station')) return redirect()->route('station.dashboard');
    if ($user->hasRole('garage'))     return redirect()->route('garage.dashboard');
    return view('dashboard');
})
    ->middleware(['auth'])
    ->name('dashboard');

// ── BUYER routes ───────────────────────────────────────────────────────
Route::middleware(['auth', 'role:buyer', 'verified.account'])
    ->prefix('buyer')
    ->name('buyer.')
    ->group(function () {
        Route::get('/dashboard', fn() => view('dashboard.buyer', ['user' => auth()->user()]))->name('dashboard');

        // Orders
        Route::get('/orders', [BuyerOrderController::class, 'index'])->name('orders.index')->middleware('permission:manage own orders');
        Route::post('/orders', [BuyerOrderController::class, 'store'])->name('orders.store')->middleware('permission:manage own orders');
        Route::get('/orders/{order}', [BuyerOrderController::class, 'show'])->name('orders.show')->middleware('permission:manage own orders');
        Route::patch('/orders/{order}/cancel', [BuyerOrderController::class, 'cancel'])->name('orders.cancel')->middleware('permission:manage own orders');

        // Purchases
        Route::get('/purchases', [BuyerPurchaseController::class, 'index'])->name('purchases.index')->middleware('permission:purchase vehicle');

        // Pre-orders
        Route::get('/preorders', [BuyerPreOrderController::class, 'index'])->name('preorders.index')->middleware('permission:manage own orders');
        Route::get('/preorders/car/{car}', [BuyerPreOrderController::class, 'create'])->name('preorders.create')->middleware('permission:manage own orders');
        Route::post('/preorders', [BuyerPreOrderController::class, 'store'])->name('preorders.store')->middleware('permission:manage own orders');
        Route::get('/preorders/{preOrder}', [BuyerPreOrderController::class, 'show'])->name('preorders.show')->middleware('permission:manage own orders');
        Route::patch('/preorders/{preOrder}/cancel', [BuyerPreOrderController::class, 'cancel'])->name('preorders.cancel')->middleware('permission:manage own orders');

        // Reviews
        Route::get('/reviews', [BuyerReviewController::class, 'index'])->name('reviews.index')->middleware('permission:write reviews');
        Route::get('/reviews/create/{car}', [BuyerReviewController::class, 'create'])->name('reviews.create')->middleware('permission:write reviews');
        Route::post('/reviews', [BuyerReviewController::class, 'store'])->name('reviews.store')->middleware('permission:write reviews');
        Route::get('/reviews/{review}/edit', [BuyerReviewController::class, 'edit'])->name('reviews.edit')->middleware('permission:write reviews');
        Route::patch('/reviews/{review}', [BuyerReviewController::class, 'update'])->name('reviews.update')->middleware('permission:write reviews');
        Route::delete('/reviews/{review}', [BuyerReviewController::class, 'destroy'])->name('reviews.destroy')->middleware('permission:write reviews');
    });

// ── SELLER routes ──────────────────────────────────────────────────────
Route::middleware(['auth', 'role:seller', 'verified.account'])
    ->prefix('seller')
    ->name('seller.')
    ->group(function () {
        Route::get('/dashboard', fn() => view('dashboard.seller', ['user' => auth()->user()]))->name('dashboard');

        // Cars
        Route::get('/cars', [SellerCarController::class, 'index'])->name('cars.index')->middleware('permission:manage car listing(seller)');
        Route::get('/cars/create', [SellerCarController::class, 'create'])->name('cars.create')->middleware('permission:manage car listing(seller)');
        Route::post('/cars', [SellerCarController::class, 'store'])->name('cars.store')->middleware('permission:manage car listing(seller)');
        Route::get('/cars/{car}/edit', [SellerCarController::class, 'edit'])->name('cars.edit')->middleware('permission:manage car listing(seller)');
        Route::patch('/cars/{car}', [SellerCarController::class, 'update'])->name('cars.update')->middleware('permission:manage car listing(seller)');
        Route::delete('/cars/{car}', [SellerCarController::class, 'destroy'])->name('cars.destroy')->middleware('permission:manage car listing(seller)');

        // Orders
        Route::get('/orders', [SellerOrderController::class, 'index'])->name('orders.index')->middleware('permission:manage own orders');
        Route::get('/orders/{order}', [SellerOrderController::class, 'show'])->name('orders.show')->middleware('permission:manage own orders');
        Route::patch('/orders/{order}/confirm', [SellerOrderController::class, 'confirm'])->name('orders.confirm')->middleware('permission:manage own orders');
        Route::patch('/orders/{order}/cancel', [SellerOrderController::class, 'cancel'])->name('orders.cancel')->middleware('permission:manage own orders');
        Route::get('/orders/{order}/complete', [SellerOrderController::class, 'completeForm'])->name('orders.complete.form')->middleware('permission:manage own orders');
        Route::post('/orders/{order}/complete', [SellerOrderController::class, 'complete'])->name('orders.complete')->middleware('permission:manage own orders');

        // Pre-orders
        Route::get('/preorders', [SellerPreOrderController::class, 'index'])->name('preorders.index')->middleware('permission:manage own orders');
        Route::get('/preorders/{preOrder}', [SellerPreOrderController::class, 'show'])->name('preorders.show')->middleware('permission:manage own orders');
        Route::get('/preorders/{preOrder}/confirm-deposit', [SellerPreOrderController::class, 'confirmDepositForm'])->name('preorders.confirm_deposit.form')->middleware('permission:manage own orders');
        Route::patch('/preorders/{preOrder}/confirm-deposit', [SellerPreOrderController::class, 'confirmDeposit'])->name('preorders.confirm_deposit')->middleware('permission:manage own orders');
        Route::post('/preorders/{preOrder}/convert', [SellerPreOrderController::class, 'convert'])->name('preorders.convert')->middleware('permission:manage own orders');
        Route::patch('/preorders/{preOrder}/cancel', [SellerPreOrderController::class, 'cancel'])->name('preorders.cancel')->middleware('permission:manage own orders');
    });

// ── BUSINESS routes ────────────────────────────────────────────────────
Route::middleware(['auth', 'role:business', 'verified.account'])
    ->prefix('business')
    ->name('business.')
    ->group(function () {
        Route::get('/dashboard', fn() => view('dashboard.business', ['user' => auth()->user()]))->name('dashboard');

        // Cars
        Route::get('/cars', [SellerCarController::class, 'index'])->name('cars.index')->middleware('permission:browse listings');
        Route::get('/cars/create', [SellerCarController::class, 'create'])->name('cars.create')->middleware('permission:browse listings');
        Route::post('/cars', [SellerCarController::class, 'store'])->name('cars.store')->middleware('permission:browse listings');
        Route::get('/cars/{car}/edit', [SellerCarController::class, 'edit'])->name('cars.edit')->middleware('permission:browse listings');
        Route::patch('/cars/{car}', [SellerCarController::class, 'update'])->name('cars.update')->middleware('permission:browse listings');
        Route::delete('/cars/{car}', [SellerCarController::class, 'destroy'])->name('cars.destroy')->middleware('permission:browse listings');

        // Orders
        Route::get('/orders', [SellerOrderController::class, 'index'])->name('orders.index')->middleware('permission:manage own orders');
        Route::get('/orders/{order}', [SellerOrderController::class, 'show'])->name('orders.show')->middleware('permission:manage own orders');
        Route::patch('/orders/{order}/confirm', [SellerOrderController::class, 'confirm'])->name('orders.confirm')->middleware('permission:manage own orders');
        Route::patch('/orders/{order}/cancel', [SellerOrderController::class, 'cancel'])->name('orders.cancel')->middleware('permission:manage own orders');
        Route::get('/orders/{order}/complete', [SellerOrderController::class, 'completeForm'])->name('orders.complete.form')->middleware('permission:manage own orders');
        Route::post('/orders/{order}/complete', [SellerOrderController::class, 'complete'])->name('orders.complete')->middleware('permission:manage own orders');

        // Pre-orders
        Route::get('/preorders', [SellerPreOrderController::class, 'index'])->name('preorders.index')->middleware('permission:manage own orders');
        Route::get('/preorders/{preOrder}', [SellerPreOrderController::class, 'show'])->name('preorders.show')->middleware('permission:manage own orders');
        Route::get('/preorders/{preOrder}/confirm-deposit', [SellerPreOrderController::class, 'confirmDepositForm'])->name('preorders.confirm_deposit.form')->middleware('permission:manage own orders');
        Route::patch('/preorders/{preOrder}/confirm-deposit', [SellerPreOrderController::class, 'confirmDeposit'])->name('preorders.confirm_deposit')->middleware('permission:manage own orders');
        Route::post('/preorders/{preOrder}/convert', [SellerPreOrderController::class, 'convert'])->name('preorders.convert')->middleware('permission:manage own orders');
        Route::patch('/preorders/{preOrder}/cancel', [SellerPreOrderController::class, 'cancel'])->name('preorders.cancel')->middleware('permission:manage own orders');

        // Analytics
        Route::get('/analytics', [App\Http\Controllers\Business\BusinessAnalyticsController::class, 'index'])->name('analytics')->middleware('permission:view business analytics');

        // Business News
        Route::get('/news', [BusinessNewsController::class, 'index'])->name('news.index');
        Route::get('/news/create', [BusinessNewsController::class, 'create'])->name('news.create');
        Route::post('/news', [BusinessNewsController::class, 'store'])->name('news.store');
        Route::get('/news/{news:slug}/edit', [BusinessNewsController::class, 'edit'])->name('news.edit');
        Route::patch('/news/{news:slug}', [BusinessNewsController::class, 'update'])->name('news.update');
        Route::delete('/news/{news:slug}', [BusinessNewsController::class, 'destroy'])->name('news.destroy');

        // Advertisements
        Route::get('/advertisements', [App\Http\Controllers\Business\BusinessAdvertisementController::class, 'index'])->name('advertisements.index')->middleware('permission:create advertisements');
        Route::get('/advertisements/create', [App\Http\Controllers\Business\BusinessAdvertisementController::class, 'create'])->name('advertisements.create')->middleware('permission:create advertisements');
        Route::post('/advertisements', [App\Http\Controllers\Business\BusinessAdvertisementController::class, 'store'])->name('advertisements.store')->middleware('permission:create advertisements');
        Route::get('/advertisements/{advertisement}/edit', [App\Http\Controllers\Business\BusinessAdvertisementController::class, 'edit'])->name('advertisements.edit')->middleware('permission:create advertisements');
        Route::patch('/advertisements/{advertisement}', [App\Http\Controllers\Business\BusinessAdvertisementController::class, 'update'])->name('advertisements.update')->middleware('permission:create advertisements');
        Route::delete('/advertisements/{advertisement}', [App\Http\Controllers\Business\BusinessAdvertisementController::class, 'destroy'])->name('advertisements.destroy')->middleware('permission:create advertisements');
    });

// ── EV STATION routes ──────────────────────────────────────────────────
Route::middleware(['auth', 'role:ev-station', 'verified.account'])
    ->prefix('ev-station')
    ->name('station.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', function () {
            return view('dashboard.ev-station', ['user' => auth()->user()]);
        })->name('dashboard');

        // Map location CRUD
        Route::get('/location', [LocationController::class, 'index'])->name('location.index');
        Route::get('/location/create', [LocationController::class, 'create'])->name('location.create');
        Route::post('/location', [LocationController::class, 'store'])->name('location.store');
        Route::get('/location/edit', [LocationController::class, 'edit'])->name('location.edit');
        Route::put('/location', [LocationController::class, 'update'])->name('location.update');
        Route::delete('/location', [LocationController::class, 'destroy'])->name('location.destroy');

        // ── Slot manager ───────────────────────────────────────────────
        // View slot grid
        Route::get('/slots', [EVStationSlotController::class, 'index'])->name('slots.index');

        // Set total number of ports (syncs slot rows)
        Route::post('/slots/configure', [EVStationSlotController::class, 'configure'])->name('slots.configure');

        // Manually toggle a slot available ↔ occupied
        Route::patch('/slots/{slot}', [EVStationSlotController::class, 'updateSlot'])->name('slots.update');

        // Approve a pending customer slot request → slot becomes occupied, email sent
        Route::post('/slots/{slot}/approve', [EVStationSlotController::class, 'approveRequest'])->name('slots.approve');

        // Reject a pending customer slot request → slot back to available, email sent
        Route::post('/slots/{slot}/reject', [EVStationSlotController::class, 'rejectRequest'])->name('slots.reject');
    });

// ── GARAGE routes ──────────────────────────────────────────────────────
Route::middleware(['auth', 'role:garage', 'verified.account'])
    ->prefix('garage')
    ->name('garage.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', function () {
            return view('dashboard.garage', ['user' => auth()->user()]);
        })->name('dashboard');

        // Map location CRUD
        Route::get('/location', [LocationController::class, 'index'])->name('location.index');
        Route::get('/location/create', [LocationController::class, 'create'])->name('location.create');
        Route::post('/location', [LocationController::class, 'store'])->name('location.store');
        Route::get('/location/edit', [LocationController::class, 'edit'])->name('location.edit');
        Route::put('/location', [LocationController::class, 'update'])->name('location.update');
        Route::delete('/location', [LocationController::class, 'destroy'])->name('location.destroy');

        // ── Appointment manager ────────────────────────────────────────
        // View all appointments + bay grid
        Route::get('/appointments', [GarageAppointmentController::class, 'index'])->name('appointments.index');

        // View single appointment detail
        Route::get('/appointments/{appointment}', [GarageAppointmentController::class, 'show'])->name('appointments.show');

        // Approve a pending appointment → bay marked occupied, email sent
        Route::post('/appointments/{appointment}/approve', [GarageAppointmentController::class, 'approve'])->name('appointments.approve');

        // Reject a pending appointment → email sent
        Route::post('/appointments/{appointment}/reject', [GarageAppointmentController::class, 'reject'])->name('appointments.reject');

        // Mark an approved appointment as completed → bay freed
        Route::post('/appointments/{appointment}/complete', [GarageAppointmentController::class, 'complete'])->name('appointments.complete');

        // ── Bay configuration ──────────────────────────────────────────
        // Save total bay count + walk-in setting (syncs garage_bays rows)
        Route::post('/bays/configure', [GarageAppointmentController::class, 'configureBays'])->name('bays.configure');

        // ── Manual bay control (walk-ins) ──────────────────────────────
        // Mark a bay occupied for a walk-in customer (no appointment needed)
        Route::post('/bays/{bay}/walkin', [GarageAppointmentController::class, 'walkinOccupy'])->name('bays.walkin');

        // Free a bay manually (walk-in done or override)
        Route::post('/bays/{bay}/free', [GarageAppointmentController::class, 'walkinFree'])->name('bays.free');
    });

// ── Profile ────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';