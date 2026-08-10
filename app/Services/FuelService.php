<?php

namespace App\Services;

use App\Models\FuelLoad;
use App\Models\Trip;
use App\Models\Vehicle;

class FuelService
{
    /**
     * Registra una recarga de gasolina y actualiza el estado del vehículo.
     */
    public function recordFuelLoad(Vehicle $vehicle, array $data): FuelLoad
    {
        $amountPaid = (float) $data['amount_paid'];
        $pricePerLiter = (float) $data['price_per_liter'];
        $liters = $amountPaid > 0 && $pricePerLiter > 0
            ? round($amountPaid / $pricePerLiter, 2)
            : 0;

        $isFullTank = ! empty($data['is_full_tank']);
        $date = isset($data['date']) ? $data['date'] : now();
        $note = $data['note'] ?? null;

        // Guardar la recarga de gasolina
        $fuelLoad = FuelLoad::create([
            'vehicle_id' => $vehicle->id,
            'amount_paid' => $amountPaid,
            'price_per_liter' => $pricePerLiter,
            'liters' => $liters,
            'is_full_tank' => $isFullTank,
            'date' => $date,
            'note' => $note,
        ]);

        if ($isFullTank) {
            // Recalibración automática de consumo si es tanque lleno
            $this->recalibrateConsumption($vehicle, $liters, $fuelLoad);
            $vehicle->current_liters = $vehicle->tank_capacity;
        } else {
            // Sumar litros sin exceder la capacidad del tanque
            $vehicle->current_liters = min($vehicle->current_liters + $liters, $vehicle->tank_capacity);
        }

        $vehicle->save();

        return $fuelLoad;
    }

    /**
     * Recalcula automáticamente el consumo promedio (km/L).
     * consumo = kilómetros recorridos desde la última carga completa / litros necesarios para llenar el tanque
     */
    protected function recalibrateConsumption(Vehicle $vehicle, float $litersToFill, FuelLoad $currentFuelLoad): void
    {
        // Buscar la carga completa previa a la actual
        $previousFullLoad = FuelLoad::where('vehicle_id', $vehicle->id)
            ->where('is_full_tank', true)
            ->where('id', '!=', $currentFuelLoad->id)
            ->orderBy('date', 'desc')
            ->first();

        $sinceDate = $previousFullLoad ? $previousFullLoad->date : $vehicle->created_at;

        // Sumar kilómetros recorridos en viajes completados desde esa fecha
        $distanceDriven = Trip::where('vehicle_id', $vehicle->id)
            ->where('status', 'completed')
            ->where('start_time', '>=', $sinceDate)
            ->sum('distance_km');

        if ($distanceDriven > 0 && $litersToFill > 0) {
            $newAvgConsumption = round($distanceDriven / $litersToFill, 2);
            // Asegurar un consumo realista (> 1 km/L)
            if ($newAvgConsumption >= 1.0) {
                $vehicle->avg_consumption = $newAvgConsumption;
            }
        }
    }
}
