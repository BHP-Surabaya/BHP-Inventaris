<x-app-layout>
    <div x-data="{ 
            showToast: false, 
            toastMessage: '' 
         }" 
         class="p-6 sm:p-8 lg:p-8 space-y-6 lg:space-y-7 max-w-7xl mx-auto">

        <!-- Toast Notification -->
        <div x-show="showToast" 
             x-cloak
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-2"
             class="fixed bottom-6 right-6 z-50 bg-slate-900 text-white px-5 py-3.5 rounded-2xl shadow-2xl flex items-center gap-3 border border-slate-700 text-sm font-medium">
            <div class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></div>
            <span x-text="toastMessage"></span>
        </div>

        <!-- 1. HEADER HALAMAN: BERSIH & RAPI (SINKRON DENGAN DASHBOARD & CETAK LABEL) -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200/80">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-blue-700 uppercase tracking-wider">
                    <span>Balai Harta Peninggalan Surabaya</span>
                    <span class="text-slate-300">•</span>
                    <span class="text-slate-500 font-medium normal-case">Kemenkumham RI</span>
                </div>
                <h1 class="text-2xl lg:text-3xl font-bold text-slate-900 tracking-tight mt-1">Pusat Cetak Laporan & Rekapitulasi</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Penerbitan dokumen resmi, mutasi persediaan, berita acara stock opname, dan ekspor BMN.</p>
            </div>

            <!-- Tombol Aksi Header -->
            <div class="flex items-center gap-2.5">
                <a href="{{ route('laporan.export', request()->query()) }}" 
                   class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold shadow-2xs transition">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.5L19 7.5V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Unduh Excel / CSV</span>
                </a>

                <a href="{{ route('laporan.print', request()->query()) }}" 
                   target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#2563eb] hover:bg-blue-600 active:scale-95 text-white text-xs font-bold shadow-sm shadow-blue-500/20 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>Cetak Lembar Resmi (PDF)</span>
                </a>
            </div>
        </div>

        <!-- 2. KARTU STATISTIK (SINKRON GAYA DASHBOARD) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 lg:gap-6">
            <!-- Total Barang BMN -->
            <div class="bg-white rounded-3xl p-5 lg:p-6 border border-slate-100 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold tracking-wider text-slate-500 uppercase">KATALOG BARANG</span>
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight">{{ number_format($totalBarang) }}</span>
                    <span class="text-xs font-medium text-slate-400">Item Terdaftar</span>
                </div>
            </div>

            <!-- Total Fisik di Gudang -->
            <div class="bg-white rounded-3xl p-5 lg:p-6 border border-slate-100 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold tracking-wider text-slate-500 uppercase">TOTAL FISIK GUDANG</span>
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl lg:text-4xl font-extrabold text-indigo-600 tracking-tight">{{ number_format($totalStokFisik) }}</span>
                    <span class="text-xs font-medium text-slate-400">Unit Barang</span>
                </div>
            </div>

            <!-- Masuk Periode Ini -->
            <div class="bg-white rounded-3xl p-5 lg:p-6 border border-slate-100 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold tracking-wider text-slate-500 uppercase">MASUK (PERIODE INI)</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl lg:text-4xl font-extrabold text-emerald-600 tracking-tight">+{{ number_format($totalMasukPeriode) }}</span>
                    <span class="text-xs font-medium text-slate-400">Unit Barang</span>
                </div>
            </div>

            <!-- Keluar Periode Ini -->
            <div class="bg-white rounded-3xl p-5 lg:p-6 border border-slate-100 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold tracking-wider text-slate-500 uppercase">KELUAR (PERIODE INI)</span>
                    <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl lg:text-4xl font-extrabold text-rose-600 tracking-tight">-{{ number_format($totalKeluarPeriode) }}</span>
                    <span class="text-xs font-medium text-slate-400">Unit Distribusi</span>
                </div>
            </div>
        </div>

        <!-- 3. PARAMETER & FILTER LAPORAN -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-5">
            <!-- Header Filter Section -->
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Kategori & Parameter Laporan</h2>
                        <p class="text-[11px] text-slate-400">Pilih jenis rekapitulasi data dan rentang tanggal pelaporan.</p>
                    </div>
                </div>
                <span class="text-[11px] font-mono font-bold text-blue-700 bg-blue-50 px-2.5 py-0.5 rounded-full uppercase">
                    Mode: {{ $jenis }}
                </span>
            </div>

            <!-- Tabs Pilihan Jenis Laporan -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                <!-- Tab 1: Stok -->
                <a href="{{ route('laporan.index', ['jenis' => 'stok', 'kategori' => $selectedKategori]) }}" 
                   class="p-3 rounded-2xl transition flex flex-col justify-between border {{ $jenis === 'stok' ? 'bg-[#0c213e] text-white border-[#0c213e] shadow-sm ring-2 ring-blue-500/20' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200/80' }}">
                    <div class="flex items-center justify-between w-full">
                        <svg class="w-4 h-4 {{ $jenis === 'stok' ? 'text-blue-400' : 'text-slate-500' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        @if ($jenis === 'stok')
                            <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-blue-500 text-white">Aktif</span>
                        @endif
                    </div>
                    <div class="mt-2.5">
                        <div class="text-xs font-bold leading-tight">Saldo & Stok BMN</div>
                        <div class="text-[10px] mt-0.5 {{ $jenis === 'stok' ? 'text-slate-300' : 'text-slate-400' }}">Katalog & sisa saldo</div>
                    </div>
                </a>

                <!-- Tab 2: Pembelian / Masuk -->
                <a href="{{ route('laporan.index', ['jenis' => 'masuk', 'dari_tanggal' => $dariTanggal, 'sampai_tanggal' => $sampaiTanggal]) }}" 
                   class="p-3 rounded-2xl transition flex flex-col justify-between border {{ $jenis === 'masuk' ? 'bg-[#0c213e] text-white border-[#0c213e] shadow-sm ring-2 ring-blue-500/20' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200/80' }}">
                    <div class="flex items-center justify-between w-full">
                        <svg class="w-4 h-4 {{ $jenis === 'masuk' ? 'text-emerald-400' : 'text-slate-500' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="8" cy="21" r="1"/>
                            <circle cx="19" cy="21" r="1"/>
                            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                        </svg>
                        @if ($jenis === 'masuk')
                            <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-emerald-500 text-white">Aktif</span>
                        @endif
                    </div>
                    <div class="mt-2.5">
                        <div class="text-xs font-bold leading-tight">Barang Masuk</div>
                        <div class="text-[10px] mt-0.5 {{ $jenis === 'masuk' ? 'text-slate-300' : 'text-slate-400' }}">Faktur & pengadaan</div>
                    </div>
                </a>

                <!-- Tab 3: Bon Barang / Keluar -->
                <a href="{{ route('laporan.index', ['jenis' => 'keluar', 'dari_tanggal' => $dariTanggal, 'sampai_tanggal' => $sampaiTanggal]) }}" 
                   class="p-3 rounded-2xl transition flex flex-col justify-between border {{ $jenis === 'keluar' ? 'bg-[#0c213e] text-white border-[#0c213e] shadow-sm ring-2 ring-blue-500/20' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200/80' }}">
                    <div class="flex items-center justify-between w-full">
                        <svg class="w-4 h-4 {{ $jenis === 'keluar' ? 'text-amber-400' : 'text-slate-500' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.5L19 7.5V19a2 2 0 0 1-2 2Z"/>
                        </svg>
                        @if ($jenis === 'keluar')
                            <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-amber-500 text-white">Aktif</span>
                        @endif
                    </div>
                    <div class="mt-2.5">
                        <div class="text-xs font-bold leading-tight">Bon Barang (Keluar)</div>
                        <div class="text-[10px] mt-0.5 {{ $jenis === 'keluar' ? 'text-slate-300' : 'text-slate-400' }}">Distribusi ke seksi</div>
                    </div>
                </a>

                <!-- Tab 4: Stock Opname -->
                <a href="{{ route('laporan.index', ['jenis' => 'opname', 'dari_tanggal' => $dariTanggal, 'sampai_tanggal' => $sampaiTanggal]) }}" 
                   class="p-3 rounded-2xl transition flex flex-col justify-between border {{ $jenis === 'opname' ? 'bg-[#0c213e] text-white border-[#0c213e] shadow-sm ring-2 ring-blue-500/20' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200/80' }}">
                    <div class="flex items-center justify-between w-full">
                        <svg class="w-4 h-4 {{ $jenis === 'opname' ? 'text-indigo-400' : 'text-slate-500' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 11l3 3L22 4"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                        </svg>
                        @if ($jenis === 'opname')
                            <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-indigo-500 text-white">Aktif</span>
                        @endif
                    </div>
                    <div class="mt-2.5">
                        <div class="text-xs font-bold leading-tight">Berita Acara Opname</div>
                        <div class="text-[10px] mt-0.5 {{ $jenis === 'opname' ? 'text-slate-300' : 'text-slate-400' }}">Hasil audit fisik</div>
                    </div>
                </a>
            </div>

            <!-- Form Filter Parameter -->
            <form method="GET" action="{{ route('laporan.index') }}" class="pt-2 border-t border-slate-100 flex flex-wrap items-end gap-3 sm:gap-4">
                <input type="hidden" name="jenis" value="{{ $jenis }}" />

                @if ($jenis !== 'stok')
                    <div class="flex-1 min-w-[160px] sm:min-w-[200px]">
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">Dari Tanggal</label>
                        <input type="date" name="dari_tanggal" value="{{ $dariTanggal }}" 
                               class="w-full rounded-xl border-slate-200 text-xs font-medium focus:border-blue-600 focus:ring-blue-600 py-2.5" />
                    </div>

                    <div class="flex-1 min-w-[160px] sm:min-w-[200px]">
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">Sampai Tanggal</label>
                        <input type="date" name="sampai_tanggal" value="{{ $sampaiTanggal }}" 
                               class="w-full rounded-xl border-slate-200 text-xs font-medium focus:border-blue-600 focus:ring-blue-600 py-2.5" />
                    </div>
                @else
                    <div class="flex-1 min-w-[220px]">
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">Filter Kategori Barang</label>
                        <select name="kategori" 
                                class="w-full rounded-xl border-slate-200 text-xs font-medium focus:border-blue-600 focus:ring-blue-600 py-2.5">
                            <option value="semua">Semua Kategori</option>
                            @foreach ($kategoris as $kat)
                                <option value="{{ $kat }}" {{ $selectedKategori === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="flex items-center gap-2">
                    <button type="submit" 
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold shadow-sm transition active:scale-95">
                        <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        <span>Terapkan Filter</span>
                    </button>
                    <a href="{{ route('laporan.index', ['jenis' => $jenis]) }}" 
                       class="inline-flex items-center gap-1.5 px-3 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 text-xs font-medium transition"
                       title="Reset Filter">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span>Reset</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- 4. PRATINJAU TABEL DATA RESMI -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <span class="text-sm font-bold text-slate-900">Pratinjau Lembar Data</span>
                    <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 text-[11px] font-bold uppercase tracking-wider">
                        {{ $jenis === 'stok' ? 'Saldo Stok' : ($jenis === 'masuk' ? 'Barang Masuk' : ($jenis === 'keluar' ? 'Bon Keluar' : 'Stock Opname')) }}
                    </span>
                </div>
                <div class="text-xs text-slate-500 font-medium">
                    Total: <strong class="text-slate-800">{{ number_format($dataLaporan->total()) }}</strong> baris data
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    @if ($jenis === 'stok')
                        <!-- Table Headers for Stok -->
                        <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider text-[11px] font-bold border-b border-slate-100">
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
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="py-3.5 px-4 text-center text-slate-400 font-medium">{{ $dataLaporan->firstItem() + $i }}</td>
                                    <td class="py-3.5 px-4 font-mono font-bold text-blue-600">{{ $item->barcode_key }}</td>
                                    <td class="py-3.5 px-4 font-bold text-slate-900">{{ $item->deskripsi }}</td>
                                    <td class="py-3.5 px-4">
                                        <span class="px-2 py-0.5 rounded-lg bg-slate-100 text-slate-600 font-medium text-[11px]">{{ $item->kategori ?? 'Umum' }}</span>
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-600">{{ $item->lokasi_rak }}</td>
                                    <td class="py-3.5 px-4 text-center font-bold text-slate-900">{{ $item->stok_saldo }} {{ $item->satuan }}</td>
                                    <td class="py-3.5 px-4 text-center text-slate-500">{{ $item->min_stok }} {{ $item->satuan }}</td>
                                    <td class="py-3.5 px-4 text-center">
                                        @if ($item->stok_saldo == 0)
                                            <span class="px-2.5 py-0.5 rounded-full bg-rose-50 text-rose-700 font-bold text-[10px] border border-rose-200">Habis</span>
                                        @elseif ($item->stok_saldo <= $item->min_stok)
                                            <span class="px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 font-bold text-[10px] border border-amber-200">Kritis</span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-bold text-[10px] border border-emerald-200">Aman</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-12 text-center text-slate-400">
                                        <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                        <span>Tidak ada data stok ditemukan</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    @elseif ($jenis === 'masuk')
                        <!-- Table Headers for Pembelian / Masuk -->
                        <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider text-[11px] font-bold border-b border-slate-100">
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
                                <tr class="hover:bg-slate-50/70 transition-colors">
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
                                    <td colspan="7" class="py-12 text-center text-slate-400">
                                        <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <circle cx="8" cy="21" r="1"/>
                                            <circle cx="19" cy="21" r="1"/>
                                            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                                        </svg>
                                        <span>Tidak ada data transaksi masuk pada periode ini</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    @elseif ($jenis === 'keluar')
                        <!-- Table Headers for Bon / Keluar -->
                        <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider text-[11px] font-bold border-b border-slate-100">
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
                                <tr class="hover:bg-slate-50/70 transition-colors">
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
                                    <td colspan="7" class="py-12 text-center text-slate-400">
                                        <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.5L19 7.5V19a2 2 0 0 1-2 2Z"/>
                                        </svg>
                                        <span>Tidak ada data transaksi pengeluaran pada periode ini</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    @else
                        <!-- Table Headers for Stock Opname -->
                        <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider text-[11px] font-bold border-b border-slate-100">
                            <tr>
                                <th class="py-3.5 px-4 w-12 text-center">No</th>
                                <th class="py-3.5 px-4">No. Berita Acara</th>
                                <th class="py-3.5 px-4">Waktu Audit</th>
                                <th class="py-3.5 px-4">Nama Barang</th>
                                <th class="py-3.5 px-4 text-center">Stok Sebelum</th>
                                <th class="py-3.5 px-4 text-center">Stok Fisik</th>
                                <th class="py-3.5 px-4 text-center">Penyesuaian</th>
                                <th class="py-3.5 px-4">Alasan / Catatan Audit</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse ($dataLaporan as $i => $item)
                                <tr class="hover:bg-slate-50/70 transition-colors">
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
                                    <td colspan="8" class="py-12 text-center text-slate-400">
                                        <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 11l3 3L22 4"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                                        </svg>
                                        <span>Belum ada riwayat audit stock opname pada rentang tanggal ini</span>
                                    </td>
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
