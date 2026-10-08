<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@php $errors = $errors ?? new \Illuminate\Support\ViewErrorBag; @endphp
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'InvBHP') }} - Daftar Akun Baru</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }

        .bg-register {
            background-image: linear-gradient(
                to right,
                rgba(10, 15, 30, 0.75) 0%,
                rgba(10, 15, 30, 0.45) 50%,
                rgba(10, 15, 30, 0.70) 100%
            ), url('{{ asset("images/warehouse-bg.jpg") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .custom-input {
            width: 100%;
            border: 1px solid #CBD5E1;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 13.5px;
            color: #1E293B;
            background-color: #FFFFFF;
            transition: all 0.2s ease-in-out;
        }

        .custom-input:focus {
            outline: none;
            border-color: #2563EB;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .custom-input::placeholder {
            color: #94A3B8;
        }

        .step-badge {
            width: 22px;
            height: 22px;
            border-radius: 9999px;
            background-color: #3B82F6;
            color: #FFFFFF;
            font-size: 11px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body class="antialiased min-h-screen bg-register flex items-center justify-center p-3 sm:p-6 lg:p-10 selection:bg-amber-500 selection:text-white">

    <!-- Main Registration Split Card -->
    <div class="w-full max-w-5xl bg-white rounded-[26px] sm:rounded-[30px] shadow-2xl overflow-hidden flex flex-col md:flex-row border border-white/20">

        <!-- ================= LEFT SIDEBAR (DARK NAVY) ================= -->
        <div class="w-full md:w-[320px] lg:w-[360px] bg-[#0c234a] bg-gradient-to-b from-[#0f2956] to-[#091b38] p-7 sm:p-9 text-white flex flex-col justify-between shrink-0">

            <!-- Top: Brand & Logo -->
            <div class="flex flex-col items-center text-center">
                <!-- Courthouse Logo Badge -->
                <div class="w-20 h-20 rounded-2xl bg-white/10 border border-white/15 flex items-center justify-center mb-4 shadow-inner">
                    <svg class="w-12 h-12" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Pediment Triangle -->
                        <path d="M50 14L84 34H16L50 14Z" stroke="#FFFFFF" stroke-width="5.5" stroke-linejoin="round" stroke-linecap="round"/>
                        <!-- Entablature -->
                        <rect x="13" y="36.5" width="74" height="6.5" rx="3.25" fill="#FFFFFF"/>
                        <!-- 3 Pillars -->
                        <rect x="22.5" y="46" width="10" height="23" rx="2" fill="#FFFFFF"/>
                        <rect x="45" y="46" width="10" height="23" rx="2" fill="#FFFFFF"/>
                        <rect x="67.5" y="46" width="10" height="23" rx="2" fill="#FFFFFF"/>
                        <!-- Golden Yellow Stylobate Bar -->
                        <rect x="18" y="72" width="64" height="5.5" rx="2.75" fill="#F59E0B"/>
                        <!-- Base Plinth -->
                        <rect x="11" y="80.5" width="78" height="6.5" rx="3.25" fill="#FFFFFF"/>
                    </svg>
                </div>

                <!-- Brand Name -->
                <h2 class="text-3xl font-bold tracking-tight text-white">
                    Inv<span class="text-amber-400 font-extrabold">BHP</span>
                </h2>

                <!-- Subtitle -->
                <p class="text-xs sm:text-sm text-slate-200/80 mt-1">
                    Balai Harta Peninggalan
                </p>

                <div class="w-10 h-0.5 bg-amber-400/60 rounded-full mt-3 mb-6"></div>
            </div>

            <!-- Bottom: Features / Value Props (matching screenshot) -->
            <div class="space-y-4 pt-4 border-t border-white/10">
                <!-- Feature 1 -->
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-amber-500/20 border border-amber-400/30 flex items-center justify-center shrink-0 mt-0.5 text-amber-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-white">Layanan Resmi BHP</div>
                        <div class="text-[11px] text-slate-300/80 leading-snug">
                            Sistem pendataan dan permohonan inventaris barang secara digital.
                        </div>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-amber-500/20 border border-amber-400/30 flex items-center justify-center shrink-0 mt-0.5 text-amber-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-white">Untuk Petugas & Satuan Kerja</div>
                        <div class="text-[11px] text-slate-300/80 leading-snug">
                            Mendukung akun pegawai, pengelola BMN, dan unit kerja terkait.
                        </div>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-amber-500/20 border border-amber-400/30 flex items-center justify-center shrink-0 mt-0.5 text-amber-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-white">Aman & Terintegrasi</div>
                        <div class="text-[11px] text-slate-300/80 leading-snug">
                            Data terlindungi dan verifikasi mutasi barang lebih cepat dan akurat.
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- ================= RIGHT FORM CONTAINER ================= -->
        <div class="flex-1 p-6 sm:p-9 lg:p-10 bg-white overflow-y-auto">

            <!-- Title & Subtitle -->
            <div class="mb-6">
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">
                    Daftar Akun Baru
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Silakan lengkapi identitas diri Anda untuk mengakses layanan sistem InvBHP.
                </p>
            </div>

            <!-- Global Validation Error Alert -->
            @if (isset($errors) && $errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs sm:text-sm">
                    <div class="font-semibold mb-1 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        Terdapat kesalahan pada formulir:
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 ms-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- ================= 1. DATA IDENTITAS & PROFESI ================= -->
                <div>
                    <!-- Section Header with Number Badge -->
                    <div class="flex items-center gap-2 mb-3">
                        <span class="step-badge">1</span>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">
                            Data Identitas & Hak Akses Akun
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Nama Lengkap -->
                        <div>
                            <label for="name" class="block text-xs font-medium text-slate-700 mb-1">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input 
                                id="name" 
                                type="text" 
                                name="name" 
                                value="{{ old('name') }}" 
                                required 
                                autofocus 
                                autocomplete="name"
                                placeholder="Contoh: Budi Santoso, S.H." 
                                class="custom-input @error('name') border-red-500 @enderror"
                            >
                            @error('name')
                                <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- NIK (Nomor Induk Kependudukan) -->
                        <div>
                            <label for="nik" class="block text-xs font-medium text-slate-700 mb-1">
                                NIK (Nomor Induk Kependudukan)
                            </label>
                            <input 
                                id="nik" 
                                type="text" 
                                name="nik" 
                                value="{{ old('nik') }}" 
                                maxlength="20"
                                placeholder="16 digit NIK pada KTP" 
                                class="custom-input @error('nik') border-red-500 @enderror"
                            >
                            @error('nik')
                                <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Alamat Email -->
                        <div>
                            <label for="email" class="block text-xs font-medium text-slate-700 mb-1">
                                Alamat Email <span class="text-red-500">*</span>
                            </label>
                            <input 
                                id="email" 
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}" 
                                required 
                                autocomplete="username"
                                placeholder="email@contoh.com" 
                                class="custom-input @error('email') border-red-500 @enderror"
                            >
                            @error('email')
                                <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nomor WhatsApp / HP -->
                        <div>
                            <label for="phone" class="block text-xs font-medium text-slate-700 mb-1">
                                Nomor WhatsApp / HP
                            </label>
                            <input 
                                id="phone" 
                                type="text" 
                                name="phone" 
                                value="{{ old('phone') }}" 
                                placeholder="0812 xxxx xxxx" 
                                class="custom-input @error('phone') border-red-500 @enderror"
                            >
                            @error('phone')
                                <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Peran / Hak Akses Akun Sistem -->
                    <div class="mt-4">
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Peran / Role Akun Sistem <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <!-- Option 1: Pegawai Gudang -->
                            <label class="relative flex items-start gap-3 p-3 rounded-xl border border-slate-200 cursor-pointer transition-all hover:border-blue-400 hover:bg-blue-50/30 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/50 has-[:checked]:ring-2 has-[:checked]:ring-blue-500/20">
                                <input 
                                    type="radio" 
                                    name="role" 
                                    value="pegawai_gudang" 
                                    class="mt-1 text-blue-600 focus:ring-blue-500"
                                    {{ old('role', 'pegawai_gudang') === 'pegawai_gudang' ? 'checked' : '' }}
                                >
                                <div class="flex-1">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-xs font-bold text-slate-800">Pegawai Gudang</span>
                                        <span class="text-[10px] font-semibold bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded">Operasional</span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 mt-1 leading-snug">
                                        Pelayanan Bon Pengeluaran ATK (Kasir Scan Barcode) & Cetak Label thermal.
                                    </p>
                                </div>
                            </label>

                            <!-- Option 2: Administrator BMN -->
                            <label class="relative flex items-start gap-3 p-3 rounded-xl border border-slate-200 cursor-pointer transition-all hover:border-blue-400 hover:bg-blue-50/30 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/50 has-[:checked]:ring-2 has-[:checked]:ring-blue-500/20">
                                <input 
                                    type="radio" 
                                    name="role" 
                                    value="admin" 
                                    class="mt-1 text-blue-600 focus:ring-blue-500"
                                    {{ old('role') === 'admin' ? 'checked' : '' }}
                                >
                                <div class="flex-1">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-xs font-bold text-slate-800">Administrator BMN</span>
                                        <span class="text-[10px] font-semibold bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded">Full Access</span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 mt-1 leading-snug">
                                        Akses penuh: master data barang, pembelian, stock opname, laporan & bon.
                                    </p>
                                </div>
                            </label>
                        </div>
                        @error('role')
                            <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Unit Kerja / Seksi (BHP Surabaya) -->
                    <div class="mt-4">
                        <label for="pekerjaan" class="block text-xs font-medium text-slate-700 mb-1">
                            Unit Kerja / Seksi di Balai Harta Peninggalan
                        </label>
                        <select 
                            id="pekerjaan" 
                            name="pekerjaan" 
                            class="custom-input text-slate-700 cursor-pointer"
                        >
                            <option value="" disabled {{ old('pekerjaan') ? '' : 'selected' }}>-- Pilih Unit Kerja / Seksi --</option>
                            <option value="Subbagian Tata Usaha" {{ old('pekerjaan') == 'Subbagian Tata Usaha' ? 'selected' : '' }}>Subbagian Tata Usaha</option>
                            <option value="Seksi Kurator Negara" {{ old('pekerjaan') == 'Seksi Kurator Negara' ? 'selected' : '' }}>Seksi Kurator Negara</option>
                            <option value="Seksi Pelayanan & Pengawasan Harta Peninggalan" {{ old('pekerjaan') == 'Seksi Pelayanan & Pengawasan Harta Peninggalan' ? 'selected' : '' }}>Seksi Pelayanan & Pengawasan Harta Peninggalan</option>
                            <option value="Pengelola BMN & Gudang Persediaan" {{ old('pekerjaan') == 'Pengelola BMN & Gudang Persediaan' ? 'selected' : '' }}>Pengelola BMN & Gudang Persediaan</option>
                            <option value="Pegawai / Staf Balai Harta Peninggalan" {{ old('pekerjaan') == 'Pegawai / Staf Balai Harta Peninggalan' ? 'selected' : '' }}>Pegawai / Staf Balai Harta Peninggalan</option>
                            <option value="Lainnya" {{ old('pekerjaan') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        <p class="text-[11px] text-slate-400 mt-1 italic">
                            *Unit kerja atau seksi penempatan di Balai Harta Peninggalan Surabaya.
                        </p>
                    </div>
                </div>

                <!-- ================= 2. ALAMAT TINGGAL ================= -->
                <div class="pt-2">
                    <!-- Section Header -->
                    <div class="flex items-center gap-2 mb-3">
                        <span class="step-badge">2</span>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">
                            Alamat Tinggal
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Alamat KTP -->
                        <div>
                            <label for="alamat_ktp" class="block text-xs font-medium text-slate-700 mb-1">
                                Alamat Lengkap (Sesuai KTP)
                            </label>
                            <textarea 
                                id="alamat_ktp" 
                                name="alamat_ktp" 
                                rows="3" 
                                placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota/Kabupaten, Provinsi"
                                class="custom-input resize-none"
                            >{{ old('alamat_ktp') }}</textarea>
                        </div>

                        <!-- Alamat Domisili -->
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label for="alamat_domisili" class="text-xs font-medium text-slate-700">
                                    Alamat Domisili
                                </label>
                                <!-- Copy from KTP pill toggle -->
                                <label class="inline-flex items-center text-[11px] text-blue-600 bg-blue-50 hover:bg-blue-100 border border-blue-200 px-2 py-0.5 rounded-full cursor-pointer transition select-none">
                                    <input 
                                        type="checkbox" 
                                        id="sameAddressCheck" 
                                        onchange="copyAddress()" 
                                        class="rounded text-blue-600 border-blue-300 w-3 h-3 me-1 focus:ring-0"
                                    >
                                    <span>Sama dengan KTP</span>
                                </label>
                            </div>
                            <textarea 
                                id="alamat_domisili" 
                                name="alamat_domisili" 
                                rows="3" 
                                placeholder="Alamat domisili saat ini jika berbeda dengan KTP"
                                class="custom-input resize-none"
                            >{{ old('alamat_domisili') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- ================= 3. KEAMANAN AKUN ================= -->
                <div class="pt-2">
                    <!-- Section Header -->
                    <div class="flex items-center gap-2 mb-3">
                        <span class="step-badge">3</span>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">
                            Keamanan Akun
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-xs font-medium text-slate-700 mb-1">
                                Kata Sandi <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input 
                                    id="password" 
                                    type="password" 
                                    name="password" 
                                    required 
                                    autocomplete="new-password"
                                    placeholder="Minimal 8 karakter" 
                                    class="custom-input pe-10 @error('password') border-red-500 @enderror"
                                >
                                <button 
                                    type="button" 
                                    onclick="togglePassword('password', 'eye1Open', 'eye1Closed')" 
                                    class="absolute inset-y-0 right-0 pe-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none"
                                >
                                    <svg id="eye1Open" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg id="eye1Closed" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Konfirmasi Password -->
                        <div>
                            <label for="password_confirmation" class="block text-xs font-medium text-slate-700 mb-1">
                                Konfirmasi Kata Sandi <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input 
                                    id="password_confirmation" 
                                    type="password" 
                                    name="password_confirmation" 
                                    required 
                                    autocomplete="new-password"
                                    placeholder="Ulangi kata sandi" 
                                    class="custom-input pe-10 @error('password_confirmation') border-red-500 @enderror"
                                >
                                <button 
                                    type="button" 
                                    onclick="togglePassword('password_confirmation', 'eye2Open', 'eye2Closed')" 
                                    class="absolute inset-y-0 right-0 pe-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none"
                                >
                                    <svg id="eye2Open" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg id="eye2Closed" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Button & Login Link -->
                <div class="pt-4 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-slate-100">
                    <div class="text-xs text-slate-600">
                        Sudah memiliki akun? 
                        <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:text-blue-800 hover:underline">
                            Masuk ke Akun Anda
                        </a>
                    </div>

                    <button 
                        type="submit" 
                        class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-sm font-semibold py-3 px-8 rounded-xl shadow-lg shadow-blue-600/25 flex items-center justify-center gap-2 transition-all duration-150 transform active:scale-[0.99]"
                    >
                        <span>Daftar Sekarang</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </button>
                </div>

            </form>

        </div>

    </div>

    <!-- JavaScript Utilities -->
    <script>
        // Auto copy KTP address to Domisili
        function copyAddress() {
            const isChecked = document.getElementById('sameAddressCheck').checked;
            const ktp = document.getElementById('alamat_ktp').value;
            const domisili = document.getElementById('alamat_domisili');

            if (isChecked) {
                domisili.value = ktp;
            }
        }

        // Toggle password show/hide
        function togglePassword(inputId, openIconId, closedIconId) {
            const input = document.getElementById(inputId);
            const open = document.getElementById(openIconId);
            const closed = document.getElementById(closedIconId);

            if (input.type === 'password') {
                input.type = 'text';
                open.classList.add('hidden');
                closed.classList.remove('hidden');
            } else {
                input.type = 'password';
                open.classList.remove('hidden');
                closed.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
