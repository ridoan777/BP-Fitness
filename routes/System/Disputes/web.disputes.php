<?php

use App\Domains\Dispute_management\Controllers\WebDisputeManagementController;
use Illuminate\Support\Facades\Route;

// --------------------- DISPUTE MANAGEMENT ---------------------
Route::prefix('admin')->middleware(['auth:web'])->group(function () {

    Route::get('/dispute-management/index', [WebDisputeManagementController::class, 'index'])->name('backend_disputes_report_index');

    Route::post('/dispute-management/reports/{report}/resolution', [WebDisputeManagementController::class, 'resolution'])->name('backend_disputes_report_handle');

    Route::get('dispute-management/reports/{report}/show', [WebDisputeManagementController::class, 'show'])->name('backend_dispute_report_show');

    Route::post('dispute-management/report/{report}/toggle', [WebDisputeManagementController::class, 'toggle'])->name('backend_dispute_report_toggle');

    Route::delete('dispute-management/report/{report}/delete', [WebDisputeManagementController::class, 'delete'])->name('backend_dispute_report_delete');

    Route::post('dispute-management/reports/bulk-delete', [WebDisputeManagementController::class, 'bulkDelete'])->name('backend_dispute_report_bulk_delete');

    Route::post('/dispute-management/reports/datatable', [WebDisputeManagementController::class, 'reportsDatatable'])->name('backend_disputes_reports_datatable');
});
// --------------------- DISPUTE MANAGEMENT ---------------------

// --------------------- USER ACCESS ---------------------
Route::prefix('admin')->middleware(['auth:web'])->group(function () {

    Route::get('/user-access/index', [WebDisputeManagementController::class, 'indexUserAccess'])->name('backend_user_access_index');
});
// --------------------- USER ACCESS ---------------------