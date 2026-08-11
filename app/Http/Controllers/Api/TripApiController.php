<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\Vehicle;
use App\Services\TripService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TripApiController extends Controller
{
        //ejemplo para n8n
    public function infoCarro()
    {
        $vehicle = Vehicle::where('id', 1)->first();

        return response()->json([
            'gas_actual' => $vehicle->current_liters,
            'km_restantes' => $vehicle->autonomy_km,
            'dias_restantes' => round($vehicle->autonomy_km/32.9, 1),
            'porcentaje_tanque' => round(($vehicle->current_liters/$vehicle->tank_capacity)*100, 1),
            'status' => $vehicle->autonomy_km <= 80 ? '🔴 Tanque bajo, es momento de recargar' : '🟢 Tanque lleno, todo tranquilo',
        ]);
    }
    private function getVehicle(): Vehicle
    {
        return Vehicle::firstOrCreate(
            ['id' => 1],
            [
                'name' => 'Mi Pointer 2005',
                'brand' => 'Volkswagen',
                'model' => 'Pointer',
                'year' => 2005,
                'tank_capacity' => 51.00,
                'current_liters' => 51.00,
                'avg_consumption' => 12.50,
                'initial_avg_consumption' => 12.50,
            ]
        );
    }

    /**
     * Inicia un nuevo recorrido.
     * Soporta GET y POST. Acepta opcionalmente lat y lng.
     */
    public function iniciar(Request $request, TripService $tripService): JsonResponse
    {
        $vehicle = $this->getVehicle();

        $lat = $request->input('lat') !== null ? (float) $request->input('lat') : null;
        $lng = $request->input('lng') !== null ? (float) $request->input('lng') : null;

        $trip = $tripService->startLiveTrip($vehicle, $lat, $lng);

        $startTimeStr = $trip->start_time ? $trip->start_time->format('h:i A') : now()->format('h:i A');

        return response()->json([
            'success' => true,
            'action' => 'started',
            'message' => "Recorrido iniciado correctamente a las {$startTimeStr}.",
            'trip' => [
                'id' => $trip->id,
                'start_time' => $trip->start_time ? $trip->start_time->toIso8601String() : null,
                'status' => $trip->status,
                'start_lat' => $trip->start_lat,
                'start_lng' => $trip->start_lng,
            ],
            'vehicle' => [
                'name' => $vehicle->name,
                'current_liters' => $vehicle->current_liters,
                'autonomy_km' => $vehicle->autonomy_km,
            ],
        ]);
    }

    /**
     * Finaliza el recorrido activo.
     * Soporta GET y POST. Acepta distance_km/km/distancia, lat, lng y liters_consumed.
     */
    public function finalizar(Request $request, TripService $tripService): JsonResponse
    {
        $vehicle = $this->getVehicle();

        $trip = Trip::where('vehicle_id', $vehicle->id)
            ->where('status', 'active')
            ->latest()
            ->first();

        if (! $trip) {
            return response()->json([
                'success' => false,
                'action' => 'none',
                'message' => 'No hay ningún recorrido activo para finalizar.',
            ], 404);
        }

        $endLat = $request->input('lat') !== null ? (float) $request->input('lat') : null;
        $endLng = $request->input('lng') !== null ? (float) $request->input('lng') : null;
        $exactLiters = $request->input('liters_consumed') !== null ? (float) $request->input('liters_consumed') : null;

        // Distancia enviada explícitamente (distance_km, km o distancia)
        $distanceInput = $request->input('distance_km') ?? $request->input('km') ?? $request->input('distancia');
        $distanceKm = $distanceInput !== null ? (float) $distanceInput : null;

        // Si no se proporcionó distancia pero se tienen coordenadas de inicio y fin, calcular por Haversine
        if ($distanceKm === null && $trip->start_lat && $trip->start_lng && $endLat && $endLng) {
            $distanceKm = $this->calculateHaversineDistance(
                (float) $trip->start_lat,
                (float) $trip->start_lng,
                $endLat,
                $endLng
            );
        }

        // Si sigue sin distancia, asignar 0.0 por defecto para evitar errores
        if ($distanceKm === null) {
            $distanceKm = 0.0;
        }

        $trip = $tripService->finishLiveTrip($trip, $distanceKm, $endLat, $endLng, $exactLiters);
        $freshVehicle = $vehicle->fresh();

        $distFormatted = number_format($trip->distance_km, 1);
        $litersFormatted = number_format($trip->liters_consumed, 2);
        $fuelPercentFormatted = number_format($freshVehicle->fuel_percentage, 1);

        $message = "Recorrido finalizado. Recorriste {$distFormatted} km y consumiste {$litersFormatted} L. Tanque al {$fuelPercentFormatted}%.";

        return response()->json([
            'success' => true,
            'action' => 'finished',
            'message' => $message,
            'trip' => [
                'id' => $trip->id,
                'distance_km' => $trip->distance_km,
                'liters_consumed' => $trip->liters_consumed,
                'duration' => $trip->duration_formatted,
                'start_time' => $trip->start_time ? $trip->start_time->toIso8601String() : null,
                'end_time' => $trip->end_time ? $trip->end_time->toIso8601String() : null,
            ],
            'vehicle' => [
                'name' => $freshVehicle->name,
                'current_liters' => $freshVehicle->current_liters,
                'fuel_percentage' => $freshVehicle->fuel_percentage,
                'autonomy_km' => $freshVehicle->autonomy_km,
                'is_low_fuel' => $freshVehicle->is_low_fuel,
            ],
        ]);
    }

    /**
     * Alterna el estado del recorrido en un solo botón:
     * Si está activo lo finaliza, si está inactivo lo inicia.
     */
    public function toggle(Request $request, TripService $tripService): JsonResponse
    {
        $vehicle = $this->getVehicle();

        $activeTrip = Trip::where('vehicle_id', $vehicle->id)
            ->where('status', 'active')
            ->latest()
            ->first();

        if ($activeTrip) {
            return $this->finalizar($request, $tripService);
        }

        return $this->iniciar($request, $tripService);
    }

    /**
     * Consulta el estado del recorrido actual.
     */
    public function estado(): JsonResponse
    {
        $vehicle = $this->getVehicle();

        $activeTrip = Trip::where('vehicle_id', $vehicle->id)
            ->where('status', 'active')
            ->latest()
            ->first();

        if (! $activeTrip) {
            return response()->json([
                'success' => true,
                'is_active' => false,
                'message' => 'No hay recorrido activo.',
                'vehicle' => [
                    'current_liters' => $vehicle->current_liters,
                    'fuel_percentage' => $vehicle->fuel_percentage,
                    'autonomy_km' => $vehicle->autonomy_km,
                ],
            ]);
        }

        $durationMinutes = (int) $activeTrip->start_time->diffInMinutes(now());

        return response()->json([
            'success' => true,
            'is_active' => true,
            'message' => "Hay un recorrido activo iniciado hace {$durationMinutes} min.",
            'trip' => [
                'id' => $activeTrip->id,
                'start_time' => $activeTrip->start_time->toIso8601String(),
                'duration_minutes' => $durationMinutes,
                'start_lat' => $activeTrip->start_lat,
                'start_lng' => $activeTrip->start_lng,
            ],
            'vehicle' => [
                'current_liters' => $vehicle->current_liters,
                'fuel_percentage' => $vehicle->fuel_percentage,
                'autonomy_km' => $vehicle->autonomy_km,
            ],
        ]);
    }

    /**
     * Calcula la distancia entre dos coordenadas en kilómetros (Fórmula de Haversine).
     */
    private function calculateHaversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371; // radio terrestre en km

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }
}
