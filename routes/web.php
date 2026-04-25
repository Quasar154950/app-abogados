<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ExpedienteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotaController;
use App\Http\Controllers\SeguimientoController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ActividadController;

Route::redirect('/', '/login')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::middleware('role:abogado')->group(function () {

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('search', [SearchController::class, 'index'])->name('global.search');

        Route::get('clientes/archivados', [ClienteController::class, 'archivados'])->name('clientes.archivados');
        Route::resource('clientes', ClienteController::class);

        Route::post('clientes/{cliente}/asignar-usuario', [ClienteController::class, 'asignarUsuario'])->name('clientes.asignarUsuario');
        Route::post('clientes/{cliente}/crear-acceso', [ClienteController::class, 'crearAcceso'])->name('clientes.crearAcceso');
        Route::post('clientes/{cliente}/reset-password', [ClienteController::class, 'resetPassword'])->name('clientes.resetPassword');
        Route::delete('clientes/{cliente}/quitar-acceso', [ClienteController::class, 'quitarAcceso'])->name('clientes.quitarAcceso');

        Route::patch('clientes/{cliente}/archivar', [ClienteController::class, 'archivar'])->name('clientes.archivar');
        Route::patch('clientes/{cliente}/desarchivar', [ClienteController::class, 'desarchivar'])->name('clientes.desarchivar');

        Route::post('clientes/{cliente}/expedientes', [ExpedienteController::class, 'store'])->name('expedientes.store');
        Route::get('expedientes/{expediente}', [ExpedienteController::class, 'show'])->name('expedientes.show');
        Route::get('expedientes/{expediente}/imprimir', [ExpedienteController::class, 'imprimir'])->name('expedientes.imprimir');
        Route::get('expedientes/{expediente}/edit', [ExpedienteController::class, 'edit'])->name('expedientes.edit');
        Route::put('expedientes/{expediente}', [ExpedienteController::class, 'update'])->name('expedientes.update');
        Route::delete('expedientes/{expediente}', [ExpedienteController::class, 'destroy'])->name('expedientes.destroy');

        Route::post('notas', [NotaController::class, 'store'])->name('notas.store');
        Route::get('notas/{nota}/edit', [NotaController::class, 'edit'])->name('notas.edit');
        Route::put('notas/{nota}', [NotaController::class, 'update'])->name('notas.update');
        Route::delete('notas/{id}', [NotaController::class, 'destroy'])->name('notas.destroy');
        Route::patch('notas/{nota}/toggle-pin', [NotaController::class, 'togglePin'])->name('notas.togglePin');

        Route::get('seguimientos', [SeguimientoController::class, 'index'])->name('seguimientos.index');
        Route::post('seguimientos', [SeguimientoController::class, 'store'])->name('seguimientos.store');
        Route::get('seguimientos/{seguimiento}/edit', [SeguimientoController::class, 'edit'])->name('seguimientos.edit');
        Route::put('seguimientos/{seguimiento}', [SeguimientoController::class, 'update'])->name('seguimientos.update');
        Route::delete('seguimientos/{seguimiento}', [SeguimientoController::class, 'destroy'])->name('seguimientos.destroy');
        Route::patch('seguimientos/{seguimiento}/estado', [SeguimientoController::class, 'cambiarEstado'])->name('seguimientos.cambiarEstado');

        Route::get('historial', [ActividadController::class, 'index'])->name('actividades.index');
        Route::delete('historial/vaciar', [ActividadController::class, 'vaciar'])->name('actividades.vaciar');
    });
});

// Panel cliente
Route::middleware(['auth', 'role:cliente'])->get('/cliente/dashboard', function () {
    return view('cliente.dashboard');
})->name('cliente.dashboard');

Route::middleware(['auth', 'role:cliente'])->get('/cliente/expedientes/{expediente}/imprimir', [ExpedienteController::class, 'imprimir'])
    ->name('cliente.expedientes.imprimir');

require __DIR__ . '/settings.php';