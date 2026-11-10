<?php

use App\Domains\Achievements\Controllers\{WebAchievementBadgeController,WebAchievementRecordController,WebAchievementHeadController};
use Illuminate\Support\Facades\Route;

// ------------------------ BADGES ------------------------

Route::prefix('admin')->middleware(['auth:web', 'verify_role_key:admin,super_admin'])->group(function () {

   Route::get('/achievements/badges/index', [WebAchievementBadgeController::class, 'index'])->name('backend_achievement_badge_index');

   Route::get('/achievements/badges/create', [WebAchievementBadgeController::class, 'create'])->name('backend_achievement_badge_create');

   Route::get('/achievements/badges/{singleBadge}/{slug}/show', [WebAchievementBadgeController::class, 'show'])->name('backend_achievement_badge_show');

   Route::post('/achievements/badges/store', [WebAchievementBadgeController::class, 'store'])->name('backend_achievement_badge_store');

   Route::post('/achievements/badges/{singleBadge}/toggle', [WebAchievementBadgeController::class, 'toggle'])->name('backend_achievement_badge_toggle');

   Route::delete('/achievements/badges/{singleBadge}/delete', [WebAchievementBadgeController::class, 'delete'])->name('backend_achievement_badge_delete');

   Route::post('/achievements/badges/bulk-delete', [WebAchievementBadgeController::class, 'bulkDelete'])->name('backend_achievement_badge_bulk_delete');

   Route::post('/achievements/badges/datatable', [WebAchievementBadgeController::class, 'datatable'])->name('backend_achievement_badge_datatable');
});
// ------------------------ BADGES ------------------------

// ------------------------ HEADS ------------------------

Route::prefix('admin')->middleware(['auth:web', 'verify_role_key:admin,super_admin'])->group(function () {

   Route::get('/achievements/heads/index', [WebAchievementHeadController::class, 'index'])->name('backend_achievement_head_index');

   Route::get('/achievements/heads/create', [WebAchievementHeadController::class, 'create'])->name('backend_achievement_head_create');

   Route::get('/achievements/heads/point-poll', [WebAchievementHeadController::class, 'pointPoll'])->name('backend_achievement_head_point_poll');

   Route::get('/achievements/heads/{singleHead}/{slug}/show', [WebAchievementHeadController::class, 'show'])->name('backend_achievement_head_show');

   Route::post('/achievements/heads/store', [WebAchievementHeadController::class, 'store'])->name('backend_achievement_head_store');

   Route::post('/achievements/heads/{singleHead}/toggle', [WebAchievementHeadController::class, 'toggle'])->name('backend_achievement_head_toggle');

   Route::delete('/achievements/heads/{singleHead}/delete', [WebAchievementHeadController::class, 'delete'])->name('backend_achievement_head_delete');

   Route::post('/achievements/heads/bulk-delete', [WebAchievementHeadController::class, 'bulkDelete'])->name('backend_achievement_head_bulk_delete');

   Route::post('/achievements/heads/datatable', [WebAchievementHeadController::class, 'datatable'])->name('backend_achievement_head_datatable');
});
// ------------------------ HEADS ------------------------

// ------------------------ EARNERS ------------------------

Route::prefix('admin')->middleware(['auth:web', 'verify_role_key:admin,super_admin'])->group(function () {

   Route::get('/achievements/earners/index', [WebAchievementRecordController::class, 'index'])->name('backend_achievement_earner_index');

   Route::get('/achievements/earners/create', [WebAchievementRecordController::class, 'create'])->name('backend_achievement_earner_create');

   Route::get('/achievements/earners/{singleEarner}/{slug}/show', [WebAchievementRecordController::class, 'show'])->name('backend_achievement_earner_show');

   Route::post('/achievements/earners/{singleEarner}/toggle', [WebAchievementRecordController::class, 'toggle'])->name('backend_achievement_earner_toggle');

   Route::delete('/achievements/earners/{singleEarner}/delete', [WebAchievementRecordController::class, 'delete'])->name('backend_achievement_earner_delete');

   Route::post('/achievements/earners/bulk-delete', [WebAchievementRecordController::class, 'bulkDelete'])->name('backend_achievement_earner_bulk_delete');

   Route::post('/achievements/earners/datatable', [WebAchievementRecordController::class, 'datatable'])->name('backend_achievement_earner_datatable');
});

// ------------------------ EARNERS ------------------------

// ------------------------ RECORDS ------------------------

Route::prefix('admin')->middleware(['auth:web', 'verify_role_key:admin,super_admin'])->group(function () {

   Route::delete('/achievements/earners/records/{singleRecord}/delete', [WebAchievementRecordController::class, 'deleteRecord'])->name('backend_achievement_earner_record_delete');

   Route::post('/achievements/earners/records/bulk-delete', [WebAchievementRecordController::class, 'bulkDeleteRecords'])->name('backend_achievement_earner_record_bulk_delete');

});
// ------------------------ RECORDS ------------------------
