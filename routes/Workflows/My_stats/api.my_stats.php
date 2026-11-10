<?php

use App\Domains\My_stats\Controllers\ApiMyStatController;
use Illuminate\Support\Facades\Route;


// ------------ MY STATS ------------
Route::prefix('my-stats')->middleware(['auth:sanctum'])->group(function () {

   Route::get('/index', [ApiMyStatController::class, 'index']);
   Route::get('/{myStat}/{slug?}/show', [ApiMyStatController::class, 'show']);

   Route::post('/store', [ApiMyStatController::class, 'store']);
   Route::delete('/{myStat}/delete', [ApiMyStatController::class, 'delete']);
});
// ------------ MY STATS ------------