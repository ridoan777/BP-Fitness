<?php

use App\Domains\Billings\Shared\Enums\{CurrencyEnums, PaymentStatusEnums, TrxPhaseEnums};
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('billing_payment_transactions', function (Blueprint $table) {
			$table->id();
			$table->string('trx_uid')->unique();
			$table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

			$table->morphs('payment_source', 'payment_source_idx');

			$table->integer('ss_user_id')->nullable()->comment('snapshot');
			$table->string('ss_user_email')->nullable()->comment('snapshot');

			$table->string('payment_status')->default(PaymentStatusEnums::default());
			$table->string('trx_phase')->default(TrxPhaseEnums::default());

			$table->string('currency', 3)->default(CurrencyEnums::default());

			$table->unsignedBigInteger('trx_amount')->default(0)->comment('from stripe|cents');
			$table->unsignedBigInteger('stripe_fee')->default(0)->comment('in cents');
			$table->decimal('exchange_rate', 12, 6)->default(0)->comment('from stripe');
			$table->unsignedBigInteger('takeaway_amount')->default(0)->comment('paid - fee|cents');

			$table->json('card_details')->nullable()->comment('from stripe|array');
			$table->json('customer_details')->nullable()->comment('from stripe|array');
			$table->json('passed_metadata')->nullable()->comment('passed in session|array');

			$table->string('payment_intent')->nullable()->comment('from stripe')->index();
			$table->json('event_ids')->nullable()->comment('from stripe|events');
			$table->string('stripe_checkout_session_id')->nullable()->comment('from stripe')->unique();

			$table->text('system_note')->nullable();

			$table->boolean('status')->default(1);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('billing_payment_transactions');
	}
};
