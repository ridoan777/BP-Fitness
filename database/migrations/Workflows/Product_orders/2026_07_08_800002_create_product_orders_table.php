<?php

use App\Domains\Billings\Shared\Enums\{CurrencyEnums,PaymentStatusEnums};
use App\Domains\E_Store\Shoppings\Enums\{DeliveryStatusEnums,OrderStatusEnums};
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('product_orders', function (Blueprint $table) {
			$table->id();
			$table->string('order_uid')->unique();

			$table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete()->comment('users table');

			$table->string('order_status')->default(OrderStatusEnums::default())->comment('pending|processing|completed|cancelled|on_hold|failed');
			$table->string('payment_status')->default(PaymentStatusEnums::default())->comment('unpaid|paid|failed|refunded|partially_refunded');
			$table->string('delivery_status')->default(DeliveryStatusEnums::default())->comment('pending|delivered|cancelled');

			$table->string('currency', 3)->default(CurrencyEnums::default());

			$table->unsignedBigInteger('subtotal_amount')->default(0)->comment('items total in cents');
			$table->string('promo_code')->nullable()->comment('applied only');
			$table->unsignedBigInteger('promo_discount_amount')->default(0)->comment('in cents');
			$table->unsignedBigInteger('total_discount_amount')->default(0)->comment('in cents');
			$table->unsignedBigInteger('payable_amount')->default(0)->comment('grand payable amount|cents');
			$table->unsignedBigInteger('paid_amount')->default(0)->comment('from stripe|cents');
			$table->unsignedBigInteger('refunded_amount')->default(0)->comment('confirmed refunded amount|cents');

			$table->timestamp('placed_at')->nullable()->comment('when order is made');
			$table->timestamp('paid_at')->nullable()->comment('from stripe');
			$table->timestamp('fulfilled_at')->nullable()->comment('when order resolved');
			$table->timestamp('cancelled_at')->nullable();
			$table->timestamp('delete_req_at')->nullable()->comment('user wants to delete');

			$table->text('customer_note')->nullable();
			$table->text('system_note')->nullable();
			$table->text('admin_note')->nullable();

			$table->foreignId('creator_id')->nullable()->constrained('users')->nullOnDelete();
			$table->foreignId('last_modifier_id')->nullable()->constrained('users')->nullOnDelete();

			$table->boolean('status')->default(1);
			$table->timestamps();

			$table->index(['customer_id', 'order_status']);
			$table->index(['payment_status', 'delivery_status']);
			$table->index(['created_at']);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('product_orders');
	}
};
