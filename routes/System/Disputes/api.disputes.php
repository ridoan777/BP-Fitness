<?php

use App\Http\Controllers\System\Analytics\WebAnalyticsController;
use Illuminate\Support\Facades\Route;

// ----------------- ANALYTICS -----------------
Route::prefix('donuts')->middleware(['auth:web'])->group(function () {

   // Route::get('/users/roles', [WebAnalyticsController::class, 'userDonutChart']);

});
// ----------------- ANALYTICS -----------------

