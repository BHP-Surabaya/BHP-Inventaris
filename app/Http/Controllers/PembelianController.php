<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\MutasiBarang;
use App\Models\User;
use Illuminate\Http\JsonResponse;
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
        $daftarKdBarang = Barang::select('kd_barang', DB::raw('MIN(deskripsi) as contoh_deskripsi'), DB::raw('COUNT(*) as total_item'))
            ->groupBy('kd_barang')
            ->orderBy('kd_barang')
            ->get();

        return view('pembelian.index', compact(
            'riwayatPembelian',
            'daftarBarang',
            'daftarKdBarang',
            'totalPembelianBulanIni',
            'totalUnitMasukBulanIni',
            'totalItemTerdaftar',
            'barangPerluRestock',
            'search'
        ));
    }

    /**
     * Mengambil nomor sub-kode BMN berikutnya berdasarkan kode induk BMN.
     */
    public function getNextSubKode(Request $request): JsonResponse
    {
        $kdBarang = trim($request->string('kd_barang')->value());
        $prefix = rtrim($kdBarang, '.');

        if ($prefix === '') {
            return response()->json([
                'kd_barang' => '',
                'next_kd_sub' => '000001',
                'full_kode' => '',
                'total_existing' => 0,
            ]);
        }

        $maxSub = Barang::where('kd_barang', $prefix)->max('kd_sub');
        $nextNum = $maxSub ? ((int) $maxSub + 1) : 1;
        $nextKdSub = str_pad((string) $nextNum, 6, '0', STR_PAD_LEFT);
        $fullKode = "{$prefix}.{$nextKdSub}";
        $totalExisting = Barang::where('kd_barang', $prefix)->count();

        return response()->json([
            'kd_barang' => $prefix,
            'next_kd_sub' => $nextKdSub,
            'full_kode' => $fullKode,
            'total_existing' => $totalExisting,
        ]);
    }

    /**
     * Menyimpan transaksi pembelian / barang masuk baru.
     * Mendukung barang eksisting dari katalog maupun barang baru yang belum pernah tercatat.
     */
    public function store(Request $request): RedirectResponse
    {
        $tipeBarang = $request->string('tipe_barang')->trim()->value() ?: 'eksisting';

        if ($tipeBarang === 'baru') {
            $validated = $request->validate([
                'kd_barang' => ['required', 'string', 'max:20'],
                'deskripsi' => ['required', 'string', 'max:150'],
                'kategori' => ['nullable', 'string', 'max:60'],
                'spesifikasi' => ['nullable', 'string', 'max:255'],
                'satuan' => ['required', 'string', 'max:30'],
                'min_stok' => ['nullable', 'integer', 'min:0'],
                'barcode' => ['nullable', 'string', 'max:50'],
                'jumlah' => ['required', 'integer', 'min:1'],
                'no_dokumen' => ['nullable', 'string', 'max:100'],
                'nama_vendor' => ['nullable', 'string', 'max:150'],
                'lokasi_rak' => ['nullable', 'string', 'max:50'],
                'keterangan' => ['nullable', 'string', 'max:1000'],
            ]);
        } else {
            $validated = $request->validate([
                'barang_id' => ['required', 'integer', 'exists:barangs,id'],
                'jumlah' => ['required', 'integer', 'min:1'],
                'no_dokumen' => ['nullable', 'string', 'max:100'],
                'nama_vendor' => ['nullable', 'string', 'max:150'],
                'lokasi_rak' => ['nullable', 'string', 'max:50'],
                'keterangan' => ['nullable', 'string', 'max:1000'],
            ]);
        }

        $userId = Auth::id() ?? $request->user()?->id ?? User::value('id');

        $barang = DB::transaction(function () use ($validated, $tipeBarang, $userId) {
            if ($tipeBarang === 'baru') {
                $prefix = rtrim(trim($validated['kd_barang']), '.');

                // Hitung nomor sub berikutnya
                $maxSub = Barang::where('kd_barang', $prefix)->lockForUpdate()->max('kd_sub');
                $nextNum = $maxSub ? ((int) $maxSub + 1) : 1;
                $nextKdSub = str_pad((string) $nextNum, 6, '0', STR_PAD_LEFT);
                $barcodeKey = "{$prefix}.{$nextKdSub}";

                $barcode = ! empty($validated['barcode'])
                    ? $validated['barcode']
                    : 'BMN-'.rand(10000, 99999).'-'.substr($nextKdSub, -2);

                $lokasiRak = ! empty($validated['lokasi_rak']) ? $validated['lokasi_rak'] : 'Gudang Utama';

                $barangBaru = Barang::create([
                    'kd_barang' => $prefix,
                    'kd_sub' => $nextKdSub,
                    'barcode_key' => $barcodeKey,
                    'barcode' => $barcode,
                    'deskripsi' => $validated['deskripsi'],
                    'kategori' => $validated['kategori'] ?? 'ATK & Kertas',
                    'spesifikasi' => $validated['spesifikasi'] ?? null,
                    'satuan' => $validated['satuan'],
                    'lokasi_rak' => $lokasiRak,
                    'stok_saldo' => $validated['jumlah'],
                    'min_stok' => $validated['min_stok'] ?? 5,
                ]);

                $stokSebelum = 0;
                $stokSesudah = $validated['jumlah'];

                $ket = $validated['keterangan'] ?? '';
                if (! empty($validated['nama_vendor'])) {
                    $ket = 'Vendor/Toko: '.$validated['nama_vendor'].($ket ? ' | '.$ket : '');
                }

                MutasiBarang::create([
                    'barang_id' => $barangBaru->id,
                    'user_id' => $userId,
                    'jenis' => 'MASUK',
                    'jumlah' => $validated['jumlah'],
                    'stok_sebelum' => $stokSebelum,
                    'stok_sesudah' => $stokSesudah,
                    'no_dokumen' => $validated['no_dokumen'] ?? null,
                    'keterangan' => $ket ?: 'Pengadaan Barang Baru BMN',
                ]);

                return $barangBaru;
            }

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

            return $barang;
        });

        if ($tipeBarang === 'baru') {
            return redirect()->route('pembelian.index')
                ->with('success', "Barang baru '{$barang->deskripsi}' (Kode: {$barang->barcode_key}) berhasil didaftarkan dan {$validated['jumlah']} {$barang->satuan} stok pembelian dicatat!");
        }

        return redirect()->route('pembelian.index')
            ->with('success', "Transaksi pembelian {$validated['jumlah']} {$barang->satuan} '{$barang->deskripsi}' berhasil dicatat dan stok bertambah!");
    }
}
