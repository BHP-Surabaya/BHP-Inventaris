<x-app-layout>
    <div x-data="bonKasir()" 
         x-init="init()"
         class="p-6 sm:p-8 lg:p-8 space-y-6 lg:space-y-7 max-w-[1600px] mx-auto">

        <!-- Flash Messages & Alert -->
        @if (session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center justify-between gap-3 text-sm font-medium shadow-sm animate-fade-in">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold">✓</div>
                    <div>
                        <div class="font-bold text-emerald-900">Transaksi Bon Berhasil Disimpan!</div>
                        <div class="text-xs text-emerald-700">{{ session('success') }}</div>
                    </div>
                </div>
                @if (session('print_bon_id'))
                    <a href="{{ route('bon.print', session('print_bon_id')) }}" target="_blank"
                       class="inline-flex items-center gap-2 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        <span>Cetak Bukti Bon</span>
                    </a>
                @endif
            </div>
        @endif

        @if (isset($errors) && $errors->any())
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-sm font-medium shadow-sm">
                <div class="font-bold text-rose-900 mb-1">Gagal Menyimpan Transaksi Bon:</div>
                <ul class="list-disc list-inside space-y-1 text-xs text-rose-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- HEADER HALAMAN -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200/80">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-blue-700 uppercase tracking-wider">
                    <span>Balai Harta Peninggalan Surabaya</span>
                    <span class="text-slate-300">•</span>
                    <span class="text-slate-500 font-medium normal-case">Pengeluaran Logistik BMN</span>
                </div>
                <h1 class="text-2xl lg:text-3xl font-bold text-slate-900 tracking-tight mt-1 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.5L19 7.5V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span>Bon Pengeluaran Barang (ATK)</span>
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                    Layanan kasir scan barcode untuk penyerahan barang persediaan kepada seksi atau pegawai pemohon.
                </p>
            </div>

            <!-- Petugas Bertugas Badge -->
            <div class="flex items-center gap-3 bg-white px-4 py-2.5 rounded-2xl border border-slate-200 shadow-2xs">
                <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-xs uppercase">
                    {{ substr(Auth::user()->name ?? 'P', 0, 2) }}
                </div>
                <div>
                    <div class="text-xs font-bold text-slate-900">{{ Auth::user()->name }}</div>
                    <div class="text-[10px] text-slate-400 font-medium capitalize">
                        Peran: <span class="text-blue-600 font-bold">{{ str_replace('_', ' ', Auth::user()->role ?? 'Pegawai Gudang') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- LAYOUT UTAMA: 2 KOLOM (KASIR & RIWAYAT) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-7 items-start">

            <!-- KOLOM KIRI: TRANSAKSI KASIR SCAN BARCODE (col-span-8) -->
            <div class="lg:col-span-8 space-y-6">

                <form action="{{ route('bon.store') }}" method="POST" id="form-bon" @submit="handleSubmit($event)">
                    @csrf

                    <!-- 1. IDENTITAS BON & PEMOHON -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                                <h2 class="text-sm font-bold text-slate-900">1. Data Pemohon & Dokumen</h2>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] text-slate-400 font-medium">Nomor Bon:</span>
                                <input type="text" name="no_bon" value="{{ $autoNoBon }}" readonly 
                                       class="font-mono font-bold text-xs bg-slate-50 text-blue-700 px-3 py-1 rounded-xl border border-slate-200 text-center w-36">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <!-- Tanggal -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tanggal Permintaan</label>
                                <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required
                                       class="w-full rounded-xl border-slate-200 text-xs font-medium focus:border-blue-600 focus:ring-blue-600 py-2.5">
                            </div>

                            <!-- Nama Pemohon -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Pegawai Pemohon</label>
                                <input type="text" name="nama_pemohon" x-model="pemohon" required placeholder="Contoh: Budi Santoso / Ibu Rina"
                                       class="w-full rounded-xl border-slate-200 text-xs font-medium focus:border-blue-600 focus:ring-blue-600 py-2.5">
                            </div>

                            <!-- Seksi Pemohon -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Seksi / Unit Kerja</label>
                                <select name="seksi_pemohon" x-model="seksi" required
                                        class="w-full rounded-xl border-slate-200 text-xs font-medium focus:border-blue-600 focus:ring-blue-600 py-2.5">
                                    <option value="" disabled selected>-- Pilih Seksi / Ruangan --</option>
                                    @foreach ($daftarSeksi as $s)
                                        <option value="{{ $s }}">{{ $s }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Keperluan Singkat -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Keperluan / Keterangan (Opsional)</label>
                            <input type="text" name="keperluan" x-model="keperluan" placeholder="Contoh: Kebutuhan ATK Pelayanan Sidang / Berkas Arsip Wasiat"
                                   class="w-full rounded-xl border-slate-200 text-xs font-medium focus:border-blue-600 focus:ring-blue-600 py-2">
                        </div>
                    </div>

                    <!-- 2. AREA SCAN BARCODE CEPAT (SCANNER BOX) -->
                    <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white rounded-3xl p-6 shadow-md space-y-4 mt-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-blue-500/20 text-blue-400 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-white tracking-wide">2. Pindai (Scan) Barcode Barang</h3>
                                    <p class="text-[11px] text-slate-400">Arahkan scanner ke barcode stiker atau ketik kode barang lalu tekan Enter</p>
                                </div>
                            </div>

                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-[11px] font-medium">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                Scanner Siap Aktif
                            </span>
                        </div>

                        <!-- Barcode Scanner Input Form -->
                        <div class="flex flex-col sm:flex-row gap-2.5">
                            <div class="relative flex-1">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7V5a2 2 0 012-2h2M17 3h2a2 2 0 012 2v2M21 17v2a2 2 0 01-2 2h-2M7 21H5a2 2 0 01-2-2v-2M7 8v8M11 8v8M14 8v8M17 8v8" />
                                    </svg>
                                </div>
                                <input type="text" x-ref="barcodeInput" x-model="scannedCode" @keydown.enter.prevent="processScan()"
                                       placeholder="Tembak scanner atau masukkan kode barcode (misal: 1010301001.000001)..."
                                       class="w-full pl-11 pr-4 py-3.5 bg-slate-950/80 text-white rounded-2xl border border-slate-700 text-sm font-mono tracking-wider focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 placeholder:text-slate-500 placeholder:text-xs">
                            </div>
                            <button type="button" @click="processScan()"
                                    class="px-5 py-3.5 bg-blue-600 hover:bg-blue-500 active:scale-95 text-white rounded-2xl text-xs font-bold transition flex items-center justify-center gap-2 flex-shrink-0 shadow-lg shadow-blue-600/30">
                                <span>Tambah ke Bon</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                            </button>
                        </div>

                        <!-- Fallback Pilihan Manual / Cari Cepat Dropdown -->
                        <div class="pt-2 border-t border-slate-700/60 flex items-center justify-between text-xs text-slate-400">
                            <span class="text-[11px]">Scanner sedang tidak ada? Pilih barang manual:</span>
                            <div class="w-64">
                                <select @change="addManualItem($event.target.value); $event.target.value = ''"
                                        class="w-full bg-slate-950 text-slate-300 text-xs rounded-xl border border-slate-700 py-1.5 px-2.5 focus:border-blue-500">
                                    <option value="">-- Pilih Barang dari Daftar --</option>
                                    @foreach ($barangs as $b)
                                        <option value="{{ $b->id }}" {{ $b->stok_saldo <= 0 ? 'disabled' : '' }}>
                                            {{ $b->deskripsi }} (Stok: {{ $b->stok_saldo }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- 3. KERANJANG BARANG YANG DIAMBIL -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-4 mt-6">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                <h3 class="text-sm font-bold text-slate-900">3. Rincian Barang Yang Diserahkan</h3>
                            </div>
                            <span class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full" x-text="cart.length + ' Jenis Barang'"></span>
                        </div>

                        <!-- Tabel Keranjang -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead>
                                    <tr class="text-slate-400 font-semibold border-b border-slate-100 uppercase tracking-wider text-[10px]">
                                        <th class="py-2.5 px-3">Barang & Barcode</th>
                                        <th class="py-2.5 px-3">Lokasi Rak</th>
                                        <th class="py-2.5 px-3 text-center">Sisa Stok</th>
                                        <th class="py-2.5 px-3 text-center w-36">Jumlah Keluar</th>
                                        <th class="py-2.5 px-3 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <template x-for="(item, index) in cart" :key="item.id">
                                        <tr class="hover:bg-slate-50/60 transition">
                                            <!-- Nama & Kode Barang -->
                                            <td class="py-3 px-3">
                                                <div class="font-bold text-slate-900" x-text="item.deskripsi"></div>
                                                <div class="text-[10px] font-mono text-slate-400 mt-0.5" x-text="item.barcode_key"></div>
                                                <input type="hidden" :name="'items[' + index + '][barang_id]'" :value="item.id">
                                            </td>

                                            <!-- Lokasi Rak -->
                                            <td class="py-3 px-3 text-slate-600" x-text="item.lokasi_rak || '-'"></td>

                                            <!-- Sisa Stok -->
                                            <td class="py-3 px-3 text-center">
                                                <span :class="item.stok_saldo <= item.jumlah ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800'"
                                                      class="px-2 py-0.5 rounded-md font-bold text-[11px]" 
                                                      x-text="item.stok_saldo + ' ' + item.satuan"></span>
                                            </td>

                                            <!-- Counter Jumlah -->
                                            <td class="py-3 px-3 text-center">
                                                <div class="flex items-center justify-center gap-1.5 bg-slate-50 p-1 rounded-xl border border-slate-200">
                                                    <button type="button" @click="if (item.jumlah > 1) item.jumlah--"
                                                            class="w-6 h-6 rounded-lg bg-white border border-slate-200 font-bold flex items-center justify-center hover:bg-slate-100 text-slate-700">
                                                        –
                                                    </button>
                                                    <input type="number" :name="'items[' + index + '][jumlah]'" x-model.number="item.jumlah" min="1" :max="item.stok_saldo"
                                                           class="w-12 text-center text-xs font-bold border-0 bg-transparent p-0 focus:ring-0">
                                                    <button type="button" @click="if (item.jumlah < item.stok_saldo) item.jumlah++"
                                                            class="w-6 h-6 rounded-lg bg-white border border-slate-200 font-bold flex items-center justify-center hover:bg-slate-100 text-slate-700">
                                                        +
                                                    </button>
                                                </div>
                                            </td>

                                            <!-- Hapus Item -->
                                            <td class="py-3 px-3 text-right">
                                                <button type="button" @click="removeItem(index)" title="Hapus dari keranjang"
                                                        class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>

                                    <!-- Empty State Keranjang -->
                                    <tr x-show="cart.length === 0">
                                        <td colspan="5" class="py-12 text-center text-slate-400">
                                            <div class="flex flex-col items-center justify-center gap-2">
                                                <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-300">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                                    </svg>
                                                </div>
                                                <span class="text-xs font-medium">Belum ada barang di dalam bon. Silakan scan barcode barang di atas.</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Ringkasan & Tombol Simpan -->
                        <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4" x-show="cart.length > 0">
                            <div>
                                <div class="text-xs text-slate-500">Total Unit Barang Diserahkan:</div>
                                <div class="text-xl font-black text-slate-900" x-text="totalQty() + ' Unit Barang'"></div>
                            </div>

                            <button type="submit" 
                                    class="inline-flex items-center justify-center gap-2.5 px-6 py-3.5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-bold text-sm rounded-2xl shadow-lg shadow-blue-600/25 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Konfirmasi & Simpan Bon Pengeluaran</span>
                            </button>
                        </div>
                    </div>

                </form>

            </div>

            <!-- KOLOM KANAN: RIWAYAT BON HARI INI & CETAK SLIP (col-span-4) -->
            <div class="lg:col-span-4 space-y-6">

                <!-- KARTU RIWAYAT TRANSAKSI BON TERBARU -->
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-slate-900">Riwayat Bon Terbaru</h3>
                        </div>
                    </div>

                    <!-- Search Riwayat -->
                    <form method="GET" action="{{ route('bon.index') }}" class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor bon / pemohon..."
                               class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:border-blue-600 focus:ring-0">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </form>

                    <!-- Daftar Riwayat Bon -->
                    <div class="space-y-3">
                        @forelse ($riwayatBons as $bon)
                            <div class="p-3.5 rounded-2xl border border-slate-100 bg-slate-50/60 hover:bg-slate-50 transition space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="font-mono font-bold text-xs text-blue-700 bg-blue-50 px-2 py-0.5 rounded-md border border-blue-100">
                                        {{ $bon->no_bon }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-medium">
                                        {{ $bon->tanggal->format('d M Y') }}
                                    </span>
                                </div>

                                <div>
                                    <div class="font-bold text-xs text-slate-900">{{ $bon->nama_pemohon }}</div>
                                    <div class="text-[11px] text-slate-500">{{ $bon->seksi_pemohon }}</div>
                                </div>

                                <div class="flex items-center justify-between pt-1 border-t border-slate-200/60 text-[11px]">
                                    <span class="text-slate-500 font-medium">{{ $bon->total_item }} Barang</span>
                                    <a href="{{ route('bon.print', $bon->id) }}" target="_blank"
                                       class="inline-flex items-center gap-1.5 text-blue-600 hover:text-blue-800 font-bold hover:underline">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                        <span>Cetak Bukti</span>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-xs text-slate-400">
                                Belum ada riwayat pengeluaran bon barang.
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if ($riwayatBons->hasPages())
                        <div class="pt-2">
                            {{ $riwayatBons->links() }}
                        </div>
                    @endif
                </div>

            </div>

        </div>

    </div>

    <!-- SCRIPT ALPINE.JS INTERAKTIF KASIR SCAN -->
    <script>
    function bonKasir() {
        return {
            pemohon: '',
            seksi: '',
            keperluan: '',
            scannedCode: '',
            cart: [],
            allBarangs: @json($barangs),

            init() {
                this.$nextTick(() => {
                    this.$refs.barcodeInput.focus();
                });
            },

            playBeep() {
                try {
                    const ctx = new (window.AudioContext || window.webkitAudioContext)();
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(800, ctx.currentTime);
                    gain.gain.setValueAtTime(0.15, ctx.currentTime);
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.start();
                    osc.stop(ctx.currentTime + 0.1);
                } catch(e) {}
            },

            processScan() {
                const code = (this.scannedCode || '').trim();
                if (!code) return;

                // Cari barang berdasarkan barcode_key atau barcode
                const item = this.allBarangs.find(b => 
                    b.barcode_key === code || b.barcode === code || String(b.id) === code
                );

                if (!item) {
                    alert('Barang dengan barcode [' + code + '] tidak ditemukan dalam sistem!');
                    this.scannedCode = '';
                    this.$refs.barcodeInput.focus();
                    return;
                }

                if (item.stok_saldo <= 0) {
                    alert('Stok barang [' + item.deskripsi + '] habis (0 ' + item.satuan + ')!');
                    this.scannedCode = '';
                    this.$refs.barcodeInput.focus();
                    return;
                }

                this.addToCart(item);
                this.playBeep();
                this.scannedCode = '';
                this.$refs.barcodeInput.focus();
            },

            addManualItem(barangId) {
                if (!barangId) return;
                const item = this.allBarangs.find(b => b.id == barangId);
                if (item) {
                    this.addToCart(item);
                    this.playBeep();
                    this.$refs.barcodeInput.focus();
                }
            },

            addToCart(item) {
                const existing = this.cart.find(c => c.id === item.id);
                if (existing) {
                    if (existing.jumlah < item.stok_saldo) {
                        existing.jumlah++;
                    } else {
                        alert('Jumlah pengambilan melebihi sisa stok yang ada di rak (' + item.stok_saldo + ' ' + item.satuan + ')!');
                    }
                } else {
                    this.cart.push({
                        id: item.id,
                        barcode_key: item.barcode_key,
                        deskripsi: item.deskripsi,
                        satuan: item.satuan,
                        lokasi_rak: item.lokasi_rak,
                        stok_saldo: item.stok_saldo,
                        jumlah: 1
                    });
                }
            },

            removeItem(index) {
                this.cart.splice(index, 1);
            },

            totalQty() {
                return this.cart.reduce((sum, item) => sum + (parseInt(item.jumlah) || 0), 0);
            },

            handleSubmit(e) {
                if (this.cart.length === 0) {
                    e.preventDefault();
                    alert('Keranjang masih kosong! Silakan scan minimal 1 barang yang dikeluarkan.');
                    return;
                }
            }
        };
    }
    </script>
</x-app-layout>
