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
		Schema::create('billing_subscription_transactions', function (Blueprint $table) {
			$table->id();
			$table->string('transaction_id')->nullable()->comment('non-ori transaction id of RVC');
			$table->foreignId('subscription_id')->nullable()->constrained('subscription_app_users')->nullOnDelete()->comment('subscription_app_users id');
			$table->string('rc_app_user_id')->nullable();
			$table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

			$table->string('store')->nullable()->comment('APP_STORE, PLAY_STORE');
			$table->string('phase')->nullable()->comment('initiated, completed, failed');
			$table->string('trx_type')->nullable()->comment('INITIAL_PURCHASE, RENEWAL, etc.');

			$table->string('currency')->nullable();
			$table->decimal('amount', 10, 2)->default(0)->comment('float values');

			$table->text('system_note')->nullable();
			$table->string('event_id')->nullable()->comment('uniq on each event of RVC');

			$table->boolean('status')->default(1);

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('billing_subscription_transactions');
	}
};
