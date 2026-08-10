<x-layout :vehicle="$vehicle" title="Estadísticas - My Popo">

    <div class="glass-card p-5 rounded-3xl space-y-5">
        <div class="flex items-center justify-between border-b border-zinc-800/80 pb-3">
            <div>
                <h2 class="text-base font-bold text-white">Estadísticas & Rendimiento</h2>
                <p class="text-xs text-zinc-400">Resumen mensual y acumulado de tu automóvil</p>
            </div>
            <div class="p-2 bg-cyan-500/10 text-cyan-400 rounded-2xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
        </div>

        <!-- 2x3 Grid for Required 6 Metrics -->
        <div class="grid grid-cols-2 gap-3">
            <!-- 1. Litros cargados este mes -->
            <div class="glass-pill p-4 rounded-2xl border border-zinc-800/60 space-y-1">
                <div class="text-[11px] text-zinc-400 font-medium flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    Cargados (Mes)
                </div>
                <div class="text-2xl font-black text-white">
                    {{ number_format($stats['liters_loaded_this_month'], 1) }}
                    <span class="text-xs font-semibold text-zinc-400">L</span>
                </div>
            </div>

            <!-- 2. Dinero gastado este mes -->
            <div class="glass-pill p-4 rounded-2xl border border-zinc-800/60 space-y-1">
                <div class="text-[11px] text-zinc-400 font-medium flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-cyan-400"></span>
                    Gastado (Mes)
                </div>
                <div class="text-2xl font-black text-white">
                    ${{ number_format($stats['money_spent_this_month'], 2) }}
                </div>
            </div>

            <!-- 3. Kilómetros recorridos -->
            <div class="glass-pill p-4 rounded-2xl border border-zinc-800/60 space-y-1">
                <div class="text-[11px] text-zinc-400 font-medium flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-purple-400"></span>
                    Km Recorridos
                </div>
                <div class="text-2xl font-black text-white">
                    {{ number_format($stats['total_km_driven'], 1) }}
                    <span class="text-xs font-semibold text-zinc-400">km</span>
                </div>
            </div>

            <!-- 4. Consumo promedio -->
            <div class="glass-pill p-4 rounded-2xl border border-zinc-800/60 space-y-1">
                <div class="text-[11px] text-zinc-400 font-medium flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                    Consumo Prom.
                </div>
                <div class="text-2xl font-black text-white">
                    {{ number_format($stats['avg_consumption'], 1) }}
                    <span class="text-xs font-semibold text-zinc-400">km/L</span>
                </div>
            </div>

            <!-- 5. Costo por kilómetro -->
            <div class="glass-pill p-4 rounded-2xl border border-zinc-800/60 space-y-1">
                <div class="text-[11px] text-zinc-400 font-medium flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                    Costo / km
                </div>
                <div class="text-2xl font-black text-white">
                    ${{ number_format($stats['cost_per_km'], 2) }}
                    <span class="text-xs font-semibold text-zinc-400">/km</span>
                </div>
            </div>

            <!-- 6. Autonomía restante -->
            <div class="glass-pill p-4 rounded-2xl border border-zinc-800/60 space-y-1">
                <div class="text-[11px] text-zinc-400 font-medium flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-teal-400"></span>
                    Autonomía Rest.
                </div>
                <div class="text-2xl font-black text-emerald-400">
                    {{ number_format($stats['remaining_autonomy'], 0) }}
                    <span class="text-xs font-semibold text-zinc-400">km</span>
                </div>
            </div>
        </div>

        <!-- Additional vehicle specs summary -->
        <div class="glass-pill p-4 rounded-2xl border border-zinc-800/80 space-y-2">
            <div class="text-xs font-bold text-white">Ficha Técnica del Vehículo</div>
            <div class="grid grid-cols-2 gap-2 text-xs text-zinc-400">
                <div>Vehículo: <span class="text-white font-medium">{{ $vehicle->name }}</span></div>
                <div>Capacidad: <span class="text-white font-medium">{{ $vehicle->tank_capacity }} L</span></div>
                <div>Año / Modelo: <span class="text-white font-medium">{{ $vehicle->year }} {{ $vehicle->model }}</span></div>
                <div>Combustible Actual: <span class="text-white font-medium">{{ $vehicle->current_liters }} L</span></div>
            </div>
        </div>
    </div>

</x-layout>
