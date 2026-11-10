<?php

use App\Http\Controllers\Workflows\Journeys\ApiJourneyController;
use Illuminate\Support\Facades\Route;


Route::prefix('journeys')->middleware(['auth:sanctum'])->group(function () {

    Route::get('/index', [ApiJourneyController::class, 'index']);
    Route::get('/{journey}/show', [ApiJourneyController::class, 'show']);

    Route::post('/store', [ApiJourneyController::class, 'store']);
    
    Route::delete('/{journey}/delete', [ApiJourneyController::class, 'delete']);
});
