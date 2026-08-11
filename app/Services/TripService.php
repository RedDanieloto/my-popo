<?php

namespace App\Services;

use App\Models\Trip;
use App\Models\Vehicle;

class TripService
{
    /**
     * Inicia un recorrido en tiempo real mediante GPS.
     */
    public function startLiveTrip(Vehicle $vehicle, ?float $startLat = null, ?float $startLng = null): Trip
    {
        // Si había un viaje previo sin finalizar, se cancela o se marca como tal para no dejar duplicados activos
        Trip::where('vehicle_id', $vehicle->id)
            ->where('status', 'active')
            ->update(['status' => 'cancelled']);

        return Trip::create([
            'vehicle_id' => $vehicle->id,
            'start_time' => now(),
            'distance_km' => 0,
            'liters_consumed' => 0,
            'status' => 'active',
            'is_manual' => false,
            'start_lat' => $startLat,
            'start_lng' => $startLng,
        ]);
    }

    /**
     * Finaliza un recorrido en tiempo real, calcula litros consumidos (o usa el cálculo exacto del velocímetro) y actualiza el tanque.
     */
    public function finishLiveTrip(Trip $trip, float $distanceKm, ?float $endLat = null, ?float $endLng = null, ?float $exactLitersConsumed = null): Trip
    {
        $vehicle = $trip->vehicle;
        $avgConsumption = max(1.0, $vehicle->avg_consumption);

        // Si se proporciona el cálculo exacto del velocímetro/integración, usarlo; de lo contrario usar distancia/consumo_promedio
        $litersConsumed = ($exactLitersConsumed !== null && $exactLitersConsumed > 0)
            ? round($exactLitersConsumed, 2)
            : round($distanceKm / $avgConsumption, 2);

        $trip->update([
            'end_time' => now(),
            'distance_km' => round($distanceKm, 2),
            'liters_consumed' => $litersConsumed,
            'status' => 'completed',
            'end_lat' => $endLat,
            'end_lng' => $endLng,
        ]);

        // Restar litros del tanque actual (nunca menor a 0)
        $vehicle->current_liters = max(0.0, round($vehicle->current_liters - $litersConsumed, 2));
        $vehicle->save();

        return $trip;
    }

    /**
     * Registra un recorrido de forma manual (ej. viajes pasados sin GPS en vivo).
     */
    public function recordManualTrip(Vehicle $vehicle, float $distanceKm, ?string $title = null, ?string $date = null): Trip
    {
        $avgConsumption = max(1.0, $vehicle->avg_consumption);
        $litersConsumed = round($distanceKm / $avgConsumption, 2);
        $tripDate = $date ? \Carbon\Carbon::parse($date) : now();

        $trip = Trip::create([
            'vehicle_id' => $vehicle->id,
            'start_time' => $tripDate,
            'end_time' => $tripDate,
            'distance_km' => round($distanceKm, 2),
            'liters_consumed' => $litersConsumed,
            'status' => 'completed',
            'is_manual' => true,
            'title' => $title ?: 'Recorrido manual',
        ]);

        // Restar litros del tanque
        $vehicle->current_liters = max(0.0, round($vehicle->current_liters - $litersConsumed, 2));
        $vehicle->save();

        return $trip;
    }

    /**
     * Cancela/Elimina un recorrido y devuelve los litros consumidos al tanque del vehículo.
     */
    public function deleteTrip(Trip $trip): void
    {
        $vehicle = $trip->vehicle;

        if ($trip->liters_consumed > 0) {
            $vehicle->current_liters = min(
                (float) $vehicle->tank_capacity,
                round((float) $vehicle->current_liters + (float) $trip->liters_consumed, 2)
            );
            $vehicle->save();
        }

        $trip->delete();
    }
}
