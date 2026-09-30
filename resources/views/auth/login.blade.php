<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'InvBHP') }} - Login</title>

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

        .bg-login {
            background-image: linear-gradient(
                to right,
                rgba(10, 15, 30, 0.65) 0%,
                rgba(10, 15, 30, 0.30) 45%,
                rgba(10, 15, 30, 0.55) 100%
            ), url('{{ asset("images/warehouse-bg.jpg") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .glass-panel {
            background: rgba(30, 41, 59, 0.46);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.22);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.55), inset 0 1px 1px rgba(255, 255, 255, 0.25);
        }

        .input-box {
            background-color: #FFFFFF;
            transition: all 0.2s ease-in-out;
        }

        .input-box:focus-within {
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.35);
            border-color: #2563EB;
        }
    </style>
</head>
<body class="antialiased min-h-screen bg-login flex items-center justify-center p-4 sm:p-6 lg:p-12 selection:bg-amber-500 selection:text-white">

    <div class="w-full max-w-6xl mx-auto flex flex-col lg:flex-row items-center justify-between gap-10 lg:gap-16 py-6">

        <!-- Left Side: Brand Identity (InvBHP) -->
        <div class="w-full lg:w-1/2 text-white flex flex-col justify-center animate-fade-in">
            
            <!-- Logo Row: Icon + Title -->
            <div class="flex items-center gap-4 mb-4">
                <!-- Courthouse Building Icon with 3 Pillars & Gold Accent -->
                <svg class="w-14 h-14 sm:w-16 sm:h-16 shrink-0 drop-shadow-lg" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Gable roof triangle -->
                    <path d="M50 14L84 34H16L50 14Z" stroke="#FFFFFF" stroke-width="5.5" stroke-linejoin="round" stroke-linecap="round"/>
                    <!-- Entablature / Roof Base Beam -->
                    <rect x="13" y="36.5" width="74" height="6.5" rx="3.25" fill="#FFFFFF"/>
                    <!-- 3 Pillars -->
                    <rect x="22.5" y="46" width="10" height="23" rx="2" fill="#FFFFFF"/>
                    <rect x="45" y="46" width="10" height="23" rx="2" fill="#FFFFFF"/>
                    <rect x="67.5" y="46" width="10" height="23" rx="2" fill="#FFFFFF"/>
                    <!-- Golden Yellow Stylobate Bar -->
                    <rect x="18" y="72" width="64" height="5.5" rx="2.75" fill="#F59E0B"/>
                    <!-- Plinth / Bottom Base -->
                    <rect x="11" y="80.5" width="78" height="6.5" rx="3.25" fill="#FFFFFF"/>
                </svg>

                <!-- Brand Name -->
                <h1 class="text-4xl sm:text-5xl font-bold tracking-tight text-white drop-shadow-md">
                    Inv<span class="text-amber-400 font-extrabold">BHP</span>
                </h1>
            </div>

            <!-- Subtitle (Deskripsi Sistem) -->
            <div class="text-lg sm:text-xl font-medium text-white/95 leading-snug drop-shadow mb-5">
                Sistem Pendukung Inventaris Terpadu<br>
                Balai Harta Peninggalan
            </div>

            <!-- Golden Yellow Divider Bar -->
            <div class="w-12 h-1 bg-amber-400 rounded-full mb-5 shadow-sm shadow-amber-400/50"></div>

            <!-- Motto / Slogan -->
            <div class="text-sm sm:text-base text-slate-100/90 leading-relaxed drop-shadow-sm font-normal">
                Melayani dengan Integritas,<br>
                Menuju Kepastian Hukum dan Akuntabilitas
            </div>

        </div>

        <!-- Right Side: Glassmorphism Login Card -->
        <div class="w-full lg:w-[440px] shrink-0">
            <div class="glass-panel rounded-[28px] p-7 sm:p-9 text-white">

                <!-- Circular Avatar Header -->
                <div class="flex flex-col items-center mb-6">
                    <div class="w-14 h-14 rounded-full border-2 border-white/80 flex items-center justify-center mb-3 shadow-inner bg-white/10">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold tracking-tight text-white">Login</h2>
                    <p class="text-xs sm:text-sm text-slate-200/80 mt-1 text-center">
                        Masuk untuk mengakses aplikasi InvBHP
                    </p>
                </div>

                <!-- Session Status Message -->
                @if (session('status'))
                    <div class="mb-4 p-3 rounded-xl bg-emerald-500/20 border border-emerald-400/40 text-emerald-200 text-xs text-center">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Validation Errors Alert -->
                @if (isset($errors) && $errors->any())
                    <div class="mb-4 p-3 rounded-xl bg-red-500/20 border border-red-400/40 text-red-200 text-xs text-center">
                        {{ $errors->first() }}
                    </div>
                @endif

                <!-- Form Login -->
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- Username / Email Input -->
                    <div>
                        <div class="input-box flex items-center px-3.5 py-3 rounded-xl border border-slate-200">
                            <!-- User Icon -->
                            <svg class="w-5 h-5 text-slate-400 shrink-0 me-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <input 
                                id="email" 
                                type="text" 
                                name="email" 
                                value="{{ old('email') }}" 
                                required 
                                autofocus 
                                autocomplete="username"
                                placeholder="Username"
                                class="w-full bg-transparent border-0 p-0 text-sm text-slate-800 placeholder-slate-400 focus:ring-0 focus:outline-none"
                            >
                        </div>
                    </div>

                    <!-- Password Input with Toggle Eye -->
                    <div>
                        <div class="input-box flex items-center px-3.5 py-3 rounded-xl border border-slate-200">
                            <!-- Lock Icon -->
                            <svg class="w-5 h-5 text-slate-400 shrink-0 me-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <input 
                                id="password" 
                                type="password" 
                                name="password" 
                                required 
                                autocomplete="current-password"
                                placeholder="Password"
                                class="w-full bg-transparent border-0 p-0 text-sm text-slate-800 placeholder-slate-400 focus:ring-0 focus:outline-none"
                            >
                            <!-- Eye Toggle Button -->
                            <button 
                                type="button" 
                                onclick="togglePasswordVisibility()" 
                                class="text-slate-400 hover:text-slate-600 focus:outline-none ms-2"
                                title="Lihat/Sembunyikan Sandi"
                            >
                                <!-- Eye Open Icon -->
                                <svg id="eyeOpenIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <!-- Eye Closed Icon (Hidden by default) -->
                                <svg id="eyeClosedIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me & Forgot Password Row -->
                    <div class="flex items-center justify-between text-xs pt-1">
                        <label for="remember_me" class="inline-flex items-center text-slate-200 cursor-pointer select-none">
                            <input 
                                id="remember_me" 
                                type="checkbox" 
                                name="remember" 
                                class="rounded border-white/30 bg-white/20 text-blue-600 shadow-sm focus:ring-blue-500 focus:ring-offset-0 w-4 h-4"
                            >
                            <span class="ms-2">Ingat saya</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-slate-200 hover:text-white hover:underline transition">
                                Lupa Kata Sandi?
                            </a>
                        @endif
                    </div>

                    <!-- Masuk Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full mt-3 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold py-3 px-4 rounded-xl shadow-lg shadow-blue-600/30 flex items-center justify-center gap-2 transition-all duration-150 transform active:scale-[0.99]"
                    >
                        <span>Masuk</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </button>

                    <!-- Register Link -->
                    @if (Route::has('register'))
                        <div class="text-center pt-2 text-xs sm:text-sm text-slate-200">
                            Belum memiliki akun? 
                            <a href="{{ route('register') }}" class="text-amber-400 font-semibold hover:text-amber-300 hover:underline transition">
                                Daftar Akun Baru
                            </a>
                        </div>
                    @endif

                </form>

                <!-- Footer note in card -->
                <div class="mt-6 pt-4 border-t border-white/10 text-center text-[11px] text-slate-300/60 tracking-wide">
                    Balai Harta Peninggalan dan Kurator Negara
                </div>

            </div>
        </div>

    </div>

    <!-- Toggle Password Visibility Script -->
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eyeOpenIcon');
            const eyeClosed = document.getElementById('eyeClosedIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
