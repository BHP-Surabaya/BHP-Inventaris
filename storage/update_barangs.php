<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Barang;
use Illuminate\Support\Facades\DB;

$jsonFile = __DIR__ . '/app/bahan_opname_807.json';
if (!file_exists($jsonFile)) {
    die("File $jsonFile tidak ditemukan!\n");
}

$items = json_decode(file_get_contents($jsonFile), true);
echo "Memproses " . count($items) . " data barang dari Bahan Opname BHP Surabaya...\n";

DB::beginTransaction();

try {
    $updatedCount = 0;
    $createdCount = 0;
    $processedKeys = [];

    foreach ($items as $item) {
        $barcodeKey = $item['barcode_key'];
        $processedKeys[] = $barcodeKey;

        $barang = Barang::where('barcode_key', $barcodeKey)->first();

        if ($barang) {
            $barang->update([
                'kd_barang' => $item['kd_barang'],
                'kd_sub' => $item['kd_sub'],
                'deskripsi' => $item['deskripsi'],
                'satuan' => $item['satuan'],
                'stok_saldo' => $item['stok_saldo'],
                'kategori' => $item['kategori'],
            ]);
            $updatedCount++;
        } else {
            Barang::create([
                'kd_barang' => $item['kd_barang'],
                'kd_sub' => $item['kd_sub'],
                'barcode_key' => $barcodeKey,
                'barcode' => null,
                'deskripsi' => $item['deskripsi'],
                'satuan' => $item['satuan'],
                'stok_saldo' => $item['stok_saldo'],
                'min_stok' => 5,
                'lokasi_rak' => 'Gudang Utama',
                'kategori' => $item['kategori'],
            ]);
            $createdCount++;
        }
    }

    // Cek apakah ada barang lama yang tidak ada di daftar 807
    $deletedCount = 0;
    $orphans = Barang::whereNotIn('barcode_key', $processedKeys)->get();
    foreach ($orphans as $orphan) {
        // Hapus mutasi terkait jika ada agar tidak foreign key violation
        $orphan->mutasiBarangs()->delete();
        $orphan->delete();
        $deletedCount++;
    }

    DB::commit();

    echo "SUKSES!\n";
    echo "- Total Data Bahan Opname: " . count($items) . "\n";
    echo "- Barang Diperbarui: $updatedCount\n";
    echo "- Barang Baru Ditambahkan: $createdCount\n";
    echo "- Barang Lama Dihapus (tidak ada di opname): $deletedCount\n";
    echo "- Total Akhir di Database: " . Barang::count() . "\n";
    echo "- Total Stok Saldo Fisik: " . Barang::sum('stok_saldo') . "\n";
    echo "- Jumlah Barang Berstok (> 0): " . Barang::where('stok_saldo', '>', 0)->count() . "\n";
    echo "- Jumlah Barang Kosong (0): " . Barang::where('stok_saldo', 0)->count() . "\n";

} catch (\Throwable $e) {
    DB::rollBack();
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
