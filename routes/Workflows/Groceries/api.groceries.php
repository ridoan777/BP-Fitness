<?php

use App\Http\Controllers\Workflows\Groceries\ApiGroceryController;
use Illuminate\Support\Facades\Route;


// ------------ GROCERIES ------------
Route::prefix('groceries')->middleware(['auth:sanctum'])->group(function () {

   Route::get('/{preference}/index', [ApiGroceryController::class, 'index']);
   Route::post('/track/store', [ApiGroceryController::class, 'store']);
});
// ------------ GROCERIES ------------