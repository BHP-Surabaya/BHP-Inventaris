<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\MutasiBarang;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StockOpnameController extends Controller
{
    /**
     * Menampilkan halaman Stock Opname (Pemeriksaan Fisik & Penyesuaian Saldo Stok).
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->value();
        $selectedKategori = $request->string('kategori')->trim()->value();

        $query = Barang::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('deskripsi', 'like', "%{$search}%")
                  ->orWhere('barcode_key', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%")
                  ->orWhere('lokasi_rak', 'like', "%{$search}%");
            });
        }

        if ($selectedKategori !== '' && $selectedKategori !== 'semua') {
            $query->where('kategori', 'like', "%{$selectedKategori}%");
        }

        // Metrik Opname
        $totalBarang = Barang::count();
        $totalStokSistem = Barang::sum('stok_saldo');
        $stokAmanCount = Barang::whereColumn('stok_saldo', '>', 'min_stok')->count();
        $stokKritisCount = Barang::whereColumn('stok_saldo', '<=', 'min_stok')->count();

        $barangs = $query->orderBy('deskripsi')->paginate(20)->withQueryString();

        return view('stock-opname.index', compact(
            'barangs',
            'totalBarang',
            'totalStokSistem',
            'stokAmanCount',
            'stokKritisCount',
            'search',
            'selectedKategori'
        ));
    }

    /**
     * Memproses penyesuaian (adjustment) stok fisik hasil Stock Opname.
     */
    public function adjust(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'barang_id' => ['required', 'integer', 'exists:barangs,id'],
            'stok_fisik' => ['required', 'integer', 'min:0'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $userId = Auth::id() ?? $request->user()?->id ?? User::value('id');

        DB::transaction(function () use ($validated, $userId) {
            /** @var Barang $barang */
            $barang = Barang::where('id', $validated['barang_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $stokSebelum = $barang->stok_saldo;
            $stokFisik = $validated['stok_fisik'];
            $selisih = $stokFisik - $stokSebelum;

            if ($selisih !== 0) {
                $jenis = $selisih > 0 ? 'MASUK' : 'KELUAR';
                $jumlahMutasi = abs($selisih);

                $barang->update(['stok_saldo' => $stokFisik]);

                $ket = 'Penyesuaian Fisik (Stock Opname): ' . ($validated['catatan'] ?? 'Pencocokan saldo fisik');

                MutasiBarang::create([
                    'barang_id' => $barang->id,
                    'user_id' => $userId,
                    'jenis' => $jenis,
                    'jumlah' => $jumlahMutasi,
                    'stok_sebelum' => $stokSebelum,
                    'stok_sesudah' => $stokFisik,
                    'no_dokumen' => 'BA-OPNAME-' . date('Ymd'),
                    'keterangan' => $ket,
                ]);
            }
        });

        return back()->with('success', 'Stok berhasil disesuaikan dengan data fisik stock opname.');
    }
}
