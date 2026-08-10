<x-layout :vehicle="$vehicle" title="Configurar Vehículo - My Popo">

    <div class="glass-card p-5 rounded-3xl space-y-4">
        <div class="flex items-center justify-between border-b border-zinc-800/80 pb-3">
            <div>
                <h2 class="text-base font-bold text-white">Configuración del Vehículo</h2>
                <p class="text-xs text-zinc-400">Administra los parámetros de tu automóvil y capacidad</p>
            </div>
            <div class="p-2 bg-cyan-500/10 text-cyan-400 rounded-2xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
        </div>

        <form action="{{ route('vehicle.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Nombre -->
            <div class="space-y-1">
                <label class="text-xs font-semibold text-zinc-300">Nombre del vehículo</label>
                <input type="text" name="name" value="{{ old('name', $vehicle->name) }}" required
                       class="w-full bg-zinc-900 border border-zinc-800 focus:border-cyan-500 text-white text-sm rounded-xl px-3.5 py-2.5 outline-none transition">
            </div>

            <!-- Marca y Modelo -->
            <div class="grid grid-cols-2 gap-3">
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-zinc-300">Marca</label>
                    <input type="text" name="brand" value="{{ old('brand', $vehicle->brand) }}" required
                           class="w-full bg-zinc-900 border border-zinc-800 focus:border-cyan-500 text-white text-sm rounded-xl px-3.5 py-2.5 outline-none transition">
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-semibold text-zinc-300">Modelo</label>
                    <input type="text" name="model" value="{{ old('model', $vehicle->model) }}" required
                           class="w-full bg-zinc-900 border border-zinc-800 focus:border-cyan-500 text-white text-sm rounded-xl px-3.5 py-2.5 outline-none transition">
                </div>
            </div>

            <!-- Año y Capacidad del tanque -->
            <div class="grid grid-cols-2 gap-3">
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-zinc-300">Año</label>
                    <input type="number" name="year" value="{{ old('year', $vehicle->year) }}" min="1900" max="2100" required
                           class="w-full bg-zinc-900 border border-zinc-800 focus:border-cyan-500 text-white text-sm rounded-xl px-3.5 py-2.5 outline-none transition">
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-semibold text-zinc-300">Capacidad (Litros)</label>
                    <input type="number" step="0.1" name="tank_capacity" value="{{ old('tank_capacity', $vehicle->tank_capacity) }}" min="1" required
                           class="w-full bg-zinc-900 border border-zinc-800 focus:border-cyan-500 text-white text-sm rounded-xl px-3.5 py-2.5 outline-none transition">
                </div>
            </div>

            <!-- Litros Actuales (Ajuste Manual) -->
            <div class="space-y-1">
                <label class="text-xs font-semibold text-zinc-300">Litros actuales en el tanque (Medición manual inicial)</label>
                <div class="relative">
                    <input type="number" step="0.1" name="current_liters" value="{{ old('current_liters', $vehicle->current_liters) }}" min="0" required
                           class="w-full bg-zinc-900 border border-zinc-800 focus:border-cyan-500 text-white text-sm rounded-xl px-3.5 py-2.5 outline-none transition">
                    <span class="absolute right-3 top-2.5 text-xs text-zinc-500 font-medium">Litros</span>
                </div>
                <p class="text-[11px] text-zinc-500">Ajusta los litros si inicias la app con el tanque a la mitad o en reserva.</p>
            </div>

            <!-- Consumo Promedio Inicial -->
            <div class="space-y-1">
                <label class="text-xs font-semibold text-zinc-300">Consumo promedio (km/L)</label>
                <div class="relative">
                    <input type="number" step="0.1" name="avg_consumption" value="{{ old('avg_consumption', $vehicle->avg_consumption) }}" min="0.1" required
                           class="w-full bg-zinc-900 border border-zinc-800 focus:border-cyan-500 text-white text-sm rounded-xl px-3.5 py-2.5 outline-none transition">
                    <span class="absolute right-3 top-2.5 text-xs text-zinc-500 font-medium">km/L</span>
                </div>
                <p class="text-[11px] text-zinc-500">Se recalibrará automáticamente cuando indiques que cargaste tanque lleno.</p>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-cyan-500 to-emerald-400 hover:from-cyan-400 hover:to-emerald-300 text-zinc-950 font-bold text-sm rounded-xl shadow-lg shadow-cyan-500/20 transition active:scale-[0.98]">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>

</x-layout>
