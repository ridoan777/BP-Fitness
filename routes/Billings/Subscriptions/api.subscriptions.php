<?php

use App\Domains\Billings\Shared\Controllers\Webhooks\RevenueCatWebhookController;
use App\Domains\Billings\Subscriptions\Controllers\Generals\ApiGeneralSubscriptionController;
use App\Domains\Billings\Subscriptions\Controllers\Tiers\ApiSubscriptionTierController;
use Illuminate\Support\Facades\{Auth, Route};

// ------------------------- GET TIERS/PACKAGES -------------------------
Route::prefix('users')->middleware(['auth:sanctum'])->group(function () {

   Route::get('/billing/subscriptions/web/tiers/index', [ApiSubscriptionTierController::class, 'indexWeb']);
   Route::get('/billing/subscriptions/mobile/tiers/index', [ApiSubscriptionTierController::class, 'indexMobile']);
});
// ------------------------- GET TIERS/PACKAGES -------------------------

// ------------------------- GENERALS -------------------------
Route::prefix('revenuecat')->middleware(['auth:sanctum'])->group(function () {

   Route::get('/user/subscriptions/status', [ApiGeneralSubscriptionController::class, 'status']);
});
// ------------------------- GENERALS -------------------------


// ------------------------- STRIPE + WEBHOOKS -------------------------
/*
Route::prefix('stripe')->group(function () {

   Route::post('/tiers/purchase', [StripeSubscribeCheckoutController::class, 'checkout'])->name('stripe_checkout')->middleware(['auth:sanctum']);

   Route::get('https://checkout.stripe.com/c/pay/{stripe_checkout_session_id}');

   Route::get('/checkout/success', [StripeSubscribeCheckoutController::class, 'success'])->name('checkout_success');
   Route::get('/checkout/cancel', [StripeSubscribeCheckoutController::class, 'cancel'])->name('checkout_cancel');

   Route::post('/master-admin/webhook', [StripeSubscriptionWebhookController::class, 'handleWebhook'])->name('stripe.webhook');
});
*/
// ------------------------- STRIPE + WEBHOOKS -------------------------


// ------------------------- REVENUECAT + WEBHOOKS -------------------------
Route::prefix('revenuecat')->group(function () {

   Route::post('/sandbox/webhook', [RevenueCatWebhookController::class, 'handleWebhook']);
   Route::post('/live/webhook', [RevenueCatWebhookController::class, 'handleWebhook']);

});
// ------------------------- REVENUECAT + WEBHOOKS -------------------------


// ------------------------- REVENUECAT + REST API -------------------------
// Route::prefix('https://api.revenuecat.com/v2')->group(function () {

//    Route::get('/projects/{rvc_project_id}/subscriptions?store_subscription_identifier={subscription_id}', [RevenueCatWebhookController::class, 'subscriptionObject']);

// });
// ------------------------- REVENUECAT + REST API -------------------------