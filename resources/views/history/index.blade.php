<x-layout :vehicle="$vehicle" title="Historial - My Popo">

    <div class="glass-card p-5 rounded-3xl space-y-4">
        <div class="flex items-center justify-between border-b border-zinc-800/80 pb-3">
            <div>
                <h2 class="text-base font-bold text-white">Historial de Actividad</h2>
                <p class="text-xs text-zinc-400">Recargas de combustible y recorridos ordenados</p>
            </div>
            <div class="p-2 bg-zinc-800 text-zinc-300 rounded-2xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        @if ($unifiedHistory->isEmpty())
            <div class="text-center py-10 space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-zinc-900 border border-zinc-800 text-zinc-600 mx-auto flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
                <div class="text-xs text-zinc-400">Aún no hay registros de recargas o recorridos.</div>
            </div>
        @else
            <div class="space-y-3">
                @foreach ($unifiedHistory as $item)
                    @if ($item['type'] === 'fuel_load')
                        <!-- Recarga de Gasolina Card -->
                        <div class="glass-pill p-3.5 rounded-2xl border border-emerald-500/20 bg-emerald-950/10 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-white flex items-center gap-1.5">
                                        Recarga de Gasolina
                                        @if ($item['details']['is_full_tank'])
                                            <span class="text-[9px] bg-emerald-500/20 text-emerald-300 font-semibold px-1.5 py-0.5 rounded border border-emerald-500/30">Tanque lleno</span>
                                        @endif
                                    </div>
                                    <div class="text-[11px] text-zinc-400">
                                        {{ \Carbon\Carbon::parse($item['date'])->format('d/m/Y - H:i') }}
                                        @if ($item['details']['note'])
                                            • <span class="italic text-zinc-500">{{ $item['details']['note'] }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-bold text-emerald-400">+{{ number_format($item['details']['liters'], 2) }} L</div>
                                <div class="text-[11px] text-zinc-400">${{ number_format($item['details']['amount_paid'], 2) }}</div>
                            </div>
                        </div>
                    @else
                        <!-- Recorrido Card -->
                        <div class="glass-pill p-3.5 rounded-2xl border border-cyan-500/20 bg-cyan-950/10 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-white flex items-center gap-1.5">
                                        {{ $item['title'] }}
                                        @if ($item['details']['is_manual'])
                                            <span class="text-[9px] bg-zinc-800 text-zinc-400 font-semibold px-1.5 py-0.5 rounded">Manual</span>
                                        @endif
                                    </div>
                                    <div class="text-[11px] text-zinc-400">
                                        {{ \Carbon\Carbon::parse($item['date'])->format('d/m/Y - H:i') }} • {{ $item['details']['duration'] }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-bold text-white">{{ number_format($item['details']['distance_km'], 2) }} km</div>
                                <div class="text-[11px] text-rose-400 font-medium">-{{ number_format($item['details']['liters_consumed'], 2) }} L</div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>

</x-layout>
