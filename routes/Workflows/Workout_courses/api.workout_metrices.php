<?php

use App\Domains\Workout_courses\Controllers\Matrices\ApiWorkoutMatrixController;
use Illuminate\Support\Facades\Route;


// ------------ MATRICES ------------

Route::prefix('workout')->middleware(['auth:sanctum'])->group(function () {

   Route::get('/matrix/lesson/{lesson}/index', [ApiWorkoutMatrixController::class, 'index']);

});
// ------------ MATRICES ------------

// ------------ WORKOUT REACTIONS ------------

Route::prefix('workout')->middleware(['auth:sanctum'])->group(function () {

   Route::post('/matrix/lesson/reaction/store', [ApiWorkoutMatrixController::class, 'like']);

});
// ------------ WORKOUT REACTIONS ------------


// ------------ WORKOUT COMMENTS ------------

Route::prefix('workout/')->middleware(['auth:sanctum'])->group(function () {

   Route::post('/matrix/lesson/comment/store', [ApiWorkoutMatrixController::class, 'comment']);
   
   Route::delete('/matrix/lesson/comment/{comment}/delete', [ApiWorkoutMatrixController::class, 'deleteComment']);

});
// ------------ WORKOUT COMMENTS ------------


// ------------ WORKOUT COMPLETIONS ------------

Route::prefix('workout/')->middleware(['auth:sanctum'])->group(function () {

   Route::post('/matrix/lesson/completion/store', [ApiWorkoutMatrixController::class, 'completion']);

});
// ------------ WORKOUT COMPLETIONS ------------
