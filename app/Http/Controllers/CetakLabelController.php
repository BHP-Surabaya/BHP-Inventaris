<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CetakLabelController extends Controller
{
    /**
     * Menampilkan antarmuka Pusat Cetak Label Stiker Fisik (Thermal 75mm x 20mm).
     */
    public function index(Request $request): View
    {
        $barangId = $request->integer('barang_id');

        // Ambil 4 item antrean default (prioritaskan 4 item representatif)
        $queueBarangs = Barang::orderByRaw("
            CASE 
                WHEN barcode_key = '1010301001.000001' THEN 1
                WHEN barcode_key = '1010301002.000042' THEN 2
                WHEN barcode_key = '3050204008.000003' THEN 3
                WHEN barcode_key = '3020101004.000015' THEN 4
                ELSE 5
            END, id ASC
        ")->take(4)->get();

        // Cari item yang dipilih, jika tidak ada gunakan item pertama di antrean
        if ($barangId) {
            $selectedBarang = Barang::find($barangId) ?? $queueBarangs->first();
        } else {
            $selectedBarang = $queueBarangs->first();
        }

        $allBarangs = Barang::select('id', 'barcode_key', 'deskripsi', 'satuan', 'lokasi_rak')->take(50)->get();
        $totalAntrean = $queueBarangs->count();

        return view('cetak_label.index', compact('selectedBarang', 'queueBarangs', 'allBarangs', 'totalAntrean'));
    }
}
