<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_add_to_carts', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable()->unique();
            $table->string('cart_item_uid')->unique();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('sku_sellable_id')->constrained('product_sku_sellables')->cascadeOnDelete();

            $table->boolean('order_conversion')->default(0)->comment('whether this item was sent to order or not');
            $table->integer('quantity')->default(1);
            $table->integer('legacy_rate')->default(0)->comment('in cents');
            $table->integer('legacy_total_price')->default(0)->comment('in cents');
            
            $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('last_modifier_id')->nullable()->constrained('users')->nullOnUpdate();
            $table->boolean('status')->default(1);
            
            $table->timestamps();

            $table->index(['customer_id', 'status', 'order_conversion'], 'idx_active_cart_items');
            $table->index('sku_sellable_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_add_to_carts');
    }
};
