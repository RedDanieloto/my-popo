<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Services\FuelService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FuelLoadController extends Controller
{   
    //ejemplo para n8n
    public function gasActual()
    {
        $vehicle = Vehicle::where('id', 1)->first();

        return response()->json([
            'gas_actual' => $vehicle->current_liters
        ]);
    }








    
    public function create(): View
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

        return view('fuel_loads.create', compact('vehicle'));
    }

    public function store(Request $request, FuelService $fuelService): RedirectResponse
    {
        $vehicle = Vehicle::firstOrCreate(
            ['id' => 1],
            [
                'name' => 'Mi Pointer 2005',
                'brand' => 'Volkswagen',
                'model' => 'Pointer',
                'year' => 2005,
                'tank_capacity' => 50.00,
                'current_liters' => 50.00,
                'avg_consumption' => 10.50,
                'initial_avg_consumption' => 10.50,
            ]
        );

        $validated = $request->validate([
            'amount_paid' => 'required|numeric|min:0.01',
            'price_per_liter' => 'required|numeric|min:0.01',
            'is_full_tank' => 'nullable|boolean',
            'date' => 'required|date',
            'note' => 'nullable|string|max:1000',
        ]);

        $fuelService->recordFuelLoad($vehicle, $validated);

        return redirect()->route('dashboard')->with('success', 'Gasolina agregada correctamente.');
    }
}
