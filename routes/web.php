<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FuelLoadController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Vehículo
Route::get('/vehiculo', [VehicleController::class, 'edit'])->name('vehicle.edit');
Route::put('/vehiculo', [VehicleController::class, 'update'])->name('vehicle.update');

// Carga de Gasolina
Route::get('/gasolina/agregar', [FuelLoadController::class, 'create'])->name('fuel_loads.create');
Route::post('/gasolina', [FuelLoadController::class, 'store'])->name('fuel_loads.store');

// Recorridos
Route::get('/recorrido', [TripController::class, 'track'])->name('trips.track');
Route::post('/recorrido/iniciar', [TripController::class, 'start'])->name('trips.start');
Route::post('/recorrido/finalizar/{trip?}', [TripController::class, 'finish'])->name('trips.finish');
Route::post('/recorrido/manual', [TripController::class, 'storeManual'])->name('trips.manual');
Route::delete('/recorrido/{trip}', [TripController::class, 'destroy'])->name('trips.destroy');

// Historial y Estadísticas
Route::get('/historial', [HistoryController::class, 'index'])->name('history.index');
Route::get('/estadisticas', [StatsController::class, 'index'])->name('stats.index');

