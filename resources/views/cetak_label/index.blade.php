<x-app-layout>
    <div x-data="labelPrinter()" 
         x-init="init()"
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

        <!-- 1. HEADER HALAMAN: BERSIH & RAPI -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200/80">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-blue-700 uppercase tracking-wider">
                    <span>Balai Harta Peninggalan Surabaya</span>
                    <span class="text-slate-300">•</span>
                    <span class="text-slate-500 font-medium normal-case">Kemenkumham RI</span>
                </div>
                <h1 class="text-2xl lg:text-3xl font-bold text-slate-900 tracking-tight mt-1">Cetak Label Barcode BMN</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Pencetakan stiker label inventaris thermal presisi 1:1 untuk identifikasi fisik BMN.</p>
            </div>

            <!-- Tombol Aksi Header -->
            <div class="flex items-center gap-2.5">
                <button type="button" @click="downloadBitmap()" 
                        class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold shadow-2xs transition">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    <span>Unduh PNG</span>
                </button>
                <button type="button" @click="printThermal()" 
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#2563eb] hover:bg-blue-600 active:scale-95 text-white text-xs font-bold shadow-sm shadow-blue-500/20 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>Cetak Semua Antrean ({{ $totalAntrean }})</span>
                </button>
            </div>
        </div>

        <!-- 2. TATA LETAK UTAMA: 2 KOLOM SEIMBANG -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-7 items-start">
            
            <!-- KOLOM KIRI: PARAMETER & PENGATURAN CETAK (col-span-5) -->
            <div class="lg:col-span-5 space-y-6">
                
                <!-- BOX 1: PILIH BARANG & PARAMETER CETAK -->
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-5">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                </svg>
                            </div>
                            <h2 class="text-sm font-bold text-slate-900">Pengaturan Cetak Label</h2>
                        </div>
                        <span class="text-[11px] font-mono font-bold text-blue-700 bg-blue-50 px-2.5 py-0.5 rounded-full" x-text="selectedItem.barcode_key"></span>
                    </div>

                    <!-- Pilih Barang Terpilih -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Barang Yang Dicetak</label>
                        <select @change="
                                    const b = {{ $allBarangs->toJson() }}.find(i => i.id == $event.target.value);
                                    if(b) setBarang(b);
                                "
                                class="w-full rounded-xl border-slate-200 text-xs font-medium focus:border-blue-600 focus:ring-blue-600 py-2.5">
                            @foreach ($allBarangs as $b)
                                <option value="{{ $b->id }}" {{ ($selectedBarang->id ?? null) == $b->id ? 'selected' : '' }}>
                                    {{ $b->barcode_key }} — {{ $b->deskripsi }}
                                </option>
                            @endforeach
                        </select>
                        <div class="flex items-center justify-between text-[11px] text-slate-400 px-1 pt-0.5">
                            <span>Lokasi: <strong class="text-slate-600" x-text="selectedItem.lokasi_rak || '-'"></strong></span>
                            <span>Satuan: <strong class="text-slate-600" x-text="selectedItem.satuan || '-'"></strong></span>
                        </div>
                    </div>

                    <!-- Jumlah Salinan -->
                    <div class="space-y-1.5 pt-1">
                        <div class="flex items-center justify-between text-xs font-bold text-slate-700">
                            <span>Jumlah Salinan Label</span>
                            <span class="text-slate-400 font-normal">Maks. 50 Lembar</span>
                        </div>
                        <div class="flex items-center justify-between bg-slate-50/80 rounded-xl p-1.5 border border-slate-200">
                            <button type="button" @click="if (copies > 1) { copies--; renderCanvas(); }" 
                                    class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-700 hover:bg-slate-100 font-bold transition">
                                –
                            </button>
                            <div class="font-extrabold text-slate-900 text-sm flex items-center gap-1.5">
                                <span x-text="copies" class="text-base"></span>
                                <span class="text-slate-500 font-normal text-xs">Lembar</span>
                            </div>
                            <button type="button" @click="if (copies < 50) { copies++; renderCanvas(); }" 
                                    class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-700 hover:bg-slate-100 font-bold transition">
                                +
                            </button>
                        </div>
                    </div>

                    <!-- Pilihan Ukuran Kertas Stiker Thermal -->
                    <div class="space-y-2 pt-1">
                        <div class="flex items-center justify-between text-xs font-bold text-slate-700">
                            <span>Ukuran Fisik Stiker Label</span>
                            <span class="text-blue-600 text-[11px] font-mono font-bold" x-text="labelPresetLabel"></span>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                            <!-- 75 x 50 mm (Rekomendasi / Sesuai Foto) -->
                            <button type="button" @click="setPreset('75x50')"
                                    :class="labelPreset === '75x50' ? 'bg-[#0c213e] text-white shadow-xs ring-2 ring-blue-500/20' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200'"
                                    class="p-2.5 rounded-xl text-left transition flex flex-col justify-between">
                                <div class="flex items-center justify-between w-full">
                                    <span class="font-extrabold text-xs">75 x 50 mm</span>
                                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-emerald-500 text-white" x-show="labelPreset === '75x50'">Pas</span>
                                </div>
                                <span class="text-[10px] opacity-75 mt-0.5">Standar Stiker (Rekomendasi)</span>
                            </button>

                            <!-- 75 x 40 mm -->
                            <button type="button" @click="setPreset('75x40')"
                                    :class="labelPreset === '75x40' ? 'bg-[#0c213e] text-white shadow-xs ring-2 ring-blue-500/20' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200'"
                                    class="p-2.5 rounded-xl text-left transition flex flex-col justify-between">
                                <span class="font-extrabold text-xs">75 x 40 mm</span>
                                <span class="text-[10px] opacity-75 mt-0.5">Sedang (320px)</span>
                            </button>

                            <!-- 75 x 30 mm -->
                            <button type="button" @click="setPreset('75x30')"
                                    :class="labelPreset === '75x30' ? 'bg-[#0c213e] text-white shadow-xs ring-2 ring-blue-500/20' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200'"
                                    class="p-2.5 rounded-xl text-left transition flex flex-col justify-between">
                                <span class="font-extrabold text-xs">75 x 30 mm</span>
                                <span class="text-[10px] opacity-75 mt-0.5">Kecil (240px)</span>
                            </button>

                            <!-- 75 x 20 mm -->
                            <button type="button" @click="setPreset('75x20')"
                                    :class="labelPreset === '75x20' ? 'bg-[#0c213e] text-white shadow-xs ring-2 ring-blue-500/20' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200'"
                                    class="p-2.5 rounded-xl text-left transition flex flex-col justify-between">
                                <span class="font-extrabold text-xs">75 x 20 mm</span>
                                <span class="text-[10px] opacity-75 mt-0.5">Slim / Tipis (160px)</span>
                            </button>

                            <!-- 58 x 40 mm -->
                            <button type="button" @click="setPreset('58x40')"
                                    :class="labelPreset === '58x40' ? 'bg-[#0c213e] text-white shadow-xs ring-2 ring-blue-500/20' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200'"
                                    class="p-2.5 rounded-xl text-left transition flex flex-col justify-between">
                                <span class="font-extrabold text-xs">58 x 40 mm</span>
                                <span class="text-[10px] opacity-75 mt-0.5">Portable (RPP02N)</span>
                            </button>

                            <!-- 58 x 30 mm -->
                            <button type="button" @click="setPreset('58x30')"
                                    :class="labelPreset === '58x30' ? 'bg-[#0c213e] text-white shadow-xs ring-2 ring-blue-500/20' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200'"
                                    class="p-2.5 rounded-xl text-left transition flex flex-col justify-between">
                                <span class="font-extrabold text-xs">58 x 30 mm</span>
                                <span class="text-[10px] opacity-75 mt-0.5">Portable Mini</span>
                            </button>
                        </div>

                        <!-- Pengaturan Posisi Gambar (Agak Ke Atas) -->
                        <div class="space-y-1.5 pt-2 border-t border-slate-100">
                            <div class="flex items-center justify-between text-xs font-bold text-slate-700">
                                <span>Posisi Gambar Cetak</span>
                                <span class="text-blue-600 text-[11px]" x-text="verticalOffset < 0 ? 'Agak Ke Atas (' + verticalOffset + 'px)' : (verticalOffset === 0 ? 'Normal (Tengah)' : 'Agak Ke Bawah (+' + verticalOffset + 'px)')"></span>
                            </div>
                            <div class="grid grid-cols-3 gap-2">
                                <button type="button" @click="setVerticalOffset(-14)"
                                        :class="verticalOffset === -14 ? 'bg-[#0c213e] text-white shadow-xs font-bold' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200'"
                                        class="py-2 px-2 rounded-xl text-xs transition text-center">
                                    Sangat Ke Atas
                                </button>
                                <button type="button" @click="setVerticalOffset(-7)"
                                        :class="verticalOffset === -7 ? 'bg-[#0c213e] text-white shadow-xs font-bold' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200'"
                                        class="py-2 px-2 rounded-xl text-xs transition text-center">
                                    Agak Ke Atas
                                </button>
                                <button type="button" @click="setVerticalOffset(0)"
                                        :class="verticalOffset === 0 ? 'bg-[#0c213e] text-white shadow-xs font-bold' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200'"
                                        class="py-2 px-2 rounded-xl text-xs transition text-center">
                                    Normal
                                </button>
                            </div>
                        </div>

                        <!-- Panjang / Tinggi Gambar Cetak -->
                        <div class="flex items-center justify-between bg-slate-50 p-2.5 rounded-xl border border-slate-200 text-xs">
                            <div>
                                <span class="font-bold text-slate-800">Tinggi Gambar:</span>
                                <span class="text-[10px] text-slate-500 block">Kurangi jika gambar kepanjangan</span>
                            </div>
                            <div class="flex items-center gap-1.5 font-mono">
                                <button type="button" @click="adjustHeightPixels(-15)" title="Kurang 15px (Lebih Pendek)"
                                        class="w-7 h-7 rounded-lg bg-white border border-slate-200 hover:bg-slate-100 font-bold flex items-center justify-center text-slate-700 transition">
                                    -
                                </button>
                                <span class="font-bold text-slate-900 px-1 min-w-[50px] text-center" x-text="labelHeight + ' px'"></span>
                                <button type="button" @click="adjustHeightPixels(15)" title="Tambah 15px (Lebih Panjang)"
                                        class="w-7 h-7 rounded-lg bg-white border border-slate-200 hover:bg-slate-100 font-bold flex items-center justify-center text-slate-700 transition">
                                    +
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Pilihan Tata Letak (Layout) -->
                    <div class="space-y-1.5 pt-1">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Tata Letak (Layout)</label>
                        <div class="grid grid-cols-2 gap-2.5">
                            <button type="button" @click="layoutStyle = 'side'; renderCanvas()" 
                                    :class="layoutStyle === 'side' ? 'border-blue-600 bg-blue-50/50 shadow-xs' : 'border-slate-200 bg-white hover:bg-slate-50'"
                                    class="p-2.5 rounded-xl border text-left flex items-center gap-2 transition">
                                <div class="w-3.5 h-3.5 rounded-full border-2 flex items-center justify-center flex-shrink-0"
                                     :class="layoutStyle === 'side' ? 'border-blue-600' : 'border-slate-300'">
                                    <div x-show="layoutStyle === 'side'" class="w-1.5 h-1.5 rounded-full bg-blue-600"></div>
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 text-xs">Kiri & Kanan (2 Kolom)</div>
                                    <div class="text-[10px] text-slate-400">Barcode Kiri, Teks Kanan</div>
                                </div>
                            </button>

                            <button type="button" @click="layoutStyle = 'stack'; renderCanvas()" 
                                    :class="layoutStyle === 'stack' ? 'border-blue-600 bg-blue-50/50 shadow-xs' : 'border-slate-200 bg-white hover:bg-slate-50'"
                                    class="p-2.5 rounded-xl border text-left flex items-center gap-2 transition">
                                <div class="w-3.5 h-3.5 rounded-full border-2 flex items-center justify-center flex-shrink-0"
                                     :class="layoutStyle === 'stack' ? 'border-blue-600' : 'border-slate-300'">
                                    <div x-show="layoutStyle === 'stack'" class="w-1.5 h-1.5 rounded-full bg-blue-600"></div>
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 text-xs">Atas & Bawah (Vertikal)</div>
                                    <div class="text-[10px] text-slate-400">Barcode Lebar di Atas</div>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Simbologi Kode -->
                    <div class="space-y-1.5 pt-1">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Format Simbologi Barcode</label>
                        <div class="grid grid-cols-2 gap-2.5">
                            <button type="button" @click="symbology = 'code128'; renderCanvas()" 
                                    :class="symbology === 'code128' ? 'border-blue-600 bg-blue-50/50 shadow-xs' : 'border-slate-200 bg-white hover:bg-slate-50'"
                                    class="p-3 rounded-xl border text-left flex items-center gap-2.5 transition">
                                <div class="w-3.5 h-3.5 rounded-full border-2 flex items-center justify-center flex-shrink-0"
                                     :class="symbology === 'code128' ? 'border-blue-600' : 'border-slate-300'">
                                    <div x-show="symbology === 'code128'" class="w-1.5 h-1.5 rounded-full bg-blue-600"></div>
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 text-xs">Code 128 (1D)</div>
                                    <div class="text-[10px] text-slate-400">Garis Barcode Linear</div>
                                </div>
                            </button>

                            <button type="button" @click="symbology = 'qrcode'; renderCanvas()" 
                                    :class="symbology === 'qrcode' ? 'border-blue-600 bg-blue-50/50 shadow-xs' : 'border-slate-200 bg-white hover:bg-slate-50'"
                                    class="p-3 rounded-xl border text-left flex items-center gap-2.5 transition">
                                <div class="w-3.5 h-3.5 rounded-full border-2 flex items-center justify-center flex-shrink-0"
                                     :class="symbology === 'qrcode' ? 'border-blue-600' : 'border-slate-300'">
                                    <div x-show="symbology === 'qrcode'" class="w-1.5 h-1.5 rounded-full bg-blue-600"></div>
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 text-xs">QR Code (2D)</div>
                                    <div class="text-[10px] text-slate-400">Matriks 2 Dimensi</div>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Tombol Cetak Utama -->
                    <button type="button" @click="printThermal()" 
                            class="w-full flex items-center justify-center gap-2.5 py-3.5 px-5 rounded-xl bg-[#2563eb] hover:bg-blue-600 active:scale-95 text-white font-bold text-sm shadow-md shadow-blue-500/20 transition mt-2">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        <span>Cetak Stiker Label Sekarang</span>
                    </button>
                </div>

                <!-- BOX 2: KONEKSI PERANGKAT PRINTER (RINGKAS & RAPI) -->
                <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm space-y-3.5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full" :class="isConnected ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400'"></div>
                            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Perangkat Thermal Printer</h3>
                        </div>
                        <span :class="isConnected ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-600 border-slate-200'"
                              class="text-[11px] font-bold px-2.5 py-0.5 rounded-full border">
                            <span x-text="isConnected ? 'ONLINE (' + connectionType + ')' : 'SIAP CETAK'"></span>
                        </span>
                    </div>

                    <div class="flex items-center justify-between bg-slate-50 p-3 rounded-xl border border-slate-100 text-xs">
                        <div>
                            <div class="font-bold text-slate-900" x-text="deviceName"></div>
                            <div class="text-[11px] text-slate-400 font-mono">203 DPI • ESC/POS Ready</div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" @click="connectBluetooth()" 
                                    class="px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-2xs transition">
                                Pair Bluetooth
                            </button>
                            <button type="button" @click="testFeed()" 
                                    class="px-2.5 py-1.5 rounded-lg bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 font-semibold text-xs transition">
                                Feed
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- KOLOM KANAN: PRATINJAU REAL-TIME & ANTREAN (col-span-7) -->
            <div class="lg:col-span-7 space-y-6">
                
                <!-- BOX 1: PRATINJAU FISIK REAL-TIME (CANVAS) -->
                <div class="bg-white rounded-3xl p-6 lg:p-7 border border-slate-100 shadow-sm space-y-5">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-sm font-bold text-slate-900">Pratinjau Fisik Stiker (1:1)</h2>
                                <p class="text-[11px] text-slate-400">Rasio aktual hasil cetak printer thermal</p>
                            </div>
                        </div>

                        <!-- Zoom Controls -->
                        <div class="flex items-center gap-1 bg-slate-50 p-1 rounded-xl border border-slate-200">
                            <button type="button" @click="if (zoomLevel < 1.4) zoomLevel += 0.1" title="Perbesar" 
                                    class="p-1 rounded-lg hover:bg-white text-slate-600 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                            </button>
                            <button type="button" @click="zoomLevel = 1" title="Reset Ukuran" 
                                    class="px-2 py-0.5 text-[10px] font-mono font-bold text-slate-500 hover:bg-white rounded-md transition">
                                <span x-text="Math.round(zoomLevel * 100) + '%'"></span>
                            </button>
                            <button type="button" @click="if (zoomLevel > 0.8) zoomLevel -= 0.1" title="Perkecil" 
                                    class="p-1 rounded-lg hover:bg-white text-slate-600 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- AREA CANVAS WORKSPACE -->
                    <div class="bg-slate-50 rounded-2xl p-6 lg:p-8 border border-dashed border-slate-200 flex flex-col items-center justify-center min-h-[220px]">
                        <div class="w-full max-w-[540px] flex items-center justify-between text-[11px] font-mono text-slate-500 pb-2">
                            <span class="font-bold text-slate-700" x-text="'Stiker: ' + labelWidthMm + ' x ' + labelHeightMm + ' mm (' + printerWidth + ' x ' + labelHeight + ' px)'"></span>
                            <span>Die-Cut Sensor Gap: 2.0 mm</span>
                        </div>

                        <!-- LIVE HTML5 CANVAS ELEMENT -->
                        <div :style="'transform: scale(' + zoomLevel + '); transform-origin: center center;'"
                             class="transition-transform duration-200 bg-white p-3 rounded-2xl shadow-lg border border-slate-200 flex justify-center items-center max-w-full overflow-hidden">
                            <canvas id="thermal-canvas" :width="printerWidth" :height="labelHeight" class="block bg-white shadow-2xs border border-slate-300 max-w-full max-h-[360px] w-auto h-auto object-contain"></canvas>
                        </div>

                        <div class="text-[10px] font-mono text-slate-400 pt-3">
                            Monochrome Raster 1-Bit ESC/POS (GS v 0) • Head Thermal 0 mm Offset
                        </div>
                    </div>

                    <!-- Ringkasan Info Barang -->
                    <div class="grid grid-cols-3 gap-2.5 pt-1 text-center">
                        <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                            <div class="text-[10px] text-slate-400 font-bold uppercase">Barcode Key</div>
                            <div class="text-xs font-mono font-bold text-slate-800 mt-0.5 truncate" x-text="selectedItem.barcode_key"></div>
                        </div>
                        <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                            <div class="text-[10px] text-slate-400 font-bold uppercase">Satuan</div>
                            <div class="text-xs font-bold text-slate-800 mt-0.5 truncate" x-text="selectedItem.satuan"></div>
                        </div>
                        <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                            <div class="text-[10px] text-slate-400 font-bold uppercase">Lokasi Rak</div>
                            <div class="text-xs font-bold text-slate-800 mt-0.5 truncate" x-text="selectedItem.lokasi_rak || 'Gudang'"></div>
                        </div>
                    </div>
                </div>

                <!-- BOX 2: ANTREAN CETAK CEPAT -->
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-3.5">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Antrean Cetak Cepat (4 Item Teratas)</h3>
                        <span class="text-[11px] font-bold text-blue-600 bg-blue-50 px-2.5 py-0.5 rounded-full">
                            {{ $totalAntrean }} item antrean
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        @foreach ($queueBarangs as $qb)
                            <button type="button" 
                                    @click="setBarang({
                                        id: {{ $qb->id }},
                                        barcode_key: {{ json_encode($qb->barcode_key) }},
                                        barcode: {{ json_encode($qb->barcode ?? 'BMN-'.$qb->id) }},
                                        deskripsi: {{ json_encode($qb->deskripsi) }},
                                        satuan: {{ json_encode($qb->satuan) }},
                                        lokasi_rak: {{ json_encode($qb->lokasi_rak) }}
                                    })"
                                    :class="selectedItem.id === {{ $qb->id }} ? 'border-blue-600 bg-blue-50/50 shadow-2xs' : 'border-slate-100 bg-slate-50/60 hover:bg-slate-100/70'"
                                    class="p-3 rounded-xl border text-left flex items-center justify-between transition">
                                <div class="min-w-0 mr-2">
                                    <div class="text-xs font-bold text-slate-900 truncate">{{ $qb->deskripsi }}</div>
                                    <div class="text-[10px] font-mono text-slate-400 mt-0.5">{{ $qb->barcode_key }}</div>
                                </div>
                                <span :class="selectedItem.id === {{ $qb->id }} ? 'bg-blue-600 text-white' : 'bg-white text-slate-600 border border-slate-200'"
                                      class="text-[10px] font-bold px-2 py-0.5 rounded-md flex-shrink-0 transition">
                                    <span x-text="selectedItem.id === {{ $qb->id }} ? 'Aktif' : 'Pilih'"></span>
                                </span>
                            </button>
                        @endforeach
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- CLEAN JAVASCRIPT COMPONENT SCRIPT -->
    <script>
    function labelPrinter() {
        return {
            selectedItem: {
                id: {{ $selectedBarang->id ?? 1 }},
                barcode_key: @json($selectedBarang->barcode_key ?? '1010301001.000001'),
                barcode: @json($selectedBarang->barcode ?? 'BMN-88329-01'),
                deskripsi: @json($selectedBarang->deskripsi ?? 'Bolpoint Faster C6 (Hitam 0.7mm)'),
                satuan: @json($selectedBarang->satuan ?? 'Buah'),
                lokasi_rak: @json($selectedBarang->lokasi_rak ?? 'Lemari ATK-1 (Lt.2)')
            },
            copies: 1,
            density: 'High Contrast (120%) - Standar Arsip BHP',
            symbology: 'code128',
            layoutStyle: 'stack',
            labelPreset: '75x50',
            printerWidth: 576,
            labelHeight: 340,
            verticalOffset: -7,
            labelWidthMm: 75,
            labelHeightMm: 50,
            labelPresetLabel: '75 x 50 mm (Tinggi Pas 340px)',
            zoomLevel: 1,
            toastMessage: '',
            showToast: false,
            isConnected: false,
            connectionType: 'None',
            deviceName: 'RPP02N Thermal',
            bluetoothCharacteristic: null,
            serialPort: null,

            // ISO/IEC 15417 Code 128 Pattern Table
            code128Patterns: [
                '212222','222122','222221','121223','121322','131222','122213','122312','132212','221213',
                '221312','231212','112232','122132','122231','113222','123122','123221','223211','221132',
                '221231','213212','223112','312131','311222','321122','321221','312212','322112','322211',
                '212123','212321','232121','111323','131123','131321','112313','132113','132311','211313',
                '231113','231311','112133','112331','132131','113123','113321','133121','313121','211331',
                '231131','213113','213311','213131','311123','311321','331121','312113','312311','332111',
                '314111','221411','431111','111224','111422','121124','121421','141122','141221','112214',
                '112412','122114','122411','142112','142211','241211','221114','413111','241112','134111',
                '111242','121142','121241','114212','124112','124211','411212','421112','421211','212141',
                '214121','412121','111143','111341','131141','114113','114311','411113','411311','113141',
                '114131','311141','411131','211412','211214','211232','2331112'
            ],

            init() {
                setTimeout(() => {
                    this.renderCanvas();
                }, 100);
            },

            setPreset(preset) {
                this.labelPreset = preset;
                if (preset === '75x50') {
                    this.printerWidth = 576;
                    this.labelHeight = 340;
                    this.verticalOffset = -7;
                    this.labelWidthMm = 75;
                    this.labelHeightMm = 50;
                    this.labelPresetLabel = '75 x 50 mm (Tinggi Pas 340px)';
                } else if (preset === '75x40') {
                    this.printerWidth = 576;
                    this.labelHeight = 280;
                    this.verticalOffset = -5;
                    this.labelWidthMm = 75;
                    this.labelHeightMm = 40;
                    this.labelPresetLabel = '75 x 40 mm (280 px)';
                } else if (preset === '75x30') {
                    this.printerWidth = 576;
                    this.labelHeight = 210;
                    this.verticalOffset = -4;
                    this.labelWidthMm = 75;
                    this.labelHeightMm = 30;
                    this.labelPresetLabel = '75 x 30 mm (210 px)';
                } else if (preset === '75x20') {
                    this.printerWidth = 576;
                    this.labelHeight = 150;
                    this.verticalOffset = 0;
                    this.labelWidthMm = 75;
                    this.labelHeightMm = 20;
                    this.labelPresetLabel = '75 x 20 mm (150 px)';
                } else if (preset === '58x40') {
                    this.printerWidth = 384;
                    this.labelHeight = 280;
                    this.verticalOffset = -5;
                    this.labelWidthMm = 58;
                    this.labelHeightMm = 40;
                    this.labelPresetLabel = '58 x 40 mm (280 px)';
                } else if (preset === '58x30') {
                    this.printerWidth = 384;
                    this.labelHeight = 210;
                    this.verticalOffset = -4;
                    this.labelWidthMm = 58;
                    this.labelHeightMm = 30;
                    this.labelPresetLabel = '58 x 30 mm (210 px)';
                }
                this.renderCanvas();
                this.triggerToast('Ukuran diubah: ' + this.labelPresetLabel);
            },

            adjustHeightPixels(deltaPx) {
                let newH = this.labelHeight + deltaPx;
                if (newH < 120) newH = 120;
                if (newH > 420) newH = 420;
                this.labelHeight = newH;
                this.labelPreset = 'custom';
                this.labelPresetLabel = this.labelWidthMm + ' x ' + this.labelHeightMm + ' mm (' + this.printerWidth + ' x ' + this.labelHeight + ' px)';
                this.renderCanvas();
            },

            setVerticalOffset(offset) {
                this.verticalOffset = offset;
                this.renderCanvas();
            },

            triggerToast(msg) {
                this.toastMessage = msg;
                this.showToast = true;
                setTimeout(() => this.showToast = false, 4000);
            },

            setBarang(item) {
                this.selectedItem = item;
                this.renderCanvas();
                this.triggerToast('Memuat pratinjau: ' + item.deskripsi);
            },

            wrapText(ctx, text, maxWidth) {
                const words = (text || '').trim().split(/\s+/);
                const lines = [];
                let currentLine = '';

                for (let i = 0; i < words.length; i++) {
                    const word = words[i];
                    const testLine = currentLine ? currentLine + ' ' + word : word;
                    if (ctx.measureText(testLine).width <= maxWidth) {
                        currentLine = testLine;
                    } else {
                        if (currentLine) lines.push(currentLine);
                        currentLine = word;
                    }
                }
                if (currentLine) lines.push(currentLine);
                return lines;
            },

            drawQrPattern(ctx, qrX, qrY, qrSize) {
                ctx.fillStyle = '#000000';
                const eyeSize = Math.max(16, Math.round(qrSize * 0.28));
                const innerEye = Math.max(6, Math.round(eyeSize * 0.4));

                // 3 Finder patterns (Top-Left, Top-Right, Bottom-Left)
                ctx.fillRect(qrX, qrY, eyeSize, eyeSize);
                ctx.clearRect(qrX + 3, qrY + 3, eyeSize - 6, eyeSize - 6);
                ctx.fillRect(qrX + (eyeSize - innerEye) / 2, qrY + (eyeSize - innerEye) / 2, innerEye, innerEye);

                ctx.fillRect(qrX + qrSize - eyeSize, qrY, eyeSize, eyeSize);
                ctx.clearRect(qrX + qrSize - eyeSize + 3, qrY + 3, eyeSize - 6, eyeSize - 6);
                ctx.fillRect(qrX + qrSize - eyeSize + (eyeSize - innerEye) / 2, qrY + (eyeSize - innerEye) / 2, innerEye, innerEye);

                ctx.fillRect(qrX, qrY + qrSize - eyeSize, eyeSize, eyeSize);
                ctx.clearRect(qrX + 3, qrY + qrSize - eyeSize + 3, eyeSize - 6, eyeSize - 6);
                ctx.fillRect(qrX + (eyeSize - innerEye) / 2, qrY + qrSize - eyeSize + (eyeSize - innerEye) / 2, innerEye, innerEye);

                // Modules
                const dotStep = Math.max(4, Math.round(qrSize / 21));
                const dotSize = dotStep * 0.7;
                for (let dx = 4; dx < qrSize - 4; dx += dotStep) {
                    for (let dy = 4; dy < qrSize - 4; dy += dotStep) {
                        if (dx < eyeSize && dy < eyeSize) continue;
                        if (dx > qrSize - eyeSize && dy < eyeSize) continue;
                        if (dx < eyeSize && dy > qrSize - eyeSize) continue;

                        if ((Math.sin(dx * 12.9898 + dy * 78.233) * 43758.5453) % 1 > 0.45) {
                            ctx.fillRect(qrX + dx, qrY + dy, dotSize, dotSize);
                        }
                    }
                }
            },

            renderCanvas() {
                const canvas = document.getElementById('thermal-canvas');
                if (!canvas) return;
                const ctx = canvas.getContext('2d');
                const w = parseInt(this.printerWidth) || 576;
                const h = parseInt(this.labelHeight) || 340;
                canvas.width = w;
                canvas.height = h;

                // 1. Background Putih
                ctx.fillStyle = '#FFFFFF';
                ctx.fillRect(0, 0, w, h);

                // 2. Garis Batas Luar Stiker (Border)
                ctx.strokeStyle = '#000000';
                ctx.lineWidth = 2;
                ctx.strokeRect(3, 3, w - 6, h - 6);

                const headerH = 26;
                const vOff = parseInt(this.verticalOffset) || -7;

                // 3. Header Atas: Instansi (Posisi Atas Kompak)
                ctx.fillStyle = '#000000';
                ctx.font = '900 12px Arial, Helvetica, sans-serif';
                ctx.textAlign = 'left';
                ctx.fillText('BHP SURABAYA - PERSEDIAAN', 8, 18);

                ctx.font = '900 11px Arial, Helvetica, sans-serif';
                ctx.textAlign = 'right';
                ctx.fillText('BHP', w - 8, 18);

                // Garis Pemisah Header
                ctx.beginPath();
                ctx.moveTo(3, headerH);
                ctx.lineTo(w - 3, headerH);
                ctx.lineWidth = 1.2;
                ctx.stroke();

                const barcodeStr = (this.selectedItem.barcode_key || '1010301001.000001').trim();

                // 4. ISI KONTEN (TATA LETAK)
                if (this.layoutStyle === 'stack') {
                    // --- TATA LETAK ATAS-BAWAH (VERTIKAL) ---
                    // Posisi Barcode lebih ke atas sesuai verticalOffset
                    const barY = Math.max(headerH + 4, headerH + 6 + vOff);

                    // A. Bagian Atas: Barcode / QR Code
                    if (this.symbology === 'code128') {
                        let chars = [104]; // Start B
                        let checksum = 104;
                        for (let i = 0; i < barcodeStr.length; i++) {
                            let code = barcodeStr.charCodeAt(i) - 32;
                            chars.push(code);
                            checksum += code * (i + 1);
                        }
                        chars.push(checksum % 103);
                        chars.push(106); // Stop

                        let patternSequence = chars.map(c => this.code128Patterns[c] || '212222').join('');
                        let totalModules = 0;
                        for (let p of patternSequence) totalModules += parseInt(p);

                        let availableW = w - 40;
                        let moduleWidth = Math.max(1, Math.min(3, Math.floor(availableW / totalModules)));
                        let barWidth = totalModules * moduleWidth;
                        let startX = Math.round((w - barWidth) / 2);
                        let barHeight = Math.min(80, Math.max(50, Math.round((h - headerH) * 0.26)));

                        let curX = startX;
                        let isBar = true;
                        for (let p of patternSequence) {
                            let widthVal = parseInt(p) * moduleWidth;
                            if (isBar) {
                                ctx.fillRect(curX, barY, widthVal, barHeight);
                            }
                            curX += widthVal;
                            isBar = !isBar;
                        }

                        ctx.font = 'bold 12px monospace';
                        ctx.textAlign = 'center';
                        ctx.fillText('* ' + barcodeStr + ' *', Math.round(w / 2), barY + barHeight + 13);
                        var midY = barY + barHeight + 19;
                    } else {
                        let qrSize = Math.min(w - 40, Math.round((h - headerH) * 0.36));
                        let qrX = Math.round((w - qrSize) / 2);
                        this.drawQrPattern(ctx, qrX, barY, qrSize);

                        ctx.font = 'bold 11px monospace';
                        ctx.textAlign = 'center';
                        ctx.fillText(barcodeStr, Math.round(w / 2), barY + qrSize + 13);
                        var midY = barY + qrSize + 19;
                    }

                    // Garis Pemisah Tengah Horizontal
                    ctx.beginPath();
                    ctx.moveTo(3, midY);
                    ctx.lineTo(w - 3, midY);
                    ctx.stroke();

                    // B. Bagian Bawah: Informasi Barang (Mulai tepat di bawah garis tengah)
                    ctx.fillStyle = '#000000';
                    ctx.textAlign = 'left';

                    // Nama Barang
                    ctx.font = 'bold 15px Arial, Helvetica, sans-serif';
                    let descLines = this.wrapText(ctx, this.selectedItem.deskripsi || 'Barang Persediaan', w - 30);
                    let lineY = midY + 18;
                    for (let i = 0; i < Math.min(2, descLines.length); i++) {
                        ctx.fillText(descLines[i], 16, lineY);
                        lineY += 18;
                    }

                    // Detail Grid di Bawah Nama
                    ctx.font = 'bold 12px monospace';
                    ctx.fillText('Kode: ' + barcodeStr, 16, lineY + 12);

                    ctx.font = 'bold 12.5px Arial';
                    ctx.fillText('Rak: ' + (this.selectedItem.lokasi_rak || '-'), 16, lineY + 30);

                    const rightColX = Math.round(w * 0.54);
                    ctx.font = '12.5px Arial';
                    ctx.fillText('Satuan: ' + (this.selectedItem.satuan || '-'), rightColX, lineY + 12);
                    ctx.fillText('Kondisi: Baik', rightColX, lineY + 30);

                } else {
                    // --- TATA LETAK KIRI-KANAN (2 KOLOM) ---
                    const splitX = Math.round(w * 0.48);

                    // Garis Pemisah Vertikal
                    ctx.beginPath();
                    ctx.moveTo(splitX, headerH);
                    ctx.lineTo(splitX, h - 3);
                    ctx.stroke();

                    // KOLOM KIRI: Barcode / QR Code
                    const barY = Math.max(headerH + 4, headerH + 8 + vOff);

                    if (this.symbology === 'code128') {
                        let chars = [104]; // Start B
                        let checksum = 104;
                        for (let i = 0; i < barcodeStr.length; i++) {
                            let code = barcodeStr.charCodeAt(i) - 32;
                            chars.push(code);
                            checksum += code * (i + 1);
                        }
                        chars.push(checksum % 103);
                        chars.push(106); // Stop

                        let patternSequence = chars.map(c => this.code128Patterns[c] || '212222').join('');
                        let totalModules = 0;
                        for (let p of patternSequence) totalModules += parseInt(p);

                        let availableWidth = splitX - 16;
                        let moduleWidth = Math.max(1, Math.floor(availableWidth / totalModules));
                        let barWidth = totalModules * moduleWidth;
                        let startX = Math.round(8 + (availableWidth - barWidth) / 2);
                        let barHeight = Math.min(140, Math.round((h - headerH) * 0.52));

                        let curX = startX;
                        let isBar = true;
                        for (let p of patternSequence) {
                            let widthVal = parseInt(p) * moduleWidth;
                            if (isBar) {
                                ctx.fillRect(curX, barY, widthVal, barHeight);
                            }
                            curX += widthVal;
                            isBar = !isBar;
                        }

                        ctx.font = 'bold 11px monospace';
                        ctx.textAlign = 'center';
                        ctx.fillText('* ' + barcodeStr + ' *', Math.round(splitX / 2), barY + barHeight + 14);

                    } else {
                        // QR Code
                        let qrSize = Math.min(splitX - 26, Math.round((h - headerH) * 0.56));
                        let qrX = Math.round((splitX - qrSize) / 2);
                        this.drawQrPattern(ctx, qrX, barY, qrSize);

                        ctx.font = 'bold 10px monospace';
                        ctx.textAlign = 'center';
                        ctx.fillText(barcodeStr, Math.round(splitX / 2), barY + qrSize + 13);
                    }

                    // KOLOM KANAN: Informasi Barang
                    ctx.fillStyle = '#000000';
                    ctx.textAlign = 'left';
                    const textStartX = splitX + 10;
                    const maxTextWidth = w - textStartX - 8;

                    let currentY = Math.max(headerH + 8, headerH + 12 + vOff);

                    // Section Title
                    ctx.font = '900 10px Arial, Helvetica, sans-serif';
                    ctx.fillStyle = '#64748b';
                    ctx.fillText('NAMA BARANG:', textStartX, currentY);
                    currentY += 16;

                    // Nama Barang
                    ctx.fillStyle = '#000000';
                    ctx.font = 'bold 14px Arial, Helvetica, sans-serif';
                    let descLines = this.wrapText(ctx, this.selectedItem.deskripsi || 'Barang Persediaan', maxTextWidth);
                    for (let i = 0; i < Math.min(3, descLines.length); i++) {
                        ctx.fillText(descLines[i], textStartX, currentY);
                        currentY += 18;
                    }

                    // Garis Pemisah Kecil
                    currentY += 3;
                    ctx.beginPath();
                    ctx.moveTo(textStartX, currentY);
                    ctx.lineTo(w - 10, currentY);
                    ctx.strokeStyle = '#cbd5e1';
                    ctx.stroke();
                    ctx.strokeStyle = '#000000';
                    currentY += 14;

                    // Kode BMN
                    ctx.font = '900 9.5px Arial, Helvetica, sans-serif';
                    ctx.fillStyle = '#64748b';
                    ctx.fillText('KODE BARCODE / BMN:', textStartX, currentY);
                    currentY += 14;

                    ctx.font = 'bold 12px monospace';
                    ctx.fillStyle = '#000000';
                    ctx.fillText(barcodeStr, textStartX, currentY);
                    currentY += 18;

                    // Lokasi Rak
                    ctx.font = '900 9.5px Arial, Helvetica, sans-serif';
                    ctx.fillStyle = '#64748b';
                    ctx.fillText('LOKASI PENYIMPANAN:', textStartX, currentY);
                    currentY += 14;

                    ctx.font = 'bold 13px Arial, Helvetica, sans-serif';
                    ctx.fillStyle = '#000000';
                    let rakStr = this.selectedItem.lokasi_rak || 'Gudang Utama';
                    if (ctx.measureText(rakStr).width > maxTextWidth) {
                        rakStr = rakStr.substring(0, 19) + '...';
                    }
                    ctx.fillText(rakStr, textStartX, currentY);
                    currentY += 18;

                    // Satuan & Kondisi
                    ctx.font = '12px Arial, Helvetica, sans-serif';
                    ctx.fillText((this.selectedItem.satuan || 'Buah') + ' • Baik', textStartX, currentY);
                }
            },

            // Konversi Canvas ke ESC/POS Raster Monochrome (GS v 0)
            buildCanvasEscPosBytes() {
                const canvas = document.getElementById('thermal-canvas');
                const ctx = canvas.getContext('2d');
                const w = canvas.width;
                const h = canvas.height;
                const imgData = ctx.getImageData(0, 0, w, h);
                const data = imgData.data;

                const widthBytes = Math.ceil(w / 8);
                const bytes = [];

                for (let c = 0; c < this.copies; c++) {
                    // ESC @ (Initialize)
                    bytes.push(0x1B, 0x40);

                    // GS v 0 0 xL xH yL yH (Raster Bit Image Mode)
                    const xL = widthBytes % 256;
                    const xH = Math.floor(widthBytes / 256);
                    const yL = h % 256;
                    const yH = Math.floor(h / 256);

                    bytes.push(0x1D, 0x76, 0x30, 0x00, xL, xH, yL, yH);

                    // 1-Bit Monochrome packing
                    for (let y = 0; y < h; y++) {
                        for (let xByte = 0; xByte < widthBytes; xByte++) {
                            let byteVal = 0;
                            for (let bit = 0; bit < 8; bit++) {
                                const x = xByte * 8 + bit;
                                if (x < w) {
                                    const idx = (y * w + x) * 4;
                                    const r = data[idx];
                                    const g = data[idx + 1];
                                    const b = data[idx + 2];
                                    const a = data[idx + 3];
                                    const brightness = (r * 0.299 + g * 0.587 + b * 0.114);
                                    if (a > 128 && brightness < 160) {
                                        byteVal |= (1 << (7 - bit));
                                    }
                                }
                            }
                            bytes.push(byteVal);
                        }
                    }
                }

                return new Uint8Array(bytes);
            },

            async connectBluetooth() {
                if (!navigator.bluetooth) {
                    this.triggerToast('Gunakan Google Chrome atau Microsoft Edge untuk Web Bluetooth.');
                    return;
                }
                try {
                    this.triggerToast('Mencari Bluetooth printer thermal...');
                    const device = await navigator.bluetooth.requestDevice({
                        acceptAllDevices: true,
                        optionalServices: [
                            '000018f0-0000-1000-8000-00805f9b34fb',
                            'e7810a71-73ae-499d-8c15-faa9aef0c3f2',
                            '49535343-fe7d-4ae5-8fa9-9fafd205e455',
                            0xffe0,
                            0xffe1
                        ]
                    });

                    const server = await device.gatt.connect();
                    this.deviceName = device.name || 'RPP02N Thermal';

                    const services = await server.getPrimaryServices();
                    for (let s of services) {
                        try {
                            const chars = await s.getCharacteristics();
                            for (let c of chars) {
                                if (c.properties.write || c.properties.writeWithoutResponse) {
                                    this.bluetoothCharacteristic = c;
                                    break;
                                }
                            }
                        } catch (e) {}
                        if (this.bluetoothCharacteristic) break;
                    }

                    this.isConnected = true;
                    this.connectionType = 'Bluetooth';
                    this.triggerToast('Terkoneksi ke ' + this.deviceName + ' via Bluetooth!');
                } catch (err) {
                    console.error(err);
                    this.triggerToast('Koneksi Bluetooth dibatalkan.');
                }
            },

            async connectSerial() {
                if (!('serial' in navigator)) {
                    this.triggerToast('Web Serial belum didukung di browser ini.');
                    return;
                }
                try {
                    this.triggerToast('Membuka port serial COM...');
                    this.serialPort = await navigator.serial.requestPort();
                    await this.serialPort.open({ baudRate: 115200 });
                    this.isConnected = true;
                    this.connectionType = 'Serial COM';
                    this.deviceName = 'RPP02N (COM Port)';
                    this.triggerToast('Terkoneksi ke Port COM Serial!');
                } catch (err) {
                    console.error(err);
                    this.triggerToast('Koneksi Serial dibatalkan.');
                }
            },

            async sendRawData(bytes) {
                if (this.bluetoothCharacteristic) {
                    for (let i = 0; i < bytes.length; i += 100) {
                        const chunk = bytes.slice(i, i + 100);
                        await this.bluetoothCharacteristic.writeValue(chunk);
                        await new Promise(r => setTimeout(r, 20));
                    }
                    return true;
                } else if (this.serialPort && this.serialPort.writable) {
                    const writer = this.serialPort.writable.getWriter();
                    await writer.write(bytes);
                    writer.releaseLock();
                    return true;
                }
                return false;
            },

            async printThermal() {
                this.renderCanvas();

                if (this.isConnected) {
                    this.triggerToast('Mencetak fisik raster bitmap ESC/POS ke ' + this.deviceName + '...');
                    try {
                        const bytes = this.buildCanvasEscPosBytes();
                        await this.sendRawData(bytes);
                        this.triggerToast('Sukses! Label fisik dicetak persis seperti pratinjau.');
                    } catch (err) {
                        console.error(err);
                        this.triggerToast('Gagal kirim ke printer: ' + err.message);
                    }
                } else {
                    // Dialog windows print fallback
                    this.triggerToast('Membuka cetak Windows untuk printer...');
                    window.open('/inventaris/' + this.selectedItem.id + '/print?w=' + this.labelWidthMm + '&h=' + this.labelHeightMm, '_blank');
                }
            },

            async testFeed() {
                if (this.isConnected) {
                    this.triggerToast('Memajukan kertas 10mm...');
                    const enc = new TextEncoder();
                    const bytes = new Uint8Array([0x1B, 0x40, 0x0A, 0x0A, 0x0A]);
                    await this.sendRawData(bytes);
                } else {
                    this.triggerToast('Koneksikan Bluetooth printer terlebih dahulu.');
                }
            },

            downloadBitmap() {
                const canvas = document.getElementById('thermal-canvas');
                if (!canvas) return;
                const link = document.createElement('a');
                link.download = 'label_' + this.selectedItem.barcode_key + '.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
                this.triggerToast('Render bitmap label berhasil diunduh.');
            }
        };
    }
    </script>
</x-app-layout>
