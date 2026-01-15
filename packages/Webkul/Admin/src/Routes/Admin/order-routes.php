<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Order\OrderController;

Route::controller(OrderController::class)->prefix('orders')->group(function () {
    Route::get('', 'index')->name('admin.orders.index');

    Route::get('create', 'create')->name('admin.orders.create');

    Route::post('create', 'store')->name('admin.orders.store');

    Route::get('view/{id}', 'show')->name('admin.orders.show');

    Route::get('edit/{id}', 'edit')->name('admin.orders.edit');

    Route::put('edit/{id}', 'update')->name('admin.orders.update');

    Route::post('status/{id}', 'updateStatus')->name('admin.orders.update_status');

    Route::delete('{id}', 'destroy')->name('admin.orders.delete');

    Route::post('mass-destroy', 'massDestroy')->name('admin.orders.mass_delete');
});
