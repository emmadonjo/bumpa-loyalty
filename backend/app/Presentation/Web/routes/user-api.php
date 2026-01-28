<?php

use App\Presentation\Web\Controllers\UserAchievementController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function (){
    Route::get('/{id}/achievements', [UserAchievementController::class, 'userAchievements'])->name('users.achievements.index');
});
