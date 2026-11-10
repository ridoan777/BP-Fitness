<?php

// use App\Events\MessageSent;


use App\Domains\Billings\Shared\Controllers\Customers\WebCustomerController;
use App\Domains\Billings\Payments\Controllers\Customers\WebOneTimeCustomerController;
use App\Domains\Billings\Subscriptions\Controllers\Customers\WebSubscriptionCustomerController;
use Illuminate\Support\Facades\Route;

// ------------------------ CUSTOMERS ------------------------
Route::prefix('admin')->middleware(['auth:web'])->group(function () {

   Route::get('/billings/customers/index', [WebCustomerController::class, 'index'])->name('backend_billings_customers_index');

});
// ------------------------ CSUTOMERS ------------------------


// ------------------------ SUBSCRIPTIONS ------------------------
Route::prefix('admin')->middleware(['auth:web'])->group(function () {

   Route::get('/billings/customers/subscribers/index', [WebSubscriptionCustomerController::class, 'index'])->name('backend_billings_customers_subscribers_index');

   Route::get('/billings/customers/subscribers/{subscription}/show', [WebSubscriptionCustomerController::class, 'show'])->name('backend_billings_customer_subscription_show');

   Route::post('/billings/customers/subscribers/{subscription}/toggle', [WebSubscriptionCustomerController::class, 'toggle'])->name('backend_billings_customer_subscription_toggle');

   Route::delete('/billings/customers/subscribers/{subscription}/delete', [WebSubscriptionCustomerController::class, 'delete'])->name('backend_billings_customer_subscription_delete');

   Route::post('/billings/customers/subscribers/bulk-delete', [WebSubscriptionCustomerController::class, 'bulkDelete'])->name('backend_billings_customer_subscription_bulk_delete');

   Route::post('/billings/customers/subscribers/datatable', [WebSubscriptionCustomerController::class, 'datatable'])->name('backend_billings_customer_subscription_datatable');
});
// ------------------------ SUBSCRIPTIONS ------------------------


// ------------------------ BUYERS ------------------------
Route::prefix('admin')->middleware(['auth:web'])->group(function () {

   Route::get('/billings/customers/buyers/index', [WebOneTimeCustomerController::class, 'index'])->name('backend_billings_customers_buyers_index');

   Route::get('/billings/customers/buyers/{buyer}/show', [WebOneTimeCustomerController::class, 'show'])->name('backend_billings_customer_buyer_show');

   Route::post('/billings/customers/buyers/{subscription}/toggle', [WebOneTimeCustomerController::class, 'toggle'])->name('backend_billings_customer_buyer_toggle');

   Route::delete('/billings/customers/buyers/{subscription}/delete', [WebOneTimeCustomerController::class, 'delete'])->name('backend_billings_customer_buyer_delete');

   Route::post('/billings/customers/buyers/bulk-delete', [WebOneTimeCustomerController::class, 'bulkDelete'])->name('backend_billings_customer_buyer_bulk_delete');

   Route::post('/billings/customers/buyers/datatable', [WebOneTimeCustomerController::class, 'datatable'])->name('backend_billings_customer_buyer_datatable');
});
// ------------------------ BUYERS ------------------------
