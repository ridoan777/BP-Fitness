<?php

use App\Domains\Achievements\Controllers\ApiAchievementController;
use Illuminate\Support\Facades\Route;


// ------------ ACHIEVEMENTS ------------
Route::prefix('achievements')->middleware(['auth:sanctum'])->group(function () {

   Route::get('/all-challenges/index', [ApiAchievementController::class, 'challenges']);

   Route::get('/login-streaks/view', [ApiAchievementController::class, 'loginStreaks']);
   Route::get('/my-achievements/view', [ApiAchievementController::class, 'view']);
});
// ------------ ACHIEVEMENTS ------------