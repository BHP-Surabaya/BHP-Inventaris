<x-app-layout>
    <div x-data="{ 
            showAdjustModal: false, 
            activeItem: { id: null, deskripsi: '', stok_sistem: 0, satuan: '', barcode: '', lokasi: '' },
            stokFisikInput: 0,
            catatanInput: '',
            get selisih() {
                return (parseInt(this.stokFisikInput) || 0) - this.activeItem.stok_sistem;
            },
            openAdjust(id, deskripsi, stok, satuan, barcode, lokasi) {
                this.activeItem = { id, deskripsi, stok_sistem: stok, satuan, barcode, lokasi };
                this.stokFisikInput = stok;
                this.catatanInput = '';
                this.showAdjustModal = true;
            }
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
                    <span class="text-slate-800 font-bold">Stock Opname</span>
                </div>

                <!-- Page Title -->
                <h1 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight mt-2 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 11l3 3L22 4"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                        </svg>
                    </div>
                    <span>Stock Opname & Pemeriksaan Fisik</span>
                </h1>
                <p class="text-xs text-slate-500 mt-1 max-w-2xl leading-relaxed">
                    Audit berkala pencocokan saldo buku sistem dengan kondisi riil barang di rak/gudang logistik BHP Surabaya.
                </p>
            </div>

            <!-- Action Status -->
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-indigo-50 text-indigo-700 text-xs font-bold border border-indigo-100">
                    <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                    Mode Audit Fisik Aktif
                </span>
            </div>
        </div>

        <!-- STAT METRICS CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1 -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div>
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Item Terdaftar</div>
                    <div class="text-2xl font-black text-slate-800">{{ number_format($totalBarang) }}</div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Stok Buku</div>
                    <div class="text-2xl font-black text-blue-600">{{ number_format($totalStokSistem) }} Unit</div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Stok Aman (> Min)</div>
                    <div class="text-2xl font-black text-emerald-600">{{ number_format($stokAmanCount) }}</div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kritis / Habis</div>
                    <div class="text-2xl font-black text-rose-600">{{ number_format($stokKritisCount) }}</div>
                </div>
            </div>
        </div>

        <!-- SEARCH & TABLE CARD -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <!-- Filter Bar -->
            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <span>Daftar Saldo Fisik & Sistem</span>
                    <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-xs font-bold">{{ $barangs->total() }} Item</span>
                </div>
                <form method="GET" action="{{ route('stock-opname.index') }}" class="w-full sm:w-auto flex items-center gap-2">
                    <div class="relative w-full sm:w-80">
                        <input type="text" 
                               name="search" 
                               value="{{ $search }}" 
                               placeholder="Cari Barang / Barcode / Rak..." 
                               class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <button type="submit" class="px-3.5 py-2 rounded-xl bg-slate-800 text-white text-xs font-semibold hover:bg-slate-900 transition-colors">
                        Filter
                    </button>
                    @if($search)
                        <a href="{{ route('stock-opname.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 text-slate-600 text-xs font-semibold hover:bg-slate-200">
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
                            <th class="py-3.5 px-4">Kode BMN / Barcode</th>
                            <th class="py-3.5 px-4">Nama Barang & Kategori</th>
                            <th class="py-3.5 px-4">Lokasi Rak</th>
                            <th class="py-3.5 px-4 text-center">Stok Sistem</th>
                            <th class="py-3.5 px-4 text-center">Status</th>
                            <th class="py-3.5 px-4 text-center w-36">Aksi Opname</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse ($barangs as $i => $b)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3.5 px-4 text-center text-slate-400 font-medium">
                                    {{ $barangs->firstItem() + $i }}
                                </td>
                                <td class="py-3.5 px-4 font-mono font-bold text-slate-900">
                                    <div>{{ $b->barcode_key }}</div>
                                    @if($b->barcode)
                                        <div class="text-[10px] text-slate-400">{{ $b->barcode }}</div>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-900 text-sm">{{ $b->deskripsi }}</div>
                                    <div class="text-[11px] text-slate-400 flex items-center gap-2 mt-0.5">
                                        <span class="px-2 py-0.5 rounded-md bg-slate-100 font-medium">{{ $b->kategori ?? 'Umum' }}</span>
                                        <span>Batas Min: {{ $b->min_stok }} {{ $b->satuan }}</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-slate-600 font-medium">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 font-semibold text-[11px]">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        </svg>
                                        {{ $b->lokasi_rak }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="font-black text-sm {{ $b->stok_saldo <= $b->min_stok ? 'text-rose-600' : 'text-slate-800' }}">
                                        {{ $b->stok_saldo }}
                                    </span>
                                    <span class="text-slate-400 text-[11px]">{{ $b->satuan }}</span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @if($b->stok_saldo == 0)
                                        <span class="px-2.5 py-1 rounded-full bg-rose-100 text-rose-700 font-bold text-[10px]">Habis</span>
                                    @elseif($b->stok_saldo <= $b->min_stok)
                                        <span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-700 font-bold text-[10px]">Kritis</span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700 font-bold text-[10px]">Aman</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <button type="button" 
                                            @click="openAdjust({{ $b->id }}, '{{ addslashes($b->deskripsi) }}', {{ $b->stok_saldo }}, '{{ $b->satuan }}', '{{ $b->barcode_key }}', '{{ $b->lokasi_rak }}')"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white font-bold text-xs transition-all shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        <span>Hitung Fisik</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400">
                                    <p class="font-bold text-slate-700">Tidak ada barang yang cocok dengan filter pencarian</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($barangs->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $barangs->links() }}
                </div>
            @endif
        </div>

        <!-- MODAL SESUAIKAN STOK FISIK (STOCK OPNAME ADJUSTMENT) -->
        <div x-show="showAdjustModal" 
             x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div @click.away="showAdjustModal = false"
                 class="bg-white rounded-3xl shadow-2xl border border-slate-100 max-w-lg w-full p-6 lg:p-8 space-y-6">
                
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 11l3 3L22 4"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-900">Input Hasil Stock Opname</h3>
                            <p class="text-xs text-slate-500">Pencocokan fisik dengan saldo sistem buku</p>
                        </div>
                    </div>
                    <button @click="showAdjustModal = false" class="text-slate-400 hover:text-slate-600 p-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Info Barang Aktif -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                    <div class="text-[11px] font-mono text-slate-400 font-semibold" x-text="activeItem.barcode"></div>
                    <div class="text-sm font-bold text-slate-900" x-text="activeItem.deskripsi"></div>
                    <div class="text-xs text-slate-500 flex items-center gap-2">
                        <span>Lokasi: <span class="font-semibold text-slate-700" x-text="activeItem.lokasi"></span></span>
                    </div>
                </div>

                <form method="POST" action="{{ route('stock-opname.adjust') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="barang_id" :value="activeItem.id" />

                    <!-- Perbandingan Stok -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 rounded-2xl border border-slate-200 bg-slate-50/50">
                            <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Stok Sistem (Buku)</div>
                            <div class="text-2xl font-black text-slate-800 mt-1">
                                <span x-text="activeItem.stok_sistem"></span>
                                <span class="text-xs font-normal text-slate-400" x-text="activeItem.satuan"></span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Hitung Fisik Riil <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" 
                                   name="stok_fisik" 
                                   min="0" 
                                   required 
                                   x-model="stokFisikInput"
                                   class="w-full px-4 py-2.5 rounded-xl border border-indigo-300 text-sm font-bold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                        </div>
                    </div>

                    <!-- Status Selisih Live Indicator -->
                    <div class="p-3.5 rounded-2xl border flex items-center justify-between text-xs font-bold"
                         :class="{
                            'bg-emerald-50 border-emerald-200 text-emerald-800': selisih === 0,
                            'bg-amber-50 border-amber-200 text-amber-800': selisih > 0,
                            'bg-rose-50 border-rose-200 text-rose-800': selisih < 0
                         }">
                        <span>Kondisi Selisih:</span>
                        <span x-show="selisih === 0">✅ Saldo Cocok (Tidak ada selisih)</span>
                        <span x-show="selisih > 0" x-text="'⚠️ Lebih +' + selisih + ' ' + activeItem.satuan"></span>
                        <span x-show="selisih < 0" x-text="'❌ Defisit/Kurang ' + selisih + ' ' + activeItem.satuan"></span>
                    </div>

                    <!-- Catatan Penyesuaian -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Catatan Audit / Alasan Selisih
                        </label>
                        <textarea name="catatan" 
                                  rows="2" 
                                  x-model="catatanInput"
                                  placeholder="Contoh: Barang fisik rusak, salah pencatatan bon sebelumnya, dsb..." 
                                  class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="showAdjustModal = false" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-bold hover:bg-slate-50 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/30 transition-colors">
                            Simpan Penyesuaian
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
