<?php

use App\Http\Controllers\Api\GuestController;
use App\Http\Controllers\Api\TableController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::resource('/guests', GuestController::class);
Route::resource('/tables', TableController::class);