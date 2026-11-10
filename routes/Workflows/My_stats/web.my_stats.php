<?php

use App\Domains\My_stats\Controllers\WebMyStatController;
use Illuminate\Support\Facades\Route;


// ------------ MY STATS ------------
Route::prefix('admin')->middleware(['auth:web', 'verify_role_key:admin,super_admin'])->group(function () {
   Route::get('/my-stats/index', [WebMyStatController::class, 'index'])->name('backend_my_stats_index');

   Route::get('/my-stats/{myStat}/{slug}/show', [WebMyStatController::class, 'show'])->name('backend_my_stats_show');


   Route::post('/my-stats/{myStat}/toggle', [WebMyStatController::class, 'toggle'])->name('backend_my_stats_toggle');


   Route::delete('/my-stats/{myStat}/delete', [WebMyStatController::class, 'delete'])->name('backend_my_stats_delete');

   Route::post('/my-stats/bulk-delete', [WebMyStatController::class, 'bulkDelete'])->name('backend_my_stats_bulk_delete');


   Route::post('/my-stats/datatable', [WebMyStatController::class, 'myStatDatatable'])->name('backend_my_stats_datatable');
});
// ------------ MY STATS ------------