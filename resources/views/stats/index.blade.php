<x-layout :vehicle="$vehicle" title="Estadísticas - My Popo">

    <div class="glass-card p-5 md:p-8 rounded-3xl space-y-6">
        <div class="flex items-center justify-between border-b border-zinc-800/80 pb-3">
            <div>
                <h2 class="text-base md:text-lg font-bold text-white flex items-center gap-2">
                    <i class="bi bi-bar-chart-line-fill text-cyan-400"></i>
                    Estadísticas & Rendimiento
                </h2>
                <p class="text-xs text-zinc-400">Resumen mensual y acumulado de tu automóvil</p>
            </div>
            <div class="p-2.5 bg-cyan-500/10 text-cyan-400 rounded-2xl">
                <i class="bi bi-graph-up-arrow text-xl"></i>
            </div>
        </div>

        <!-- 2x3 on Mobile, 3x2 on PC Grid for Required 6 Metrics -->
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-5">
            <!-- 1. Litros cargados este mes -->
            <div class="glass-pill p-4 md:p-5 rounded-2xl border border-zinc-800/60 space-y-1.5">
                <div class="text-[11px] md:text-xs text-zinc-400 font-medium flex items-center gap-1.5">
                    <i class="bi bi-fuel-pump-fill text-emerald-400 text-sm"></i>
                    <span>Cargados (Mes)</span>
                </div>
                <div class="text-2xl md:text-3xl font-black text-white">
                    {{ number_format($stats['liters_loaded_this_month'], 1) }}
                    <span class="text-xs md:text-sm font-semibold text-zinc-400">L</span>
                </div>
            </div>

            <!-- 2. Dinero gastado este mes -->
            <div class="glass-pill p-4 md:p-5 rounded-2xl border border-zinc-800/60 space-y-1.5">
                <div class="text-[11px] md:text-xs text-zinc-400 font-medium flex items-center gap-1.5">
                    <i class="bi bi-currency-dollar text-cyan-400 text-sm"></i>
                    <span>Gastado (Mes)</span>
                </div>
                <div class="text-2xl md:text-3xl font-black text-white">
                    ${{ number_format($stats['money_spent_this_month'], 2) }}
                </div>
            </div>

            <!-- 3. Kilómetros recorridos -->
            <div class="glass-pill p-4 md:p-5 rounded-2xl border border-zinc-800/60 space-y-1.5">
                <div class="text-[11px] md:text-xs text-zinc-400 font-medium flex items-center gap-1.5">
                    <i class="bi bi-speedometer text-purple-400 text-sm"></i>
                    <span>Km Recorridos</span>
                </div>
                <div class="text-2xl md:text-3xl font-black text-white">
                    {{ number_format($stats['total_km_driven'], 1) }}
                    <span class="text-xs md:text-sm font-semibold text-zinc-400">km</span>
                </div>
            </div>

            <!-- 4. Consumo promedio -->
            <div class="glass-pill p-4 md:p-5 rounded-2xl border border-zinc-800/60 space-y-1.5">
                <div class="text-[11px] md:text-xs text-zinc-400 font-medium flex items-center gap-1.5">
                    <i class="bi bi-graph-up-arrow text-amber-400 text-sm"></i>
                    <span>Consumo Prom.</span>
                </div>
                <div class="text-2xl md:text-3xl font-black text-white">
                    {{ number_format($stats['avg_consumption'], 1) }}
                    <span class="text-xs md:text-sm font-semibold text-zinc-400">km/L</span>
                </div>
            </div>

            <!-- 5. Costo por kilómetro -->
            <div class="glass-pill p-4 md:p-5 rounded-2xl border border-zinc-800/60 space-y-1.5">
                <div class="text-[11px] md:text-xs text-zinc-400 font-medium flex items-center gap-1.5">
                    <i class="bi bi-tag-fill text-rose-400 text-sm"></i>
                    <span>Costo / km</span>
                </div>
                <div class="text-2xl md:text-3xl font-black text-white">
                    ${{ number_format($stats['cost_per_km'], 2) }}
                    <span class="text-xs md:text-sm font-semibold text-zinc-400">/km</span>
                </div>
            </div>

            <!-- 6. Autonomía restante -->
            <div class="glass-pill p-4 md:p-5 rounded-2xl border border-zinc-800/60 space-y-1.5">
                <div class="text-[11px] md:text-xs text-zinc-400 font-medium flex items-center gap-1.5">
                    <i class="bi bi-geo-alt-fill text-teal-400 text-sm"></i>
                    <span>Autonomía Rest.</span>
                </div>
                <div class="text-2xl md:text-3xl font-black text-emerald-400">
                    {{ number_format($stats['remaining_autonomy'], 0) }}
                    <span class="text-xs md:text-sm font-semibold text-zinc-400">km</span>
                </div>
            </div>
        </div>

        <!-- Additional vehicle specs summary -->
        <div class="glass-pill p-5 rounded-2xl border border-zinc-800/80 space-y-3">
            <div class="text-xs md:text-sm font-bold text-white flex items-center gap-2">
                <i class="bi bi-info-circle-fill text-cyan-400"></i>
                <span>Ficha Técnica del Vehículo</span>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-xs md:text-sm text-zinc-400">
                <div>Vehículo: <span class="text-white font-medium block md:inline">{{ $vehicle->name }}</span></div>
                <div>Capacidad: <span class="text-white font-medium block md:inline">{{ $vehicle->tank_capacity }} L</span></div>
                <div>Año / Modelo: <span class="text-white font-medium block md:inline">{{ $vehicle->year }} {{ $vehicle->model }}</span></div>
                <div>Combustible Actual: <span class="text-white font-medium block md:inline">{{ $vehicle->current_liters }} L</span></div>
            </div>
        </div>
    </div>

</x-layout>
