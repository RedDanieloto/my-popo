<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FuelLoadController;

Route::get('/gas-actual', [FuelLoadController::class, 'gasActual']);
