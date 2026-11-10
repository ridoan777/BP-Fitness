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
		Schema::create('product_galleries', function (Blueprint $table) {
			$table->id();
			$table->foreignId('product_item_id')->nullable()->constrained('product_items')->cascadeOnDelete();
			$table->foreignId('sku_sellable_id')->nullable()->constrained('product_sku_sellables')->cascadeOnDelete();

			$table->string('filetype')->default('gallery')->comment('featured|gallery');

			$table->string('filename')->nullable();
			$table->text('filepath');

			$table->json('metadata')->nullable();

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
		Schema::dropIfExists('product_galleries');
	}
};
