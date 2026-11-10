<?php

use App\Domains\Motivations\Controllers\Categories\WebMotivationController;
use Illuminate\Support\Facades\Route;

// ------------ MOTIVATIONS ------------
Route::prefix('admin')->middleware(['auth:web', 'verify_role_key:admin,super_admin'])->group(function () {

   Route::get('/motivations/index', [WebMotivationController::class, 'index'])->name('backend_motivation_index');

   Route::get('/motivations/create', [WebMotivationController::class, 'create'])->name('backend_motivation_create');

   Route::get('/motivations/{motivation}/{slug}/show', [WebMotivationController::class, 'show'])->name('backend_motivation_show');

   Route::post('/motivations/store', [WebMotivationController::class, 'store'])->name('backend_motivation_store');

   Route::post('/motivations/{motivation}/toggle', [WebMotivationController::class, 'toggle'])->name('backend_motivation_toggle');

   Route::delete('/motivations/{motivation}/delete', [WebMotivationController::class, 'delete'])->name('backend_motivation_delete');

   Route::post('/motivations/delete', [WebMotivationController::class, 'bulkDelete'])->name('backend_motivation_bulk_delete');

   Route::post('/motivations/datatable', [WebMotivationController::class, 'datatable'])->name('backend_motivation_datatable');
});
// ------------ MOTIVATIONS ------------
