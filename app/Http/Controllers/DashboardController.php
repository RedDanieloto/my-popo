<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Vehicle;
use App\Services\StatsService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(StatsService $statsService): View
    {
        // Buscar o crear el vehículo por defecto (Mi Pointer 2005)
        $vehicle = Vehicle::firstOrCreate(
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

        $stats = $statsService->getVehicleStats($vehicle);

        // Comprobar si hay un recorrido en curso
        $activeTrip = Trip::where('vehicle_id', $vehicle->id)
            ->where('status', 'active')
            ->latest()
            ->first();

        return view('dashboard', compact('vehicle', 'stats', 'activeTrip'));
    }
}
