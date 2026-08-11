<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Vehicle;
use App\Services\TripService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TripController extends Controller
{
    public function track(): View
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
        $activeTrip = Trip::where('vehicle_id', $vehicle->id)
            ->where('status', 'active')
            ->latest()
            ->first();

        return view('trips.track', compact('vehicle', 'activeTrip'));
    }

    public function start(Request $request, TripService $tripService): JsonResponse|RedirectResponse
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

        $lat = $request->input('lat') ? (float) $request->input('lat') : null;
        $lng = $request->input('lng') ? (float) $request->input('lng') : null;

        $trip = $tripService->startLiveTrip($vehicle, $lat, $lng);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'trip' => $trip,
                'message' => 'Recorrido iniciado correctamente.',
            ]);
        }

        return redirect()->route('trips.track')->with('success', 'Recorrido iniciado.');
    }

    public function finish(Request $request, ?Trip $trip = null, TripService $tripService): JsonResponse|RedirectResponse
    {
        if (! $trip || ! $trip->exists) {
            $vehicle = Vehicle::firstOrCreate(['id' => 1]);
            $trip = Trip::where('vehicle_id', $vehicle->id)
                ->where('status', 'active')
                ->latest()
                ->first();
        }

        if (! $trip) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay un recorrido activo para finalizar en el sistema.',
                ], 404);
            }

            return redirect()->route('dashboard')->with('error', 'No hay un recorrido activo para finalizar.');
        }

        $validated = $request->validate([
            'distance_km' => 'required|numeric|min:0',
            'liters_consumed' => 'nullable|numeric|min:0',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
        ]);

        $endLat = isset($validated['lat']) ? (float) $validated['lat'] : null;
        $endLng = isset($validated['lng']) ? (float) $validated['lng'] : null;
        $exactLiters = isset($validated['liters_consumed']) ? (float) $validated['liters_consumed'] : null;

        $tripService->finishLiveTrip($trip, (float) $validated['distance_km'], $endLat, $endLng, $exactLiters);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'trip' => $trip->fresh(),
                'vehicle' => $trip->vehicle->fresh(),
                'message' => 'Recorrido finalizado correctamente.',
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Recorrido finalizado. Se han descontado los litros consumidos.');
    }

    public function storeManual(Request $request, TripService $tripService): RedirectResponse
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

        $validated = $request->validate([
            'distance_km' => 'required|numeric|min:0.01',
            'title' => 'nullable|string|max:255',
            'date' => 'nullable|date',
        ]);

        $tripService->recordManualTrip(
            $vehicle,
            (float) $validated['distance_km'],
            $validated['title'] ?? 'Recorrido manual',
            $validated['date'] ?? null
        );

        return redirect()->route('dashboard')->with('success', 'Recorrido manual registrado correctamente.');
    }

    public function destroy(Trip $trip, TripService $tripService): RedirectResponse
    {
        $liters = $trip->liters_consumed;
        $tripService->deleteTrip($trip);

        return redirect()->route('history.index')
            ->with('success', "Recorrido cancelado correctamente. Se han devuelto {$liters} L de gasolina al tanque.");
    }
}
