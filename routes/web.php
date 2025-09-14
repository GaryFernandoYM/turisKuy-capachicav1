<?php

use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\UbicacionController;
use App\Http\Controllers\LugarController;

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use Illuminate\Http\Request;
use App\Models\Ubicacion;
use App\Http\Controllers\UbicacionRealtimeController;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VisitaController;
use App\Http\Controllers\PlacesController;
use App\Http\Controllers\TrackingController;
use App\Models\UbicacionRealtime;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;


Route::get('/', function () {
    return view('welcome');
})->name('home');



Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');





Route::prefix('api')->group(function () {
    Route::post('/guardar-ubicacion', [UbicacionController::class, 'store']);
    Route::get('/ubicaciones', [UbicacionController::class, 'api']);
});
Route::get('/usuarios', [UsuarioController::class, 'index'])->middleware(['auth', 'verified'])->name('usuarios.index');

Route::get('/ubicaciones', [UbicacionController::class, 'index'])->middleware(['auth', 'verified'])->name('ubicacions.index');

Route::get('/api/ubicaciones', [UbicacionController::class, 'api'])->middleware('auth');


Route::get('/reporte-hoy', [UbicacionController::class, 'reporteHoy'])->name('ubicacions.reporte.hoy');
Route::get('/reporte-mes', [UbicacionController::class, 'reporteMes'])->name('ubicacions.reporte.mes');

Route::post('/guardar-ubicacion', [UbicacionController::class, 'store']);

Route::get('/guardar-ubicacion', function () {
    return response()->json([
        'status' => 'error',
        'message' => 'Método GET no permitido. Usa POST.'
    ], 405); // Código HTTP 405: Method Not Allowed
});


Route::resource('lugares', LugarController::class);
Route::get('visitas', [VisitaController::class, 'visitasPorLugar'])->name('visitas.index');
Route::put('/lugares/{id}/estado', [LugarController::class, 'updateEstado'])->name('lugares.updateEstado');
Route::get('/lugares/{id}/edit', [LugarController::class, 'edit'])->name('lugares.edit');
Route::put('/lugares/{id}', [LugarController::class, 'update'])->name('lugares.update');
Route::get('/api/lugares', [TrackingController::class, 'verLugares']);


//Route::post('/ubicacion/guardar', [TrackingController::class, 'guardar']);
Route::post('/ubicacion/guardar', [TrackingController::class, 'guardar'])
    ->withoutMiddleware([VerifyCsrfToken::class]);



Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    // 2. Guardar ubicación (solo si el usuario está autenticado)

    // 3. Mostrar y alimentar el mapa de rastreo en tiempo real
    Route::get('/admin/tracking', [TrackingController::class, 'verUbicaciones'])
        ->name('admin.tracking');
    Route::get('/admin/tracking/json', [TrackingController::class, 'ubicacionesJson']);

    // 4. Vista alternativa tipo panel con layout admin


    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

Route::get('/usuarios', [UsuarioController::class, 'index'])->middleware(['auth', 'verified'])->name('usuarios.index');

// routes/web.php
#Route::get('/nominatim-reverse', [App\Http\Controllers\NominatimController::class, 'reverse']);
#Route::get('/nominatim-search', [App\Http\Controllers\NominatimController::class, 'search']);


Route::post('/registrar-ubicacion', function (Request $request) {
    Ubicacion::create([
        'latitud' => $request->latitud,
        'longitud' => $request->longitud,
    ]);
    return response()->json(['status' => 'ok']);
})->withoutMiddleware([
    Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class
]);


Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');


require __DIR__ . '/auth.php';
