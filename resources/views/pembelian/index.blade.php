<x-app-layout>
    <div x-data="{ 
            showCreateModal: false, 
            searchQuery: '{{ $search }}',
            selectedBarang: '',
            satuanLabel: ''
         }" 
         class="p-6 lg:p-8 space-y-6 max-w-[1600px] mx-auto">

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 text-sm font-medium shadow-sm animate-fade-in">
                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (isset($errors) && $errors->any())
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-sm font-medium shadow-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- TOP HEADER AREA -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 bg-white p-6 lg:p-8 rounded-3xl border border-slate-100 shadow-sm">
            <div>
                <!-- Breadcrumbs -->
                <div class="flex flex-wrap items-center gap-2 text-xs font-semibold text-slate-500">
                    <span class="text-blue-600 font-bold">BHP Surabaya</span>
                    <span>/</span>
                    <span>Bon Barang</span>
                    <span>/</span>
                    <span class="text-slate-800 font-bold">Pembelian & Pengadaan</span>
                </div>

                <!-- Page Title -->
                <h1 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight mt-2 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <circle cx="8" cy="21" r="1"/>
                            <circle cx="19" cy="21" r="1"/>
                            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                        </svg>
                    </div>
                    <span>Pembelian & Pengadaan Barang</span>
                </h1>
                <p class="text-xs text-slate-500 mt-1 max-w-2xl leading-relaxed">
                    Pusat pencatatan faktur barang masuk, restock logistik, serta dokumentasi pengadaan persediaan kantor BHP Kemenkumham.
                </p>
            </div>

            <!-- Action Button -->
            <div class="flex items-center gap-3">
                <button type="button" 
                        @click="showCreateModal = true"
                        class="px-5 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm flex items-center gap-2.5 shadow-lg shadow-blue-600/25 transition-all duration-150 transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Catat Pembelian Baru</span>
                </button>
            </div>
        </div>

        <!-- STAT METRICS CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1 -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.5L19 7.5V19a2 2 0 0 1-2 2Z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Faktur Bulan Ini</div>
                    <div class="text-2xl font-black text-slate-800">{{ number_format($totalPembelianBulanIni) }}</div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                    </svg>
                </div>
                <div>
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Unit Masuk (Bulan Ini)</div>
                    <div class="text-2xl font-black text-emerald-600">+{{ number_format($totalUnitMasukBulanIni) }}</div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Butuh Restock</div>
                    <div class="text-2xl font-black text-amber-600">{{ number_format($barangPerluRestock) }}</div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div>
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Katalog Barang</div>
                    <div class="text-2xl font-black text-slate-800">{{ number_format($totalItemTerdaftar) }}</div>
                </div>
            </div>
        </div>

        <!-- SEARCH & TABLE CARD -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <!-- Filter Bar -->
            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <span>Riwayat Pembelian & Barang Masuk</span>
                    <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-xs font-bold">{{ $riwayatPembelian->total() }} Data</span>
                </div>
                <form method="GET" action="{{ route('pembelian.index') }}" class="w-full sm:w-auto flex items-center gap-2">
                    <div class="relative w-full sm:w-80">
                        <input type="text" 
                               name="search" 
                               value="{{ $search }}" 
                               placeholder="Cari No. Faktur / Barang / Vendor..." 
                               class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <button type="submit" class="px-3.5 py-2 rounded-xl bg-slate-800 text-white text-xs font-semibold hover:bg-slate-900 transition-colors">
                        Cari
                    </button>
                    @if($search)
                        <a href="{{ route('pembelian.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 text-slate-600 text-xs font-semibold hover:bg-slate-200">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-[11px] font-bold border-b border-slate-100">
                        <tr>
                            <th class="py-3.5 px-4 w-12 text-center">No</th>
                            <th class="py-3.5 px-4">No. Dokumen / Faktur</th>
                            <th class="py-3.5 px-4">Waktu Transaksi</th>
                            <th class="py-3.5 px-4">Barang (BMN)</th>
                            <th class="py-3.5 px-4 text-center">Jumlah Masuk</th>
                            <th class="py-3.5 px-4">Vendor / Keterangan</th>
                            <th class="py-3.5 px-4">Petugas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse ($riwayatPembelian as $i => $item)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3.5 px-4 text-center text-slate-400 font-medium">
                                    {{ $riwayatPembelian->firstItem() + $i }}
                                </td>
                                <td class="py-3.5 px-4 font-mono font-bold text-blue-600">
                                    <span class="px-2.5 py-1 rounded-lg bg-blue-50 border border-blue-100">
                                        {{ $item->no_dokumen ?: 'PO-'.date('ymd', strtotime($item->created_at)).'-'.$item->id }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-slate-500">
                                    <div class="font-semibold text-slate-800">{{ $item->created_at->format('d M Y') }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $item->created_at->format('H:i') }} WIB</div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-900">{{ $item->barang?->deskripsi ?? 'Barang Telah Dihapus' }}</div>
                                    <div class="text-[10px] text-slate-400 font-mono">{{ $item->barang?->barcode_key }}</div>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 font-bold border border-emerald-200">
                                        +{{ $item->jumlah }} {{ $item->barang?->satuan ?? 'Unit' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 max-w-xs text-slate-600">
                                    {{ $item->keterangan ?: 'Pembelian persediaan BHP' }}
                                </td>
                                <td class="py-3.5 px-4 text-slate-500">
                                    {{ $item->user?->name ?? 'Admin' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400">
                                    <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-3 text-slate-300">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                    </div>
                                    <p class="font-bold text-slate-700">Belum ada riwayat pembelian barang</p>
                                    <p class="text-xs mt-1">Klik tombol "Catat Pembelian Baru" untuk menambahkan stok masuk.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($riwayatPembelian->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $riwayatPembelian->links() }}
                </div>
            @endif
        </div>

        <!-- MODAL CATAT PEMBELIAN BARU -->
        <div x-show="showCreateModal" 
             x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div @click.away="showCreateModal = false"
                 class="bg-white rounded-3xl shadow-2xl border border-slate-100 max-w-lg w-full p-6 lg:p-8 space-y-6">
                
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="8" cy="21" r="1"/>
                                <circle cx="19" cy="21" r="1"/>
                                <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-900">Catat Pembelian Barang</h3>
                            <p class="text-xs text-slate-500">Menambah stok persediaan BMN yang dibeli</p>
                        </div>
                    </div>
                    <button @click="showCreateModal = false" class="text-slate-400 hover:text-slate-600 p-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('pembelian.store') }}" class="space-y-4">
                    @csrf

                    <!-- Pilih Barang -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Pilih Barang (BMN) <span class="text-rose-500">*</span>
                        </label>
                        <select name="barang_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Barang yang Dibeli --</option>
                            @foreach ($daftarBarang as $b)
                                <option value="{{ $b->id }}">
                                    {{ $b->deskripsi }} (Stok saat ini: {{ $b->stok_saldo }} {{ $b->satuan }}) - {{ $b->barcode_key }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Jumlah Beli -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Jumlah Pembelian <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" name="jumlah" min="1" required placeholder="Contoh: 10" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        </div>

                        <!-- No Dokumen / Faktur -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                No. Faktur / Kuitansi
                            </label>
                            <input type="text" name="no_dokumen" placeholder="Contoh: FKT-2026/09/01" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Nama Vendor -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Nama Toko / Vendor
                            </label>
                            <input type="text" name="nama_vendor" placeholder="Contoh: CV. Berkah Logistik" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        </div>

                        <!-- Lokasi Rak Penyimpanan -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Lokasi Rak Simpan
                            </label>
                            <input type="text" name="lokasi_rak" placeholder="Contoh: Rak ATK-02" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                    </div>

                    <!-- Keterangan Tambahan -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Keterangan / Catatan Pengadaan
                        </label>
                        <textarea name="keterangan" rows="2" placeholder="Catatan pengadaan atau peruntukan DIPA..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="showCreateModal = false" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-bold hover:bg-slate-50 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-600/30 transition-colors">
                            Simpan Pembelian
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
