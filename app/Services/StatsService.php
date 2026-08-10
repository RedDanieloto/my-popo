<?php

namespace App\Services;

use App\Models\FuelLoad;
use App\Models\Trip;
use App\Models\Vehicle;
use Carbon\Carbon;

class StatsService
{
    /**
     * Calcula todas las estadísticas requeridas para el vehículo.
     */
    public function getVehicleStats(Vehicle $vehicle): array
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Cargas de combustible del mes actual
        $monthlyFuelLoads = FuelLoad::where('vehicle_id', $vehicle->id)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get();

        $litersLoadedThisMonth = round($monthlyFuelLoads->sum('liters'), 2);
        $moneySpentThisMonth = round($monthlyFuelLoads->sum('amount_paid'), 2);

        // Kilómetros recorridos este mes
        $kmDrivenThisMonth = round(
            Trip::where('vehicle_id', $vehicle->id)
                ->where('status', 'completed')
                ->whereBetween('start_time', [$startOfMonth, $endOfMonth])
                ->sum('distance_km'),
            2
        );

        // Total histórico
        $totalKmDriven = round(
            Trip::where('vehicle_id', $vehicle->id)
                ->where('status', 'completed')
                ->sum('distance_km'),
            2
        );

        $totalMoneySpent = round(
            FuelLoad::where('vehicle_id', $vehicle->id)->sum('amount_paid'),
            2
        );

        // Costo por kilómetro
        $costPerKmMonth = $kmDrivenThisMonth > 0
            ? round($moneySpentThisMonth / $kmDrivenThisMonth, 2)
            : 0;

        $costPerKmTotal = $totalKmDriven > 0
            ? round($totalMoneySpent / $totalKmDriven, 2)
            : ($monthlyFuelLoads->avg('price_per_liter') / max(1, $vehicle->avg_consumption));

        return [
            'liters_loaded_this_month' => $litersLoadedThisMonth,
            'money_spent_this_month' => $moneySpentThisMonth,
            'km_driven_this_month' => $kmDrivenThisMonth,
            'total_km_driven' => $totalKmDriven,
            'avg_consumption' => round($vehicle->avg_consumption, 2),
            'cost_per_km' => round($costPerKmTotal, 2),
            'cost_per_km_month' => round($costPerKmMonth, 2),
            'remaining_autonomy' => $vehicle->autonomy_km,
            'current_liters' => $vehicle->current_liters,
            'tank_capacity' => $vehicle->tank_capacity,
            'fuel_percentage' => $vehicle->fuel_percentage,
        ];
    }
}
