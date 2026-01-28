<?php

use App\Presentation\Web\Controllers\UserAchievementController;
use App\Presentation\Web\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function (){
    Route::prefix('/users')->group(function (){
        Route::get('/', [UserController::class, 'index'])->name('users.index');

        Route::get('/achievements', [UserAchievementController::class, 'index'])->name('userAchievements.index');
    });
});
