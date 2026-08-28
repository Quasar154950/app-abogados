<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MobileExpedienteController;
use App\Http\Controllers\Api\MobileHomeController;
use App\Http\Controllers\Api\MobileMensajeController;
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
            '/home',
            [MobileHomeController::class, 'index']
        );

        Route::get(
            '/expedientes',
            [MobileExpedienteController::class, 'index']
        );

        Route::get(
            '/mensajes',
            [MobileMensajeController::class, 'index']
        );

        Route::post(
            '/mensajes',
            [MobileMensajeController::class, 'store']
        );

        Route::post(
            '/logout',
            [AuthController::class, 'logout']
        );
    });
});