<?php

use App\Domains\E_Store\Products\Controllers\Variant_items\ApiVariantItemController;
use Illuminate\Support\Facades\Route;


Route::get('/e-store/product/variant-types/{variantType}/items', [ApiVariantItemController::class, 'dropdownItemsByType'])->name('api_estore_fetch_variant_items_by_type');