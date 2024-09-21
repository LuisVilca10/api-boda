<?php

use App\Http\Controllers\Api\GuestController;
use App\Http\Controllers\Api\TableController;
use App\Http\Controllers\Api\ImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Ruta para obtener información del usuario autenticado
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rutas de recursos para huéspedes y mesas
Route::resource('/guests', GuestController::class);
Route::resource('/tables', TableController::class);
//ruta a imagenes
Route::resource('/images', ImageController::class);
