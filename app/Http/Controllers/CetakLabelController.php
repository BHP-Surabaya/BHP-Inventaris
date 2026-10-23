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

        // Ambil 4 item antrean default (utamakan barang yang memiliki stok)
        $queueBarangs = Barang::where('stok_saldo', '>', 0)
            ->orderBy('deskripsi')
            ->take(4)
            ->get();

        if ($queueBarangs->isEmpty()) {
            $queueBarangs = Barang::orderBy('deskripsi')->take(4)->get();
        }

        // Cari item yang dipilih, jika tidak ada gunakan item pertama di antrean
        if ($barangId) {
            $selectedBarang = Barang::find($barangId) ?? $queueBarangs->first();
        } else {
            $selectedBarang = $queueBarangs->first();
        }

        $allBarangs = Barang::select('id', 'barcode_key', 'barcode', 'deskripsi', 'satuan', 'lokasi_rak', 'stok_saldo')
            ->orderBy('deskripsi')
            ->get();
        $totalAntrean = $queueBarangs->count();

        return view('cetak_label.index', compact('selectedBarang', 'queueBarangs', 'allBarangs', 'totalAntrean'));
    }
}
