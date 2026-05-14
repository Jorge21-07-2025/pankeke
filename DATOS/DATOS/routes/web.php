<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\AdoptionRequestController;
use App\Http\Controllers\MessageController;

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
    Route::put('/mascotas/{id}', [PetController::class, 'update'])->name('mascotas.update');
    Route::delete('/mascotas/{id}', [PetController::class, 'destroy'])->name('mascotas.destroy');
    Route::patch('/solicitudes/{id}', [AdoptionRequestController::class, 'update'])->name('solicitudes.update');
    Route::get('/favoritos', [App\Http\Controllers\FavoriteController::class, 'index'])->name('favoritos.index');
    Route::post('/favoritos/{pet}', [App\Http\Controllers\FavoriteController::class, 'toggle'])->name('favoritos.toggle');
    Route::post('/mensajes/{pet}', [MessageController::class, 'send'])->name('mensajes.send');
    Route::get('/mensajes', [MessageController::class, 'conversations'])->name('mensajes.conversations');
    Route::get('/mensajes/chat', [MessageController::class, 'messages'])->name('mensajes.messages');
    Route::get('/mensajes/no-leidos', [MessageController::class, 'unread'])->name('mensajes.unread');
});