<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ExpedienteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotaController;
use App\Http\Controllers\SeguimientoController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ActividadController;

// 🧪 TEST SIMPLE
Route::get('/zzz-test', function () {
    return 'ZZZ TEST OK';
});

// 🧪 CREAR CLIENTE DE PRUEBA EN RAILWAY
Route::get('/crear-cliente-prueba', function () {
    $user = \App\Models\User::where('email', 'cliente1@prueba.com')->first();

    if ($user) {
        return 'EL USUARIO YA EXISTE';
    }

    \App\Models\User::create([
        'name' => 'Ismael Cliente',
        'email' => 'cliente1@prueba.com',
        'password' => bcrypt('12345678'),
        'role' => 'cliente',
        'email_verified_at' => now(),
    ]);

    return 'USUARIO CLIENTE CREADO OK';
});

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Buscador global
    Route::get('search', [SearchController::class, 'index'])->name('global.search');

    // Clientes
    Route::get('clientes/archivados', [ClienteController::class, 'archivados'])->name('clientes.archivados');
    Route::resource('clientes', ClienteController::class);

    // Archivo de clientes
    Route::patch('clientes/{cliente}/archivar', [ClienteController::class, 'archivar'])->name('clientes.archivar');
    Route::patch('clientes/{cliente}/desarchivar', [ClienteController::class, 'desarchivar'])->name('clientes.desarchivar');

    // Expedientes
    Route::post('clientes/{cliente}/expedientes', [ExpedienteController::class, 'store'])->name('expedientes.store');
    Route::get('expedientes/{expediente}', [ExpedienteController::class, 'show'])->name('expedientes.show');
    Route::get('expedientes/{expediente}/edit', [ExpedienteController::class, 'edit'])->name('expedientes.edit');
    Route::put('expedientes/{expediente}', [ExpedienteController::class, 'update'])->name('expedientes.update');
    Route::delete('expedientes/{expediente}', [ExpedienteController::class, 'destroy'])->name('expedientes.destroy');

    // Notas
    Route::post('notas', [NotaController::class, 'store'])->name('notas.store');
    Route::get('notas/{nota}/edit', [NotaController::class, 'edit'])->name('notas.edit');
    Route::put('notas/{nota}', [NotaController::class, 'update'])->name('notas.update');
    Route::delete('notas/{id}', [NotaController::class, 'destroy'])->name('notas.destroy');

    // Pin de notas
    Route::patch('notas/{nota}/toggle-pin', [NotaController::class, 'togglePin'])->name('notas.togglePin');

    // Seguimientos
    Route::get('seguimientos', [SeguimientoController::class, 'index'])->name('seguimientos.index');
    Route::post('seguimientos', [SeguimientoController::class, 'store'])->name('seguimientos.store');
    Route::get('seguimientos/{seguimiento}/edit', [SeguimientoController::class, 'edit'])->name('seguimientos.edit');
    Route::put('seguimientos/{seguimiento}', [SeguimientoController::class, 'update'])->name('seguimientos.update');
    Route::delete('seguimientos/{seguimiento}', [SeguimientoController::class, 'destroy'])->name('seguimientos.destroy');

    // Cambio rápido de estado
    Route::patch('seguimientos/{seguimiento}/estado', [SeguimientoController::class, 'cambiarEstado'])->name('seguimientos.cambiarEstado');

    // Historial de actividad
    Route::get('historial', [ActividadController::class, 'index'])->name('actividades.index');
    Route::delete('historial/vaciar', [ActividadController::class, 'vaciar'])->name('actividades.vaciar');
});

// 🔵 PANEL CLIENTE
Route::middleware(['auth'])->get('/cliente/dashboard', function () {
    if (auth()->user()?->role !== 'cliente') {
        abort(403);
    }

    return '
        <div style="font-family: Arial, sans-serif; padding: 30px;">
            <h1>Panel Cliente OK - ' . auth()->user()->name . '</h1>

            <form method="POST" action="/logout" style="margin-top: 20px;">
                <input type="hidden" name="_token" value="' . csrf_token() . '">
                <button type="submit" style="padding: 10px 16px; background: #dc2626; color: white; border: none; border-radius: 8px; cursor: pointer;">
                    Cerrar sesión
                </button>
            </form>
        </div>
    ';
});

// 🧪 TEST LOGIN DIRECTO ISMAEL
Route::get('/test-ismael-login', function () {
    $user = \App\Models\User::where('email', 'cliente1@prueba.com')->first();

    if (! $user) {
        return 'NO EXISTE cliente1@prueba.com EN LA BASE DE DATOS DE RAILWAY';
    }

    Auth::login($user);
    request()->session()->regenerate();

    return redirect('/cliente/dashboard');
});

require __DIR__ . '/settings.php';