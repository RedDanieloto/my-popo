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

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Custom scrollbar & safe areas */
        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            padding-bottom: calc(5.5rem + env(safe-area-inset-bottom, 0px));
        }
        @media (min-width: 768px) {
            body {
                padding-bottom: 2rem;
            }
        }
        header.app-header {
            padding-top: max(1rem, calc(0.75rem + env(safe-area-inset-top, 0px)));
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

    <!-- Top Header (Responsive Desktop + Mobile) -->
    <header class="app-header sticky top-0 z-40 bg-zinc-950/90 backdrop-blur-md border-b border-zinc-800/60 pb-3 px-4 md:px-8">
        <div class="max-w-md md:max-w-5xl lg:max-w-6xl mx-auto flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-cyan-500 to-emerald-400 p-0.5 flex items-center justify-center shadow-lg shadow-cyan-500/20">
                    <div class="w-full h-full bg-zinc-950 rounded-[10px] flex items-center justify-center">
                        <i class="bi bi-speedometer text-cyan-400 text-lg"></i>
                    </div>
                </div>
                <div>
                    <h1 class="text-base md:text-lg font-bold tracking-tight text-white flex items-center gap-2">
                        My Popo
                    </h1>
                    <p class="text-xs text-zinc-400 font-medium">
                        {{ $vehicle->name ?? 'Mi Pointer 2005' }}
                    </p>
                </div>
            </a>

            <!-- Desktop Navigation Menu (Visible on md and above) -->
            <nav class="hidden md:flex items-center gap-1 bg-zinc-900/80 p-1.5 rounded-2xl border border-zinc-800/80">
                <a href="{{ route('dashboard') }}" class="px-3.5 py-2 rounded-xl text-xs font-semibold flex items-center gap-2 transition {{ request()->routeIs('dashboard') ? 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/30' : 'text-zinc-400 hover:text-white hover:bg-zinc-800/60' }}">
                    <i class="bi bi-speedometer2 text-sm"></i>
                    <span>Inicio</span>
                </a>
                <a href="{{ route('trips.track') }}" class="px-3.5 py-2 rounded-xl text-xs font-semibold flex items-center gap-2 transition {{ request()->routeIs('trips.track') ? 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/30' : 'text-zinc-400 hover:text-white hover:bg-zinc-800/60' }}">
                    <i class="bi bi-geo-alt-fill text-sm"></i>
                    <span>Recorrido</span>
                </a>
                <a href="{{ route('fuel_loads.create') }}" class="px-3.5 py-2 rounded-xl text-xs font-semibold flex items-center gap-2 transition {{ request()->routeIs('fuel_loads.create') ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'text-zinc-400 hover:text-white hover:bg-zinc-800/60' }}">
                    <i class="bi bi-fuel-pump-fill text-sm"></i>
                    <span>Cargar Gasolina</span>
                </a>
                <a href="{{ route('history.index') }}" class="px-3.5 py-2 rounded-xl text-xs font-semibold flex items-center gap-2 transition {{ request()->routeIs('history.index') ? 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/30' : 'text-zinc-400 hover:text-white hover:bg-zinc-800/60' }}">
                    <i class="bi bi-clock-history text-sm"></i>
                    <span>Historial</span>
                </a>
                <a href="{{ route('stats.index') }}" class="px-3.5 py-2 rounded-xl text-xs font-semibold flex items-center gap-2 transition {{ request()->routeIs('stats.index') ? 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/30' : 'text-zinc-400 hover:text-white hover:bg-zinc-800/60' }}">
                    <i class="bi bi-bar-chart-line-fill text-sm"></i>
                    <span>Stats</span>
                </a>
            </nav>

            <a href="{{ route('vehicle.edit') }}" class="p-2 rounded-xl text-zinc-400 hover:text-white hover:bg-zinc-900 transition border border-transparent hover:border-zinc-800 flex items-center gap-2" title="Configurar Vehículo">
                <i class="bi bi-gear-fill text-lg"></i>
                <span class="hidden md:inline text-xs font-semibold">Configuración</span>
            </a>
        </div>
    </header>

    <!-- Main Container (Expanded max-width on Desktop) -->
    <main class="flex-1 max-w-md md:max-w-5xl lg:max-w-6xl w-full mx-auto px-4 md:px-8 py-4 md:py-6 space-y-4 md:space-y-6">
        <!-- Flash Notifications -->
        @if (session('success'))
            <div class="glass-card bg-emerald-950/40 border-emerald-500/30 text-emerald-300 px-4 py-3 rounded-2xl flex items-center gap-3 text-sm animate-fade-in">
                <i class="bi bi-check-circle-fill text-emerald-400 text-lg flex-shrink-0"></i>
                <div class="font-medium">{{ session('success') }}</div>
            </div>
        @endif

        @if ($errors->any())
            <div class="glass-card bg-rose-950/40 border-rose-500/30 text-rose-300 px-4 py-3 rounded-2xl text-sm space-y-1">
                <div class="font-bold flex items-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill text-rose-400"></i>
                    <span>Por favor corrige los siguientes errores:</span>
                </div>
                <ul class="list-disc list-inside text-xs space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif



        {{ $slot }}
    </main>

    <!-- Bottom Navigation Bar (Mobile Only - Hidden on Desktop) -->
    <nav class="app-nav fixed bottom-0 left-0 right-0 z-40 bg-zinc-950/90 backdrop-blur-xl border-t border-zinc-800/80 px-4 py-2 md:hidden">
        <div class="max-w-md mx-auto flex items-center justify-around">
            
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1 p-1.5 transition {{ request()->routeIs('dashboard') ? 'text-cyan-400 font-semibold' : 'text-zinc-500 hover:text-zinc-300' }}">
                <i class="bi bi-speedometer2 text-xl"></i>
                <span class="text-[10px]">Inicio</span>
            </a>

            <!-- Recorrido -->
            <a href="{{ route('trips.track') }}" class="flex flex-col items-center gap-1 p-1.5 transition {{ request()->routeIs('trips.track') ? 'text-cyan-400 font-semibold' : 'text-zinc-500 hover:text-zinc-300' }}">
                <i class="bi bi-geo-alt-fill text-xl"></i>
                <span class="text-[10px]">Recorrido</span>
            </a>

            <!-- Agregar Gasolina Button -->
            <a href="{{ route('fuel_loads.create') }}" class="flex flex-col items-center -mt-5">
                <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-cyan-500 to-emerald-400 p-0.5 shadow-lg shadow-cyan-500/30 hover:scale-105 active:scale-95 transition-transform flex items-center justify-center">
                    <div class="w-full h-full bg-zinc-950 rounded-full flex items-center justify-center text-cyan-400">
                        <i class="bi bi-fuel-pump-fill text-xl"></i>
                    </div>
                </div>
                <span class="text-[10px] mt-1 text-zinc-400 font-medium">Cargar</span>
            </a>

            <!-- Historial -->
            <a href="{{ route('history.index') }}" class="flex flex-col items-center gap-1 p-1.5 transition {{ request()->routeIs('history.index') ? 'text-cyan-400 font-semibold' : 'text-zinc-500 hover:text-zinc-300' }}">
                <i class="bi bi-clock-history text-xl"></i>
                <span class="text-[10px]">Historial</span>
            </a>

            <!-- Estadísticas -->
            <a href="{{ route('stats.index') }}" class="flex flex-col items-center gap-1 p-1.5 transition {{ request()->routeIs('stats.index') ? 'text-cyan-400 font-semibold' : 'text-zinc-500 hover:text-zinc-300' }}">
                <i class="bi bi-bar-chart-line-fill text-xl"></i>
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
