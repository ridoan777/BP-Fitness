<?php

use App\Domains\E_Store\Shoppings\Controllers\Add_to_carts\WebAddToCartController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['auth:web', 'verify_role_key:admin,super_admin'])->group(function () {

   Route::get('/e-store/add-to-carts/index', [WebAddToCartController::class, 'index'])->name('backend_estore_add_to_cart_index');

   Route::get('/e-store/add-to-carts/{singleAddToCart}/{slug}/show', [WebAddToCartController::class, 'show'])->name('backend_estore_add_to_cart_show');

   Route::post('/e-store/add-to-carts/store', [WebAddToCartController::class, 'store'])->name('backend_estore_add_to_cart_store');

   Route::post('/e-store/add-to-carts/{singleAddToCart}/toggle', [WebAddToCartController::class, 'toggle'])->name('backend_estore_add_to_cart_toggle');

   Route::delete('/e-store/add-to-carts/{singleAddToCart}/delete', [WebAddToCartController::class, 'delete'])->name('backend_estore_add_to_cart_delete');

   Route::post('/e-store/add-to-carts/datatable', [WebAddToCartController::class, 'addToCartDatatable'])->name('backend_estore_add_to_cart_datatable');
});