<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    //  This is the actual variant that a user buys

    public function up(): void
    {
        Schema::create('product_sku_sellables', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable()->unique();
            $table->string('product_sku_uid')->unique();
            $table->foreignId('product_item_id')->nullable()->constrained('product_items')->nullOnDelete();

            $table->string('name');
            $table->text('description')->nullable();

            $table->integer('stock')->default(0);
            $table->integer('starting_price')->default(0)->comment('in cents');
            $table->integer('final_price')->default(0)->comment('in cents');

            $table->boolean('featured')->default(0);

            $table->boolean('status')->default(1);

            $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('last_modifier_id')->nullable()->constrained('users')->nullOnUpdate();
            $table->timestamps();

            $table->comment('This is the final sellable version of a base-product stored in product_items. Each product variant with one/more variant_items will be considered as a single sellable unit.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_sku_sellables');
    }
};
