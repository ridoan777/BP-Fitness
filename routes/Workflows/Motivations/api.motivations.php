<?php

use App\Domains\Motivations\Controllers\Categories\ApiMotivationController;
use Illuminate\Support\Facades\Route;

// ------------ MOTIVATION ------------
Route::prefix('motivation')->middleware(['auth:sanctum'])->group(function () {

   Route::get('/random/show', [ApiMotivationController::class, 'show']);
});
// ------------ MOTIVATION ------------