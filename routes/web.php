<?php

use App\Http\Controllers\BonBarangController;
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

    // 1. Fitur Operasional Gudang & Bersama (Admin & Pegawai Gudang)
    Route::get('/inventaris', [InventarisController::class, 'index'])->name('inventaris.index');
    Route::get('/inventaris/{barang}/print', [InventarisController::class, 'printLabel'])->name('inventaris.print');
    Route::get('/cetak-label', [CetakLabelController::class, 'index'])->name('cetak-label.index');

    // Modul Bon Pengeluaran Barang (Scan Barcode)
    Route::prefix('bon-barang')->name('bon.')->group(function () {
        Route::get('/', [BonBarangController::class, 'index'])->name('index');
        Route::post('/', [BonBarangController::class, 'store'])->name('store');
        Route::post('/scan', [BonBarangController::class, 'scan'])->name('scan');
        Route::get('/{id}/print', [BonBarangController::class, 'print'])->name('print');
    });

    // 2. Fitur Khusus Admin (Master Data, Pembelian, Stock Opname, Laporan)
    Route::middleware('role:admin')->group(function () {
        Route::post('/inventaris', [InventarisController::class, 'store'])->name('inventaris.store');
        Route::get('/inventaris/export', [InventarisController::class, 'export'])->name('inventaris.export');
        Route::post('/inventaris/masuk', [InventarisController::class, 'storeMasuk'])->name('inventaris.masuk');
        Route::post('/inventaris/keluar', [InventarisController::class, 'storeKeluar'])->name('inventaris.keluar');

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
    });
});

require __DIR__.'/auth.php';
