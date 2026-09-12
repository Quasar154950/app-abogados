<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MobileExpedienteController;
use App\Http\Controllers\Api\MobileHomeController;
use App\Http\Controllers\Api\MobileMensajeController;
use App\Http\Controllers\Api\MobileDocumentoController;
use App\Http\Controllers\Api\SoporteApiController;
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

        Route::delete(
            '/mensajes',
            [MobileMensajeController::class, 'clear']
        );

        Route::get(
            '/documentos',
            [MobileDocumentoController::class, 'index']
        );

        Route::post(
            '/documentos',
            [MobileDocumentoController::class, 'store']
        );

        Route::delete(
            '/documentos/{documentoId}',
            [MobileDocumentoController::class, 'destroy']
        );

        Route::post(
            '/documentos/{documentoId}/abrir',
            [MobileDocumentoController::class, 'marcarComoAbierto']
        );

        Route::post(
            '/logout',
            [AuthController::class, 'logout']
        );
    });
});


// 🛠 API SOPORTE MCTANDIL
Route::prefix('soporte')
    ->middleware('mctandil.support')
    ->group(function () {

        Route::get(
            '/estudios',
            [SoporteApiController::class, 'estudios']
        );

        Route::post(
            '/estudios/{estudio}/renovar',
            [SoporteApiController::class, 'renovar']
        );

    });