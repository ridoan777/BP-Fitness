<?php

// use App\Events\MessageSent;

use App\Domains\E_Store\Shoppings\Controllers\Orders\WebOrderController;
use Illuminate\Support\Facades\Route;

// ------------------------ ORDERS ------------------------
Route::prefix('admin')->middleware(['auth:web'])->group(function () {

   Route::get('/e-store/orders/index', [WebOrderController::class, 'index'])->name('backend_estore_orders_index');

   Route::get('/e-store/orders/delete-requests/index', [WebOrderController::class, 'deleteRequest'])->name('backend_estore_orders_delete_reqs');

   Route::get('/e-store/orders/{singleOrder}/{slug}/show', [WebOrderController::class, 'show'])->name('backend_estore_orders_show');

   Route::post('/e-store/orders/store', [WebOrderController::class, 'store'])->name('backend_estore_orders_store');

   Route::post('/e-store/orders/{singleOrder}/toggle', [WebOrderController::class, 'toggle'])->name('backend_estore_orders_toggle');

   Route::delete('/e-store/orders/{singleOrder}/delete', [WebOrderController::class, 'delete'])->name('backend_estore_orders_delete');

   Route::post('/e-store/orders/bulk-delete', [WebOrderController::class, 'bulkDelete'])->name('backend_estore_orders_bulk_delete');

   Route::post('/e-store/orders/datatable', [WebOrderController::class, 'datatable'])->name('backend_estore_orders_datatable');
});
// ------------------------ ORDERS ------------------------
