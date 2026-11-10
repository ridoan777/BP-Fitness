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
		Schema::create('subscription_tiers', function (Blueprint $table) {
			$table->id();
			$table->string('tier_uid')->unique();
			// $table->foreignId('service_id')->nullable()->constrained('projects')->nullOnDelete();

			$table->string('platform')->nullable();

			$table->string('name')->nullable();
			$table->integer('duration')->nullable();

			$table->integer('starting_price')->default(0)->comment('in cents');
			$table->integer('final_price')->default(0)->comment('in cents');

			// $table->string('slogan')->nullable();
			// $table->string('price_label')->nullable();

			$table->text('benefits')->nullable();
			$table->text('description')->nullable();

			$table->text('google_product_id')->nullable();
			$table->text('apple_product_id')->nullable();
			$table->text('google_entitlement')->nullable();
			$table->text('apple_entitlement')->nullable();
			$table->text('google_rest_api_id')->nullable();
			$table->text('apple_rest_api_id')->nullable();

			$table->text('stripe_product_id')->nullable();
			$table->text('stripe_price_id')->nullable();

			$table->string('image')->nullable();

			$table->boolean('status')->default(1);

			$table->foreignId('creator_id')->constrained('users')->cascadeOnDelete();
			$table->foreignId('last_modifier_id')->nullable()->constrained('users')->nullOnUpdate();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('subscription_tiers');
	}
};
