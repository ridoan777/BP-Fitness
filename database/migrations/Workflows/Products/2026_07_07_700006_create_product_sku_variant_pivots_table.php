<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    //  This is the actual variant that a user buys

    public function up(): void
    {
        Schema::create('product_sku_variant_pivots', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_sku_sellable_id')->constrained('product_sku_sellables')->cascadeOnDelete();

            $table->foreignId('product_variant_item_id')->constrained('product_variant_items')->cascadeOnDelete();
            $table->integer('base_product_item')->nullable()->comment('product_items readonly reference');

            $table->unique(
                ['product_sku_sellable_id', 'product_variant_item_id'],
                'product_sku_variant_unique'
            );

            $table->timestamps();
            $table->comment('This stores all variant items (stored in variant_items) one by one, that were used to make a single product variant. T-Shirt Black L, XL will have three records here: black, L, XL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_sku_variant_pivots');
    }
};
