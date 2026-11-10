<?php

// use App\Http\Controllers\Workflows\Products\Categories\WebProductCategoryController;
use App\Domains\E_Store\Products\Controllers\Categories\WebProductCategoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['auth:web', 'verify_role_key:admin,super_admin'])->group(function () {

   Route::get('/e-store/product/categories/index', [WebProductCategoryController::class, 'index'])->name('backend_product_category_index');

   Route::get('/e-store/product/categories/{singleCategory}/{slug}/show', [WebProductCategoryController::class, 'show'])->name('backend_product_category_show');

   Route::post('/e-store/product/categories/store', [WebProductCategoryController::class, 'store'])->name('backend_product_category_store');

   Route::post('/e-store/product/categories/{singleCategory}/toggle', [WebProductCategoryController::class, 'toggle'])->name('backend_product_category_toggle');

   Route::delete('/e-store/product/categories/{singleCategory}/delete', [WebProductCategoryController::class, 'delete'])->name('backend_product_category_delete');

   Route::post('/e-store/product/categories/datatable', [WebProductCategoryController::class, 'productCategoryDatatable'])->name('backend_product_category_datatable');
});