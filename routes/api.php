<?php

use App\Http\Controllers\Api\GuestController;
use App\Http\Controllers\Api\TableController;
use App\Http\Controllers\Api\ImageUploadController; // Importar el controlador
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Ruta para obtener información del usuario autenticado
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rutas de recursos para huéspedes y mesas
Route::resource('/guests', GuestController::class);
Route::resource('/tables', TableController::class);

// Ruta para subir imágenes
Route::post('/upload', [ImageUploadController::class, 'store']);
Route::get('/upload', [ImageUploadController::class, 'index']);
