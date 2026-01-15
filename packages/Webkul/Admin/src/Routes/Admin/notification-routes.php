<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Notification\NotificationController;

Route::controller(NotificationController::class)->prefix('notifications')->group(function () {
    Route::post('read', 'markAsRead')->name('admin.notifications.read');
});
