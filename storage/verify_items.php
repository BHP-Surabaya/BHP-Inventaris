<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Barang;

$sampleKeys = [
    '1010301001.000001',
    '1010301001.000002',
    '1010301006.000020',
    '1010305002.000016',
    '1010501001.000002'
];

foreach ($sampleKeys as $key) {
    $b = Barang::where('barcode_key', $key)->first();
    if ($b) {
        echo sprintf("%-20s | %-32s | %-8s | Stok: %3d | %s\n", $b->barcode_key, $b->deskripsi, $b->satuan, $b->stok_saldo, $b->kategori);
    } else {
        echo "Not found: $key\n";
    }
}
