<?php

use App\Presentation\Web\Controllers\AuthenticationController;
use App\Presentation\Web\Controllers\PurchaseController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthenticationController::class, 'login'])
    ->middleware('guest')->name('login');
Route::post('/logout', [AuthenticationController::class, 'logout'])
    ->middleware('auth:sanctum')->name('logout');

Route::prefix('/payments')
    ->controller(PurchaseController::class)
    ->group(function () {
    Route::post('/{reference}/verify', 'verify')->name('payments.verify');
    Route::post('/{reference}/cancel', 'cancel')->name('payments.cancel');
});
