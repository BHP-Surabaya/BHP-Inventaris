<?php

use App\Http\Controllers\CetakLabelController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventarisController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StockOpnameController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('inventaris')->name('inventaris.')->group(function () {
        Route::get('/', [InventarisController::class, 'index'])->name('index');
        Route::post('/', [InventarisController::class, 'store'])->name('store');
        Route::get('/export', [InventarisController::class, 'export'])->name('export');
        Route::post('/masuk', [InventarisController::class, 'storeMasuk'])->name('masuk');
        Route::post('/keluar', [InventarisController::class, 'storeKeluar'])->name('keluar');
        Route::get('/{barang}/print', [InventarisController::class, 'printLabel'])->name('print');
    });

    Route::prefix('pembelian')->name('pembelian.')->group(function () {
        Route::get('/', [PembelianController::class, 'index'])->name('index');
        Route::post('/', [PembelianController::class, 'store'])->name('store');
    });

    Route::prefix('stock-opname')->name('stock-opname.')->group(function () {
        Route::get('/', [StockOpnameController::class, 'index'])->name('index');
        Route::post('/adjust', [StockOpnameController::class, 'adjust'])->name('adjust');
    });

    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/print', [LaporanController::class, 'print'])->name('print');
        Route::get('/export', [LaporanController::class, 'export'])->name('export');
    });

    Route::get('/cetak-label', [CetakLabelController::class, 'index'])->name('cetak-label.index');
});

require __DIR__.'/auth.php';
