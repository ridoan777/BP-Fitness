<?php

use App\Http\Controllers\Workflows\Journeys\WebJourneyController;
use App\Http\Controllers\Workflows\Referrals\ApiReferralsController;
use Illuminate\Support\Facades\Route;




Route::prefix('admin')->middleware(['auth:web', 'verify_role_key:admin,super_admin'])->group(function () {

    Route::get('/journeys/index', [WebJourneyController::class, 'index'])->name('backend_journeys_index');

    Route::get('/journeys/{journey}/show', [WebJourneyController::class, 'show'])->name('backend_journeys_show');

    Route::post('/journeys/{journey}/toggle', [WebJourneyController::class, 'toggle'])->name('backend_journeys_toggle');


    Route::delete('/journeys/{journey}/delete', [WebJourneyController::class, 'delete'])->name('backend_journeys_delete');

    Route::post('/journeyss/bulk-delete', [WebJourneyController::class, 'bulkDelete'])->name('backend_journeys_bulk_delete');


    Route::post('/journeys/datatable', [WebJourneyController::class, 'journeyDatatable'])->name('backend_journeys_datatable');

});
