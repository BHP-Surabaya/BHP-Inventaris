<x-app-layout>
    <div x-data="{ 
            showRestockModal: false, 
            restockItem: { id: null, deskripsi: '', satuan: '', barcode: '' },
            toastMessage: '',
            showToast: false,
            triggerToast(msg) {
                this.toastMessage = msg;
                this.showToast = true;
                setTimeout(() => this.showToast = false, 3500);
            },
            openRestock(id, deskripsi, satuan, barcode) {
                this.restockItem = { id: id, deskripsi: deskripsi, satuan: satuan, barcode: barcode };
                this.showRestockModal = true;
            }
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

        <!-- 1. HEADER UTAMA: BERSIH & RAPI -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200/80">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-blue-700 uppercase tracking-wider">
                    <span>Balai Harta Peninggalan Surabaya</span>
                    <span class="text-slate-300">•</span>
                    <span class="text-slate-500 font-medium normal-case">Kemenkumham RI</span>
                </div>
                <h1 class="text-2xl lg:text-3xl font-bold text-slate-900 tracking-tight mt-1">Dashboard Logistik & Persediaan</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Pusat kendali inventaris, sirkulasi mutasi, dan ketersediaan barang BMN.</p>
            </div>
            
            <!-- Tanggal Hari Ini -->
            <div class="inline-flex items-center gap-2.5 px-3.5 py-2 rounded-xl bg-slate-100/80 border border-slate-200 text-slate-700 text-xs font-semibold shadow-2xs">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                <span>{{ now()->translatedFormat('l, d F Y') }}</span>
            </div>
        </div>

        <!-- 2. KARTU STATISTIK -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 lg:gap-6">
            <!-- Total Aset Terdaftar -->
            <div class="bg-white rounded-3xl p-5 lg:p-6 border border-slate-100 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold tracking-wider text-slate-500 uppercase">TOTAL BARANG BMN</span>
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight">{{ number_format($totalAset) }}</span>
                    <span class="text-xs font-medium text-slate-400">Item Terdaftar</span>
                </div>
            </div>

            <!-- Masuk Hari Ini -->
            <div class="bg-white rounded-3xl p-5 lg:p-6 border border-slate-100 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold tracking-wider text-slate-500 uppercase">MASUK HARI INI</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl lg:text-4xl font-extrabold text-emerald-600 tracking-tight">{{ number_format($barangMasukHariIni) }}</span>
                    <span class="text-xs font-medium text-slate-400">Unit Barang</span>
                </div>
            </div>

            <!-- Keluar Hari Ini -->
            <div class="bg-white rounded-3xl p-5 lg:p-6 border border-slate-100 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold tracking-wider text-slate-500 uppercase">KELUAR HARI INI</span>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight">{{ number_format($barangKeluarHariIni) }}</span>
                    <span class="text-xs font-medium text-slate-400">Unit Terdistribusi</span>
                </div>
            </div>

            <!-- Stok Menipis / Perlu Restock -->
            <div class="bg-white rounded-3xl p-5 lg:p-6 border border-rose-100 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold tracking-wider text-rose-600 uppercase">STOK MENIPIS</span>
                    <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl lg:text-4xl font-extrabold text-rose-600 tracking-tight">{{ number_format($stokKritisCount) }}</span>
                    <span class="text-xs font-medium text-rose-500">Perlu Restock</span>
                </div>
            </div>
        </div>

        <!-- 3. PINTASAN MENU UTAMA -->
        <div class="space-y-3">
            <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider px-1">Aksi & Pintasan Menu</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 lg:gap-6">
                <!-- Pintasan 1: Pembelian -->
                <a href="{{ route('pembelian.index') }}" 
                   class="bg-white hover:bg-blue-50/50 p-4 lg:p-5 rounded-2xl border border-slate-100 hover:border-blue-200 shadow-sm hover:shadow-md transition group flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 group-hover:bg-[#2563eb] group-hover:text-white flex items-center justify-center flex-shrink-0 transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="8" cy="21" r="1"/>
                            <circle cx="19" cy="21" r="1"/>
                            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <div class="text-sm font-bold text-slate-900 group-hover:text-blue-700 transition">Pembelian Baru</div>
                        <div class="text-xs text-slate-400 mt-0.5 truncate">Pencatatan barang masuk BMN</div>
                    </div>
                </a>

                <!-- Pintasan 2: Stock Opname -->
                <a href="{{ route('stock-opname.index') }}" 
                   class="bg-white hover:bg-emerald-50/50 p-4 lg:p-5 rounded-2xl border border-slate-100 hover:border-emerald-200 shadow-sm hover:shadow-md transition group flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white flex items-center justify-center flex-shrink-0 transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
                            <path d="m9 14 2 2 4-4"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <div class="text-sm font-bold text-slate-900 group-hover:text-emerald-700 transition">Stock Opname</div>
                        <div class="text-xs text-slate-400 mt-0.5 truncate">Pengecekan fisik & selisih</div>
                    </div>
                </a>

                <!-- Pintasan 3: Cetak Label Barcode -->
                <a href="{{ route('cetak-label.index') }}" 
                   class="bg-white hover:bg-amber-50/50 p-4 lg:p-5 rounded-2xl border border-slate-100 hover:border-amber-200 shadow-sm hover:shadow-md transition group flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 group-hover:bg-amber-600 group-hover:text-white flex items-center justify-center flex-shrink-0 transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2"/>
                            <path d="M7 8v8M11 8v8M14 8v8M17 8v8"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <div class="text-sm font-bold text-slate-900 group-hover:text-amber-700 transition">Cetak Label</div>
                        <div class="text-xs text-slate-400 mt-0.5 truncate">Stiker barcode thermal 58mm</div>
                    </div>
                </a>

                <!-- Pintasan 4: Cetak Laporan -->
                <a href="{{ route('laporan.index') }}" 
                   class="bg-white hover:bg-indigo-50/50 p-4 lg:p-5 rounded-2xl border border-slate-100 hover:border-indigo-200 shadow-sm hover:shadow-md transition group flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white flex items-center justify-center flex-shrink-0 transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <div class="text-sm font-bold text-slate-900 group-hover:text-indigo-700 transition">Cetak Laporan</div>
                        <div class="text-xs text-slate-400 mt-0.5 truncate">Rekap mutasi & PDF BAST</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- 4. BAGIAN BAWAH: 2 KOLOM SEIMBANG -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-7 lg:gap-8 items-stretch">
            
            <!-- KOLOM KIRI: RIWAYAT MUTASI TERKINI (col-span-7) -->
            <div class="lg:col-span-7 flex flex-col">
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm flex flex-col h-full overflow-hidden">
                    <div class="p-6 lg:p-7 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-2.5 h-2.5 rounded-full bg-blue-600"></div>
                            <div>
                                <h2 class="text-sm font-bold text-slate-900">Riwayat Mutasi Terkini</h2>
                                <p class="text-xs text-slate-400 mt-0.5">Sirkulasi transaksi masuk dan keluar barang terbaru</p>
                            </div>
                        </div>
                        <a href="{{ route('laporan.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline inline-flex items-center gap-1.5">
                            <span>Laporan Lengkap</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>

                    @if ($historiTransaksi->count() > 0)
                        <div class="overflow-x-auto flex-1 p-2">
                            <table class="min-w-full divide-y divide-slate-100 text-xs">
                                <thead class="bg-slate-50/70 text-slate-500 font-semibold uppercase tracking-wider">
                                    <tr>
                                        <th class="px-5 py-3.5 text-left">Waktu</th>
                                        <th class="px-5 py-3.5 text-left">Jenis</th>
                                        <th class="px-5 py-3.5 text-left">Nama Barang</th>
                                        <th class="px-5 py-3.5 text-center">Jumlah</th>
                                        <th class="px-5 py-3.5 text-left">Pemohon</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    @foreach ($historiTransaksi as $mutasi)
                                        <tr class="hover:bg-slate-50/60 transition">
                                            <td class="px-5 py-3.5 text-slate-500 whitespace-nowrap">
                                                {{ $mutasi->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-5 py-3.5 whitespace-nowrap">
                                                @if ($mutasi->jenis === 'MASUK')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px]">
                                                        MASUK
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-bold bg-amber-50 text-amber-700 border border-amber-200 text-[10px]">
                                                        KELUAR
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-5 py-3.5 text-slate-900 font-medium">
                                                <div class="font-semibold text-slate-900">{{ $mutasi->barang->deskripsi ?? '-' }}</div>
                                            </td>
                                            <td class="px-5 py-3.5 text-center font-bold text-slate-900 whitespace-nowrap">
                                                {{ number_format($mutasi->jumlah) }} {{ $mutasi->barang->satuan ?? '' }}
                                            </td>
                                            <td class="px-5 py-3.5 text-slate-600">
                                                {{ $mutasi->seksi_pemohon ?? $mutasi->penerima ?? '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <!-- TAMPILAN LEGA BERSIH -->
                        <div class="flex-1 flex flex-col items-center justify-center p-12 lg:p-16 text-center min-h-[340px]">
                            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4 ring-8 ring-blue-50/40">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                            </div>
                            <h3 class="text-base font-bold text-slate-800">Belum Ada Transaksi Mutasi</h3>
                            <p class="text-xs text-slate-400 mt-2 max-w-sm leading-relaxed">
                                Transaksi barang masuk dari pembelian atau penyesuaian stock opname akan otomatis tercatat di sini.
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- KOLOM KANAN: PERHATIAN STOK MENIPIS (col-span-5) -->
            <div class="lg:col-span-5 flex flex-col">
                <div class="bg-white rounded-3xl p-6 lg:p-7 border border-slate-100 shadow-sm flex flex-col h-full space-y-4">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-2.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-pulse"></span>
                            <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Perlu Restock Segera</h2>
                        </div>
                        <span class="text-[11px] font-semibold text-rose-600 bg-rose-50 px-2.5 py-0.5 rounded-full border border-rose-100">
                            {{ $stokKritisCount }} item
                        </span>
                    </div>

                    <div class="space-y-3.5 flex-1">
                        @forelse ($criticalItems as $item)
                            <div class="p-4 rounded-2xl border border-slate-100/90 hover:border-slate-200 bg-slate-50/60 hover:bg-slate-50 transition flex items-center justify-between gap-4">
                                <div class="min-w-0 flex-1">
                                    <div class="font-bold text-xs text-slate-900 truncate" title="{{ $item->deskripsi }}">
                                        {{ $item->deskripsi }}
                                    </div>
                                    <div class="text-[11px] text-slate-400 mt-1 flex items-center gap-2.5">
                                        <span class="text-slate-500">Sisa: <strong class="text-rose-600 font-bold">{{ $item->stok_saldo }}</strong> {{ $item->satuan }}</span>
                                        <span class="text-slate-300">•</span>
                                        <span>Min: {{ $item->min_stok }}</span>
                                    </div>
                                </div>
                                <button @click="openRestock({{ $item->id }}, '{{ addslashes($item->deskripsi) }}', '{{ $item->satuan }}', '{{ $item->barcode_key }}')"
                                        class="inline-flex items-center px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-xs active:scale-95 transition flex-shrink-0">
                                    <span>+ Restock</span>
                                </button>
                            </div>
                        @empty
                            <div class="py-10 text-center text-xs text-slate-400">
                                Semua stok persediaan dalam batas aman.
                            </div>
                        @endforelse
                    </div>

                    <div class="pt-4 text-center border-t border-slate-100">
                        <a href="{{ route('stock-opname.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline inline-flex items-center gap-1.5">
                            <span>Periksa Seluruh Stok Fisik</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <!-- MODAL: QUICK RESTOCK (STOK MASUK) -->
        <div x-show="showRestockModal" 
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            
            <div @click.away="showRestockModal = false"
                 class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-5 border border-slate-100">
                
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Restock Barang Masuk</h3>
                        <p class="text-xs text-slate-400">Tambah persediaan fisik inventaris BHP Surabaya</p>
                    </div>
                    <button @click="showRestockModal = false" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('inventaris.masuk') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="barang_id" :value="restockItem.id">

                    <!-- Target Item Info Box -->
                    <div class="bg-blue-50/70 border border-blue-100 rounded-xl p-3">
                        <div class="text-[11px] font-mono text-blue-700 font-bold" x-text="restockItem.barcode"></div>
                        <div class="font-bold text-slate-900 text-xs mt-0.5" x-text="restockItem.deskripsi"></div>
                    </div>

                    <!-- Jumlah Masuk -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Jumlah Restock (<span x-text="restockItem.satuan"></span>)
                        </label>
                        <input type="number" name="jumlah" min="1" required placeholder="Contoh: 10" 
                               class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-600 focus:ring-blue-600 py-2">
                    </div>

                    <!-- Nomor Dokumen -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Nomor Dokumen / BAST
                        </label>
                        <input type="text" name="no_dokumen" placeholder="Contoh: BAST-BMN/2026/09/012" 
                               class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-600 focus:ring-blue-600 py-2">
                    </div>

                    <!-- Keterangan -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Keterangan Pengadaan (Opsional)
                        </label>
                        <textarea name="keterangan" rows="2" placeholder="Catatan pengadaan atau sumber barang..."
                                  class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-600 focus:ring-blue-600 py-1.5"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                        <button type="button" @click="showRestockModal = false" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-[#2563eb] hover:bg-blue-600 text-white font-bold text-xs shadow-sm transition">
                            Simpan Restock
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
