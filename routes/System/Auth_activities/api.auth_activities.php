<?php

use App\Http\Controllers\Users\UserAuthActivityController;
use Illuminate\Support\Facades\Route;

// ----------------- ACTIVITIES -----------------
Route::prefix('activity')->middleware(['auth:sanctum'])->group(function () {

   Route::post('/users/tracking', [UserAuthActivityController::class, 'store']);

});
// ----------------- ACTIVITIES -----------------

