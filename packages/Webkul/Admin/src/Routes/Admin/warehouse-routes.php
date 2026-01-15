<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Warehouse\StockEntryController;


Route::controller(StockEntryController::class)->prefix('warehouse/stock-entries')->group(function () {
     Route::get('', [StockEntryController::class, 'index'])->name('admin.warehouse.stock_entries.index');
        Route::get('create', [StockEntryController::class, 'create'])->name('admin.warehouse.stock_entries.create');
        Route::post('create', [StockEntryController::class, 'store'])->name('admin.warehouse.stock_entries.store');
        Route::delete('delete/{id}', [StockEntryController::class, 'destroy'])->name('admin.warehouse.stock_entries.delete');
});