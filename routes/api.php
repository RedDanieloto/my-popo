<?php

use App\Http\Controllers\Api\TripApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// Ejemplo n8n
Route::get('/info', [TripApiController::class, 'infoCarro']);

// Rutas directas para Atajos
Route::match(['get', 'post'], '/recorrido/iniciar', [TripApiController::class, 'iniciar']);
Route::match(['get', 'post'], '/recorrido/finalizar', [TripApiController::class, 'finalizar']);
Route::match(['get', 'post'], '/recorrido/toggle', [TripApiController::class, 'toggle']);
Route::match(['get', 'post', 'delete'], '/recorrido/cancelar/{trip?}', [TripApiController::class, 'cancelar']);
Route::get('/recorrido/estado', [TripApiController::class, 'estado']);