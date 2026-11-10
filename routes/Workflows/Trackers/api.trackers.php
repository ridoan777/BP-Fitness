<?php

use App\Http\Controllers\Workflows\Trackers\ApiTrackerController;
use Illuminate\Support\Facades\Route;


// ------------ TRACKERS ------------
Route::prefix('trackers')->middleware(['auth:sanctum'])->group(function () {

   Route::post('/store', [ApiTrackerController::class, 'store']);
});
// ------------ TRACKERS ------------
/*
   -  meal
   -  workout
   -  workout: like, comment
*/