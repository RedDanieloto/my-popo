<x-layout :vehicle="$vehicle" title="Agregar Gasolina - My Popo">

    <div class="max-w-md md:max-w-2xl mx-auto glass-card p-5 md:p-8 rounded-3xl space-y-6">
        <div class="flex items-center justify-between border-b border-zinc-800/80 pb-3">
            <div>
                <h2 class="text-base md:text-lg font-bold text-white flex items-center gap-2">
                    <i class="bi bi-fuel-pump-fill text-emerald-400"></i>
                    Agregar Gasolina
                </h2>
                <p class="text-xs text-zinc-400">Registra una recarga para actualizar el tanque</p>
            </div>
            <div class="p-2.5 bg-emerald-500/10 text-emerald-400 rounded-2xl">
                <i class="bi bi-plus-circle-fill text-xl"></i>
            </div>
        </div>

        <form action="{{ route('fuel_loads.store') }}" method="POST" class="space-y-4 md:space-y-5">
            @csrf

            <!-- Cantidad Pagada & Precio por Litro -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-zinc-300 flex items-center gap-1.5">
                        <i class="bi bi-currency-dollar text-emerald-400"></i>
                        <span>Total pagado ($)</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-2.5 text-xs text-zinc-500 font-bold">$</span>
                        <input type="number" step="0.01" min="0.01" id="amount_paid" name="amount_paid" value="{{ old('amount_paid') }}" placeholder="500.00" required
                               class="w-full bg-zinc-900 border border-zinc-800 focus:border-emerald-500 text-white text-sm rounded-xl pl-8 pr-3 py-2.5 outline-none transition">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-zinc-300 flex items-center gap-1.5">
                        <i class="bi bi-tag-fill text-emerald-400"></i>
                        <span>Precio / Litro ($)</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-2.5 text-xs text-zinc-500 font-bold">$</span>
                        <input type="number" step="0.01" min="0.01" id="price_per_liter" name="price_per_liter" value="{{ old('price_per_liter', '24.50') }}" placeholder="24.50" required
                               class="w-full bg-zinc-900 border border-zinc-800 focus:border-emerald-500 text-white text-sm rounded-xl pl-8 pr-3 py-2.5 outline-none transition">
                    </div>
                </div>
            </div>

            <!-- Litros Calculados Automáticamente -->
            <div class="glass-pill p-4 rounded-2xl border border-zinc-800/80 flex items-center justify-between">
                <div>
                    <div class="text-xs md:text-sm text-zinc-400 font-medium flex items-center gap-1.5">
                        <i class="bi bi-calculator text-cyan-400"></i>
                        <span>Litros a ingresar</span>
                    </div>
                    <div class="text-[11px] text-zinc-500">Calculado automáticamente</div>
                </div>
                <div class="text-2xl md:text-3xl font-black text-emerald-400" id="calculated_liters">
                    0.00 L
                </div>
            </div>

            <!-- Toggle de Tanque Lleno (Recalibración) -->
            <div class="glass-pill p-4 rounded-2xl border border-zinc-800/80 flex items-center justify-between">
                <div>
                    <label for="is_full_tank" class="text-xs md:text-sm font-bold text-white cursor-pointer flex items-center gap-1.5">
                        <i class="bi bi-fuel-pump-fill text-emerald-400"></i>
                        <span>¿Llenaste el tanque por completo?</span>
                    </label>
                    <p class="text-[11px] text-zinc-400 mt-0.5">Recalibrará tu consumo km/L promedio.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="is_full_tank" name="is_full_tank" value="1" class="sr-only peer" {{ old('is_full_tank') ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-zinc-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-zinc-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                </label>
            </div>

            <!-- Fecha -->
            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-zinc-300 flex items-center gap-1.5">
                    <i class="bi bi-calendar-event text-zinc-400"></i>
                    <span>Fecha y hora de recarga</span>
                </label>
                <input type="datetime-local" name="date" value="{{ old('date', now()->format('Y-m-d\TH:i')) }}" required
                       class="w-full bg-zinc-900 border border-zinc-800 focus:border-emerald-500 text-white text-sm rounded-xl px-3.5 py-2.5 outline-none transition">
            </div>

            <!-- Nota opcional -->
            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-zinc-300 flex items-center gap-1.5">
                    <i class="bi bi-chat-left-text text-zinc-400"></i>
                    <span>Nota (opcional)</span>
                </label>
                <input type="text" name="note" value="{{ old('note') }}" placeholder="Ej. Gasolinera Pemex Centro"
                       class="w-full bg-zinc-900 border border-zinc-800 focus:border-emerald-500 text-white text-sm rounded-xl px-3.5 py-2.5 outline-none transition">
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-zinc-950 font-bold text-sm md:text-base rounded-xl shadow-lg shadow-emerald-500/20 transition active:scale-[0.98] flex items-center justify-center gap-2">
                    <i class="bi bi-check-circle-fill text-lg"></i>
                    <span>Guardar Carga de Gasolina</span>
                </button>
            </div>
        </form>
    </div>

    <!-- JS script for automatic liters calculation -->
    <script>
        const amountInput = document.getElementById('amount_paid');
        const priceInput = document.getElementById('price_per_liter');
        const displayLiters = document.getElementById('calculated_liters');

        function updateLiters() {
            const amount = parseFloat(amountInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;
            if (amount > 0 && price > 0) {
                const liters = (amount / price).toFixed(2);
                displayLiters.textContent = `${liters} L`;
            } else {
                displayLiters.textContent = `0.00 L`;
            }
        }

        amountInput.addEventListener('input', updateLiters);
        priceInput.addEventListener('input', updateLiters);
        updateLiters();
    </script>

</x-layout>
