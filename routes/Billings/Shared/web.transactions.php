<?php

// use App\Events\MessageSent;

use App\Domains\Billings\Shared\Controllers\Transactions\WebTransactionsController;
use App\Domains\Billings\Payments\Controllers\Transactions\WebPaymentTransactionController;
use App\Domains\Billings\Subscriptions\Controllers\Transactions\WebSubscriptionTransactionController;
use Illuminate\Support\Facades\Route;

// ------------------------ SHARED ------------------------
Route::prefix('admin')->middleware(['auth:web'])->group(function () {

   Route::get('/billings/transactions/index', [WebTransactionsController::class, 'index'])->name('backend_transactions_index');

});
// ------------------------ SHARED ------------------------


// ------------------------ SUBSCRIPTIONS ------------------------
Route::prefix('admin')->middleware(['auth:web'])->group(function () {

   Route::get('/billings/transactions/subscriptions/index', [WebSubscriptionTransactionController::class, 'index'])->name('backend_billings_trx_sub_index');

   Route::get('/billings/transactions/subscribers/{transaction}/show', [WebSubscriptionTransactionController::class, 'show'])->name('backend_billings_subscription_show');

   Route::post('/billings/transactions/subscriptions/{transaction}/toggle', [WebSubscriptionTransactionController::class, 'toggle'])->name('backend_billings_trx_sub_toggle');

   Route::delete('/billings/transactions/subscriptions/{transaction}/delete', [WebSubscriptionTransactionController::class, 'delete'])->name('backend_billings_trx_sub_delete');

   Route::post('/billings/transactions/subscriptions/bulk-delete', [WebSubscriptionTransactionController::class, 'bulkDelete'])->name('backend_billings_trx_sub_bulk_delete');

   Route::post('/billings/transactions/subscriptions/datatable', [WebSubscriptionTransactionController::class, 'datatable'])->name('backend_billings_trx_sub_bulk_datatable');
});
// ------------------------ SUBSCRIPTIONS ------------------------


// ------------------------ PAYMENTS ------------------------
Route::prefix('admin')->middleware(['auth:web'])->group(function () {

   Route::get('/billings/transactions/payments/index', [WebPaymentTransactionController::class, 'index'])->name('backend_billings_trx_pay_index');
   
   Route::get('/billings/transactions/payments/{transaction}/show', [WebPaymentTransactionController::class, 'show'])->name('backend_billings_trx_pay_show');

   Route::post('/billings/transactions/payments/{transaction}/toggle', [WebPaymentTransactionController::class, 'toggle'])->name('backend_billings_trx_pay_toggle');

   Route::delete('/billings/transactions/payments/{transaction}/delete', [WebPaymentTransactionController::class, 'delete'])->name('backend_billings_trx_pay_delete');

   Route::post('/billings/transactions/payments/bulk-delete', [WebPaymentTransactionController::class, 'bulkDelete'])->name('backend_billings_trx_pay_bulk_delete');

   Route::post('/billings/transactions/payments/datatable', [WebPaymentTransactionController::class, 'datatable'])->name('backend_billings_trx_pay_datatable');
});
// ------------------------ PAYMENTS ------------------------
