<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\AdoptionRequestController;

Route::get('/', [AuthController::class, 'home'])->name('home');
Route::post('/registrar', [AuthController::class, 'registrar'])->name('usuario.registrar');
Route::post('/login', [AuthController::class, 'login'])->name('usuario.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', [AuthController::class, 'dashboard'])
    ->middleware('auth')
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/mascotas/json', [PetController::class, 'index'])->name('mascotas.json');
    Route::get('/mascota/{id}', [PetController::class, 'show'])->name('mascotas.show');
    Route::get('/mascota/{id}/data', [PetController::class, 'data'])->name('mascotas.data');
    Route::post('/mascotas', [PetController::class, 'store'])->name('mascotas.store');
    Route::post('/adoptar/{pet}', [AdoptionRequestController::class, 'store'])->name('adoptar.store');
    Route::get('/solicitudes/mias', [AdoptionRequestController::class, 'mine'])->name('solicitudes.mias');
    Route::get('/perfil', [AuthController::class, 'perfil'])->name('perfil');
    Route::post('/perfil/actualizar', [AuthController::class, 'perfilActualizar'])->name('perfil.actualizar');
});