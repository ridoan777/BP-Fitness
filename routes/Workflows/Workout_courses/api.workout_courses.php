<?php

use App\Domains\Workout_courses\Controllers\Categories\ApiWorkoutCategoryController;
use App\Domains\Workout_courses\Controllers\Courses\ApiWorkoutCourseController;
use App\Domains\Workout_courses\Controllers\Lessons\ApiWorkoutLessonController;
use Illuminate\Support\Facades\Route;


// ------------ HOMEPAGE ------------

Route::prefix('workout/')->middleware(['auth:sanctum'])->group(function () {

   Route::get('/home', [ApiWorkoutCategoryController::class, 'home']);
});
// ------------ HOMEPAGE ------------

// ------------ WORKOUT CATEGORIES ------------

Route::prefix('workout/')->middleware(['auth:sanctum'])->group(function () {

   Route::get('/categories/index', [ApiWorkoutCategoryController::class, 'index']);
   Route::get('/categories/{category}/show', [ApiWorkoutCategoryController::class, 'show']);
});
// ------------ WORKOUT CATEGORIES ------------


// ------------ WORKOUT COURSES ------------

Route::prefix('workout/')->middleware(['auth:sanctum'])->group(function () {

   Route::get('/course/{course}/show', [ApiWorkoutCourseController::class, 'show']);
});
// ------------ WORKOUT COURSES ------------


// ------------ WORKOUT LESSONS ------------

Route::prefix('workout/')->middleware(['auth:sanctum'])->group(function () {

   Route::get('/course/lessons/{lesson}/show', [ApiWorkoutLessonController::class, 'show']);
});
// ------------ WORKOUT LESSONS ------------