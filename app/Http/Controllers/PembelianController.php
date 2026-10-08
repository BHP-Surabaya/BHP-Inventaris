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

class PembelianController extends Controller
{
    /**
     * Menampilkan halaman transaksi & riwayat Pembelian / Pengadaan Barang.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->value();

        // Metrik Pengadaan & Pembelian
        $totalPembelianBulanIni = MutasiBarang::where('jenis', 'MASUK')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $totalUnitMasukBulanIni = MutasiBarang::where('jenis', 'MASUK')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('jumlah');

        $totalItemTerdaftar = Barang::count();
        $barangPerluRestock = Barang::whereColumn('stok_saldo', '<=', 'min_stok')->count();

        // Riwayat Transaksi Pembelian (Barang Masuk)
        $query = MutasiBarang::with(['barang', 'user'])
            ->where('jenis', 'MASUK');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('no_dokumen', 'like', "%{$search}%")
                    ->orWhere('keterangan', 'like', "%{$search}%")
                    ->orWhereHas('barang', function ($bq) use ($search) {
                        $bq->where('deskripsi', 'like', "%{$search}%")
                            ->orWhere('barcode_key', 'like', "%{$search}%");
                    });
            });
        }

        $riwayatPembelian = $query->latest()->paginate(15)->withQueryString();
        $daftarBarang = Barang::orderBy('deskripsi')->get();

        return view('pembelian.index', compact(
            'riwayatPembelian',
            'daftarBarang',
            'totalPembelianBulanIni',
            'totalUnitMasukBulanIni',
            'totalItemTerdaftar',
            'barangPerluRestock',
            'search'
        ));
    }

    /**
     * Menyimpan transaksi pembelian / barang masuk baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'barang_id' => ['required', 'integer', 'exists:barangs,id'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'no_dokumen' => ['nullable', 'string', 'max:100'],
            'nama_vendor' => ['nullable', 'string', 'max:150'],
            'lokasi_rak' => ['nullable', 'string', 'max:50'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
        ]);

        $userId = Auth::id() ?? $request->user()?->id ?? User::value('id');

        DB::transaction(function () use ($validated, $userId) {
            /** @var Barang $barang */
            $barang = Barang::where('id', $validated['barang_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $stokSebelum = $barang->stok_saldo;
            $stokSesudah = $stokSebelum + $validated['jumlah'];

            $updateData = ['stok_saldo' => $stokSesudah];
            if (! empty($validated['lokasi_rak'])) {
                $updateData['lokasi_rak'] = $validated['lokasi_rak'];
            }

            $barang->update($updateData);

            $ket = $validated['keterangan'] ?? '';
            if (! empty($validated['nama_vendor'])) {
                $ket = 'Vendor/Toko: '.$validated['nama_vendor'].($ket ? ' | '.$ket : '');
            }

            MutasiBarang::create([
                'barang_id' => $barang->id,
                'user_id' => $userId,
                'jenis' => 'MASUK',
                'jumlah' => $validated['jumlah'],
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $stokSesudah,
                'no_dokumen' => $validated['no_dokumen'] ?? null,
                'keterangan' => $ket ?: 'Pembelian / Pengadaan Barang',
            ]);
        });

        return back()->with('success', 'Transaksi pembelian barang berhasil dicatat dan stok telah ditambahkan.');
    }
}
