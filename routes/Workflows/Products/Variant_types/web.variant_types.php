<?php

use App\Domains\E_Store\Products\Controllers\Variant_types\WebVariantTypeController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['auth:web', 'verify_role_key:admin,super_admin'])->group(function () {

   Route::get('/e-store/product/variant/types/index', [WebVariantTypeController::class, 'index'])->name('backend_product_variant_type_index');

   Route::get('/e-store/product/variant/types/{singleProductVariantType}/{slug}/show', [WebVariantTypeController::class, 'show'])->name('backend_product_variant_type_show');

   Route::post('/e-store/product/variant/types/store', [WebVariantTypeController::class, 'store'])->name('backend_product_variant_type_store');

   Route::post('/e-store/product/variant/types/{singleProductVariantType}/toggle', [WebVariantTypeController::class, 'toggle'])->name('backend_product_variant_type_toggle');

   Route::delete('/e-store/product/variant/types/{singleProductVariantType}/delete', [WebVariantTypeController::class, 'delete'])->name('backend_product_variant_type_delete');

   Route::post('/e-store/product/variant/types/datatable', [WebVariantTypeController::class, 'variantTypeDatatable'])->name('backend_product_variant_type_datatable');
});