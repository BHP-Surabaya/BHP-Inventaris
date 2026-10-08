<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\MutasiBarang;
use App\Models\User;
use DomainException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InventarisController extends Controller
{
    /**
     * Menampilkan katalog inventaris BMN dengan filter kategori, pencarian, dan metrik.
     */
    public function index(Request $request): View|JsonResponse
    {
        $search = $request->string('search')->trim()->value();
        $selectedKategori = $request->string('kategori')->trim()->value();
        $selectedStatus = $request->string('status')->trim()->value();

        // Metrik Statistik
        $totalItemFisik = Barang::sum('stok_saldo');
        $stokAmanCount = Barang::where('stok_saldo', '>=', 10)->count();
        $stokMenipisCount = Barang::where('stok_saldo', '<', 10)->count();
        $mutasiHariIniCount = MutasiBarang::whereDate('created_at', today())->count();

        // Jumlah Kategori untuk Filter Pills
        $countSemua = Barang::count();
        $countAtk = Barang::where('kategori', 'like', '%ATK%')->count();
        $countElektronik = Barang::where('kategori', 'like', '%Elektronik%')->count();
        $countPeralatan = Barang::where('kategori', 'like', '%Peralatan%')->count();

        $query = Barang::query();

        // Filter Pencarian
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('deskripsi', 'like', "%{$search}%")
                    ->orWhere('spesifikasi', 'like', "%{$search}%")
                    ->orWhere('barcode_key', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%")
                    ->orWhere('kd_barang', 'like', "%{$search}%")
                    ->orWhere('kd_sub', 'like', "%{$search}%")
                    ->orWhere('lokasi_rak', 'like', "%{$search}%");
            });
        }

        // Filter Kategori
        if ($selectedKategori !== '' && $selectedKategori !== 'semua') {
            $query->where('kategori', 'like', "%{$selectedKategori}%");
        }

        // Filter Status
        if ($selectedStatus === 'aman') {
            $query->where('stok_saldo', '>=', 10);
        } elseif ($selectedStatus === 'menipis') {
            $query->where('stok_saldo', '<', 10)->where('stok_saldo', '>', 0);
        } elseif ($selectedStatus === 'sitaan') {
            $query->whereNotNull('status_khusus');
        }

        // Urutan: Prioritaskan item mockup di awal, kemudian item terbaru
        $barangs = $query->orderByRaw("
            CASE 
                WHEN barcode_key = '1010301001.000001' THEN 1
                WHEN barcode_key = '1010301002.000042' THEN 2
                WHEN barcode_key = '3050204008.000003' THEN 3
                WHEN barcode_key = '3020101004.000015' THEN 4
                ELSE 5
            END, id ASC
        ")->paginate(15)->withQueryString();

        if ($request->wantsJson()) {
            return response()->json($barangs);
        }

        return view('inventaris.index', compact(
            'barangs',
            'search',
            'selectedKategori',
            'selectedStatus',
            'totalItemFisik',
            'stokAmanCount',
            'stokMenipisCount',
            'mutasiHariIniCount',
            'countSemua',
            'countAtk',
            'countElektronik',
            'countPeralatan'
        ));
    }

    /**
     * Menyimpan data barang inventaris baru (+ Barang Baru).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kd_barang' => ['required', 'string', 'max:20'],
            'kd_sub' => ['required', 'string', 'max:10'],
            'barcode' => ['nullable', 'string', 'max:50'],
            'deskripsi' => ['required', 'string', 'max:150'],
            'spesifikasi' => ['nullable', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:60'],
            'satuan' => ['required', 'string', 'max:30'],
            'lokasi_rak' => ['required', 'string', 'max:100'],
            'stok_saldo' => ['required', 'integer', 'min:0'],
            'min_stok' => ['required', 'integer', 'min:0'],
            'status_khusus' => ['nullable', 'string', 'max:50'],
        ]);

        $validated['barcode_key'] = "{$validated['kd_barang']}.{$validated['kd_sub']}";
        if (empty($validated['barcode'])) {
            $validated['barcode'] = 'BMN-'.rand(10000, 99999).'-'.substr($validated['kd_sub'], -2);
        }

        Barang::create($validated);

        return redirect()->route('inventaris.index')->with('success', 'Barang baru berhasil ditambahkan ke katalog!');
    }

    /**
     * Ekspor data inventaris ke format CSV / Excel.
     */
    public function export(): StreamedResponse
    {
        $fileName = 'inventaris_bhp_'.date('Ymd_His').'.csv';
        $barangs = Barang::all();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ];

        return response()->stream(function () use ($barangs) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['No', 'Kode BMN', 'Kode Sub', 'Barcode Key', 'Barcode', 'Nama Barang', 'Spesifikasi', 'Kategori', 'Lokasi Rak', 'Satuan', 'Stok Saldo', 'Min Stok', 'Status']);

            foreach ($barangs as $i => $b) {
                fputcsv($handle, [
                    $i + 1,
                    $b->kd_barang,
                    $b->kd_sub,
                    $b->barcode_key,
                    $b->barcode ?? '-',
                    $b->deskripsi,
                    $b->spesifikasi ?? '-',
                    $b->kategori_label,
                    $b->lokasi_rak,
                    $b->satuan,
                    $b->stok_saldo,
                    $b->min_stok,
                    $b->status_data['text'],
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Transaksi stok masuk barang yang aman dari race condition menggunakan lockForUpdate.
     */
    public function storeMasuk(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'barang_id' => ['required', 'integer', 'exists:barangs,id'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'no_dokumen' => ['nullable', 'string', 'max:100'],
            'lokasi_rak' => ['nullable', 'string', 'max:50'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
        ]);

        $userId = Auth::id() ?? $request->user()?->id ?? User::value('id');

        $mutasi = DB::transaction(function () use ($validated, $userId) {
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

            return MutasiBarang::create([
                'barang_id' => $barang->id,
                'user_id' => $userId,
                'jenis' => 'MASUK',
                'jumlah' => $validated['jumlah'],
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $stokSesudah,
                'no_dokumen' => $validated['no_dokumen'] ?? null,
                'keterangan' => $validated['keterangan'] ?? null,
            ]);
        });

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Stok masuk berhasil dicatat.',
                'data' => $mutasi->load('barang'),
            ], 201);
        }

        return back()->with('success', 'Stok masuk berhasil dicatat.');
    }

    /**
     * Transaksi stok keluar barang dengan validasi kecukupan fisik dan lockForUpdate.
     */
    public function storeKeluar(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'barang_id' => ['required', 'integer', 'exists:barangs,id'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'seksi_pemohon' => ['nullable', 'string', 'max:100'],
            'penerima' => ['nullable', 'string', 'max:100'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
        ]);

        $userId = Auth::id() ?? $request->user()?->id ?? User::value('id');

        try {
            $mutasi = DB::transaction(function () use ($validated, $userId) {
                /** @var Barang $barang */
                $barang = Barang::where('id', $validated['barang_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($barang->stok_saldo < $validated['jumlah']) {
                    throw new DomainException(
                        "Stok tidak mencukupi! Stok saat ini: {$barang->stok_saldo}, permintaan: {$validated['jumlah']}."
                    );
                }

                $stokSebelum = $barang->stok_saldo;
                $stokSesudah = $stokSebelum - $validated['jumlah'];

                $barang->update(['stok_saldo' => $stokSesudah]);

                return MutasiBarang::create([
                    'barang_id' => $barang->id,
                    'user_id' => $userId,
                    'jenis' => 'KELUAR',
                    'jumlah' => $validated['jumlah'],
                    'stok_sebelum' => $stokSebelum,
                    'stok_sesudah' => $stokSesudah,
                    'seksi_pemohon' => $validated['seksi_pemohon'] ?? null,
                    'penerima' => $validated['penerima'] ?? null,
                    'keterangan' => $validated['keterangan'] ?? null,
                ]);
            });
        } catch (DomainException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ], 422);
            }

            return back()->withErrors(['jumlah' => $e->getMessage()])->withInput();
        }

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Stok keluar berhasil dicatat.',
                'data' => $mutasi->load('barang'),
            ], 201);
        }

        return back()->with('success', 'Stok keluar berhasil dicatat.');
    }

    /**
     * Menampilkan template label barcode barang untuk dicetak.
     */
    public function printLabel(Request $request, Barang $barang): View
    {
        $w = (int) $request->query('w', 75);
        $h = (int) $request->query('h', 50);

        return view('inventaris.print_label', compact('barang', 'w', 'h'));
    }

    /**
     * Lookup data barang berdasarkan barcode_key untuk integrasi mobile / React Native scanner.
     */
    public function showByBarcode(string $barcode_key): JsonResponse
    {
        $barang = Barang::where('barcode_key', $barcode_key)
            ->with(['mutasiBarangs' => function ($query): void {
                $query->latest()->limit(5);
            }])
            ->first();

        if (! $barang) {
            return response()->json([
                'status' => 'error',
                'message' => "Barang dengan barcode key '{$barcode_key}' tidak ditemukan.",
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $barang,
        ]);
    }
}
