<?php

use App\Domains\E_Store\Shoppings\Controllers\Add_to_carts\ApiAddToCartController;
use Illuminate\Support\Facades\Route;

// ------------ PRODUCT ADD-TO-CART ------------
Route::prefix('e-store')->middleware(['auth:sanctum'])->group(function () {

   Route::get('/add-to-cart/my-cart/view', [ApiAddToCartController::class, 'view']);

   Route::post('/add-to-cart/store', [ApiAddToCartController::class, 'store']);

   Route::delete('/add-to-cart/{singleAddToCart}/delete', [ApiAddToCartController::class, 'delete']);
});
// ------------ PRODUCT ADD-TO-CART ------------

/*

   app\Domains\E_Store\Shoppings\Models:

*/