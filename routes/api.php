<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetrolPumpController;

Route::get('/petrol-pumps', [PetrolPumpController::class, 'nearby'])
    ->middleware('throttle:60,1');