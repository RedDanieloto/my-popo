<?php

namespace App\Http\Controllers;

use App\Models\FuelLoad;
use App\Models\Trip;
use App\Models\Vehicle;
use Illuminate\View\View;

class HistoryController extends Controller
{
    public function index(): View
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

        $fuelLoads = FuelLoad::where('vehicle_id', $vehicle->id)
            ->orderBy('date', 'desc')
            ->get();

        $trips = Trip::where('vehicle_id', $vehicle->id)
            ->where('status', 'completed')
            ->orderBy('start_time', 'desc')
            ->get();

        // Crear una lista unificada de eventos ordenados por fecha
        $unifiedHistory = collect();

        foreach ($fuelLoads as $load) {
            $unifiedHistory->push([
                'type' => 'fuel_load',
                'date' => $load->date,
                'title' => 'Recarga de Gasolina',
                'details' => [
                    'amount_paid' => $load->amount_paid,
                    'price_per_liter' => $load->price_per_liter,
                    'liters' => $load->liters,
                    'is_full_tank' => $load->is_full_tank,
                    'note' => $load->note,
                ],
            ]);
        }

        foreach ($trips as $trip) {
            $unifiedHistory->push([
                'type' => 'trip',
                'date' => $trip->start_time,
                'title' => $trip->title ?: 'Recorrido en automóvil',
                'details' => [
                    'distance_km' => $trip->distance_km,
                    'duration' => $trip->duration_formatted,
                    'liters_consumed' => $trip->liters_consumed,
                    'is_manual' => $trip->is_manual,
                ],
            ]);
        }

        $unifiedHistory = $unifiedHistory->sortByDesc('date')->values();

        return view('history.index', compact('vehicle', 'fuelLoads', 'trips', 'unifiedHistory'));
    }
}
