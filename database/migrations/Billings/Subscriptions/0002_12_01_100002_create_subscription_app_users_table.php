<?php

use App\Domains\Billings\Shared\Enums\{SubscriptionStatusEnums, PaymentStatusEnums};
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
	public function up(): void
	{
		Schema::create('subscription_app_users', function (Blueprint $table) {
			$table->id();
			$table->foreignId('user_id')->nullable()->references('id')->on('users')->nullOnDelete();
			$table->foreignId('tier_id')->nullable()->references('id')->on('subscription_tiers')->nullOnDelete();
			$table->json('entitlement_id')->nullable();
			$table->string('provider')->default('revenuecat');
			$table->string('store')->nullable();

			$table->string('payment_status')->default(PaymentStatusEnums::default());
			$table->string('sub_status')->default(SubscriptionStatusEnums::default());

			$table->string('country')->nullable();
			$table->string('currency')->nullable();
			$table->decimal('amount', 10, 2)->default(0);
			$table->string('duration')->nullable();
			$table->integer('iteration')->default(0)->comment('times this sub is renewed');

			$table->text('system_note')->nullable();
			$table->boolean('status')->default(1);

			$table->text('rc_product_id')->nullable()->comment('unique per product');
			$table->text('rc_subscription_id')->nullable()->comment('ori_trx_id|unique per sub lifecycle');
			$table->text('rc_transaction_id')->nullable()->comment('unique per trx');
			$table->text('rc_event_id')->nullable();
			$table->text('rc_user_id')->nullable()->comment('app user id');
			$table->text('rc_ori_user_id')->nullable()->comment('original app user id');

			$table->dateTime('purchased_at')->nullable();
			$table->dateTime('expire_at')->nullable();
			$table->dateTime('renewal_at')->nullable();
			$table->dateTime('cancelled_at')->nullable();

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('subscription_app_users');
	}
};
