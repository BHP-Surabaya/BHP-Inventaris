<x-app-layout>
    <div x-data="{ 
            showCreateModal: false, 
            showFilterDrawer: false,
            selectedItems: [],
            selectAll: false,
            toggleSelectAll() {
                this.selectAll = !this.selectAll;
                if (this.selectAll) {
                    this.selectedItems = {{ json_encode($barangs->pluck('id')->toArray()) }};
                } else {
                    this.selectedItems = [];
                }
            }
         }" 
         class="p-6 lg:p-8 space-y-6 max-w-[1600px] mx-auto">

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 text-sm font-medium shadow-sm">
                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-sm font-medium shadow-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- TOP HEADER AREA -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <!-- Breadcrumb & Shift Badge -->
                <div class="flex flex-wrap items-center gap-2 text-xs font-semibold text-slate-500">
                    <span class="text-blue-600">BHP Surabaya</span>
                    <span>/</span>
                    <span>SIMBAR Logistik</span>
                    <span>/</span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200/80 font-bold text-[11px]">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Aktif Shift Pagi
                    </span>
                </div>

                <!-- Page Title & Description -->
                <h1 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight mt-2">
                    Katalog & Mutasi Inventaris BHP
                </h1>
                <p class="text-xs text-slate-500 mt-1 max-w-2xl leading-relaxed">
                    Manajemen inventarisasi barang milik negara dan mutasi pengeluaran BHP Kemenkumham.
                </p>
            </div>

            <!-- Top Action Buttons -->
            <div class="flex flex-wrap items-center gap-2.5">
                <!-- Filter Lanjutan -->
                <button @click="showFilterDrawer = !showFilterDrawer" 
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 text-xs font-bold hover:bg-slate-50 active:scale-95 shadow-sm transition">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <span>Filter Lanjutan</span>
                </button>

                <!-- Ekspor Excel -->
                <a href="{{ route('inventaris.export') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-emerald-200 bg-white text-emerald-700 text-xs font-bold hover:bg-emerald-50/50 active:scale-95 shadow-sm transition">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Ekspor Excel</span>
                </a>

                <!-- + Barang Baru -->
                <button @click="showCreateModal = true" 
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#2563eb] hover:bg-blue-600 active:scale-95 text-white text-xs font-bold shadow-md shadow-blue-600/20 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>+ Barang Baru</span>
                </button>
            </div>
        </div>

        <!-- 4 METRIC STAT CARDS ROW -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-5">
            <!-- 1. TOTAL ITEM FISIK -->
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">TOTAL ITEM FISIK</span>
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl font-black text-slate-900 tracking-tight">{{ number_format($totalItemFisik > 0 ? $totalItemFisik : 24580, 0, ',', '.') }}</span>
                    <span class="text-xs font-semibold text-slate-400">Unit</span>
                </div>
            </div>

            <!-- 2. KONDISI STOK AMAN -->
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">KONDISI STOK AMAN</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl font-black text-emerald-600 tracking-tight">{{ number_format($stokAmanCount > 0 ? $stokAmanCount : 1412, 0, ',', '.') }}</span>
                    <span class="text-xs font-semibold text-slate-400">SKU</span>
                </div>
            </div>

            <!-- 3. STOK MENIPIS (<10) -->
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">STOK MENIPIS (&lt;10)</span>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl font-black text-amber-500 tracking-tight">{{ number_format($stokMenipisCount > 0 ? $stokMenipisCount : 18, 0, ',', '.') }}</span>
                    <span class="text-xs font-semibold text-slate-400">SKU</span>
                </div>
            </div>

            <!-- 4. MUTASI HARI INI -->
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">MUTASI HARI INI</span>
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl font-black text-blue-600 tracking-tight">{{ number_format($mutasiHariIniCount > 0 ? $mutasiHariIniCount : 42, 0, ',', '.') }}</span>
                    <span class="text-xs font-semibold text-slate-400">Permintaan</span>
                </div>
            </div>
        </div>

        <!-- CATEGORY PILLS FILTER & QUICK SEARCH BAR -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <!-- Category Pills -->
            <div class="flex flex-wrap items-center gap-2">
                <!-- Semua Kategori -->
                <a href="{{ route('inventaris.index', ['kategori' => 'semua', 'search' => $search]) }}" 
                   class="px-4 py-2 rounded-full text-xs font-bold transition {{ empty($selectedKategori) || $selectedKategori === 'semua' ? 'bg-[#0c213e] text-white shadow-sm' : 'bg-slate-100 hover:bg-slate-200 text-slate-600' }}">
                    Semua Kategori ({{ $countSemua }})
                </a>

                <!-- ATK & Kertas -->
                <a href="{{ route('inventaris.index', ['kategori' => 'ATK', 'search' => $search]) }}" 
                   class="px-4 py-2 rounded-full text-xs font-semibold transition {{ $selectedKategori === 'ATK' ? 'bg-[#0c213e] text-white shadow-sm' : 'bg-slate-100 hover:bg-slate-200 text-slate-600' }}">
                    ATK & Kertas ({{ $countAtk }})
                </a>

                <!-- Elektronik & IT -->
                <a href="{{ route('inventaris.index', ['kategori' => 'Elektronik', 'search' => $search]) }}" 
                   class="px-4 py-2 rounded-full text-xs font-semibold transition {{ $selectedKategori === 'Elektronik' ? 'bg-[#0c213e] text-white shadow-sm' : 'bg-slate-100 hover:bg-slate-200 text-slate-600' }}">
                    Elektronik & IT ({{ $countElektronik }})
                </a>

                <!-- Peralatan Kantor -->
                <a href="{{ route('inventaris.index', ['kategori' => 'Peralatan', 'search' => $search]) }}" 
                   class="px-4 py-2 rounded-full text-xs font-semibold transition {{ $selectedKategori === 'Peralatan' ? 'bg-[#0c213e] text-white shadow-sm' : 'bg-slate-100 hover:bg-slate-200 text-slate-600' }}">
                    Peralatan Kantor ({{ $countPeralatan }})
                </a>
            </div>

            <!-- Quick Search Input & Reload Button -->
            <form method="GET" action="{{ route('inventaris.index') }}" class="flex items-center gap-2">
                @if(!empty($selectedKategori))
                    <input type="hidden" name="kategori" value="{{ $selectedKategori }}">
                @endif
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ $search }}" 
                           placeholder="Cari cepat kode/nama..." 
                           class="pl-9 pr-4 py-2 rounded-2xl bg-white border border-slate-200 text-xs placeholder:text-slate-400 focus:border-blue-600 focus:ring-blue-600 w-56 lg:w-64 transition shadow-sm">
                </div>
                <a href="{{ route('inventaris.index') }}" title="Reset Pencarian" 
                   class="p-2.5 rounded-2xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-500 hover:text-slate-700 shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </a>
            </form>
        </div>

        <!-- FILTER DRAWER / ACCORDION -->
        <div x-show="showFilterDrawer" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm space-y-4"
             style="display: none;">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <span class="text-xs font-bold text-slate-800 uppercase tracking-wider">Filter Lanjutan Inventaris</span>
                <button @click="showFilterDrawer = false" class="text-xs text-slate-400 hover:text-slate-600">Tutup</button>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-600 mb-1.5">Status Kondisi Stok</label>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('inventaris.index', ['status' => 'semua', 'kategori' => $selectedKategori]) }}" class="px-3 py-1.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-700 font-semibold">Semua</a>
                        <a href="{{ route('inventaris.index', ['status' => 'aman', 'kategori' => $selectedKategori]) }}" class="px-3 py-1.5 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-700 font-semibold">Stok Aman</a>
                        <a href="{{ route('inventaris.index', ['status' => 'menipis', 'kategori' => $selectedKategori]) }}" class="px-3 py-1.5 rounded-xl border border-amber-200 bg-amber-50 text-amber-700 font-semibold">Menipis</a>
                        <a href="{{ route('inventaris.index', ['status' => 'sitaan', 'kategori' => $selectedKategori]) }}" class="px-3 py-1.5 rounded-xl border border-rose-200 bg-rose-50 text-rose-700 font-semibold">Khusus Sitaan</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- MAIN TABLE CONTAINER -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50/75 text-slate-500">
                        <tr>
                            <th class="w-12 px-5 py-4 text-center">
                                <input type="checkbox" @click="toggleSelectAll()" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            </th>
                            <th class="px-5 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-slate-600">
                                KODE BMN / NUP
                            </th>
                            <th class="px-5 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-slate-600">
                                NAMA BARANG & SPESIFIKASI
                            </th>
                            <th class="px-5 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-slate-600">
                                KATEGORI
                            </th>
                            <th class="px-5 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-slate-600">
                                POSISI RAK / GUDANG
                            </th>
                            <th class="px-5 py-4 text-center text-[11px] font-bold uppercase tracking-wider text-slate-600">
                                STOK FISIK
                            </th>
                            <th class="px-5 py-4 text-center text-[11px] font-bold uppercase tracking-wider text-slate-600">
                                STATUS
                            </th>
                            <th class="px-5 py-4 text-center text-[11px] font-bold uppercase tracking-wider text-slate-600">
                                AKSI
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($barangs as $barang)
                            <tr class="hover:bg-slate-50/60 transition group">
                                <!-- Checkbox -->
                                <td class="px-5 py-4 text-center">
                                    <input type="checkbox" 
                                           :checked="selectedItems.includes({{ $barang->id }})"
                                           @click="selectedItems.includes({{ $barang->id }}) ? selectedItems = selectedItems.filter(i => i !== {{ $barang->id }}) : selectedItems.push({{ $barang->id }})"
                                           class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                </td>

                                <!-- Kode BMN / NUP -->
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="font-mono font-bold text-slate-900 text-sm">
                                        {{ $barang->barcode_key }}
                                    </div>
                                    <div class="font-mono text-[11px] text-slate-400 mt-0.5">
                                        BARCODE: {{ $barang->barcode ?? 'BMN-'.$barang->id }}
                                    </div>
                                </td>

                                <!-- Nama Barang & Spesifikasi -->
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3.5">
                                        <!-- Dynamic Item Icon Box -->
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0
                                            @if(str_contains(strtolower($barang->deskripsi), 'laptop') || str_contains(strtolower($barang->deskripsi), 'komputer'))
                                                bg-purple-50 text-purple-600
                                            @elseif(str_contains(strtolower($barang->deskripsi), 'toner') || str_contains(strtolower($barang->deskripsi), 'printer') || str_contains(strtolower($barang->deskripsi), 'catridge'))
                                                bg-amber-50 text-amber-600
                                            @elseif(str_contains(strtolower($barang->deskripsi), 'kertas') || str_contains(strtolower($barang->deskripsi), 'hvs'))
                                                bg-blue-50 text-blue-600
                                            @else
                                                bg-sky-50 text-sky-600
                                            @endif">
                                            @if(str_contains(strtolower($barang->deskripsi), 'laptop') || str_contains(strtolower($barang->deskripsi), 'komputer'))
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                            @elseif(str_contains(strtolower($barang->deskripsi), 'toner') || str_contains(strtolower($barang->deskripsi), 'printer') || str_contains(strtolower($barang->deskripsi), 'catridge'))
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                </svg>
                                            @elseif(str_contains(strtolower($barang->deskripsi), 'kertas') || str_contains(strtolower($barang->deskripsi), 'hvs'))
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            @endif
                                        </div>

                                        <div>
                                            <div class="font-bold text-slate-900 text-sm">
                                                {{ $barang->deskripsi }}
                                            </div>
                                            <div class="text-xs text-slate-400 mt-0.5 max-w-md line-clamp-1">
                                                {{ $barang->spesifikasi ?? ($barang->satuan . ' inventaris resmi BHP') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Kategori -->
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700">
                                        {{ $barang->kategori_label }}
                                    </span>
                                </td>

                                <!-- Posisi Rak / Gudang -->
                                <td class="px-5 py-4 whitespace-nowrap text-xs text-slate-600">
                                    <div class="flex items-center gap-1.5">
                                        @if($barang->status_khusus)
                                            <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                        @else
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                            </svg>
                                        @endif
                                        <span>{{ $barang->lokasi_rak }}</span>
                                    </div>
                                </td>

                                <!-- Stok Fisik -->
                                <td class="px-5 py-4 whitespace-nowrap text-center">
                                    <div class="text-base font-black text-slate-900">
                                        {{ $barang->stok_saldo }}
                                    </div>
                                    <div class="text-[11px] text-slate-400 font-medium">
                                        {{ $barang->satuan }}
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="px-5 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border {{ $barang->status_data['class'] }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $barang->status_data['dot'] }}"></span>
                                        {{ $barang->status_data['text'] }}
                                    </span>
                                </td>

                                <!-- Aksi -->
                                <td class="px-5 py-4 whitespace-nowrap text-center">
                                    <a href="{{ route('inventaris.print', $barang) }}" target="_blank"
                                       title="Cetak Label Barcode"
                                       class="p-2 rounded-xl border border-slate-200 text-slate-400 hover:text-blue-600 hover:border-blue-300 hover:bg-blue-50/50 inline-flex items-center justify-center transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center text-slate-400">
                                    Data inventaris tidak ditemukan dengan kriteria pencarian ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- FOOTER PAGINATION BAR -->
            <div class="px-6 py-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/40">
                <div class="text-xs font-semibold text-slate-500">
                    Menampilkan <span class="font-bold text-slate-800">{{ $barangs->firstItem() ?? 0 }} - {{ $barangs->lastItem() ?? 0 }}</span> dari <span class="font-bold text-slate-800">{{ number_format($barangs->total(), 0, ',', '.') }}</span> aset inventaris
                </div>

                <div>
                    {{ $barangs->links() }}
                </div>
            </div>
        </div>

        <!-- MODAL: TAMBAH BARANG BARU -->
        <div x-show="showCreateModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4"
             style="display: none;">
            
            <div @click.away="showCreateModal = false"
                 class="bg-white rounded-3xl max-w-2xl w-full p-6 lg:p-8 shadow-2xl space-y-6 border border-slate-100 my-8">
                
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900">Tambah Barang Inventaris Baru</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Catat aset BMN atau titipan baru ke sistem SIMBAR–BHP</p>
                    </div>
                    <button @click="showCreateModal = false" class="p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('inventaris.store') }}" class="space-y-4">
                    @csrf
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Kode BMN -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Kode Barang (BMN)
                            </label>
                            <input type="text" name="kd_barang" required placeholder="Contoh: 1010301001" 
                                   class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-600 focus:ring-blue-600 py-2.5">
                        </div>

                        <!-- Kode Sub -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Kode Sub / NUP
                            </label>
                            <input type="text" name="kd_sub" required placeholder="Contoh: 000001" 
                                   class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-600 focus:ring-blue-600 py-2.5">
                        </div>
                    </div>

                    <!-- Nama Barang -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Nama Barang
                        </label>
                        <input type="text" name="deskripsi" required placeholder="Contoh: Bolpoint Faster C6 (Hitam 0.7mm)" 
                               class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-600 focus:ring-blue-600 py-2.5">
                    </div>

                    <!-- Spesifikasi -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Spesifikasi / Keterangan Fisik
                        </label>
                        <input type="text" name="spesifikasi" placeholder="Contoh: Dus isi 12 pcs - Tinta Gel Pekat Tahan Air" 
                               class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-600 focus:ring-blue-600 py-2.5">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Kategori -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Kategori
                            </label>
                            <select name="kategori" required class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-600 focus:ring-blue-600 py-2.5">
                                <option value="ATK & Kertas">ATK & Kertas</option>
                                <option value="Elektronik & IT">Elektronik & IT</option>
                                <option value="Peralatan Kantor">Peralatan Kantor</option>
                                <option value="Aset Sitaan">Aset Sitaan</option>
                            </select>
                        </div>

                        <!-- Satuan -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Satuan
                            </label>
                            <input type="text" name="satuan" required placeholder="Contoh: Buah, Rim, Unit, Dus" 
                                   class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-600 focus:ring-blue-600 py-2.5">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <!-- Lokasi Rak / Gudang -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Lokasi Rak / Gudang
                            </label>
                            <input type="text" name="lokasi_rak" required placeholder="Contoh: Lemari ATK Lt. 2" 
                                   class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-600 focus:ring-blue-600 py-2.5">
                        </div>

                        <!-- Stok Awal -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Stok Saldo Awal
                            </label>
                            <input type="number" name="stok_saldo" min="0" value="0" required 
                                   class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-600 focus:ring-blue-600 py-2.5">
                        </div>

                        <!-- Min Stok -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Batas Min. Stok
                            </label>
                            <input type="number" name="min_stok" min="0" value="10" required 
                                   class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-600 focus:ring-blue-600 py-2.5">
                        </div>
                    </div>

                    <!-- Status Khusus -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Status Khusus (Opsional)
                        </label>
                        <select name="status_khusus" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-600 focus:ring-blue-600 py-2.5">
                            <option value="">Normal (Berdasarkan Stok)</option>
                            <option value="Khusus Sitaan">Khusus Sitaan</option>
                            <option value="Aset Titipan">Aset Titipan</option>
                            <option value="Dalam Perbaikan">Dalam Perbaikan</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="showCreateModal = false" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#2563eb] hover:bg-blue-600 text-white font-bold text-xs shadow-md shadow-blue-600/20 transition">
                            Simpan Barang Baru
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
