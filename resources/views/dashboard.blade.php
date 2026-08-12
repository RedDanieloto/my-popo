<x-layout :vehicle="$vehicle" title="Dashboard - My Popo">

    <!-- Active Trip Banner if currently tracking -->
    @if ($activeTrip)
        <div class="glass-card bg-gradient-to-r from-amber-950/40 via-zinc-900 to-amber-950/40 border-amber-500/30 p-4 rounded-3xl animate-pulse">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 rounded-full bg-amber-400 animate-ping"></div>
                    <div>
                        <div class="text-xs font-semibold text-amber-300 flex items-center gap-1.5">
                            <i class="bi bi-record-circle-fill text-amber-400"></i>
                            <span>Recorrido en curso...</span>
                        </div>
                        <div class="text-[11px] text-zinc-400">Iniciado a las {{ $activeTrip->start_time->format('H:i') }}</div>
                    </div>
                </div>
                <a href="{{ route('trips.track') }}" class="px-3.5 py-1.5 bg-amber-500 hover:bg-amber-400 text-zinc-950 font-bold text-xs rounded-xl shadow-md transition flex items-center gap-1">
                    <span>Ver recorrido</span>
                    <i class="bi bi-chevron-right"></i>
                </a>
            </div>
        </div>
    @endif

    <!-- Low Fuel / Reserve Warning Alerts -->
    @if ($vehicle->is_low_fuel || $vehicle->is_low_autonomy)
        <div class="glass-card bg-rose-950/80 border-rose-500/70 p-4 rounded-3xl glow-red flex items-start gap-3.5 animate-pulse">
            <div class="p-2 bg-rose-500/30 text-rose-400 rounded-2xl flex-shrink-0">
                <i class="bi bi-exclamation-triangle-fill text-xl"></i>
            </div>
            <div class="flex-1">
                <h3 class="text-sm font-black text-rose-200 uppercase tracking-wide flex items-center gap-1.5">
                    <i class="bi bi-fuel-pump-fill text-rose-400"></i>
                    <span>¡PELIGRO: ZONA DE RESERVA (7L)!</span>
                </h3>
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
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Responsive Desktop Grid (Main Tank on left, Actions & Quick manual trip on right) -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 md:gap-6 items-start">
        
        <!-- Main Apple/Tesla Sleek Fuel Tank Display (Left Column on PC) -->
        <div class="md:col-span-7 lg:col-span-7 glass-card p-6 md:p-8 rounded-3xl text-center relative overflow-hidden space-y-5">
            <!-- Background Ambient Glow -->
            <div class="absolute -top-24 -left-24 w-48 h-48 rounded-full blur-3xl opacity-20 {{ $vehicle->is_low_fuel ? 'bg-rose-600' : 'bg-cyan-500' }}"></div>
            <div class="absolute -bottom-24 -right-24 w-48 h-48 rounded-full blur-3xl opacity-20 {{ $vehicle->is_low_fuel ? 'bg-red-600' : 'bg-emerald-500' }}"></div>

            <!-- Card Title -->
            <div class="flex items-center justify-between text-xs text-zinc-400 font-semibold tracking-wider uppercase">
                <span class="flex items-center gap-1.5">
                    <i class="bi bi-fuel-pump text-cyan-400"></i>
                    Tanque Actual
                </span>
                <span class="flex items-center gap-1 {{ $vehicle->is_low_fuel ? 'text-rose-400' : 'text-emerald-400' }}">
                    <span class="w-2 h-2 rounded-full {{ $vehicle->is_low_fuel ? 'bg-rose-500 animate-ping' : 'bg-emerald-400 animate-pulse' }}"></span>
                    {{ $vehicle->is_low_fuel ? 'PELIGRO RESERVA' : 'Calibrado' }}
                </span>
            </div>

            <!-- Big Liters Readout -->
            <div class="space-y-1 py-2">
                <div class="text-5xl md:text-6xl font-black tracking-tight text-white flex items-baseline justify-center gap-1.5">
                    <span class="bg-gradient-to-r {{ $vehicle->is_low_fuel ? 'from-rose-500 via-red-500 to-amber-400 animate-pulse' : 'from-cyan-300 via-emerald-300 to-white' }} bg-clip-text text-transparent">
                        {{ number_format($vehicle->current_liters, 1) }}
                    </span>
                    <span class="text-lg md:text-xl font-bold text-zinc-400">/ {{ number_format($vehicle->tank_capacity, 0) }} L</span>
                </div>
                <div class="text-xs md:text-sm text-zinc-400 font-medium">
                    {{ number_format($vehicle->fuel_percentage, 1) }}% de capacidad disponible
                </div>
            </div>

            <!-- Visual Fuel Bar (Tesla Style Liquid Gauge with 7L Reserve Danger Line) -->
            <div class="space-y-2 relative pt-2">
                <div class="w-full h-7 bg-zinc-900/90 rounded-full p-1 border border-zinc-800 relative overflow-visible shadow-inner">
                    <!-- Liquid Fill Level -->
                    <div class="h-full rounded-full transition-all duration-700 ease-out bg-gradient-to-r {{ $vehicle->is_low_fuel ? 'from-rose-600 via-red-600 to-rose-500 glow-red animate-pulse' : ($vehicle->fuel_percentage < 30 ? 'from-amber-500 to-orange-400' : 'from-cyan-500 via-emerald-400 to-teal-300 glow-cyan') }}"
                         style="width: {{ max(4, $vehicle->fuel_percentage) }}%;">
                    </div>

                    <!-- 7 Liters Danger Marker Line (7 / 51 = ~13.7%) -->
                    <div class="absolute top-0 bottom-0 w-0.5 bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,1)] z-20" style="left: 13.7255%;">
                        <!-- Danger Tag Label -->
                        <div class="absolute -top-6 left-1/2 -translate-x-1/2 whitespace-nowrap bg-rose-950 text-rose-300 text-[9px] font-black px-1.5 py-0.5 rounded border border-rose-500/60 shadow-md flex items-center gap-1">
                            <i class="bi bi-fuel-pump-fill text-rose-400"></i>
                            <span>7L RESERVA</span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between text-[10px] md:text-xs text-zinc-500 font-mono px-1">
                    <span>0 L</span>
                    <span class="text-rose-400 font-bold">7 L (Reserva)</span>
                    <span>{{ number_format($vehicle->tank_capacity / 2, 0) }} L</span>
                    <span>{{ number_format($vehicle->tank_capacity, 0) }} L</span>
                </div>
            </div>

            <!-- Metrics Row (Autonomy & Avg Consumption) -->
            <div class="grid grid-cols-2 gap-3 pt-2">
                <div class="glass-pill p-4 rounded-2xl text-left border border-zinc-800/60">
                    <div class="text-[11px] md:text-xs text-zinc-400 font-medium flex items-center gap-1.5">
                        <i class="bi bi-speedometer2 text-cyan-400"></i>
                        <span>Autonomía Est.</span>
                    </div>
                    <div class="text-xl md:text-2xl font-bold text-white mt-1 flex items-baseline gap-1">
                        {{ number_format($vehicle->autonomy_km, 0) }}
                        <span class="text-xs text-zinc-400 font-normal">km</span>
                    </div>
                </div>

                <div class="glass-pill p-4 rounded-2xl text-left border border-zinc-800/60">
                    <div class="text-[11px] md:text-xs text-zinc-400 font-medium flex items-center gap-1.5">
                        <i class="bi bi-graph-up-arrow text-emerald-400"></i>
                        <span>Consumo Prom.</span>
                    </div>
                    <div class="text-xl md:text-2xl font-bold text-white mt-1 flex items-baseline gap-1">
                        {{ number_format($vehicle->avg_consumption, 1) }}
                        <span class="text-xs text-zinc-400 font-normal">km/L</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column on Desktop (Quick Actions + Manual Trip Card) -->
        <div class="md:col-span-5 lg:col-span-5 space-y-4 md:space-y-6">
            
            <!-- Quick Action Buttons -->
            <div class="grid grid-cols-3 gap-3">
                <!-- Iniciar Recorrido Button -->
                <a href="{{ route('trips.track') }}" class="glass-card hover:border-cyan-500/50 p-4 rounded-2xl flex flex-col items-center justify-center gap-2 text-center group transition active:scale-95">
                    <div class="w-11 h-11 rounded-xl bg-cyan-500/10 text-cyan-400 group-hover:bg-cyan-500 group-hover:text-zinc-950 transition flex items-center justify-center text-xl">
                        <i class="bi bi-play-circle-fill"></i>
                    </div>
                    <span class="text-xs font-semibold text-zinc-200 group-hover:text-white">Recorrido</span>
                </a>

                <!-- Agregar Gasolina Button -->
                <a href="{{ route('fuel_loads.create') }}" class="glass-card hover:border-emerald-500/50 p-4 rounded-2xl flex flex-col items-center justify-center gap-2 text-center group transition active:scale-95">
                    <div class="w-11 h-11 rounded-xl bg-emerald-500/10 text-emerald-400 group-hover:bg-emerald-500 group-hover:text-zinc-950 transition flex items-center justify-center text-xl">
                        <i class="bi bi-fuel-pump-fill"></i>
                    </div>
                    <span class="text-xs font-semibold text-zinc-200 group-hover:text-white">Cargar Gas</span>
                </a>

                <!-- Historial Button -->
                <a href="{{ route('history.index') }}" class="glass-card hover:border-zinc-500/50 p-4 rounded-2xl flex flex-col items-center justify-center gap-2 text-center group transition active:scale-95">
                    <div class="w-11 h-11 rounded-xl bg-zinc-800 text-zinc-300 group-hover:bg-zinc-700 group-hover:text-white transition flex items-center justify-center text-xl">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <span class="text-xs font-semibold text-zinc-200 group-hover:text-white">Historial</span>
                </a>
            </div>

            <!-- Manual Trip Quick Card -->
            <div class="glass-card p-5 rounded-3xl space-y-3.5">
                <div class="flex items-center justify-between">
                    <div class="text-xs font-semibold text-zinc-300 flex items-center gap-2">
                        <i class="bi bi-geo-alt text-cyan-400"></i>
                        <span>¿Olvidaste iniciar el GPS en un viaje?</span>
                    </div>
                </div>
                <p class="text-[11px] md:text-xs text-zinc-400 leading-relaxed">
                    Puedes registrar un recorrido manualmente especificando los kilómetros recorridos para mantener actualizado el tanque.
                </p>

                <form action="{{ route('trips.manual') }}" method="POST" class="flex gap-2">
                    @csrf
                    <div class="relative flex-1">
                        <input type="number" step="any" min="0.01" inputmode="decimal" name="distance_km" placeholder="Km recorridos (ej. 15.5)" required
                               class="w-full bg-zinc-900/90 border border-zinc-800 focus:border-cyan-500 text-white text-xs md:text-sm rounded-xl px-3 py-2.5 outline-none transition placeholder-zinc-500">
                        <span class="absolute right-3 top-2.5 text-xs text-zinc-500 font-medium">km</span>
                    </div>
                    <button type="submit" class="px-4 py-2.5 bg-zinc-800 hover:bg-zinc-700 text-white font-semibold text-xs md:text-sm rounded-xl border border-zinc-700 transition flex items-center gap-1.5">
                        <i class="bi bi-plus-circle"></i>
                        <span>Registrar</span>
                    </button>
                </form>
            </div>

        </div>
    </div>

</x-layout>
