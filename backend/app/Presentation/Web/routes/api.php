<?php

use App\Presentation\Web\Controllers\AuthenticationController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthenticationController::class, 'login'])
    ->middleware('guest')->name('login');
Route::post('/logout', [AuthenticationController::class, 'logout'])
    ->middleware('auth:sanctum')->name('logout');
