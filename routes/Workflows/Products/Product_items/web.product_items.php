<?php

use App\Domains\E_Store\Products\Controllers\Product_items\WebProductItemController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['auth:web', 'verify_role_key:admin,super_admin'])->group(function () {

   Route::get('/product/items/index', [WebProductItemController::class, 'index'])->name('backend_product_item_index');

   Route::get('/product/items/create', [WebProductItemController::class, 'create'])->name('backend_product_item_create');

   Route::get('/product/items/{singleProductItem}/{slug}/show', [WebProductItemController::class, 'show'])->name('backend_product_item_show');

   Route::get('/product/items/{singleProductItem}/{slug}/edit', [WebProductItemController::class, 'edit'])->name('backend_product_item_edit');

   Route::post('/product/items/{singleProductItem}/toggle', [WebProductItemController::class, 'toggle'])->name('backend_product_item_toggle');

   Route::post('/product/items/{singleProductItem}/delete', [WebProductItemController::class, 'delete'])->name('backend_product_item_delete');

   Route::post('/product/items/store', [WebProductItemController::class, 'store'])->name('backend_product_item_store');

   Route::post('/product/items/bulk-delete', [WebProductItemController::class, 'bulkDelete'])->name('backend_product_item_bulk_delete');

   Route::post('/product/items/datatable', [WebProductItemController::class, 'productItemDatatable'])->name('backend_product_item_datatable');
});
