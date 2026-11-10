<?php

use App\Domains\E_Store\Products\Controllers\Product_items\ApiProductItemController;
use Illuminate\Support\Facades\Route;

// ------------ PRODUCT SKU SELLABLES ------------
Route::prefix('e-store')->group(function () {

   Route::get('/products/items/index', [ApiProductItemController::class, 'index']);
   Route::get('/products/{singleProductItem}/{slug}/show', [ApiProductItemController::class, 'show']);
   
});
// ------------ PRODUCT SKU SELLABLES ------------