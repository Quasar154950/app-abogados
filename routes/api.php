<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MobileExpedienteController;
use Illuminate\Support\Facades\Route;

Route::prefix('mobile')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | RUTAS PÚBLICAS
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/login',
        [AuthController::class, 'login']
    );

    /*
    |--------------------------------------------------------------------------
    | RUTAS PROTEGIDAS
    |--------------------------------------------------------------------------
    */

    Route::middleware('auth:sanctum')->group(function () {

        Route::get(
            '/me',
            [AuthController::class, 'me']
        );

        Route::get(
            '/expedientes',
            [MobileExpedienteController::class, 'index']
        );

        Route::post(
            '/logout',
            [AuthController::class, 'logout']
        );
    });
});