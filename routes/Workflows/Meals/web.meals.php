<?php

use App\Http\Controllers\Workflows\Meals\WebMealDisplayController;
use Illuminate\Support\Facades\Route;


// ------------ MEALS ------------

Route::prefix('admin')->middleware(['auth:web', 'verify_role_key:admin,super_admin'])->group(function () {
   Route::get('/meal-preference/index', [WebMealDisplayController::class, 'index'])->name('backend_preference_index');

   Route::get('/meal-preference/{preference}/show', [WebMealDisplayController::class, 'show'])->name('backend_preference_show');


   Route::post('/meal-preference/{preference}/toggle', [WebMealDisplayController::class, 'toggle'])->name('backend_preference_toggle');


   Route::delete('/meal-preference/{preference}/delete', [WebMealDisplayController::class, 'delete'])->name('backend_preference_delete');

   Route::post('/meal-preference/bulk-delete', [WebMealDisplayController::class, 'bulkDelete'])->name('backend_preference_bulk_delete');


   Route::post('/meal-preference/datatable', [WebMealDisplayController::class, 'preferenceDatatable'])->name('backend_preference_datatable');
});
// ------------ MEALS ------------