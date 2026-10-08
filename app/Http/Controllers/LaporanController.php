<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\MutasiBarang;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman Pusat Cetak Laporan Inventaris, Mutasi, dan Stock Opname.
     */
    public function index(Request $request): View
    {
        $jenis = $request->string('jenis', 'stok')->value();
        $dariTanggal = $request->string('dari_tanggal', now()->startOfMonth()->format('Y-m-d'))->value();
        $sampaiTanggal = $request->string('sampai_tanggal', now()->format('Y-m-d'))->value();
        $selectedKategori = $request->string('kategori', 'semua')->value();

        $dataLaporan = $this->getDataLaporan($jenis, $dariTanggal, $sampaiTanggal, $selectedKategori);

        // Metrik Ringkasan
        $totalBarang = Barang::count();
        $totalStokFisik = Barang::sum('stok_saldo');
        $totalMasukPeriode = MutasiBarang::where('jenis', 'MASUK')
            ->whereDate('created_at', '>=', $dariTanggal)
            ->whereDate('created_at', '<=', $sampaiTanggal)
            ->sum('jumlah');
        $totalKeluarPeriode = MutasiBarang::where('jenis', 'KELUAR')
            ->whereDate('created_at', '>=', $dariTanggal)
            ->whereDate('created_at', '<=', $sampaiTanggal)
            ->sum('jumlah');

        $kategoris = Barang::select('kategori')->whereNotNull('kategori')->distinct()->pluck('kategori');

        return view('laporan.index', compact(
            'jenis',
            'dariTanggal',
            'sampaiTanggal',
            'selectedKategori',
            'dataLaporan',
            'totalBarang',
            'totalStokFisik',
            'totalMasukPeriode',
            'totalKeluarPeriode',
            'kategoris'
        ));
    }

    /**
     * Menampilkan lembar cetak resmi (Print Preview) yang siap dicetak ke printer / PDF.
     */
    public function print(Request $request): View
    {
        $jenis = $request->string('jenis', 'stok')->value();
        $dariTanggal = $request->string('dari_tanggal', now()->startOfMonth()->format('Y-m-d'))->value();
        $sampaiTanggal = $request->string('sampai_tanggal', now()->format('Y-m-d'))->value();
        $selectedKategori = $request->string('kategori', 'semua')->value();

        $dataLaporan = $this->getDataLaporan($jenis, $dariTanggal, $sampaiTanggal, $selectedKategori, false);

        $judulLaporan = match ($jenis) {
            'masuk' => 'LAPORAN TRANSAKSI PENGADAAN & BARANG MASUK',
            'keluar' => 'LAPORAN REKAPITULASI BON BARANG & PENGELUARAN',
            'opname' => 'BERITA ACARA & LAPORAN HASIL STOCK OPNAME',
            default => 'LAPORAN REKAPITULASI INVENTARIS & SALDO BMN',
        };

        return view('laporan.print', compact(
            'jenis',
            'dariTanggal',
            'sampaiTanggal',
            'selectedKategori',
            'dataLaporan',
            'judulLaporan'
        ));
    }

    /**
     * Export data laporan terpilih ke format file CSV / Excel.
     */
    public function export(Request $request): StreamedResponse
    {
        $jenis = $request->string('jenis', 'stok')->value();
        $dariTanggal = $request->string('dari_tanggal', now()->startOfMonth()->format('Y-m-d'))->value();
        $sampaiTanggal = $request->string('sampai_tanggal', now()->format('Y-m-d'))->value();
        $selectedKategori = $request->string('kategori', 'semua')->value();

        $data = $this->getDataLaporan($jenis, $dariTanggal, $sampaiTanggal, $selectedKategori, false);

        $fileName = 'Laporan_'.strtoupper($jenis).'_'.date('Ymd_His').'.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ];

        return response()->stream(function () use ($jenis, $data) {
            $handle = fopen('php://output', 'w');

            if ($jenis === 'stok') {
                fputcsv($handle, ['No', 'Kode BMN', 'Barcode', 'Nama Barang', 'Kategori', 'Lokasi Rak', 'Satuan', 'Stok Saldo', 'Min Stok', 'Kondisi']);
                foreach ($data as $i => $item) {
                    fputcsv($handle, [
                        $i + 1,
                        $item->barcode_key,
                        $item->barcode ?? '-',
                        $item->deskripsi,
                        $item->kategori ?? 'Umum',
                        $item->lokasi_rak,
                        $item->satuan,
                        $item->stok_saldo,
                        $item->min_stok,
                        $item->stok_saldo <= $item->min_stok ? 'Kritis' : 'Aman',
                    ]);
                }
            } elseif ($jenis === 'masuk') {
                fputcsv($handle, ['No', 'No Dokumen', 'Tanggal', 'Nama Barang', 'Kode BMN', 'Jumlah Masuk', 'Satuan', 'Keterangan/Vendor', 'Petugas']);
                foreach ($data as $i => $item) {
                    fputcsv($handle, [
                        $i + 1,
                        $item->no_dokumen ?? '-',
                        $item->created_at->format('d/m/Y H:i'),
                        $item->barang?->deskripsi ?? '-',
                        $item->barang?->barcode_key ?? '-',
                        $item->jumlah,
                        $item->barang?->satuan ?? 'Unit',
                        $item->keterangan ?? '-',
                        $item->user?->name ?? 'Admin',
                    ]);
                }
            } elseif ($jenis === 'keluar') {
                fputcsv($handle, ['No', 'No Dokumen', 'Tanggal', 'Nama Barang', 'Kode BMN', 'Jumlah Keluar', 'Satuan', 'Seksi Pemohon', 'Penerima', 'Petugas']);
                foreach ($data as $i => $item) {
                    fputcsv($handle, [
                        $i + 1,
                        $item->no_dokumen ?? '-',
                        $item->created_at->format('d/m/Y H:i'),
                        $item->barang?->deskripsi ?? '-',
                        $item->barang?->barcode_key ?? '-',
                        $item->jumlah,
                        $item->barang?->satuan ?? 'Unit',
                        $item->seksi_pemohon ?? '-',
                        $item->penerima ?? '-',
                        $item->user?->name ?? 'Admin',
                    ]);
                }
            } else {
                fputcsv($handle, ['No', 'Tanggal', 'Nama Barang', 'Kode BMN', 'Jenis Mutasi', 'Selisih Unit', 'Stok Sebelum', 'Stok Sesudah', 'Catatan Audit', 'Petugas']);
                foreach ($data as $i => $item) {
                    fputcsv($handle, [
                        $i + 1,
                        $item->created_at->format('d/m/Y H:i'),
                        $item->barang?->deskripsi ?? '-',
                        $item->barang?->barcode_key ?? '-',
                        $item->jenis,
                        ($item->jenis === 'MASUK' ? '+' : '-').$item->jumlah,
                        $item->stok_sebelum,
                        $item->stok_sesudah,
                        $item->keterangan ?? '-',
                        $item->user?->name ?? 'Admin',
                    ]);
                }
            }

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Mengambil koleksi data berdasarkan filter jenis dan periode.
     */
    private function getDataLaporan(string $jenis, string $dariTanggal, string $sampaiTanggal, string $kategori, bool $paginate = true)
    {
        if ($jenis === 'stok') {
            $query = Barang::query();
            if ($kategori !== '' && $kategori !== 'semua') {
                $query->where('kategori', 'like', "%{$kategori}%");
            }
            $query->orderBy('deskripsi');

            return $paginate ? $query->paginate(25)->withQueryString() : $query->get();
        }

        if ($jenis === 'masuk') {
            $query = MutasiBarang::with(['barang', 'user'])
                ->where('jenis', 'MASUK')
                ->whereDate('created_at', '>=', $dariTanggal)
                ->whereDate('created_at', '<=', $sampaiTanggal)
                ->latest();

            return $paginate ? $query->paginate(25)->withQueryString() : $query->get();
        }

        if ($jenis === 'keluar') {
            $query = MutasiBarang::with(['barang', 'user'])
                ->where('jenis', 'KELUAR')
                ->whereDate('created_at', '>=', $dariTanggal)
                ->whereDate('created_at', '<=', $sampaiTanggal)
                ->latest();

            return $paginate ? $query->paginate(25)->withQueryString() : $query->get();
        }

        // Opname / Penyesuaian
        $query = MutasiBarang::with(['barang', 'user'])
            ->where(function ($q) {
                $q->where('no_dokumen', 'like', 'BA-OPNAME%')
                    ->orWhere('keterangan', 'like', '%Opname%')
                    ->orWhere('keterangan', 'like', '%Penyesuaian%');
            })
            ->whereDate('created_at', '>=', $dariTanggal)
            ->whereDate('created_at', '<=', $sampaiTanggal)
            ->latest();

        return $paginate ? $query->paginate(25)->withQueryString() : $query->get();
    }
}
