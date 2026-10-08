<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\MutasiBarang;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama SIMBAR–BHP.
     */
    public function index(Request $request): View
    {
        $totalAset = Barang::count();
        $barangMasukHariIni = MutasiBarang::where('jenis', 'MASUK')->whereDate('created_at', today())->sum('jumlah');
        $barangKeluarHariIni = MutasiBarang::where('jenis', 'KELUAR')->whereDate('created_at', today())->sum('jumlah');
        $stokKritisCount = Barang::whereColumn('stok_saldo', '<=', 'min_stok')->count();
        $historiTransaksiCount = MutasiBarang::count();
        $historiTransaksi = MutasiBarang::with('barang', 'user')->latest()->take(10)->get();

        // Ambil barang-barang dengan stok kritis untuk kartu peringatan
        $criticalItems = Barang::whereColumn('stok_saldo', '<=', 'min_stok')
            ->orderByRaw('(stok_saldo / NULLIF(min_stok, 0)) ASC')
            ->take(5)
            ->get()
            ->map(function ($item) {
                $min = $item->min_stok > 0 ? $item->min_stok : 1;
                $defisit = max(0, round((($min - $item->stok_saldo) / $min) * 100));

                $status = 'Menipis';
                $badgeBg = 'bg-sky-50 text-sky-700 border-sky-200';
                $iconType = 'box';

                if ($defisit >= 70) {
                    $status = 'Kritis';
                    $badgeBg = 'bg-rose-50 text-rose-700 border-rose-200';
                } elseif ($defisit >= 50) {
                    $status = 'Segera Restock';
                    $badgeBg = 'bg-amber-50 text-amber-800 border-amber-200';
                }

                $desk = strtolower($item->deskripsi);
                if (str_contains($desk, 'kertas') || str_contains($desk, 'hvs') || str_contains($desk, 'buku')) {
                    $iconType = 'document';
                } elseif (str_contains($desk, 'toner') || str_contains($desk, 'tinta') || str_contains($desk, 'catridge') || str_contains($desk, 'printer')) {
                    $iconType = 'toner';
                } elseif (str_contains($desk, 'ordner') || str_contains($desk, 'map') || str_contains($desk, 'binder') || str_contains($desk, 'arsip')) {
                    $iconType = 'folder';
                }

                $item->defisit_persen = $defisit;
                $item->status_text = $status;
                $item->badge_bg = $badgeBg;
                $item->icon_type = $iconType;

                return $item;
            });

        return view('dashboard', compact(
            'totalAset',
            'barangMasukHariIni',
            'barangKeluarHariIni',
            'stokKritisCount',
            'historiTransaksiCount',
            'historiTransaksi',
            'criticalItems'
        ));
    }
}
