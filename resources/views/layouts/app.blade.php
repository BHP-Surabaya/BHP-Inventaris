<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SIMBAR-BHP') }} - Balai Harta Peninggalan Surabaya</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="font-sans antialiased bg-[#f4f6fb] text-slate-800" x-data="{ sidebarOpen: false }">
        <div class="min-h-screen flex flex-col lg:flex-row">
            <!-- Mobile Top Bar -->
            <header class="lg:hidden bg-[#0c213e] text-white px-5 py-3.5 flex items-center justify-between shadow-md z-30 sticky top-0">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo-pengayoman.svg') }}" 
                         alt="Logo Pengayoman" 
                         class="h-9 w-auto rounded-lg shadow-sm" />
                    <div>
                        <div class="text-base font-extrabold tracking-wide text-white leading-none">BHP</div>
                        <div class="text-[10px] text-amber-400 font-semibold mt-0.5">Surabaya</div>
                    </div>
                </div>
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-xl text-slate-300 hover:text-white hover:bg-white/10 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </header>

            <!-- Backdrop for Mobile Sidebar -->
            <div x-show="sidebarOpen" 
                 x-transition:enter="transition-opacity ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="sidebarOpen = false" 
                 class="fixed inset-0 bg-slate-950/70 z-40 lg:hidden backdrop-blur-sm"
                 style="display: none;"></div>

            <!-- Left Sidebar -->
            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
                   class="fixed inset-y-0 left-0 z-50 w-64 xl:w-72 bg-[#0c213e] text-white flex flex-col justify-between px-5 py-6 transition-transform duration-300 ease-in-out lg:sticky lg:top-0 lg:h-screen flex-shrink-0 shadow-2xl lg:shadow-none overflow-y-auto select-none border-r border-slate-800/40">
                
                <!-- Top: Logo & Navigation -->
                <div class="space-y-7">
                    <!-- Brand Header -->
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-1 group">
                        <img src="{{ asset('images/logo-pengayoman.svg') }}" 
                             alt="Logo Pengayoman Kemenkumham" 
                             class="h-11 w-auto rounded-lg shadow-md ring-1 ring-white/10 group-hover:scale-105 group-hover:ring-amber-400/40 transition-all duration-200" />
                        <div>
                            <div class="text-xl font-extrabold tracking-wide text-white leading-tight">BHP</div>
                            <div class="text-[11px] text-slate-300 font-normal leading-tight">Balai Harta Peninggalan</div>
                            <div class="text-[11px] font-semibold text-amber-400 leading-tight">Surabaya</div>
                        </div>
                    </a>

                    <!-- Navigation Menu -->
                    <nav class="space-y-1.5">
                        <!-- Dashboard -->
                        <a href="{{ route('dashboard') }}" 
                           class="flex items-center gap-3.5 px-4 py-3 rounded-2xl font-medium text-sm transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-[#2563eb] text-white shadow-lg shadow-blue-600/30' : 'text-slate-400 hover:text-white hover:bg-white/[0.06]' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                                <rect x="14" y="3" width="7" height="7" rx="1.5"/>
                                <rect x="3" y="14" width="7" height="7" rx="1.5"/>
                                <rect x="14" y="14" width="7" height="7" rx="1.5"/>
                            </svg>
                            <span>Dashboard</span>
                        </a>


                        <!-- Bon Barang Dropdown Group -->
                        <div x-data="{ 
                                open: {{ request()->routeIs('bon.*') || request()->routeIs('pembelian.*') || request()->routeIs('stock-opname.*') ? 'true' : 'false' }} 
                             }" 
                             class="space-y-1">
                            <!-- Parent Button / Toggle -->
                            <button type="button" 
                                    @click="open = !open" 
                                    class="w-full flex items-center justify-between px-4 py-3 rounded-2xl font-medium text-sm transition-all duration-150 {{ request()->routeIs('bon.*') || request()->routeIs('pembelian.*') || request()->routeIs('stock-opname.*') ? 'bg-white/[0.08] text-white' : 'text-slate-400 hover:text-white hover:bg-white/[0.06]' }}">
                                <div class="flex items-center gap-3.5">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                        <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.5L19 7.5V19a2 2 0 0 1-2 2Z"/>
                                    </svg>
                                    <span>Bon Barang</span>
                                </div>
                                <svg class="w-4 h-4 transition-transform duration-200" 
                                     :class="open ? 'rotate-180 text-white' : 'text-slate-400'" 
                                     fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Submenu Items -->
                            <div x-show="open" 
                                 x-cloak
                                 x-transition:enter="transition ease-out duration-150" 
                                 x-transition:enter-start="opacity-0 -translate-y-1" 
                                 x-transition:enter-end="opacity-100 translate-y-0" 
                                 x-transition:leave="transition ease-in duration-100" 
                                 x-transition:leave-start="opacity-100 translate-y-0" 
                                 x-transition:leave-end="opacity-0 -translate-y-1" 
                                 class="pl-4 pr-1 py-1 space-y-1 border-l border-slate-700/60 ml-6">
                                
                                <!-- Submenu 1: Pengeluaran ATK (Kasir Scan Barcode) -->
                                <a href="{{ route('bon.index') }}" 
                                   class="flex items-center gap-3 px-3 py-2 rounded-xl font-medium text-xs transition-all duration-150 {{ request()->routeIs('bon.*') ? 'bg-[#2563eb] text-white shadow-md shadow-blue-600/30' : 'text-slate-400 hover:text-white hover:bg-white/[0.06]' }}">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                        <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.5L19 7.5V19a2 2 0 0 1-2 2Z"/>
                                    </svg>
                                    <span>Pengeluaran ATK</span>
                                </a>

                                @if (Auth::user()?->isAdmin())
                                    <!-- Submenu 2: Pembelian / Masuk (Khusus Admin) -->
                                    <a href="{{ route('pembelian.index') }}" 
                                       class="flex items-center gap-3 px-3 py-2 rounded-xl font-medium text-xs transition-all duration-150 {{ request()->routeIs('pembelian.*') ? 'bg-[#2563eb] text-white shadow-md shadow-blue-600/30' : 'text-slate-400 hover:text-white hover:bg-white/[0.06]' }}">
                                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                            <circle cx="8" cy="21" r="1"/>
                                            <circle cx="19" cy="21" r="1"/>
                                            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                                        </svg>
                                        <span>Pembelian / Masuk</span>
                                    </a>

                                    <!-- Submenu 3: Stock Opname (Khusus Admin) -->
                                    <a href="{{ route('stock-opname.index') }}" 
                                       class="flex items-center gap-3 px-3 py-2 rounded-xl font-medium text-xs transition-all duration-150 {{ request()->routeIs('stock-opname.*') ? 'bg-[#2563eb] text-white shadow-md shadow-blue-600/30' : 'text-slate-400 hover:text-white hover:bg-white/[0.06]' }}">
                                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                                            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
                                            <path d="m9 14 2 2 4-4"/>
                                        </svg>
                                        <span>Stock Opname</span>
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Cetak Label (Admin & Pegawai Gudang) -->
                        <a href="{{ route('cetak-label.index') }}" 
                           class="flex items-center gap-3.5 px-4 py-3 rounded-2xl font-medium text-sm transition-all duration-150 {{ request()->routeIs('cetak-label.*') ? 'bg-[#2563eb] text-white shadow-lg shadow-blue-600/30' : 'text-slate-400 hover:text-white hover:bg-white/[0.06]' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2"/>
                                <path d="M7 8v8M11 8v8M14 8v8M17 8v8"/>
                            </svg>
                            <span>Cetak Label</span>
                        </a>

                        @if (Auth::user()?->isAdmin())
                            <!-- Cetak Laporan (Khusus Admin) -->
                            <a href="{{ route('laporan.index') }}" 
                               class="flex items-center gap-3.5 px-4 py-3 rounded-2xl font-medium text-sm transition-all duration-150 {{ request()->routeIs('laporan.*') ? 'bg-[#2563eb] text-white shadow-lg shadow-blue-600/30' : 'text-slate-400 hover:text-white hover:bg-white/[0.06]' }}">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                    <line x1="16" y1="13" x2="8" y2="13"/>
                                    <line x1="16" y1="17" x2="8" y2="17"/>
                                    <polyline points="10 9 9 9 8 9"/>
                                </svg>
                                <span>Cetak Laporan</span>
                            </a>
                        @endif
                    </nav>
                </div>

                <!-- Bottom: Background Silhouette, Profile Card & Logout -->
                <div class="relative pt-6">
                    <!-- Subtle Building Silhouette Watermark at the Bottom -->
                    <div class="absolute -top-10 left-0 right-0 pointer-events-none opacity-15 overflow-hidden flex justify-center z-0">
                        <svg viewBox="0 0 240 90" fill="currentColor" class="w-56 text-blue-300">
                            <!-- Pediment / Triangle Roof -->
                            <polygon points="120,4 235,32 5,32" />
                            <rect x="0" y="34" width="240" height="6" rx="2" />
                            <!-- Columns -->
                            <rect x="24" y="44" width="18" height="34" rx="2" />
                            <rect x="66" y="44" width="18" height="34" rx="2" />
                            <rect x="111" y="44" width="18" height="34" rx="2" />
                            <rect x="156" y="44" width="18" height="34" rx="2" />
                            <rect x="198" y="44" width="18" height="34" rx="2" />
                            <rect x="0" y="82" width="240" height="8" rx="2" />
                        </svg>
                    </div>

                    <!-- Profile Card -->
                    <div class="relative z-10 bg-[#0e274c]/90 backdrop-blur-md border border-white/10 rounded-2xl p-3 flex items-center gap-3 shadow-md">
                        <div class="w-10 h-10 rounded-full bg-[#183a68] border border-blue-400/30 text-blue-300 flex items-center justify-center flex-shrink-0 shadow-inner font-bold text-xs">
                            {{ substr(Auth::user()?->name ?? 'U', 0, 2) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-bold text-white truncate">{{ Auth::user()?->name ?? 'Pegawai BHP' }}</div>
                            <div class="text-[11px] text-slate-300 truncate flex items-center gap-1.5 mt-0.5">
                                <span class="inline-block w-1.5 h-1.5 rounded-full {{ Auth::user()?->isAdmin() ? 'bg-amber-400' : 'bg-emerald-400' }}"></span>
                                <span class="capitalize font-medium">{{ str_replace('_', ' ', Auth::user()?->role ?? 'Pegawai Gudang') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Logout Button -->
                    <form method="POST" action="{{ route('logout') }}" class="relative z-10 mt-3">
                        @csrf
                        <button type="submit" class="flex items-center gap-3 px-3 py-2 text-sm text-slate-400 hover:text-rose-300 hover:bg-white/[0.06] rounded-xl transition-colors w-full font-medium">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/>
                            </svg>
                            <span>Keluar</span>
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Content Area -->
            <main class="flex-1 min-w-0 bg-[#f4f6fb] min-h-screen overflow-y-auto">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
