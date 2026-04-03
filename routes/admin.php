<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Admin\AdminVerificationController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {
        // ── 1. Guest routes ────────────────────────────────────────────
        Route::get('/login', [AdminAuthController::class, 'showForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login']);

        // ── 2. Authenticated admin routes ──────────────────────────────
        Route::middleware(['auth.admin'])->group(function () {
            Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

            // ── 3. Standard admin + superadmin ─────────────────────────
            Route::middleware(['role:superadmin|admin,admin'])->group(function () {
                // Users
                Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
                Route::patch('/users/{user}/role', [AdminDashboardController::class, 'updateUserRole'])->name('users.updateRole');
                Route::delete('/users/{user}', [AdminDashboardController::class, 'destroy'])->name('users.destroy');

                // Verifications
                Route::get('/verifications', [AdminVerificationController::class, 'index'])->name('verifications.index');

                Route::post('/verifications/seller/{verification}/approve', [AdminVerificationController::class, 'approveSeller'])->name('verifications.seller.approve');
                Route::post('/verifications/seller/{verification}/reject', [AdminVerificationController::class, 'rejectSeller'])->name('verifications.seller.reject');

                Route::post('/verifications/business/{verification}/approve', [AdminVerificationController::class, 'approveBusiness'])->name('verifications.business.approve');
                Route::post('/verifications/business/{verification}/reject', [AdminVerificationController::class, 'rejectBusiness'])->name('verifications.business.reject');

                Route::post('/verifications/ev/{verification}/approve', [AdminVerificationController::class, 'approveEV'])->name('verifications.ev.approve');
                Route::post('/verifications/ev/{verification}/reject', [AdminVerificationController::class, 'rejectEV'])->name('verifications.ev.reject');

                Route::get('/verifications/document/{type}/{id}', [AdminVerificationController::class, 'viewDocument'])
                    ->name('verifications.document')
                    ->where('type', 'seller|business|ev');

                // Permissions
                Route::resource('permissions', PermissionController::class)->except(['show']);

                // Roles
                Route::resource('roles', RoleController::class)->except(['show']);
                Route::post('/roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.permissions.update');

                // Locations
                Route::resource('/locations', App\Http\Controllers\Admin\LocationController::class)->except(['show']);

                // Contact messages
                Route::get('/contact-messages', [App\Http\Controllers\Admin\ContactMessageController::class, 'index'])->name('contact_messages.index');
                Route::get('/contact-messages/{id}', [App\Http\Controllers\Admin\ContactMessageController::class, 'show'])->name('contact_messages.show');
                Route::post('/contact-messages/{id}/read', [App\Http\Controllers\Admin\ContactMessageController::class, 'markAsRead'])->name('contact_messages.read');
                Route::post('/contact-messages/{id}/undo', [App\Http\Controllers\Admin\ContactMessageController::class, 'undoRead'])->name('contact_messages.undo');
                Route::delete('/contact-messages/{id}', [App\Http\Controllers\Admin\ContactMessageController::class, 'destroy'])->name('contact_messages.destroy');

                // Banners & Content
                Route::resource('contact_banner', App\Http\Controllers\Admin\ContactBannerController::class);
                Route::resource('contact_details', App\Http\Controllers\Admin\ContactDetailsController::class);
                Route::resource('news_banner', App\Http\Controllers\Admin\NewsBannerController::class);
                Route::resource('home_banner', App\Http\Controllers\Admin\HomeBannerController::class);

                // Admin Roles
                Route::get('/admin-roles', [App\Http\Controllers\Admin\AdminRoleController::class, 'index'])->name('admin_roles.index');
                Route::get('/admin-roles/create', [App\Http\Controllers\Admin\AdminRoleController::class, 'create'])->name('admin_roles.create');
                Route::post('/admin-roles', [App\Http\Controllers\Admin\AdminRoleController::class, 'store'])->name('admin_roles.store');
                Route::get('/admin-roles/{role}/edit', [App\Http\Controllers\Admin\AdminRoleController::class, 'edit'])->name('admin_roles.edit');
                Route::put('/admin-roles/{role}', [App\Http\Controllers\Admin\AdminRoleController::class, 'update'])->name('admin_roles.update');
                Route::post('/admin-roles/{role}/permissions', [App\Http\Controllers\Admin\AdminRoleController::class, 'updatePermissions'])->name('admin_roles.permissions.update');
                Route::delete('/admin-roles/{role}', [App\Http\Controllers\Admin\AdminRoleController::class, 'destroy'])->name('admin_roles.destroy');

                // Admin Permissions
                Route::get('/admin-permissions', [App\Http\Controllers\Admin\AdminPermissionController::class, 'index'])->name('admin_permissions.index');
                Route::get('/admin-permissions/create', [App\Http\Controllers\Admin\AdminPermissionController::class, 'create'])->name('admin_permissions.create');
                Route::post('/admin-permissions', [App\Http\Controllers\Admin\AdminPermissionController::class, 'store'])->name('admin_permissions.store');
                Route::get('/admin-permissions/{permission}/edit', [App\Http\Controllers\Admin\AdminPermissionController::class, 'edit'])->name('admin_permissions.edit');
                Route::put('/admin-permissions/{permission}', [App\Http\Controllers\Admin\AdminPermissionController::class, 'update'])->name('admin_permissions.update');
                Route::delete('/admin-permissions/{permission}', [App\Http\Controllers\Admin\AdminPermissionController::class, 'destroy'])->name('admin_permissions.destroy');

                // News Categories
                Route::get('/news-categories', [App\Http\Controllers\Admin\NewsCategoryController::class, 'index'])->name('news_categories.index');
                Route::get('/news-categories/create', [App\Http\Controllers\Admin\NewsCategoryController::class, 'create'])->name('news_categories.create');
                Route::post('/news-categories', [App\Http\Controllers\Admin\NewsCategoryController::class, 'store'])->name('news_categories.store');
                Route::get('/news-categories/{category}/edit', [App\Http\Controllers\Admin\NewsCategoryController::class, 'edit'])->name('news_categories.edit');
                Route::put('/news-categories/{category}', [App\Http\Controllers\Admin\NewsCategoryController::class, 'update'])->name('news_categories.update');
                Route::delete('/news-categories/{category}', [App\Http\Controllers\Admin\NewsCategoryController::class, 'destroy'])->name('news_categories.destroy');
            });

            // ── News (separate role group) ─────────────────────────────
            Route::middleware(['role:superadmin|admin|newsadmin,admin'])->group(function () {
                Route::resource('news', App\Http\Controllers\Admin\NewsArticleController::class);
            });

            // ── 4. Superadmin only ─────────────────────────────────────
            Route::middleware(['role:superadmin,admin'])->group(function () {
                Route::resource('admins', AdminManagementController::class)->except(['show']);
            });
        });
    });
