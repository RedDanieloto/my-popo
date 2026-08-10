<x-layout :vehicle="$vehicle" title="Velocímetro & Recorrido - My Popo">

    <div class="glass-card p-5 rounded-3xl space-y-5 text-center relative overflow-hidden">
        <!-- Ambient Glow -->
        <div id="ambient-glow" class="absolute -top-28 left-1/2 -translate-x-1/2 w-64 h-64 rounded-full blur-3xl opacity-25 bg-cyan-500 transition-all duration-700"></div>

        <!-- Top Header Status -->
        <div class="flex items-center justify-between border-b border-zinc-800/80 pb-3">
            <div class="text-left">
                <h2 class="text-base font-bold text-white flex items-center gap-2">
                    Velocímetro & Recorrido
                    <span class="text-[9px] bg-cyan-500/20 text-cyan-300 font-extrabold px-2 py-0.5 rounded-full border border-cyan-500/30">PRECISIÓN 100%</span>
                </h2>
                <p class="text-xs text-zinc-400">Telemetría e integración de consumo en tiempo real</p>
            </div>
            <div id="gps-status-badge" class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-zinc-800 text-zinc-400 flex items-center gap-1.5 border border-zinc-700">
                <span id="gps-dot" class="w-2 h-2 rounded-full bg-zinc-500"></span>
                <span id="gps-text">GPS Inactivo</span>
            </div>
        </div>

        <!-- HTTP / Location Security Diagnostic Warning -->
        <div id="https-warning" class="hidden glass-card bg-amber-950/60 border-amber-500/50 p-3.5 rounded-2xl text-left text-xs text-amber-200 space-y-1">
            <div class="font-bold flex items-center gap-1.5">
                <span>⚠️ Aviso de Geolocalización en iPhone/Android</span>
            </div>
            <p class="text-[11px] text-amber-300/90 leading-relaxed">
                El navegador solicitará permiso de ubicación únicamente al presionar <strong>"Iniciar Recorrido"</strong>. Si usas Safari en iPhone por IP directa, asegúrate de otorgar permisos o prueba el modo simulación abajo.
            </p>
        </div>

        <!-- Tesla / Apple Style Digital Speedometer Gauge -->
        <div class="relative py-2 flex flex-col items-center justify-center">
            <!-- SVG Speedometer Dial Arc -->
            <div class="relative w-56 h-56 flex items-center justify-center">
                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 200 200">
                    <!-- Background Track Arc -->
                    <circle cx="100" cy="100" r="82" stroke="currentColor" stroke-width="12" class="text-zinc-900" fill="none"
                            stroke-dasharray="386" stroke-dashoffset="96" stroke-linecap="round" />
                    <!-- Dynamic Speed Progress Arc -->
                    <circle id="speed-arc" cx="100" cy="100" r="82" stroke="url(#speedGradient)" stroke-width="12" fill="none"
                            stroke-dasharray="386" stroke-dashoffset="386" stroke-linecap="round" class="transition-all duration-500 ease-out" />
                    <defs>
                        <linearGradient id="speedGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#06b6d4" />
                            <stop offset="50%" stop-color="#10b981" />
                            <stop offset="85%" stop-color="#f59e0b" />
                            <stop offset="100%" stop-color="#ef4444" />
                        </linearGradient>
                    </defs>
                </svg>

                <!-- Center Digital Speed Readout -->
                <div class="absolute inset-0 flex flex-col items-center justify-center space-y-0.5">
                    <div class="text-xs font-semibold text-zinc-400 uppercase tracking-widest">Velocidad</div>
                    <div class="text-6xl font-black text-white tracking-tighter flex items-baseline justify-center gap-1">
                        <span id="display-speed" class="bg-gradient-to-b from-white via-zinc-100 to-zinc-400 bg-clip-text text-transparent font-mono">0</span>
                        <span class="text-sm font-bold text-cyan-400">km/h</span>
                    </div>

                    <!-- Max Speed Badge -->
                    <div class="text-[11px] font-bold text-zinc-400 bg-zinc-900/90 border border-zinc-800 px-2.5 py-0.5 rounded-full flex items-center gap-1">
                        <span>Máx:</span>
                        <span id="display-max-speed" class="text-white font-mono">0</span>
                        <span>km/h</span>
                    </div>
                </div>
            </div>

            <!-- Instantaneous Efficiency & Engine Status Pill -->
            <div id="efficiency-pill" class="mt-1 glass-pill px-4 py-1.5 rounded-full border border-zinc-800 text-xs font-bold text-zinc-300 flex items-center gap-2 transition-all">
                <span id="efficiency-dot" class="w-2 h-2 rounded-full bg-zinc-500"></span>
                <span id="efficiency-text">Detenido / Esperando movimiento</span>
            </div>
        </div>

        <!-- Real-Time Precision Metrics Grid (4 Readouts) -->
        <div class="grid grid-cols-2 gap-3 pt-1">
            <!-- 1. Distancia Recorrida -->
            <div class="glass-pill p-3.5 rounded-2xl text-left border border-zinc-800/60">
                <div class="text-[11px] text-zinc-400 font-medium">Distancia Recorrida</div>
                <div class="text-xl font-bold text-white mt-0.5 font-mono flex items-baseline gap-1">
                    <span id="display-km">0.00</span>
                    <span class="text-xs text-zinc-400 font-normal">km</span>
                </div>
            </div>

            <!-- 2. Litros Consumidos en Tiempo Real -->
            <div class="glass-pill p-3.5 rounded-2xl text-left border border-zinc-800/60">
                <div class="text-[11px] text-zinc-400 font-medium flex items-center justify-between">
                    <span>Consumo Real</span>
                    <span class="text-[9px] text-emerald-400 font-bold uppercase">Integrado</span>
                </div>
                <div class="text-xl font-bold text-emerald-400 mt-0.5 font-mono flex items-baseline gap-1">
                    <span id="display-liters">0.000</span>
                    <span class="text-xs text-zinc-400 font-normal">L</span>
                </div>
            </div>

            <!-- 3. Tiempo Transcurrido -->
            <div class="glass-pill p-3.5 rounded-2xl text-left border border-zinc-800/60">
                <div class="text-[11px] text-zinc-400 font-medium">Tiempo Transcurrido</div>
                <div id="display-timer" class="text-xl font-bold text-white mt-0.5 font-mono">
                    00:00:00
                </div>
            </div>

            <!-- 4. Consumo Promedio del Viaje -->
            <div class="glass-pill p-3.5 rounded-2xl text-left border border-zinc-800/60">
                <div class="text-[11px] text-zinc-400 font-medium">Rendimiento Viaje</div>
                <div class="text-xl font-bold text-cyan-300 mt-0.5 font-mono flex items-baseline gap-1">
                    <span id="display-trip-avg">--.-</span>
                    <span class="text-xs text-zinc-400 font-normal">km/L</span>
                </div>
            </div>
        </div>

        <!-- Controls: Start, Stop & Simulation Mode Buttons -->
        <div class="pt-2 space-y-3">
            <input type="hidden" id="active-trip-id" value="{{ $activeTrip ? $activeTrip->id : '' }}">
            <input type="hidden" id="vehicle-avg-consumption" value="{{ $vehicle->avg_consumption }}">

            <!-- Iniciar Recorrido Button -->
            <button id="btn-start-trip" class="w-full py-4 bg-gradient-to-r from-cyan-500 to-emerald-400 hover:from-cyan-400 hover:to-emerald-300 text-zinc-950 font-black text-base rounded-2xl shadow-xl shadow-cyan-500/20 transition active:scale-[0.98] flex items-center justify-center gap-2.5 {{ $activeTrip ? 'hidden' : '' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Iniciar Recorrido con GPS</span>
            </button>

            <!-- Finalizar Recorrido Button -->
            <button id="btn-finish-trip" class="w-full py-4 bg-gradient-to-r from-rose-500 to-red-600 hover:from-rose-400 hover:to-red-500 text-white font-black text-base rounded-2xl shadow-xl shadow-rose-500/30 transition active:scale-[0.98] flex items-center justify-center gap-2.5 {{ $activeTrip ? '' : 'hidden' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
                </svg>
                <span>Finalizar Recorrido</span>
            </button>

            <!-- Simulador de Manejo (Test Mode) -->
            <div class="pt-1">
                <button id="btn-simulate-trip" type="button" class="w-full py-2.5 bg-zinc-900 hover:bg-zinc-800 text-cyan-400 font-bold text-xs rounded-xl border border-cyan-500/30 transition flex items-center justify-center gap-2">
                    <span>⚡ Simular Manejo de Prueba (Sin Mover Vehículo)</span>
                </button>
            </div>
        </div>

        <p class="text-[11px] text-zinc-500 pt-1">
            Permisos requeridos por el navegador al tocar "Iniciar Recorrido".
        </p>
    </div>

    <!-- Geolocation, Speedometer, Simulation & High-Precision Integration JS -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const btnStart = document.getElementById('btn-start-trip');
            const btnFinish = document.getElementById('btn-finish-trip');
            const btnSimulate = document.getElementById('btn-simulate-trip');
            const activeTripIdInput = document.getElementById('active-trip-id');
            const baseAvgConsumption = parseFloat(document.getElementById('vehicle-avg-consumption').value) || 12.5;

            const displaySpeed = document.getElementById('display-speed');
            const displayMaxSpeed = document.getElementById('display-max-speed');
            const displayKm = document.getElementById('display-km');
            const displayLiters = document.getElementById('display-liters');
            const displayTimer = document.getElementById('display-timer');
            const displayTripAvg = document.getElementById('display-trip-avg');

            const speedArc = document.getElementById('speed-arc');
            const efficiencyPill = document.getElementById('efficiency-pill');
            const efficiencyDot = document.getElementById('efficiency-dot');
            const efficiencyText = document.getElementById('efficiency-text');

            const gpsDot = document.getElementById('gps-dot');
            const gpsText = document.getElementById('gps-text');
            const ambientGlow = document.getElementById('ambient-glow');
            const httpsWarning = document.getElementById('https-warning');

            let watchId = null;
            let timerInterval = null;
            let simulationInterval = null;
            let isSimulating = false;

            // Telemetry accumulators
            let totalDistanceKm = 0;
            let totalLitersConsumed = 0;
            let currentSpeedKmh = 0;
            let maxSpeedKmh = 0;
            let lastPosition = null;
            let lastTimestamp = null;
            let startTime = null;

            const MAX_SPEEDOMETER_SCALE = 160; 
            const ARC_TOTAL_DASH = 386;

            // Show location permission advice if HTTP
            if (location.protocol === 'http:' && location.hostname !== 'localhost' && httpsWarning) {
                httpsWarning.classList.remove('hidden');
            }

            // Safe CSRF token helper
            function getCsrfToken() {
                const meta = document.querySelector('meta[name="csrf-token"]');
                return meta ? meta.getAttribute('content') : '';
            }

            // Safe Date parser for Safari iOS
            function parseSafeDate(dateStr) {
                if (!dateStr) return new Date();
                const d = new Date(dateStr);
                return isNaN(d.getTime()) ? new Date() : d;
            }

            // Restore from localStorage if active trip exists
            const savedTrip = JSON.parse(localStorage.getItem('mypopo_active_trip') || 'null');
            if (activeTripIdInput.value || (savedTrip && savedTrip.tripId)) {
                if (savedTrip && savedTrip.tripId && (savedTrip.tripId == activeTripIdInput.value || !activeTripIdInput.value)) {
                    activeTripIdInput.value = savedTrip.tripId;
                    totalDistanceKm = parseFloat(savedTrip.distance) || 0;
                    totalLitersConsumed = parseFloat(savedTrip.litersConsumed) || 0;
                    maxSpeedKmh = parseFloat(savedTrip.maxSpeed) || 0;
                    startTime = parseSafeDate(savedTrip.startTime);
                    lastPosition = savedTrip.lastPosition || null;
                    lastTimestamp = savedTrip.lastTimestamp || Date.now();
                } else if (activeTripIdInput.value) {
                    startTime = new Date();
                    lastTimestamp = Date.now();
                }
                resumeTracking();
            }

            function calculateDistance(lat1, lon1, lat2, lon2) {
                const R = 6371;
                const dLat = (lat2 - lat1) * Math.PI / 180;
                const dLon = (lon2 - lon1) * Math.PI / 180;
                const a = 
                    Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
                    Math.sin(dLon / 2) * Math.sin(dLon / 2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                return R * c;
            }

            function calculateInstantaneousFuelDelta(speedKmh, dKm, dtSeconds) {
                if (speedKmh <= 2) {
                    const idlingLitersPerSec = 0.85 / 3600;
                    return {
                        litersDelta: idlingLitersPerSec * dtSeconds,
                        instantEfficiencyText: '🛑 Ralentí: 0.85 L/h (Semáforo)',
                        stateColor: 'amber'
                    };
                }

                let instantKmPerLiter = baseAvgConsumption;
                var stateLabel = '';
                var color = 'cyan';

                if (speedKmh <= 45) {
                    instantKmPerLiter = 10.5;
                    stateLabel = '🚦 Tráfico Urbano (10.5 km/L)';
                    color = 'cyan';
                } else if (speedKmh <= 85) {
                    instantKmPerLiter = 13.5;
                    stateLabel = '⚡ Urbano Fluido (13.5 km/L)';
                    color = 'emerald';
                } else if (speedKmh <= 115) {
                    instantKmPerLiter = 15.5;
                    stateLabel = '🏎️ Carretera Óptimo (15.5 km/L)';
                    color = 'emerald';
                } else {
                    instantKmPerLiter = 12.0;
                    stateLabel = '🔥 Alta Velocidad (12.0 km/L)';
                    color = 'rose';
                }

                const litersDelta = dKm > 0 ? (dKm / instantKmPerLiter) : 0;

                return {
                    litersDelta: litersDelta,
                    instantEfficiencyText: stateLabel,
                    stateColor: color
                };
            }

            function updateUI() {
                displaySpeed.textContent = Math.round(currentSpeedKmh);
                displayMaxSpeed.textContent = Math.round(maxSpeedKmh);

                const speedPercent = Math.min(1, currentSpeedKmh / MAX_SPEEDOMETER_SCALE);
                const offset = ARC_TOTAL_DASH * (1 - (speedPercent * 0.75));
                speedArc.style.strokeDashoffset = offset;

                displayKm.textContent = totalDistanceKm.toFixed(2);
                displayLiters.textContent = totalLitersConsumed.toFixed(3);

                if (totalDistanceKm > 0.05 && totalLitersConsumed > 0) {
                    const tripAvg = (totalDistanceKm / totalLitersConsumed).toFixed(1);
                    displayTripAvg.textContent = tripAvg;
                } else {
                    displayTripAvg.textContent = '--.-';
                }

                if (startTime) {
                    const now = new Date();
                    const diffMs = Math.max(0, now - startTime);
                    const secs = Math.floor((diffMs / 1000) % 60).toString().padStart(2, '0');
                    const mins = Math.floor((diffMs / (1000 * 60)) % 60).toString().padStart(2, '0');
                    const hrs = Math.floor(diffMs / (1000 * 60 * 60)).toString().padStart(2, '0');
                    displayTimer.textContent = `${hrs}:${mins}:${secs}`;
                }

                if (activeTripIdInput.value) {
                    localStorage.setItem('mypopo_active_trip', JSON.stringify({
                        tripId: activeTripIdInput.value,
                        distance: totalDistanceKm,
                        litersConsumed: totalLitersConsumed,
                        maxSpeed: maxSpeedKmh,
                        startTime: startTime ? startTime.toISOString() : null,
                        lastPosition: lastPosition,
                        lastTimestamp: lastTimestamp
                    }));
                }
            }

            function startTimer() {
                if (timerInterval) clearInterval(timerInterval);
                timerInterval = setInterval(updateUI, 1000);
            }

            function setGpsState(active, message = '') {
                if (active) {
                    gpsDot.className = 'w-2 h-2 rounded-full bg-emerald-400 animate-ping';
                    gpsText.textContent = message || 'GPS Grabando';
                    gpsText.className = 'text-emerald-400 font-semibold';
                    ambientGlow.className = 'absolute -top-28 left-1/2 -translate-x-1/2 w-64 h-64 rounded-full blur-3xl opacity-30 bg-emerald-500 transition-all duration-700';
                } else {
                    gpsDot.className = 'w-2 h-2 rounded-full bg-zinc-500';
                    gpsText.textContent = message || 'GPS Inactivo';
                    gpsText.className = 'text-zinc-400';
                    ambientGlow.className = 'absolute -top-28 left-1/2 -translate-x-1/2 w-64 h-64 rounded-full blur-3xl opacity-25 bg-cyan-500 transition-all duration-700';
                }
            }

            function resumeTracking() {
                btnStart.classList.add('hidden');
                btnFinish.classList.remove('hidden');
                if (!startTime) startTime = new Date();
                if (!lastTimestamp) lastTimestamp = Date.now();
                startTimer();

                if ('geolocation' in navigator) {
                    watchId = navigator.geolocation.watchPosition((pos) => {
                        setGpsState(true, 'GPS 100% Activo');
                        const coords = pos.coords;
                        const nowMs = Date.now();
                        const dtSeconds = lastTimestamp ? Math.max(0.5, (nowMs - lastTimestamp) / 1000) : 1;
                        lastTimestamp = nowMs;

                        if (coords.accuracy > 35) return;

                        let rawSpeedKmh = 0;
                        if (coords.speed !== null && coords.speed !== undefined && !isNaN(coords.speed) && coords.speed >= 0) {
                            rawSpeedKmh = coords.speed * 3.6;
                        }

                        let dKm = 0;
                        if (lastPosition) {
                            dKm = calculateDistance(
                                lastPosition.latitude,
                                lastPosition.longitude,
                                coords.latitude,
                                coords.longitude
                            );

                            if (dKm < 0.004) {
                                dKm = 0;
                            } else {
                                totalDistanceKm += dKm;
                                lastPosition = { latitude: coords.latitude, longitude: coords.longitude };
                            }

                            if (rawSpeedKmh === 0 && dKm > 0) {
                                rawSpeedKmh = (dKm / dtSeconds) * 3600;
                            }
                        } else {
                            lastPosition = { latitude: coords.latitude, longitude: coords.longitude };
                        }

                        currentSpeedKmh = Math.max(0, rawSpeedKmh);
                        if (currentSpeedKmh > maxSpeedKmh) {
                            maxSpeedKmh = currentSpeedKmh;
                        }

                        const fuelEval = calculateInstantaneousFuelDelta(currentSpeedKmh, dKm, dtSeconds);
                        totalLitersConsumed += fuelEval.litersDelta;

                        efficiencyText.textContent = fuelEval.instantEfficiencyText;
                        if (fuelEval.stateColor === 'amber') {
                            efficiencyDot.className = 'w-2 h-2 rounded-full bg-amber-400 animate-ping';
                        } else if (fuelEval.stateColor === 'emerald') {
                            efficiencyDot.className = 'w-2 h-2 rounded-full bg-emerald-400';
                        } else if (fuelEval.stateColor === 'rose') {
                            efficiencyDot.className = 'w-2 h-2 rounded-full bg-rose-500 animate-bounce';
                        } else {
                            efficiencyDot.className = 'w-2 h-2 rounded-full bg-cyan-400';
                        }

                        updateUI();
                    }, (err) => {
                        console.warn('Geolocation error:', err);
                        if (err.code === 1) {
                            setGpsState(false, 'Permiso Denegado');
                        } else {
                            setGpsState(false, 'Buscando GPS...');
                        }
                    }, {
                        enableHighAccuracy: true,
                        maximumAge: 500,
                        timeout: 10000
                    });
                }
            }

            // Iniciar Recorrido con GPS Real
            btnStart.addEventListener('click', async () => {
                let startLat = null, startLng = null;

                if ('geolocation' in navigator) {
                    try {
                        const pos = await new Promise((resolve, reject) => {
                            navigator.geolocation.getCurrentPosition(resolve, reject, { timeout: 7000 });
                        });
                        startLat = pos.coords.latitude;
                        startLng = pos.coords.longitude;
                    } catch (e) {
                        console.warn('Posición inicial no obtenida:', e);
                    }
                }

                try {
                    const startUrl = "{{ route('trips.start') }}";
                    const resp = await fetch(startUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': getCsrfToken(),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ lat: startLat, lng: startLng })
                    });
                    const data = await resp.json();
                    if (data.success && data.trip) {
                        activeTripIdInput.value = data.trip.id;
                        totalDistanceKm = 0;
                        totalLitersConsumed = 0;
                        currentSpeedKmh = 0;
                        maxSpeedKmh = 0;
                        startTime = new Date();
                        lastTimestamp = Date.now();
                        lastPosition = startLat && startLng ? { latitude: startLat, longitude: startLng } : null;
                        resumeTracking();
                    }
                } catch (err) {
                    alert('Error al iniciar el recorrido: ' + err.message);
                }
            });

            // Modo Simulador de Manejo (Test Drive Simulation)
            btnSimulate.addEventListener('click', async () => {
                if (isSimulating) {
                    alert('La simulación ya está activa.');
                    return;
                }

                if (!activeTripIdInput.value) {
                    try {
                        const startUrl = "{{ route('trips.start') }}";
                        const resp = await fetch(startUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': getCsrfToken(),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ lat: 19.4326, lng: -99.1332 })
                        });
                        const data = await resp.json();
                        if (data.success && data.trip) {
                            activeTripIdInput.value = data.trip.id;
                        }
                    } catch (e) {
                        console.warn('Error al iniciar simulación:', e);
                    }
                }

                isSimulating = true;
                btnStart.classList.add('hidden');
                btnFinish.classList.remove('hidden');
                btnSimulate.innerHTML = '<span>⚡ Simulación Activa (Manejando a ~65 km/h)...</span>';
                btnSimulate.className = 'w-full py-2.5 bg-emerald-950 border border-emerald-500/50 text-emerald-300 font-bold text-xs rounded-xl animate-pulse';

                if (!startTime) startTime = new Date();
                startTimer();

                setGpsState(true, 'Simulación Activa');

                let targetSpeed = 65;
                simulationInterval = setInterval(() => {
                    currentSpeedKmh = Math.round(targetSpeed + (Math.random() * 12 - 6));
                    if (currentSpeedKmh > maxSpeedKmh) maxSpeedKmh = currentSpeedKmh;

                    const dtSeconds = 1;
                    const dKm = (currentSpeedKmh / 3600) * dtSeconds;
                    totalDistanceKm += dKm;

                    const fuelEval = calculateInstantaneousFuelDelta(currentSpeedKmh, dKm, dtSeconds);
                    totalLitersConsumed += fuelEval.litersDelta;

                    efficiencyText.textContent = fuelEval.instantEfficiencyText;
                    efficiencyDot.className = 'w-2 h-2 rounded-full bg-emerald-400 animate-ping';

                    updateUI();
                }, 1000);
            });

            // Finalizar Recorrido
            btnFinish.addEventListener('click', async () => {
                let tripId = activeTripIdInput.value;

                // Ensure a valid trip ID exists
                if (!tripId || tripId === '' || tripId === 'undefined') {
                    try {
                        const startUrl = "{{ route('trips.start') }}";
                        const startResp = await fetch(startUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': getCsrfToken(),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({})
                        });
                        const startData = await startResp.json();
                        if (startData.success && startData.trip) {
                            tripId = startData.trip.id;
                            activeTripIdInput.value = tripId;
                        }
                    } catch (e) {}
                }

                if (!tripId) {
                    alert('No hay un recorrido activo para finalizar.');
                    return;
                }

                if (!confirm(`¿Deseas finalizar el recorrido?\n• Distancia: ${totalDistanceKm.toFixed(2)} km\n• Litros consumidos: ${totalLitersConsumed.toFixed(3)} L`)) {
                    return;
                }

                if (simulationInterval) clearInterval(simulationInterval);
                if (watchId !== null) navigator.geolocation.clearWatch(watchId);

                let endLat = null, endLng = null;
                if (lastPosition) {
                    endLat = lastPosition.latitude;
                    endLng = lastPosition.longitude;
                }

                // Construct absolute URL safely using Laravel route helper
                const finishRouteTemplate = "{{ route('trips.finish', ':id') }}";
                const finishUrl = finishRouteTemplate.replace(':id', encodeURIComponent(tripId));

                try {
                    const resp = await fetch(finishUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': getCsrfToken(),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            distance_km: totalDistanceKm,
                            liters_consumed: totalLitersConsumed,
                            lat: endLat,
                            lng: endLng
                        })
                    });
                    const data = await resp.json();

                    if (resp.ok && data.success) {
                        if (timerInterval) clearInterval(timerInterval);
                        localStorage.removeItem('mypopo_active_trip');
                        window.location.href = "{{ route('dashboard') }}";
                    } else {
                        const errMsg = data.message || (data.errors ? Object.values(data.errors).flat().join('\n') : 'Error al finalizar');
                        alert('Error: ' + errMsg);
                    }
                } catch (err) {
                    alert('Error al finalizar el recorrido: ' + err.message);
                }
            });
        });
    </script>

</x-layout>
