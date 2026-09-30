<x-app-layout>
    <div class="p-6 lg:p-8 space-y-6 max-w-[1600px] mx-auto">

        <!-- TOP HEADER AREA -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 bg-white p-6 lg:p-8 rounded-3xl border border-slate-100 shadow-sm">
            <div>
                <!-- Breadcrumbs -->
                <div class="flex flex-wrap items-center gap-2 text-xs font-semibold text-slate-500">
                    <span class="text-blue-600 font-bold">BHP Surabaya</span>
                    <span>/</span>
                    <span class="text-slate-800 font-bold">Pusat Cetak Laporan</span>
                </div>

                <!-- Page Title -->
                <h1 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight mt-2 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                    </div>
                    <span>Cetak Laporan & Rekapitulasi</span>
                </h1>
                <p class="text-xs text-slate-500 mt-1 max-w-2xl leading-relaxed">
                    Penerbitan dokumen resmi, rekap mutasi barang masuk/keluar, berita acara stock opname, dan pelaporan BMN Balai Harta Peninggalan Surabaya.
                </p>
            </div>

            <!-- Action Buttons: Print & Export -->
            <div class="flex items-center gap-3">
                <a href="{{ route('laporan.export', request()->query()) }}" 
                   class="px-4 py-2.5 rounded-2xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs flex items-center gap-2 shadow-sm transition-all duration-150">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.5L19 7.5V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Download Excel / CSV</span>
                </a>

                <a href="{{ route('laporan.print', request()->query()) }}" 
                   target="_blank"
                   class="px-5 py-2.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs flex items-center gap-2.5 shadow-lg shadow-blue-600/30 transition-all duration-150 transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>Cetak Lembar Resmi (PDF)</span>
                </a>
            </div>
        </div>

        <!-- STAT METRICS CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div>
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Katalog Barang</div>
                    <div class="text-2xl font-black text-slate-800">{{ number_format($totalBarang) }} Item</div>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Fisik di Gudang</div>
                    <div class="text-2xl font-black text-indigo-600">{{ number_format($totalStokFisik) }} Unit</div>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                    </svg>
                </div>
                <div>
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Masuk (Periode Ini)</div>
                    <div class="text-2xl font-black text-emerald-600">+{{ number_format($totalMasukPeriode) }} Unit</div>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </div>
                <div>
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Keluar (Periode Ini)</div>
                    <div class="text-2xl font-black text-rose-600">-{{ number_format($totalKeluarPeriode) }} Unit</div>
                </div>
            </div>
        </div>

        <!-- FILTER & SELECTION BAR -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-5">
            <!-- Tabs Pilihan Jenis Laporan -->
            <div class="flex flex-wrap items-center gap-2 border-b border-slate-100 pb-4">
                <a href="{{ route('laporan.index', ['jenis' => 'stok', 'kategori' => $selectedKategori]) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 {{ $jenis === 'stok' ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <span>Laporan Stok & Saldo BMN</span>
                </a>

                <a href="{{ route('laporan.index', ['jenis' => 'masuk', 'dari_tanggal' => $dariTanggal, 'sampai_tanggal' => $sampaiTanggal]) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 {{ $jenis === 'masuk' ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="8" cy="21" r="1"/>
                        <circle cx="19" cy="21" r="1"/>
                        <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                    </svg>
                    <span>Laporan Pembelian (Barang Masuk)</span>
                </a>

                <a href="{{ route('laporan.index', ['jenis' => 'keluar', 'dari_tanggal' => $dariTanggal, 'sampai_tanggal' => $sampaiTanggal]) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 {{ $jenis === 'keluar' ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.5L19 7.5V19a2 2 0 0 1-2 2Z"/>
                    </svg>
                    <span>Laporan Bon Barang (Pengeluaran)</span>
                </a>

                <a href="{{ route('laporan.index', ['jenis' => 'opname', 'dari_tanggal' => $dariTanggal, 'sampai_tanggal' => $sampaiTanggal]) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 {{ $jenis === 'opname' ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 11l3 3L22 4"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                    </svg>
                    <span>Berita Acara Stock Opname</span>
                </a>
            </div>

            <!-- Form Filter Parameter -->
            <form method="GET" action="{{ route('laporan.index') }}" class="flex flex-wrap items-end gap-4">
                <input type="hidden" name="jenis" value="{{ $jenis }}" />

                @if ($jenis !== 'stok')
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Dari Tanggal</label>
                        <input type="date" name="dari_tanggal" value="{{ $dariTanggal }}" class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-blue-500" />
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Sampai Tanggal</label>
                        <input type="date" name="sampai_tanggal" value="{{ $sampaiTanggal }}" class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-blue-500" />
                    </div>
                @else
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Filter Kategori</label>
                        <select name="kategori" class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-blue-500">
                            <option value="semua">Semua Kategori</option>
                            @foreach ($kategoris as $kat)
                                <option value="{{ $kat }}" {{ $selectedKategori === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <button type="submit" class="px-5 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold shadow-sm transition-colors">
                    Terapkan Filter
                </button>
            </form>
        </div>

        <!-- PRATINJAU TABEL DATA -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <span>Pratinjau Data Laporan</span>
                    <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 text-xs font-bold">
                        {{ strtoupper($jenis) }}
                    </span>
                </div>
                <div class="text-xs text-slate-500">
                    Menampilkan {{ $dataLaporan->total() }} baris data
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    @if ($jenis === 'stok')
                        <!-- Table Headers for Stok -->
                        <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-[11px] font-bold border-b border-slate-100">
                            <tr>
                                <th class="py-3.5 px-4 w-12 text-center">No</th>
                                <th class="py-3.5 px-4">Kode BMN</th>
                                <th class="py-3.5 px-4">Nama Barang (Deskripsi)</th>
                                <th class="py-3.5 px-4">Kategori</th>
                                <th class="py-3.5 px-4">Lokasi Rak</th>
                                <th class="py-3.5 px-4 text-center">Stok Saldo</th>
                                <th class="py-3.5 px-4 text-center">Batas Min</th>
                                <th class="py-3.5 px-4 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse ($dataLaporan as $i => $item)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-3.5 px-4 text-center text-slate-400 font-medium">{{ $dataLaporan->firstItem() + $i }}</td>
                                    <td class="py-3.5 px-4 font-mono font-bold text-slate-900">{{ $item->barcode_key }}</td>
                                    <td class="py-3.5 px-4 font-bold text-slate-900">{{ $item->deskripsi }}</td>
                                    <td class="py-3.5 px-4"><span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 font-semibold">{{ $item->kategori ?? 'Umum' }}</span></td>
                                    <td class="py-3.5 px-4 text-slate-600">{{ $item->lokasi_rak }}</td>
                                    <td class="py-3.5 px-4 text-center font-bold text-slate-900">{{ $item->stok_saldo }} {{ $item->satuan }}</td>
                                    <td class="py-3.5 px-4 text-center text-slate-500">{{ $item->min_stok }} {{ $item->satuan }}</td>
                                    <td class="py-3.5 px-4 text-center">
                                        @if ($item->stok_saldo == 0)
                                            <span class="px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-700 font-bold text-[10px]">Habis</span>
                                        @elseif ($item->stok_saldo <= $item->min_stok)
                                            <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-700 font-bold text-[10px]">Kritis</span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-700 font-bold text-[10px]">Aman</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-8 text-center text-slate-400">Tidak ada data stok ditemukan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    @elseif ($jenis === 'masuk')
                        <!-- Table Headers for Pembelian / Masuk -->
                        <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-[11px] font-bold border-b border-slate-100">
                            <tr>
                                <th class="py-3.5 px-4 w-12 text-center">No</th>
                                <th class="py-3.5 px-4">No. Dokumen / Faktur</th>
                                <th class="py-3.5 px-4">Tanggal Masuk</th>
                                <th class="py-3.5 px-4">Nama Barang (BMN)</th>
                                <th class="py-3.5 px-4 text-center">Jumlah Masuk</th>
                                <th class="py-3.5 px-4">Vendor / Keterangan</th>
                                <th class="py-3.5 px-4">Penerima</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse ($dataLaporan as $i => $item)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-3.5 px-4 text-center text-slate-400 font-medium">{{ $dataLaporan->firstItem() + $i }}</td>
                                    <td class="py-3.5 px-4 font-mono font-bold text-blue-600">{{ $item->no_dokumen ?? '-' }}</td>
                                    <td class="py-3.5 px-4 whitespace-nowrap text-slate-600">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="py-3.5 px-4 font-bold text-slate-900">{{ $item->barang?->deskripsi ?? 'Barang Telah Dihapus' }}</td>
                                    <td class="py-3.5 px-4 text-center">
                                        <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-bold border border-emerald-200">
                                            +{{ $item->jumlah }} {{ $item->barang?->satuan ?? 'Unit' }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-600">{{ $item->keterangan ?: '-' }}</td>
                                    <td class="py-3.5 px-4 text-slate-500">{{ $item->user?->name ?? 'Admin' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-slate-400">Tidak ada data transaksi masuk pada periode ini</td>
                                </tr>
                            @endforelse
                        </tbody>
                    @elseif ($jenis === 'keluar')
                        <!-- Table Headers for Bon / Keluar -->
                        <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-[11px] font-bold border-b border-slate-100">
                            <tr>
                                <th class="py-3.5 px-4 w-12 text-center">No</th>
                                <th class="py-3.5 px-4">No. Bon Barang</th>
                                <th class="py-3.5 px-4">Tanggal Keluar</th>
                                <th class="py-3.5 px-4">Nama Barang (BMN)</th>
                                <th class="py-3.5 px-4 text-center">Jumlah Keluar</th>
                                <th class="py-3.5 px-4">Seksi Pemohon</th>
                                <th class="py-3.5 px-4">Penerima</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse ($dataLaporan as $i => $item)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-3.5 px-4 text-center text-slate-400 font-medium">{{ $dataLaporan->firstItem() + $i }}</td>
                                    <td class="py-3.5 px-4 font-mono font-bold text-amber-600">{{ $item->no_dokumen ?? 'BON-'.$item->id }}</td>
                                    <td class="py-3.5 px-4 whitespace-nowrap text-slate-600">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="py-3.5 px-4 font-bold text-slate-900">{{ $item->barang?->deskripsi ?? 'Barang Telah Dihapus' }}</td>
                                    <td class="py-3.5 px-4 text-center">
                                        <span class="px-2.5 py-0.5 rounded-full bg-rose-50 text-rose-700 font-bold border border-rose-200">
                                            -{{ $item->jumlah }} {{ $item->barang?->satuan ?? 'Unit' }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-700 font-semibold">{{ $item->seksi_pemohon ?: 'Seksi Umum' }}</td>
                                    <td class="py-3.5 px-4 text-slate-600">{{ $item->penerima ?: ($item->user?->name ?? 'Pegawai BHP') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-slate-400">Tidak ada data transaksi pengeluaran pada periode ini</td>
                                </tr>
                            @endforelse
                        </tbody>
                    @else
                        <!-- Table Headers for Stock Opname -->
                        <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-[11px] font-bold border-b border-slate-100">
                            <tr>
                                <th class="py-3.5 px-4 w-12 text-center">No</th>
                                <th class="py-3.5 px-4">No. Berita Acara</th>
                                <th class="py-3.5 px-4">Waktu Audit</th>
                                <th class="py-3.5 px-4">Nama Barang</th>
                                <th class="py-3.5 px-4 text-center">Stok Sebelum</th>
                                <th class="py-3.5 px-4 text-center">Stok Fisik (Sesudah)</th>
                                <th class="py-3.5 px-4 text-center">Penyesuaian</th>
                                <th class="py-3.5 px-4">Alasan / Catatan Audit</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse ($dataLaporan as $i => $item)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-3.5 px-4 text-center text-slate-400 font-medium">{{ $dataLaporan->firstItem() + $i }}</td>
                                    <td class="py-3.5 px-4 font-mono font-bold text-indigo-600">{{ $item->no_dokumen ?? 'BA-OPNAME' }}</td>
                                    <td class="py-3.5 px-4 whitespace-nowrap text-slate-600">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="py-3.5 px-4 font-bold text-slate-900">{{ $item->barang?->deskripsi ?? 'Barang Telah Dihapus' }}</td>
                                    <td class="py-3.5 px-4 text-center text-slate-500">{{ $item->stok_sebelum }}</td>
                                    <td class="py-3.5 px-4 text-center font-bold text-slate-900">{{ $item->stok_sesudah }}</td>
                                    <td class="py-3.5 px-4 text-center">
                                        <span class="px-2.5 py-0.5 rounded-full font-bold text-xs {{ $item->jenis === 'MASUK' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                            {{ $item->jenis === 'MASUK' ? '+' : '-' }}{{ $item->jumlah }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-600">{{ $item->keterangan ?: 'Pencocokan saldo fisik' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-8 text-center text-slate-400">Belum ada riwayat audit stock opname pada rentang tanggal ini</td>
                                </tr>
                            @endforelse
                        </tbody>
                    @endif
                </table>
            </div>

            <!-- Pagination -->
            @if ($dataLaporan->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $dataLaporan->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
