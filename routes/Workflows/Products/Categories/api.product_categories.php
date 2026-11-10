<?php

use App\Domains\E_Store\Products\Controllers\Categories\ApiProductCategoryController;
use Illuminate\Support\Facades\Route;

// ------------ PRODUCT CATEGORIES ------------
Route::prefix('e-store')->middleware(['auth:sanctum'])->group(function () {

   Route::get('/categories/home', [ApiProductCategoryController::class, 'home']);
   
   Route::get('/categories/index', [ApiProductCategoryController::class, 'index']);
   Route::get('/categories/{category}/index', [ApiProductCategoryController::class, 'indexByCategory']);
});
// ------------ PRODUCT CATEGORIES ------------