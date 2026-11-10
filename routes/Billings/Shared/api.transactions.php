<?php

// use App\Events\MessageSent;

use App\Http\Controllers\Billings\Transactions\WebTransactionsController;
use Illuminate\Support\Facades\Route;

// ------------------------ TIERS ------------------------
Route::prefix('admin')->middleware(['auth:web'])->group(function () {
   
});
// ------------------------ TIERS ------------------------
