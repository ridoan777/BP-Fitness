<?php

// use App\Events\MessageSent;

use App\Domains\Billings\Controllers\Subscriptions\Tiers\WebSubscriptionTierController;
use Illuminate\Support\Facades\Route;

// ------------------------ TIERS ------------------------
Route::prefix('admin')->middleware(['auth:web'])->group(function () {
   
});
// ------------------------ TIERS ------------------------
