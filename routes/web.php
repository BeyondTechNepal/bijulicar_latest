<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Buyer\BuyerOrderController;
use App\Http\Controllers\Buyer\BuyerPurchaseController;
use App\Http\Controllers\Buyer\BuyerReviewController;
use Illuminate\Support\Facades\Route;

// ── Public frontend routes ─────────────────────────────────────────────
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/marketplace', [App\Http\Controllers\MarketplaceController::class, 'index'])->name('marketplace');
Route::get('/marketplace/search', [App\Http\Controllers\MarketplaceController::class, 'search'])->name('marketplace.search');
// Route::get('/news', fn() => view('frontend.pages.news'))->name('news');
Route::get('/news', [App\Http\Controllers\NewsController::class, 'index'])->name('news');
// Route::get('/map_location', fn() => view('frontend.pages.map_location'))->name('map_location');
Route::get('/news', [App\Http\Controllers\NewsController::class, 'index'])->name('news');
Route::get('/map_location', [App\Http\Controllers\MapController::class, 'index'])->name('map_location');
Route::get('/loan_calculator', fn() => view('frontend.pages.loan_calculator'))->name('loan_calculator');
// Route::get('/contact', fn() => view('frontend.pages.contact'))->name('contact');
Route::get('/contact', [App\Http\Controllers\Frontend\ContactController::class, 'index'])->name('contact');

Route::post('/contact', [App\Http\Controllers\ContactMessageController::class, 'store'])->name('contact.store');
Route::get('/compare_cars', [App\Http\Controllers\CompareController::class, 'index'])->name('compare_cars');
Route::get('/cars/{car}', [App\Http\Controllers\CarController::class, 'show'])->name('cars.show');

// Static frontend auth pages
// Route::get('/login', fn() => view('frontend.auth.login'))->name('user-login');
// Route::get('/registration', fn() => view('frontend.auth.registration'))->name('user-registration');

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
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// ── BUYER routes ───────────────────────────────────────────────────────
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
Route::middleware(['auth', 'role:seller'])
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
Route::middleware(['auth', 'role:business'])
    ->prefix('business')
    ->name('business.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', fn() => view('dashboard.business', ['user' => auth()->user()]))->name('dashboard');

        // Car listings (CRUD) — reuses SellerCarController, business users share the same seller_id column
        Route::get('/cars', [App\Http\Controllers\Seller\SellerCarController::class, 'index'])->name('cars.index')->middleware('permission:browse listings');
        Route::get('/cars/create', [App\Http\Controllers\Seller\SellerCarController::class, 'create'])->name('cars.create')->middleware('permission:browse listings');
        Route::post('/cars', [App\Http\Controllers\Seller\SellerCarController::class, 'store'])->name('cars.store')->middleware('permission:browse listings');
        Route::get('/cars/{car}/edit', [App\Http\Controllers\Seller\SellerCarController::class, 'edit'])->name('cars.edit')->middleware('permission:browse listings');
        Route::patch('/cars/{car}', [App\Http\Controllers\Seller\SellerCarController::class, 'update'])->name('cars.update')->middleware('permission:browse listings');
        Route::delete('/cars/{car}', [App\Http\Controllers\Seller\SellerCarController::class, 'destroy'])->name('cars.destroy')->middleware('permission:browse listings');

        // Orders on business's listings — reuses SellerOrderController
        Route::get('/orders', [App\Http\Controllers\Seller\SellerOrderController::class, 'index'])->name('orders.index')->middleware('permission:manage own orders');
        Route::get('/orders/{order}', [App\Http\Controllers\Seller\SellerOrderController::class, 'show'])->name('orders.show')->middleware('permission:manage own orders');
        Route::patch('/orders/{order}/confirm', [App\Http\Controllers\Seller\SellerOrderController::class, 'confirm'])->name('orders.confirm')->middleware('permission:manage own orders');
        Route::patch('/orders/{order}/cancel', [App\Http\Controllers\Seller\SellerOrderController::class, 'cancel'])->name('orders.cancel')->middleware('permission:manage own orders');
        Route::get('/orders/{order}/complete', [App\Http\Controllers\Seller\SellerOrderController::class, 'completeForm'])->name('orders.complete.form')->middleware('permission:manage own orders');
        Route::post('/orders/{order}/complete', [App\Http\Controllers\Seller\SellerOrderController::class, 'complete'])->name('orders.complete')->middleware('permission:manage own orders');

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
