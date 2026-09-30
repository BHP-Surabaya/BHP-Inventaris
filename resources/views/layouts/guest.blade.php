<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'InvBHP') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .bg-auth {
                background-image: linear-gradient(
                    to right,
                    rgba(10, 15, 30, 0.70) 0%,
                    rgba(10, 15, 30, 0.40) 50%,
                    rgba(10, 15, 30, 0.65) 100%
                ), url('{{ asset("images/warehouse-bg.jpg") }}');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
            }
            .glass-auth {
                background: rgba(30, 41, 59, 0.52);
                backdrop-filter: blur(24px);
                -webkit-backdrop-filter: blur(24px);
                border: 1px solid rgba(255, 255, 255, 0.22);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6), inset 0 1px 1px rgba(255, 255, 255, 0.25);
            }
        </style>
    </head>
    <body class="font-sans antialiased text-slate-100 min-h-screen bg-auth flex flex-col justify-center items-center py-8 px-4">
        <div class="mb-6">
            <a href="/">
                <x-application-logo class="text-white" />
            </a>
        </div>

        <div class="w-full sm:max-w-md px-8 py-8 glass-auth shadow-2xl rounded-3xl text-white">
            {{ $slot }}
        </div>
    </body>
</html>
