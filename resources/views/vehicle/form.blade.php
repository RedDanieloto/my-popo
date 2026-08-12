<x-layout :vehicle="$vehicle" title="Configurar Vehículo - My Popo">

    <div class="max-w-md md:max-w-2xl mx-auto glass-card p-5 md:p-8 rounded-3xl space-y-5">
        <div class="flex items-center justify-between border-b border-zinc-800/80 pb-3">
            <div>
                <h2 class="text-base md:text-lg font-bold text-white flex items-center gap-2">
                    <i class="bi bi-gear-fill text-cyan-400"></i>
                    Configuración del Vehículo
                </h2>
                <p class="text-xs text-zinc-400">Administra los parámetros de tu automóvil y capacidad del tanque</p>
            </div>
            <div class="p-2.5 bg-cyan-500/10 text-cyan-400 rounded-2xl">
                <i class="bi bi-car-front-fill text-xl"></i>
            </div>
        </div>

        <form action="{{ route('vehicle.update') }}" method="POST" class="space-y-4 md:space-y-5">
            @csrf
            @method('PUT')

            <!-- Nombre -->
            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-zinc-300 flex items-center gap-1.5">
                    <i class="bi bi-card-heading text-cyan-400"></i>
                    <span>Nombre del vehículo</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $vehicle->name) }}" required
                       class="w-full bg-zinc-900 border border-zinc-800 focus:border-cyan-500 text-white text-sm rounded-xl px-3.5 py-2.5 outline-none transition">
            </div>

            <!-- Marca y Modelo -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-zinc-300">Marca</label>
                    <input type="text" name="brand" value="{{ old('brand', $vehicle->brand) }}" required
                           class="w-full bg-zinc-900 border border-zinc-800 focus:border-cyan-500 text-white text-sm rounded-xl px-3.5 py-2.5 outline-none transition">
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-zinc-300">Modelo</label>
                    <input type="text" name="model" value="{{ old('model', $vehicle->model) }}" required
                           class="w-full bg-zinc-900 border border-zinc-800 focus:border-cyan-500 text-white text-sm rounded-xl px-3.5 py-2.5 outline-none transition">
                </div>
            </div>

            <!-- Año y Capacidad del tanque -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-zinc-300">Año</label>
                    <input type="number" name="year" value="{{ old('year', $vehicle->year) }}" min="1900" max="2100" required
                           class="w-full bg-zinc-900 border border-zinc-800 focus:border-cyan-500 text-white text-sm rounded-xl px-3.5 py-2.5 outline-none transition">
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-zinc-300">Capacidad (Litros)</label>
                    <input type="number" step="any" inputmode="decimal" name="tank_capacity" value="{{ old('tank_capacity', $vehicle->tank_capacity) }}" min="1" required
                           class="w-full bg-zinc-900 border border-zinc-800 focus:border-cyan-500 text-white text-sm rounded-xl px-3.5 py-2.5 outline-none transition">
                </div>
            </div>

            <!-- Litros Actuales (Ajuste Manual) -->
            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-zinc-300 flex items-center gap-1.5">
                    <i class="bi bi-fuel-pump text-emerald-400"></i>
                    <span>Litros actuales en el tanque (Medición manual inicial)</span>
                </label>
                <div class="relative">
                    <input type="number" step="any" inputmode="decimal" name="current_liters" value="{{ old('current_liters', $vehicle->current_liters) }}" min="0" required
                           class="w-full bg-zinc-900 border border-zinc-800 focus:border-cyan-500 text-white text-sm rounded-xl px-3.5 py-2.5 outline-none transition">
                    <span class="absolute right-3 top-2.5 text-xs text-zinc-500 font-medium">Litros</span>
                </div>
                <p class="text-[11px] text-zinc-500">Ajusta los litros si inicias la app con el tanque a la mitad o en reserva.</p>
            </div>

            <!-- Consumo Promedio Inicial -->
            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-zinc-300 flex items-center gap-1.5">
                    <i class="bi bi-speedometer text-cyan-400"></i>
                    <span>Consumo promedio (km/L)</span>
                </label>
                <div class="relative">
                    <input type="number" step="any" inputmode="decimal" name="avg_consumption" value="{{ old('avg_consumption', $vehicle->avg_consumption) }}" min="0.1" required
                           class="w-full bg-zinc-900 border border-zinc-800 focus:border-cyan-500 text-white text-sm rounded-xl px-3.5 py-2.5 outline-none transition">
                    <span class="absolute right-3 top-2.5 text-xs text-zinc-500 font-medium">km/L</span>
                </div>
                <p class="text-[11px] text-zinc-500">Se recalibrará automáticamente cuando indiques que cargaste tanque lleno.</p>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-cyan-500 to-emerald-400 hover:from-cyan-400 hover:to-emerald-300 text-zinc-950 font-bold text-sm md:text-base rounded-xl shadow-lg shadow-cyan-500/20 transition active:scale-[0.98] flex items-center justify-center gap-2">
                    <i class="bi bi-check-circle-fill text-lg"></i>
                    <span>Guardar Cambios</span>
                </button>
            </div>
        </form>
    </div>

</x-layout>
