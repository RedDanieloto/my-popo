<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Services\StatsService;
use Illuminate\View\View;

class StatsController extends Controller
{
    public function index(StatsService $statsService): View
    {
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

        return view('stats.index', compact('vehicle', 'stats'));
    }
}
