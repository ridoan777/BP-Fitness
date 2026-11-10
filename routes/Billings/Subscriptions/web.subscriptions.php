<?php

// use App\Events\MessageSent;

use App\Domains\Billings\Subscriptions\Controllers\Tiers\WebSubscriptionTierController;
use Illuminate\Support\Facades\Route;

// ------------------------ TIERS ------------------------
Route::prefix('admin')->middleware(['auth:web'])->group(function () {
   
   Route::get('/billings/subscription/tiers/index', [WebSubscriptionTierController::class, 'index'])->name('backend_subscription_tiers_index');

   Route::get('/billings/subscription/tiers/{tier}/show', [WebSubscriptionTierController::class, 'show'])->name('backend_subscription_tier_show');

   Route::post('/billings/subscription/tiers/store', [WebSubscriptionTierController::class, 'store'])->name('backend_subscription_tier_store');

   Route::delete('/billings/subscription/tiers/{tier}/delete', [WebSubscriptionTierController::class, 'delete'])->name('backend_subscription_tier_delete');
});
// ------------------------ TIERS ------------------------
