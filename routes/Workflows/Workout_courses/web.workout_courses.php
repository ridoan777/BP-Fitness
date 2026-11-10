<?php

use App\Domains\Workout_courses\Controllers\Categories\WebWorkoutCategoryController;
use App\Domains\Workout_courses\Controllers\Courses\WebWorkoutCourseController;
use App\Domains\Workout_courses\Controllers\Lessons\WebWorkoutLessonController;
use Illuminate\Support\Facades\Route;


// ------------ WORKOUT CATEGORIES ------------

Route::prefix('admin')->middleware(['auth:web', 'verify_role_key:admin,super_admin'])->group(function () {
   Route::get('/workouts/category/index', [WebWorkoutCategoryController::class, 'index'])->name('backend_workout_category_index');

   Route::get('/workouts/category/{singleCategory}/{slug}/show', [WebWorkoutCategoryController::class, 'show'])->name('backend_workout_category_show');

   Route::post('/workouts/category/store', [WebWorkoutCategoryController::class, 'store'])->name('backend_workout_category_store');

   Route::post('/workouts/category/{singleCategory}/toggle', [WebWorkoutCategoryController::class, 'toggle'])->name('backend_workout_category_toggle');

   Route::delete('/workouts/category/{singleCategory}/delete', [WebWorkoutCategoryController::class, 'delete'])->name('backend_workout_category_delete');

   Route::post('/workouts/category/datatable', [WebWorkoutCategoryController::class, 'datatable'])->name('backend_workout_category_datatable');
});
// ------------ WORKOUT CATEGORIES ------------


// ------------ WORKOUT COURSES ------------

Route::prefix('admin')->middleware(['auth:web', 'verify_role_key:admin,super_admin'])->group(function () {
   Route::get('/workouts/course/index', [WebWorkoutCourseController::class, 'index'])->name('backend_workout_course_index');

   Route::get('/workouts/course/{singleCourse}/{slug}/show', [WebWorkoutCourseController::class, 'show'])->name('backend_workout_course_show');

   Route::post('/workouts/course/store', [WebWorkoutCourseController::class, 'store'])->name('backend_workout_course_store');

   Route::post('/workouts/course/{singleCourse}/toggle', [WebWorkoutCourseController::class, 'toggle'])->name('backend_workout_course_toggle');

   Route::delete('/workouts/course/{singleCourse}/delete', [WebWorkoutCourseController::class, 'delete'])->name('backend_workout_course_delete');

   Route::post('/workouts/course/datatable', [WebWorkoutCourseController::class, 'datatable'])->name('backend_workout_course_datatable');
});
// ------------ WORKOUT COURSES ------------


// ------------ WORKOUT LESSONS ------------

Route::prefix('admin')->middleware(['auth:web', 'verify_role_key:admin,super_admin'])->group(function () {
   Route::get('/workouts/lesson/index', [WebWorkoutLessonController::class, 'index'])->name('backend_workout_lesson_index');

   Route::get('/workouts/lesson/{singleWorkoutLesson}/{slug}/show', [WebWorkoutLessonController::class, 'show'])->name('backend_workout_lesson_show');

   Route::post('/workouts/lesson/store', [WebWorkoutLessonController::class, 'store'])->name('backend_workout_lesson_store');

   Route::post('/workouts/lesson/{singleWorkoutLesson}/toggle', [WebWorkoutLessonController::class, 'toggle'])->name('backend_workout_lesson_toggle');

   Route::delete('/workouts/lesson/{singleWorkoutLesson}/delete', [WebWorkoutLessonController::class, 'delete'])->name('backend_workout_lesson_delete');

   Route::post('/workouts/lesson/bulk-delete', [WebWorkoutLessonController::class, 'bulkDelete'])->name('backend_workout_lesson_bulk_delete');

   Route::post('/workouts/lesson/datatable', [WebWorkoutLessonController::class, 'datatable'])->name('backend_workout_lesson_datatable');
});
// ------------ WORKOUT LESSONS ------------