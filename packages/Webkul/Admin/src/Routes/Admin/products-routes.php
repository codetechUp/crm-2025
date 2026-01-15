<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Products\ActivityController;
use Webkul\Admin\Http\Controllers\Products\ProductController;
use Webkul\Admin\Http\Controllers\Products\TagController;
use Webkul\Admin\Http\Controllers\Products\CategoryController;

Route::group(['middleware' => ['user']], function () {
    Route::controller(ProductController::class)->prefix('products')->group(function () {
        Route::get('', 'index')->name('admin.products.index');

        Route::get('create', 'create')->name('admin.products.create');

        Route::post('create', 'store')->name('admin.products.store');

        Route::get('view/{id}', 'view')->name('admin.products.view');

        Route::get('edit/{id}', 'edit')->name('admin.products.edit');

        Route::put('edit/{id}', 'update')->name('admin.products.update');

        Route::get('search', 'search')->name('admin.products.search');

        Route::get('{id}/warehouses', 'warehouses')->name('admin.products.warehouses');

        Route::post('{id}/inventories/{warehouseId?}', 'storeInventories')->name('admin.products.inventories.store');

        Route::delete('{id}', 'destroy')->name('admin.products.delete');

        Route::post('mass-destroy', 'massDestroy')->name('admin.products.mass_delete');

        Route::controller(ActivityController::class)->prefix('{id}/activities')->group(function () {
            Route::get('', 'index')->name('admin.products.activities.index');
        });

        Route::controller(TagController::class)->prefix('{id}/tags')->group(function () {
            Route::post('', 'attach')->name('admin.products.tags.attach');

            Route::delete('', 'detach')->name('admin.products.tags.detach');
        });

        Route::controller(CategoryController::class)->prefix('categories')->group(function () {
            Route::get('', 'index')->name('admin.products.categories.index');
            Route::get('create', 'create')->name('admin.products.categories.create');
            Route::post('create', 'store')->name('admin.products.categories.store');
            Route::get('edit/{id}', 'edit')->name('admin.products.categories.edit');
            // Using put or post for update - standardizing on put if available or match existing
            Route::put('edit/{id}', 'update')->name('admin.products.categories.update');
            Route::delete('{id}', 'destroy')->name('admin.products.categories.delete');
            Route::post('mass-destroy', 'massDestroy')->name('admin.products.categories.mass_delete');
        });
    });
});
