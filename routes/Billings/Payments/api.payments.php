<?php

use App\Domains\Billings\Shared\Controllers\Webhooks\StripePaymentWebhookController;
use App\Domains\E_Store\Shoppings\Controllers\Checkouts\StripeOneTimePayCheckoutController;
use Illuminate\Support\Facades\{Auth, Route};


// ------------------------- STRIPE + CHECKOUTS -------------------------
Route::prefix('stripe')->middleware(['auth:sanctum'])->group(function () {

   Route::post('/payment/checkout/test/expire-session/{sessionId}', [StripeOneTimePayCheckoutController::class, 'testExpireSession']);

});
// ------------------------- STRIPE + CHECKOUTS -------------------------


// ------------------------- STRIPE + WEBHOOKS -------------------------
Route::prefix('stripe')->group(function () {

   Route::post('/sandbox/payments/webhook', [StripePaymentWebhookController::class, 'handleWebhook']);
   Route::post('/live/payments/webhook', [StripePaymentWebhookController::class, 'handleWebhook']);
});
// ------------------------- STRIPE + WEBHOOKS -------------------------


// ------------------------- STRIPE + REDIRECTION -------------------------
Route::prefix('stripe')->group(function () {

   Route::get('/payments/success', [StripeOneTimePayCheckoutController::class, 'success'])->name('api_payment_success');
   Route::get('/payments/cancel', [StripeOneTimePayCheckoutController::class, 'cancel'])->name('api_payment_cancel');
});
// ------------------------- STRIPE + REDIRECTION -------------------------
/*
   stripe listen --forward-to localhost:8000/api/stripe/sandbox/payments/webhook
*/