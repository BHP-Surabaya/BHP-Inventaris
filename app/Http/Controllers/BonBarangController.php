<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BonBarang;
use App\Models\BonBarangItem;
use App\Models\MutasiBarang;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BonBarangController extends Controller
{
    /**
     * Tampilkan halaman kasir bon barang (pengeluaran ATK) dan riwayat bon.
     */
    public function index(Request $request): View
    {
        // 1. Generate Nomor Bon Otomatis (Format: BON-YYYYMM-XXXX)
        $prefix = 'BON-' . Carbon::now()->format('Ym') . '-';
        $latestBon = BonBarang::where('no_bon', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->first();

        $nextNumber = 1;
        if ($latestBon) {
            $lastSeq = (int) substr($latestBon->no_bon, -4);
            $nextNumber = $lastSeq + 1;
        }
        $autoNoBon = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        // 2. Daftar barang siap pakai untuk modal / autocomplete
        $barangs = Barang::orderBy('deskripsi')
            ->get(['id', 'kd_barang', 'kd_sub', 'barcode_key', 'barcode', 'deskripsi', 'satuan', 'lokasi_rak', 'stok_saldo']);

        // 3. Riwayat Bon Barang Terbaru
        $query = BonBarang::with(['items.barang', 'user'])->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('no_bon', 'like', "%{$s}%")
                    ->orWhere('nama_pemohon', 'like', "%{$s}%")
                    ->orWhere('seksi_pemohon', 'like', "%{$s}%");
            });
        }

        $riwayatBons = $query->paginate(10)->withQueryString();

        // 4. Daftar Seksi standar di Balai Harta Peninggalan Surabaya
        $daftarSeksi = [
            'Subbagian Tata Usaha',
            'Seksi Kurator Negara / Balai Harta Peninggalan',
            'Seksi Pelayanan & Pengawasan Harta Peninggalan',
            'Seksi Harta Peninggalan Wilayah I',
            'Seksi Harta Peninggalan Wilayah II',
            'Ruang Arsip & Registrasi',
            'Ruang Pelayanan Terpadu Satu Pintu (PTSP)',
            'Ruang Rapat & Sidang',
            'Pos Keamanan / Pengamanan',
        ];

        return view('bon.index', compact('autoNoBon', 'barangs', 'riwayatBons', 'daftarSeksi'));
    }

    /**
     * Endpoint API untuk memindai barcode (mencari barang via barcode_key / barcode).
     */
    public function scan(Request $request): JsonResponse
    {
        $code = trim($request->input('code', ''));

        if (! $code) {
            return response()->json(['success' => false, 'message' => 'Kode barcode kosong.'], 400);
        }

        $barang = Barang::where('barcode_key', $code)
            ->orWhere('barcode', $code)
            ->first();

        if (! $barang) {
            return response()->json([
                'success' => false,
                'message' => "Barang dengan barcode [{$code}] tidak ditemukan dalam sistem.",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'barang' => [
                'id' => $barang->id,
                'barcode_key' => $barang->barcode_key,
                'barcode' => $barang->barcode,
                'deskripsi' => $barang->deskripsi,
                'satuan' => $barang->satuan,
                'lokasi_rak' => $barang->lokasi_rak,
                'stok_saldo' => $barang->stok_saldo,
            ],
        ]);
    }

    /**
     * Simpan transaksi Bon Pengeluaran Barang.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'no_bon' => 'required|string|max:50|unique:bon_barangs,no_bon',
            'tanggal' => 'required|date',
            'nama_pemohon' => 'required|string|max:100',
            'seksi_pemohon' => 'required|string|max:100',
            'keperluan' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:barangs,id',
            'items.*.jumlah' => 'required|integer|min:1',
        ], [
            'no_bon.unique' => 'Nomor Bon sudah pernah digunakan, mohon refresh halaman.',
            'items.required' => 'Belum ada barang yang di-scan atau ditambahkan ke dalam bon.',
            'items.min' => 'Minimal harus menambahkan 1 barang untuk membuat bon pengeluaran.',
        ]);

        try {
            $bon = DB::transaction(function () use ($validated) {
                $totalItem = 0;
                foreach ($validated['items'] as $item) {
                    $totalItem += (int) $item['jumlah'];
                }

                // 1. Buat Header Bon Barang
                $bonBarang = BonBarang::create([
                    'no_bon' => $validated['no_bon'],
                    'tanggal' => $validated['tanggal'],
                    'nama_pemohon' => $validated['nama_pemohon'],
                    'seksi_pemohon' => $validated['seksi_pemohon'],
                    'keperluan' => $validated['keperluan'] ?? 'Kebutuhan operasional persediaan ATK',
                    'user_id' => Auth::id(),
                    'total_item' => $totalItem,
                    'status' => 'SELESAI',
                ]);

                // 2. Proses tiap item barang
                foreach ($validated['items'] as $itemData) {
                    $barang = Barang::lockForUpdate()->findOrFail($itemData['barang_id']);
                    $qtyKeluar = (int) $itemData['jumlah'];

                    // Validasi sisa stok
                    if ($barang->stok_saldo < $qtyKeluar) {
                        throw new \Exception("Stok barang [{$barang->deskripsi}] tidak mencukupi! Sisa stok di gudang hanya {$barang->stok_saldo} {$barang->satuan}, diminta {$qtyKeluar} {$barang->satuan}.");
                    }

                    $stokSebelum = $barang->stok_saldo;
                    $stokSesudah = $stokSebelum - $qtyKeluar;

                    // Update stok barang
                    $barang->update(['stok_saldo' => $stokSesudah]);

                    // Simpan detail item bon
                    BonBarangItem::create([
                        'bon_barang_id' => $bonBarang->id,
                        'barang_id' => $barang->id,
                        'jumlah' => $qtyKeluar,
                        'satuan' => $barang->satuan,
                        'stok_sebelum' => $stokSebelum,
                        'stok_sesudah' => $stokSesudah,
                    ]);

                    // Catat ke riwayat mutasi resmi
                    MutasiBarang::create([
                        'barang_id' => $barang->id,
                        'user_id' => Auth::id(),
                        'jenis' => 'KELUAR',
                        'jumlah' => $qtyKeluar,
                        'stok_sebelum' => $stokSebelum,
                        'stok_sesudah' => $stokSesudah,
                        'seksi_pemohon' => $validated['seksi_pemohon'],
                        'penerima' => $validated['nama_pemohon'],
                        'no_dokumen' => $validated['no_bon'],
                        'keterangan' => $validated['keperluan'] ?? 'Pengeluaran Bon ATK',
                    ]);
                }

                return $bonBarang;
            });

            return redirect()
                ->route('bon.index')
                ->with('success', "Bon Barang [{$bon->no_bon}] berhasil disimpan dan stok telah diperbarui!")
                ->with('print_bon_id', $bon->id);

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Cetak Lembar Bukti Pengeluaran Barang Persediaan (Bon SBBK).
     */
    public function print(int $id): View
    {
        $bon = BonBarang::with(['items.barang', 'user'])->findOrFail($id);

        return view('bon.print', compact('bon'));
    }
}
