<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleController extends Controller
{
    public function edit(): View
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

        return view('vehicle.form', compact('vehicle'));
    }

    public function update(Request $request): RedirectResponse
    {
        $vehicle = Vehicle::firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:2100',
            'tank_capacity' => 'required|numeric|min:1|max:500',
            'current_liters' => 'required|numeric|min:0|max:500',
            'avg_consumption' => 'required|numeric|min:0.1|max:100',
        ]);

        // Evitar que los litros actuales excedan la capacidad del tanque
        $validated['current_liters'] = min($validated['current_liters'], $validated['tank_capacity']);

        $vehicle->update($validated);

        return redirect()->route('dashboard')->with('success', 'Datos del vehículo actualizados correctamente.');
    }
}
