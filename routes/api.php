<?php

use App\Http\Controllers\Api\CapturaPlacaController;
use App\Http\Controllers\Api\SincronizacionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/v1/sincronizar', [SincronizacionController::class, 'recibirDatos']);

Route::post('/v1/capturas-placa', [CapturaPlacaController::class, 'registrarCaptura']);