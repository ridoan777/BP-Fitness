<?php

use App\Domains\E_Store\Products\Controllers\Variant_items\WebVariantItemController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['auth:web', 'verify_role_key:admin,super_admin'])->group(function () {

   Route::get('/e-store/product/variant/items/index', [WebVariantItemController::class, 'index'])->name('backend_product_variant_item_index');

   Route::get('/e-store/product/variant/items/{singleProductVariantItem}/{slug}/show', [WebVariantItemController::class, 'show'])->name('backend_product_variant_item_show');

   Route::post('/e-store/product/variant/items/store', [WebVariantItemController::class, 'store'])->name('backend_product_variant_item_store');

   Route::post('/e-store/product/variant/items/{singleProductVariantItem}/toggle', [WebVariantItemController::class, 'toggle'])->name('backend_product_variant_item_toggle');

   Route::delete('/e-store/product/variant/items/{singleProductVariantItem}/delete', [WebVariantItemController::class, 'delete'])->name('backend_product_variant_item_delete');

   Route::post('/e-store/product/variant/items/datatable', [WebVariantItemController::class, 'variantItemDatatable'])->name('backend_product_variant_item_datatable');
});