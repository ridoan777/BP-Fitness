<?php

use App\Http\Controllers\Workflows\Meals\ApiMealDisplayController;
use App\Http\Controllers\Workflows\Meals\ApiMealGenerateController;
use Illuminate\Support\Facades\Route;

// ------------ AGENTIC ------------
Route::prefix('meals')->middleware(['auth:sanctum'])->group(function () {

   Route::post('/preference/store', [ApiMealGenerateController::class, 'preference']);
   Route::post('/generate/store', [ApiMealGenerateController::class, 'generate']);
});
// ------------ AGENTIC ------------

// ------------ MEALS ------------
Route::prefix('meals')->middleware(['auth:sanctum'])->group(function () {

   Route::get('/all-meals/index', [ApiMealDisplayController::class, 'indexAll']);
   Route::get('/{preference}/index', [ApiMealDisplayController::class, 'indexSingleMeal']);
   Route::get('/{preference}/{day}/show', [ApiMealDisplayController::class, 'showSingleDay'])->whereNumber('day');
   Route::get('/{meal}/show', [ApiMealDisplayController::class, 'showSingleMeal']);

   Route::get('/{preference}/status', [ApiMealGenerateController::class, 'status']);
});
// ------------ MEALS ------------

// ------------ PREFERENCES ------------
Route::prefix('preferences')->middleware(['auth:sanctum'])->group(function () {

   Route::get('/index', [ApiMealDisplayController::class, 'indexPreferences']);
   Route::get('/{Preference}/show', [ApiMealDisplayController::class, 'showPreference']);
});
// ------------ PREFERENCES ------------

// ------------ OPERATIONS ------------
Route::prefix('meals')->middleware(['auth:sanctum'])->group(function () {
   
   Route::post('/single-meal/swap', [ApiMealGenerateController::class, 'swap']);
   
   Route::delete('/{preference}/delete', [ApiMealGenerateController::class, 'deleteMealPlan']);
});

Route::prefix('preferences')->middleware(['auth:sanctum'])->group(function () {
   
   Route::delete('/{preference}/delete', [ApiMealGenerateController::class, 'deletePreference']);
});
// ------------ OPERATIONS ------------

// ------------ CALORIE STATUS ------------
Route::prefix('meals')->middleware(['auth:sanctum'])->group(function () {

   Route::get('/{preference}/calorie-status/show', [ApiMealDisplayController::class, 'calorieTracker']);
});
// ------------ CALORIE STATUS ------------

/*
   Must run: php artisan queue:work
*/