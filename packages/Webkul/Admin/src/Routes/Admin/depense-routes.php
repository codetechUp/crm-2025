<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Depense\DepenseController;

// Depenses Routes
Route::controller(\Webkul\Admin\Http\Controllers\Depense\DepenseController::class)->prefix('depenses')->group(function () {
    Route::get('', 'index')->name('admin.depenses.index');
    Route::get('create', 'create')->name('admin.depenses.create');
    Route::post('create', 'store')->name('admin.depenses.store');
    Route::get('edit/{id?}', 'edit')->name('admin.depenses.edit');
    Route::put('edit/{id}', 'update')->name('admin.depenses.update');
    Route::delete('{id}', 'destroy')->name('admin.depenses.delete');
    Route::post('mass-destroy', 'massDestroy')->name('admin.depenses.mass_delete');
});