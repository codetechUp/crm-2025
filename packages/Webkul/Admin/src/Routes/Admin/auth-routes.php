<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Controllers\User\ForgotPasswordController;
use Webkul\Admin\Http\Controllers\User\ResetPasswordController;
use Webkul\Admin\Http\Controllers\User\SessionController;

Route::withoutMiddleware(['user'])->group(function () {
    /**
     * Error page preview (for testing).
     */
    Route::get('error/500', function () {
        $errorCode = 500;
        return response()->view('admin::errors.index', compact('errorCode'), 500);
    })->name('admin.errors.500');

    /**
     * Redirect route.
     */
    Route::get('/', [Controller::class, 'redirectToLogin']);

    /**
     * Session routes.
     */
    Route::controller(SessionController::class)->group(function () {
        Route::prefix('login')->group(function () {
            Route::get('', 'create')->name('admin.session.create');

            Route::post('', 'store')->name('admin.session.store');
        });

        Route::middleware(['user'])->group(function () {
            Route::delete('logout', 'destroy')->name('admin.session.destroy');
        });
    });

    /**
     * Forgot password routes.
     */
    Route::controller(ForgotPasswordController::class)->prefix('forget-password')->group(function () {
        Route::get('', 'create')->name('admin.forgot_password.create');

        Route::post('', 'store')->name('admin.forgot_password.store');
    });

    /**
     * Reset password routes.
     */
    Route::controller(ResetPasswordController::class)->prefix('reset-password')->group(function () {
        Route::get('{token}', 'create')->name('admin.reset_password.create');

        Route::post('', 'store')->name('admin.reset_password.store');
    });
});
