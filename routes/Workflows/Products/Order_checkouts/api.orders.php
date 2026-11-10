<?php

use App\Domains\E_Store\Shoppings\Controllers\Checkouts\StripeOneTimePayCheckoutController;
use App\Domains\E_Store\Shoppings\Controllers\Orders\ApiOrderController;
use Illuminate\Support\Facades\{Auth, Route};

// ------------------------- CHECKOUT -------------------------
Route::prefix('orders')->middleware(['auth:sanctum'])->group(function () {

   Route::post('/payment/checkout', [StripeOneTimePayCheckoutController::class, 'checkout']);
});
// ------------------------- CHECKOUT -------------------------

// ------------------------- ORDERS -------------------------
Route::prefix('orders')->middleware(['auth:sanctum'])->group(function () {

   Route::get('/my-orders/view', [ApiOrderController::class, 'view']);
   Route::delete('/order/{order}/cancel-request', [ApiOrderController::class, 'cancel']);
});
// ------------------------- ORDERS -------------------------