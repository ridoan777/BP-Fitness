<?php

use App\Http\Controllers\System\Analytics\WebAnalyticsController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/analytics', [WebAnalyticsController::class, 'index'])->name('backend_analytics_index');
});

// ----------------- ANALYTICS -----------------
Route::prefix('admin/charts')->middleware('auth')->group(function () {

    Route::get('/users/roles', [WebAnalyticsController::class, 'personnels']);

    Route::get('/categories/products', [WebAnalyticsController::class, 'categories']);

    Route::get('/products/items', [WebAnalyticsController::class, 'projectPhasesChart']);

    Route::get('/projects/closing-date', [WebAnalyticsController::class, 'projectClosingLines']);

    Route::get('/deliveries/schedule-date', [WebAnalyticsController::class, 'deliveryScheduleLines']);

    Route::get('/inventory/tools/usage', [WebAnalyticsController::class, 'toolsUsageBarNLines']);

});
// ----------------- ANALYTICS -----------------