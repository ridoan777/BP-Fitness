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
		Schema::create('product_order_users', function (Blueprint $table) {
			$table->id();
			$table->string('order_user_uid')->unique();
			$table->foreignId('order_id')->constrained('product_orders')->cascadeOnDelete();

			$table->string('payer_name')->nullable();
			$table->string('payer_email')->nullable();
			$table->string('payer_phone')->nullable();
			$table->string('payer_street')->nullable();
			$table->string('payer_state')->nullable();
			$table->string('payer_city')->nullable();
			$table->string('payer_zip')->nullable();
			$table->decimal('payer_latitude', 18, 15)->nullable();
			$table->decimal('payer_longitude', 18, 15)->nullable();

			$table->string('receiver_name')->nullable();
			$table->string('receiver_email')->nullable();
			$table->string('receiver_phone')->nullable();
			$table->string('receiver_street')->nullable();
			$table->string('receiver_state')->nullable();
			$table->string('receiver_city')->nullable();
			$table->string('receiver_zip')->nullable();
			$table->decimal('receiver_latitude', 18, 15)->nullable();
			$table->decimal('receiver_longitude', 18, 15)->nullable();

			$table->boolean('status')->default(1);
			$table->timestamps();

			$table->foreignId('creator_id')->nullable()->constrained('users')->nullOnDelete();
			$table->foreignId('last_modifier_id')->nullable()->constrained('users')->nullOnDelete();

			$table->unique('order_id');
			$table->index(['payer_email', 'receiver_email']);
			$table->index(['payer_phone', 'receiver_phone']);
			$table->index(['status', 'created_at']);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('product_order_users');
	}
};