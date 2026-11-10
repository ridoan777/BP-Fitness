<?php

use App\Http\Controllers\Workflows\Groceries\WebGroceryController;
use Illuminate\Support\Facades\Route;


// ------------ GROCERIES ------------

Route::prefix('admin')->middleware(['auth:web', 'verify_role_key:admin,super_admin'])->group(function () {
   Route::get('/grocery/index', [WebGroceryController::class, 'index'])->name('backend_grocery_index');

   Route::get('/grocery/create', [WebGroceryController::class, 'create'])->name('backend_grocery_create');

   Route::get('/grocery/{singleGrocery}/show', [WebGroceryController::class, 'show'])->name('backend_grocery_show');


   Route::post('/grocery/store', [WebGroceryController::class, 'store'])->name('backend_grocery_store');

   Route::post('/grocery/{grocery}/toggle', [WebGroceryController::class, 'toggle'])->name('backend_grocery_toggle');


   Route::delete('/grocery/{grocery}/delete', [WebGroceryController::class, 'delete'])->name('backend_grocery_delete');

   Route::post('/groceries/bulk-delete', [WebGroceryController::class, 'bulkDelete'])->name('backend_grocery_bulk_delete');


   Route::post('/grocery/datatable', [WebGroceryController::class, 'groceryDatatable'])->name('backend_grocery_datatable');
});
// ------------ GROCERIES ------------