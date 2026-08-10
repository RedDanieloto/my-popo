<!DOCTYPE html>
<html lang="es" class="dark h-full bg-zinc-950 text-zinc-100 antialiased selection:bg-cyan-500 selection:text-black">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'My Popo - Control de Combustible' }}</title>

    <!-- PWA Setup -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#09090b">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="My Popo">
    
    <link rel="icon" type="image/svg+xml" href="/icons/favicon.svg">
    <link rel="apple-touch-icon" href="/icons/apple-touch-icon.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Custom scrollbar & iOS safe areas */
        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            padding-bottom: calc(5.5rem + env(safe-area-inset-bottom, 0px));
        }
        header.app-header {
            padding-top: max(1.25rem, calc(0.75rem + env(safe-area-inset-top, 0px)));
        }
        nav.app-nav {
            padding-bottom: max(0.75rem, calc(0.5rem + env(safe-area-inset-bottom, 0px)));
        }
        .glass-card {
            background: rgba(24, 24, 27, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .glass-pill {
            background: rgba(39, 39, 42, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .glow-cyan {
            box-shadow: 0 0 25px -5px rgba(6, 182, 212, 0.3);
        }
        .glow-red {
            box-shadow: 0 0 25px -5px rgba(239, 68, 68, 0.4);
        }
    </style>
</head>
<body class="bg-zinc-950 text-zinc-100 min-h-screen flex flex-col justify-between selection:bg-cyan-500/30">

    <!-- Top Header -->
    <header class="app-header sticky top-0 z-40 bg-zinc-950/90 backdrop-blur-md border-b border-zinc-800/60 pb-3 px-4">
        <div class="max-w-md mx-auto flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-cyan-500 to-emerald-400 p-0.5 flex items-center justify-center shadow-lg shadow-cyan-500/20">
                    <div class="w-full h-full bg-zinc-950 rounded-[10px] flex items-center justify-center">
                        <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                </div>
                <div>
                    <h1 class="text-base font-bold tracking-tight text-white flex items-center gap-1.5">
                        My Popo
                        <span class="text-[10px] font-semibold bg-zinc-800 text-zinc-400 px-1.5 py-0.5 rounded-full border border-zinc-700">PWA</span>
                    </h1>
                    <p class="text-xs text-zinc-400 font-medium">
                        {{ $vehicle->name ?? 'Mi Pointer 2005' }}
                    </p>
                </div>
            </a>

            <a href="{{ route('vehicle.edit') }}" class="p-2 rounded-xl text-zinc-400 hover:text-white hover:bg-zinc-900 transition border border-transparent hover:border-zinc-800" title="Configurar Vehículo">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </a>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-1 max-w-md w-full mx-auto px-4 py-4 space-y-4">
        <!-- Flash Notifications -->
        @if (session('success'))
            <div class="glass-card bg-emerald-950/40 border-emerald-500/30 text-emerald-300 px-4 py-3 rounded-2xl flex items-center gap-3 text-sm animate-fade-in">
                <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="font-medium">{{ session('success') }}</div>
            </div>
        @endif

        @if ($errors->any())
            <div class="glass-card bg-rose-950/40 border-rose-500/30 text-rose-300 px-4 py-3 rounded-2xl text-sm space-y-1">
                <div class="font-bold">Por favor corrige los siguientes errores:</div>
                <ul class="list-disc list-inside text-xs space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- PWA Installation Banner -->
        <div id="pwa-install-banner" class="hidden glass-card bg-gradient-to-r from-cyan-950/50 to-zinc-900 border-cyan-500/30 p-3.5 rounded-2xl flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center font-bold text-lg">
                    ⚡
                </div>
                <div>
                    <div class="text-xs font-semibold text-white">Instalar My Popo</div>
                    <div class="text-[11px] text-zinc-400">Acceso rápido sin navegador</div>
                </div>
            </div>
            <button id="pwa-install-btn" class="px-3 py-1.5 bg-cyan-500 hover:bg-cyan-400 text-zinc-950 font-bold text-xs rounded-xl shadow-lg shadow-cyan-500/20 transition">
                Instalar
            </button>
        </div>

        {{ $slot }}
    </main>

    <!-- Bottom Navigation Bar (Tesla / iOS Style) -->
    <nav class="app-nav fixed bottom-0 left-0 right-0 z-40 bg-zinc-950/90 backdrop-blur-xl border-t border-zinc-800/80 px-4 py-2">
        <div class="max-w-md mx-auto flex items-center justify-around">
            
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1 p-1.5 transition {{ request()->routeIs('dashboard') ? 'text-cyan-400 font-semibold' : 'text-zinc-500 hover:text-zinc-300' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="text-[10px]">Inicio</span>
            </a>

            <!-- Recorrido -->
            <a href="{{ route('trips.track') }}" class="flex flex-col items-center gap-1 p-1.5 transition {{ request()->routeIs('trips.track') ? 'text-cyan-400 font-semibold' : 'text-zinc-500 hover:text-zinc-300' }}">
                <div class="relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <span class="text-[10px]">Recorrido</span>
            </a>

            <!-- Agregar Gasolina Button -->
            <a href="{{ route('fuel_loads.create') }}" class="flex flex-col items-center -mt-5">
                <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-cyan-500 to-emerald-400 p-0.5 shadow-lg shadow-cyan-500/30 hover:scale-105 active:scale-95 transition-transform flex items-center justify-center">
                    <div class="w-full h-full bg-zinc-950 rounded-full flex items-center justify-center text-cyan-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                </div>
                <span class="text-[10px] mt-1 text-zinc-400 font-medium">Cargar</span>
            </a>

            <!-- Historial -->
            <a href="{{ route('history.index') }}" class="flex flex-col items-center gap-1 p-1.5 transition {{ request()->routeIs('history.index') ? 'text-cyan-400 font-semibold' : 'text-zinc-500 hover:text-zinc-300' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-[10px]">Historial</span>
            </a>

            <!-- Estadísticas -->
            <a href="{{ route('stats.index') }}" class="flex flex-col items-center gap-1 p-1.5 transition {{ request()->routeIs('stats.index') ? 'text-cyan-400 font-semibold' : 'text-zinc-500 hover:text-zinc-300' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span class="text-[10px]">Stats</span>
            </a>

        </div>
    </nav>

    <!-- PWA & Service Worker Registration Script -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('SW registrado con éxito'))
                    .catch(err => console.error('Error al registrar SW:', err));
            });
        }

        // PWA Installation prompt logic
        let deferredPrompt;
        const installBanner = document.getElementById('pwa-install-banner');
        const installBtn = document.getElementById('pwa-install-btn');

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            if (installBanner) installBanner.classList.remove('hidden');
        });

        if (installBtn) {
            installBtn.addEventListener('click', () => {
                if (deferredPrompt) {
                    deferredPrompt.prompt();
                    deferredPrompt.userChoice.then((choiceResult) => {
                        if (choiceResult.outcome === 'accepted') {
                            console.log('El usuario aceptó la instalación');
                        }
                        deferredPrompt = null;
                        if (installBanner) installBanner.classList.add('hidden');
                    });
                }
            });
        }
    </script>
</body>
</html>
