<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', [AuthController::class, 'home'])->name('home');
Route::post('/registrar', [AuthController::class, 'registrar'])->name('usuario.registrar');
Route::post('/login', [AuthController::class, 'login'])->name('usuario.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', [AuthController::class, 'dashboard'])
    ->middleware('auth')
    ->name('dashboard');