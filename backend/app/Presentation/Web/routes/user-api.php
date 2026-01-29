<?php

use App\Presentation\Web\Controllers\UserAchievementController;
use App\Presentation\Web\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function (){
    Route::get('/{id}/achievements', [UserAchievementController::class, 'userAchievements'])->name('users.achievements.index');
    Route::get('/{id}', [UserController::class, 'show'])->name('users.show');
});
