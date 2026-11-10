<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('product_order_items', function (Blueprint $table) {
			$table->id();
			$table->string('order_item_uid')->unique();
			$table->foreignId('order_id')->constrained('product_orders')->cascadeOnDelete();
			$table->foreignId('cart_item_id')->nullable()->constrained('product_add_to_carts')->nullOnDelete();
			$table->foreignId('sku_sellable_id')->nullable()->constrained('product_sku_sellables')->nullOnDelete();

			$table->unsignedInteger('quantity')->default(1);

			$table->unsignedBigInteger('current_rate')->default(0)->comment('current rate|cents');
			$table->unsignedBigInteger('current_discount')->default(0);
			$table->unsignedBigInteger('current_subtotal_price')->default(0)->comment('current subtotal|cents');
			$table->unsignedBigInteger('current_final_price')->default(0)->comment('current grand total|cents');

			$table->json('legacy_pricing_details')->nullable()->comment('pricing when cart added|cents');
			$table->json('product_details')->nullable()->comment('product snapshot');

			$table->text('customer_note')->nullable();
			$table->text('system_note')->nullable();

			$table->boolean('status')->default(1);
			$table->timestamps();

			$table->foreignId('creator_id')->nullable()->constrained('users')->nullOnDelete();
			$table->foreignId('last_modifier_id')->nullable()->constrained('users')->nullOnDelete();

			$table->index(['order_id', 'status']);
			$table->index('sku_sellable_id');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('product_order_items');
	}
};
