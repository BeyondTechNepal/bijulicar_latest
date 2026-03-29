<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {
        // 1. Guest Routes
        Route::get('/login', [AdminAuthController::class, 'showForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login']);

        // 2. Authenticated Admin Routes
        Route::middleware(['auth.admin'])->group(function () {
            Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

            // 3. User & Permission Management (Standard Admin + Superadmin)
            // Syntax: role:role1|role2,guard
            Route::middleware(['role:superadmin|admin,admin'])->group(function () {
                // Users
                Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
                Route::patch('/users/{user}/role', [AdminDashboardController::class, 'updateUserRole'])->name('users.updateRole');
                Route::delete('/users/{user}', [AdminDashboardController::class, 'destroy'])->name('users.destroy');

                // Permissions CRUD
                Route::resource('permissions', PermissionController::class)->except(['show']);

                // Roles CRUD
                Route::resource('roles', RoleController::class)->except(['show']);
                Route::post('/roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.permissions.update');

                // map location
                Route::resource('/locations', App\Http\Controllers\Admin\LocationController::class)->except(['show']);

                // contact messages
                Route::get('/contact-messages', [App\Http\Controllers\Admin\ContactMessageController::class, 'index'])->name('contact_messages.index');
                Route::get('/contact-messages/{id}', [App\Http\Controllers\Admin\ContactMessageController::class, 'show'])->name('contact_messages.show');
                Route::post('/contact-messages/{id}/read', [App\Http\Controllers\Admin\ContactMessageController::class, 'markAsRead'])->name('contact_messages.read');
                Route::post('/contact-messages/{id}/undo', [App\Http\Controllers\Admin\ContactMessageController::class, 'undoRead'])->name('contact_messages.undo');
                Route::delete('/contact-messages/{id}', [App\Http\Controllers\Admin\ContactMessageController::class, 'destroy'])->name('contact_messages.destroy');

                // news
                Route::resource('news', \App\Http\Controllers\Admin\NewsArticleController::class);

                // contact banner
                Route::resource('contact_banner', \App\Http\Controllers\Admin\ContactBannerController::class);

                // contact Details
                Route::resource('contact_details', \App\Http\Controllers\Admin\ContactDetailsController::class);

                // news banner
                Route::resource('news_banner', \App\Http\Controllers\Admin\NewsBannerController::class);

                // home banner
                Route::resource('home_banner', \App\Http\Controllers\Admin\HomeBannerController::class);

            });

            // 4. Staff Management (Superadmin ONLY)
            Route::middleware(['role:superadmin'])->group(function () {
                Route::resource('admins', AdminManagementController::class)->except(['show']);
            });
        });
    });
