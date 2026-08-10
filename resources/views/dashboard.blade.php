<x-layout :vehicle="$vehicle" title="Dashboard - My Popo">

    <!-- Active Trip Banner if currently tracking -->
    @if ($activeTrip)
        <div class="glass-card bg-gradient-to-r from-amber-950/40 via-zinc-900 to-amber-950/40 border-amber-500/30 p-4 rounded-3xl animate-pulse">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 rounded-full bg-amber-400 animate-ping"></div>
                    <div>
                        <div class="text-xs font-semibold text-amber-300">Recorrido en curso...</div>
                        <div class="text-[11px] text-zinc-400">Iniciado a las {{ $activeTrip->start_time->format('H:i') }}</div>
                    </div>
                </div>
                <a href="{{ route('trips.track') }}" class="px-3.5 py-1.5 bg-amber-500 hover:bg-amber-400 text-zinc-950 font-bold text-xs rounded-xl shadow-md transition">
                    Ver recorrido
                </a>
            </div>
        </div>
    @endif

    <!-- Low Fuel / Reserve Warning Alerts -->
    @if ($vehicle->is_low_fuel || $vehicle->is_low_autonomy)
        <div class="glass-card bg-rose-950/80 border-rose-500/70 p-4 rounded-3xl glow-red flex items-start gap-3.5 animate-pulse">
            <div class="p-2 bg-rose-500/30 text-rose-400 rounded-2xl flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-sm font-black text-rose-200 uppercase tracking-wide">⚠️ ¡PELIGRO: ZONA DE RESERVA (7L)!</h3>
                <p class="text-xs text-rose-300/90 mt-0.5 leading-relaxed">
                    @if ($vehicle->is_low_fuel)
                        Estás en los <span class="font-bold underline text-white">{{ $vehicle->current_liters }} Litros</span> de reserva. Carga gasolina inmediatamente para no tocar la reserva.
                    @else
                        Autonomía restante crítica: <span class="font-bold underline text-white">{{ $vehicle->autonomy_km }} km</span>.
                    @endif
                </p>
                <div class="mt-2.5">
                    <a href="{{ route('fuel_loads.create') }}" class="inline-flex items-center gap-1.5 text-xs font-black text-zinc-950 bg-gradient-to-r from-rose-400 to-red-400 hover:from-rose-300 hover:to-red-300 px-3.5 py-1.5 rounded-xl shadow-lg transition">
                        <span>Cargar gasolina ya</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Apple/Tesla Sleek Fuel Tank Display -->
    <div class="glass-card p-6 rounded-3xl text-center relative overflow-hidden space-y-5">
        <!-- Background Ambient Glow -->
        <div class="absolute -top-24 -left-24 w-48 h-48 rounded-full blur-3xl opacity-20 {{ $vehicle->is_low_fuel ? 'bg-rose-600' : 'bg-cyan-500' }}"></div>
        <div class="absolute -bottom-24 -right-24 w-48 h-48 rounded-full blur-3xl opacity-20 {{ $vehicle->is_low_fuel ? 'bg-red-600' : 'bg-emerald-500' }}"></div>

        <!-- Card Title -->
        <div class="flex items-center justify-between text-xs text-zinc-400 font-semibold tracking-wider uppercase">
            <span>Tanque Actual</span>
            <span class="flex items-center gap-1 {{ $vehicle->is_low_fuel ? 'text-rose-400' : 'text-emerald-400' }}">
                <span class="w-2 h-2 rounded-full {{ $vehicle->is_low_fuel ? 'bg-rose-500 animate-ping' : 'bg-emerald-400 animate-pulse' }}"></span>
                {{ $vehicle->is_low_fuel ? 'PELIGRO RESERVA' : 'Calibrado' }}
            </span>
        </div>

        <!-- Big Liters Readout -->
        <div class="space-y-1">
            <div class="text-5xl font-black tracking-tight text-white flex items-baseline justify-center gap-1.5">
                <span class="bg-gradient-to-r {{ $vehicle->is_low_fuel ? 'from-rose-500 via-red-500 to-amber-400 animate-pulse' : 'from-cyan-300 via-emerald-300 to-white' }} bg-clip-text text-transparent">
                    {{ number_format($vehicle->current_liters, 1) }}
                </span>
                <span class="text-lg font-bold text-zinc-400">/ {{ number_format($vehicle->tank_capacity, 0) }} L</span>
            </div>
            <div class="text-xs text-zinc-400 font-medium">
                {{ number_format($vehicle->fuel_percentage, 1) }}% de capacidad disponible
            </div>
        </div>

        <!-- Visual Fuel Bar (Tesla Style Liquid Gauge with 7L Reserve Danger Line) -->
        <div class="space-y-2 relative pt-2">
            <div class="w-full h-6 bg-zinc-900/90 rounded-full p-1 border border-zinc-800 relative overflow-visible shadow-inner">
                <!-- Liquid Fill Level -->
                <div class="h-full rounded-full transition-all duration-700 ease-out bg-gradient-to-r {{ $vehicle->is_low_fuel ? 'from-rose-600 via-red-600 to-rose-500 glow-red animate-pulse' : ($vehicle->fuel_percentage < 30 ? 'from-amber-500 to-orange-400' : 'from-cyan-500 via-emerald-400 to-teal-300 glow-cyan') }}"
                     style="width: {{ max(4, $vehicle->fuel_percentage) }}%;">
                </div>

                <!-- 7 Liters Danger Marker Line (7 / 51 = ~13.7%) -->
                <div class="absolute top-0 bottom-0 w-0.5 bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,1)] z-20" style="left: 13.7255%;">
                    <!-- Danger Tag Label -->
                    <div class="absolute -top-6 left-1/2 -translate-x-1/2 whitespace-nowrap bg-rose-950 text-rose-300 text-[9px] font-black px-1.5 py-0.5 rounded border border-rose-500/60 shadow-md">
                        🛑 7L RESERVA
                    </div>
                </div>
            </div>

            <div class="flex justify-between text-[10px] text-zinc-500 font-mono px-1">
                <span>0 L</span>
                <span class="text-rose-400 font-bold">7 L (Reserva)</span>
                <span>{{ number_format($vehicle->tank_capacity / 2, 0) }} L</span>
                <span>{{ number_format($vehicle->tank_capacity, 0) }} L</span>
            </div>
        </div>

        <!-- Metrics Row (Autonomy & Avg Consumption) -->
        <div class="grid grid-cols-2 gap-3 pt-2">
            <div class="glass-pill p-3.5 rounded-2xl text-left border border-zinc-800/60">
                <div class="text-[11px] text-zinc-400 font-medium">Autonomía Est.</div>
                <div class="text-xl font-bold text-white mt-0.5 flex items-baseline gap-1">
                    {{ number_format($vehicle->autonomy_km, 0) }}
                    <span class="text-xs text-zinc-400 font-normal">km</span>
                </div>
            </div>

            <div class="glass-pill p-3.5 rounded-2xl text-left border border-zinc-800/60">
                <div class="text-[11px] text-zinc-400 font-medium">Consumo Prom.</div>
                <div class="text-xl font-bold text-white mt-0.5 flex items-baseline gap-1">
                    {{ number_format($vehicle->avg_consumption, 1) }}
                    <span class="text-xs text-zinc-400 font-normal">km/L</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Action Buttons (Dashboard Requirement 1) -->
    <div class="grid grid-cols-3 gap-3">
        <!-- Iniciar Recorrido Button -->
        <a href="{{ route('trips.track') }}" class="glass-card hover:border-cyan-500/50 p-3.5 rounded-2xl flex flex-col items-center justify-center gap-2 text-center group transition active:scale-95">
            <div class="w-10 h-10 rounded-xl bg-cyan-500/10 text-cyan-400 group-hover:bg-cyan-500 group-hover:text-zinc-950 transition flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-zinc-200 group-hover:text-white">Recorrido</span>
        </a>

        <!-- Agregar Gasolina Button -->
        <a href="{{ route('fuel_loads.create') }}" class="glass-card hover:border-emerald-500/50 p-3.5 rounded-2xl flex flex-col items-center justify-center gap-2 text-center group transition active:scale-95">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-400 group-hover:bg-emerald-500 group-hover:text-zinc-950 transition flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-zinc-200 group-hover:text-white">Cargar Gas</span>
        </a>

        <!-- Historial Button -->
        <a href="{{ route('history.index') }}" class="glass-card hover:border-zinc-500/50 p-3.5 rounded-2xl flex flex-col items-center justify-center gap-2 text-center group transition active:scale-95">
            <div class="w-10 h-10 rounded-xl bg-zinc-800 text-zinc-300 group-hover:bg-zinc-700 group-hover:text-white transition flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-zinc-200 group-hover:text-white">Historial</span>
        </a>
    </div>

    <!-- Manual Trip Quick Card (User request enhancement) -->
    <div class="glass-card p-4 rounded-3xl space-y-3">
        <div class="flex items-center justify-between">
            <div class="text-xs font-semibold text-zinc-300 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-cyan-400"></span>
                ¿Olvidaste iniciar el GPS en un viaje?
            </div>
        </div>
        <p class="text-[11px] text-zinc-400 leading-relaxed">
            Puedes registrar un recorrido manualmente especificando los kilómetros recorridos para mantener actualizado el tanque.
        </p>

        <form action="{{ route('trips.manual') }}" method="POST" class="flex gap-2">
            @csrf
            <div class="relative flex-1">
                <input type="number" step="0.1" min="0.1" name="distance_km" placeholder="Km recorridos (ej. 15)" required
                       class="w-full bg-zinc-900/90 border border-zinc-800 focus:border-cyan-500 text-white text-xs rounded-xl px-3 py-2.5 outline-none transition placeholder-zinc-500">
                <span class="absolute right-3 top-2.5 text-xs text-zinc-500 font-medium">km</span>
            </div>
            <button type="submit" class="px-4 py-2.5 bg-zinc-800 hover:bg-zinc-700 text-white font-semibold text-xs rounded-xl border border-zinc-700 transition flex items-center gap-1">
                <span>Registrar</span>
            </button>
        </form>
    </div>

</x-layout>
