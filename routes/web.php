<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Buyer\BuyerOrderController;
use App\Http\Controllers\Buyer\BuyerPurchaseController;
use App\Http\Controllers\Buyer\BuyerReviewController;
use App\Http\Controllers\Seller\SellerVerificationController;
use App\Http\Controllers\Business\BusinessVerificationController;
use Illuminate\Support\Facades\Route;

// ── Public frontend routes ─────────────────────────────────────────────
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/marketplace', [App\Http\Controllers\MarketplaceController::class, 'index'])->name('marketplace');
Route::get('/marketplace/search', [App\Http\Controllers\MarketplaceController::class, 'search'])->name('marketplace.search');
// Route::get('/news', fn() => view('frontend.pages.news'))->name('news');
Route::get('/news', [App\Http\Controllers\NewsController::class, 'index'])->name('news');
Route::get('/news/{news:slug}', [App\Http\Controllers\NewsController::class, 'show'])->name('news.show');
// Route::get('/map_location', fn() => view('frontend.pages.map_location'))->name('map_location');
// Route::get('/news', [App\Http\Controllers\NewsController::class, 'index'])->name('news');
Route::get('/map_location', [App\Http\Controllers\MapController::class, 'index'])->name('map_location');
Route::get('/loan_calculator', fn() => view('frontend.pages.loan_calculator'))->name('loan_calculator');
Route::get('/contact', [App\Http\Controllers\Frontend\ContactController::class, 'index'])->name('contact');
Route::post('/contact', [App\Http\Controllers\ContactMessageController::class, 'store'])->name('contact.store');
Route::get('/compare_cars', [App\Http\Controllers\CompareController::class, 'index'])->name('compare_cars');
Route::get('/cars/{car}', [App\Http\Controllers\CarController::class, 'show'])->name('cars.show');

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

    // Waiting / pending approval screen
    Route::get('/pending-approval', function () {
    $user         = auth()->user();
    $verification = $user->verification();

    // If already approved, send them straight to their dashboard
    if ($verification && $verification->isApproved()) {
        return redirect()->route('dashboard');
    }

    return view('verification.pending');
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

        // Analytics
        Route::get('/analytics', [App\Http\Controllers\Business\BusinessAnalyticsController::class, 'index'])
            ->name('analytics')
            ->middleware('permission:view business analytics');

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

// ── Profile ────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';