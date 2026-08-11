<x-layout :vehicle="$vehicle" title="Historial - My Popo">

    <div class="max-w-md md:max-w-4xl mx-auto glass-card p-5 md:p-8 rounded-3xl space-y-5">
        <div class="flex items-center justify-between border-b border-zinc-800/80 pb-3">
            <div>
                <h2 class="text-base md:text-lg font-bold text-white flex items-center gap-2">
                    <i class="bi bi-clock-history text-cyan-400"></i>
                    Historial de Actividad
                </h2>
                <p class="text-xs text-zinc-400">Recargas de combustible y recorridos ordenados cronológicamente</p>
            </div>
            <div class="p-2.5 bg-zinc-800 text-zinc-300 rounded-2xl">
                <i class="bi bi-list-task text-xl"></i>
            </div>
        </div>

        @if ($unifiedHistory->isEmpty())
            <div class="text-center py-12 space-y-3">
                <div class="w-14 h-14 rounded-2xl bg-zinc-900 border border-zinc-800 text-zinc-500 mx-auto flex items-center justify-center text-2xl">
                    <i class="bi bi-inbox-fill"></i>
                </div>
                <div class="text-xs md:text-sm text-zinc-400">Aún no hay registros de recargas o recorridos.</div>
            </div>
        @else
            <div class="space-y-3">
                @foreach ($unifiedHistory as $item)
                    @if ($item['type'] === 'fuel_load')
                        <!-- Recarga de Gasolina Card -->
                        <div class="glass-pill p-4 rounded-2xl border border-emerald-500/20 bg-emerald-950/10 flex items-center justify-between transition hover:border-emerald-500/40">
                            <div class="flex items-center gap-3.5">
                                <div class="w-11 h-11 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center flex-shrink-0 text-xl">
                                    <i class="bi bi-fuel-pump-fill"></i>
                                </div>
                                <div>
                                    <div class="text-xs md:text-sm font-bold text-white flex items-center gap-2">
                                        Recarga de Gasolina
                                        @if ($item['details']['is_full_tank'])
                                            <span class="text-[10px] bg-emerald-500/20 text-emerald-300 font-semibold px-2 py-0.5 rounded border border-emerald-500/30 flex items-center gap-1">
                                                <i class="bi bi-check2"></i> Tanque lleno
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-[11px] md:text-xs text-zinc-400 mt-0.5">
                                        {{ \Carbon\Carbon::parse($item['date'])->format('d/m/Y - H:i') }}
                                        @if ($item['details']['note'])
                                            • <span class="italic text-zinc-400">{{ $item['details']['note'] }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm md:text-base font-bold text-emerald-400">+{{ number_format($item['details']['liters'], 2) }} L</div>
                                <div class="text-[11px] md:text-xs text-zinc-400">${{ number_format($item['details']['amount_paid'], 2) }}</div>
                            </div>
                        </div>
                    @else
                        <!-- Recorrido Card -->
                        <div class="glass-pill p-4 rounded-2xl border border-cyan-500/20 bg-cyan-950/10 flex items-center justify-between transition hover:border-cyan-500/40">
                            <div class="flex items-center gap-3.5">
                                <div class="w-11 h-11 rounded-xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center flex-shrink-0 text-xl">
                                    <i class="bi bi-geo-alt-fill"></i>
                                </div>
                                <div>
                                    <div class="text-xs md:text-sm font-bold text-white flex items-center gap-2">
                                        {{ $item['title'] }}
                                        @if ($item['details']['is_manual'])
                                            <span class="text-[10px] bg-zinc-800 text-zinc-400 font-semibold px-2 py-0.5 rounded">Manual</span>
                                        @endif
                                    </div>
                                    <div class="text-[11px] md:text-xs text-zinc-400 mt-0.5">
                                        {{ \Carbon\Carbon::parse($item['date'])->format('d/m/Y - H:i') }} • {{ $item['details']['duration'] }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="text-right">
                                    <div class="text-sm md:text-base font-bold text-white">{{ number_format($item['details']['distance_km'], 2) }} km</div>
                                    <div class="text-[11px] md:text-xs text-rose-400 font-medium">-{{ number_format($item['details']['liters_consumed'], 2) }} L</div>
                                </div>
                                @if (isset($item['details']['id']))
                                    <form action="{{ route('trips.destroy', $item['details']['id']) }}" method="POST" onsubmit="return confirm('¿Deseas cancelar este recorrido?\nSe devolverán {{ number_format($item['details']['liters_consumed'], 2) }} L de gasolina al tanque.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-zinc-400 hover:text-rose-400 hover:bg-rose-500/10 rounded-xl transition flex items-center justify-center" title="Cancelar recorrido y devolver gasolina">
                                            <i class="bi bi-trash3 text-base"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>

</x-layout>
