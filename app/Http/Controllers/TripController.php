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

        $input = $request->all();
        foreach (['distance_km', 'liters_consumed', 'lat', 'lng'] as $field) {
            if (isset($input[$field]) && is_string($input[$field])) {
                $input[$field] = str_replace(',', '.', $input[$field]);
            }
        }
        $request->replace($input);

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

    public function telemetry(Request $request, TripService $tripService): JsonResponse
    {
        $vehicle = Vehicle::firstOrCreate(['id' => 1]);
        $trip = Trip::where('vehicle_id', $vehicle->id)
            ->where('status', 'active')
            ->latest()
            ->first();

        if (! $trip) {
            return response()->json(['success' => false, 'message' => 'No hay recorrido activo.'], 404);
        }

        $distanceKm = (float) str_replace(',', '.', (string) $request->input('distance_km', 0));
        $litersConsumed = (float) str_replace(',', '.', (string) $request->input('liters_consumed', 0));
        $lat = $request->input('lat') !== null ? (float) $request->input('lat') : null;
        $lng = $request->input('lng') !== null ? (float) $request->input('lng') : null;

        $tripService->updateLiveTelemetry($trip, $distanceKm, $litersConsumed, $lat, $lng);

        return response()->json(['success' => true, 'trip' => $trip->fresh()]);
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

        $input = $request->all();
        if (isset($input['distance_km']) && is_string($input['distance_km'])) {
            $input['distance_km'] = str_replace(',', '.', $input['distance_km']);
        }
        $request->replace($input);

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
