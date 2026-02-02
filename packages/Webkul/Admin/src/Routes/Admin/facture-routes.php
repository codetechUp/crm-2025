<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Facture\FactureController;

Route::controller(FactureController::class)->prefix('factures')->group(function () {
    Route::get('', 'index')->name('admin.factures.index');
    Route::get('search', 'search')->name('admin.factures.search');
    Route::get('create/{lead_id?}', 'create')->name('admin.factures.create');
    Route::post('create', 'store')->name('admin.factures.store');
    Route::post('send-relance', 'sendRelance')->name('admin.factures.send_relance');
    Route::post('mass-destroy', 'massDestroy')->name('admin.factures.mass_delete');
    Route::get('edit/{id?}', 'edit')->name('admin.factures.edit');
    Route::put('edit/{id}', 'update')->name('admin.factures.update');
    Route::get('print/{id?}', 'print')->name('admin.factures.print');
    Route::post('{id}/remind', 'remind')->name('admin.factures.remind');
    Route::delete('{id}', 'destroy')->name('admin.factures.delete');
});
