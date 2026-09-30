<?php

use App\Http\Controllers\InventarisController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/barang/{barcode_key}', [InventarisController::class, 'showByBarcode'])->name('api.barang.show');
