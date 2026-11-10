<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

	public function up(): void
	{
		Schema::create('grocery_lists', function (Blueprint $table) {
			$table->id();

			$table->foreignId('preference_id')->constrained('meal_preferences')->cascadeOnDelete();

			$table->string('meal_plan_uid')->nullable();

			$table->string('name');
			$table->integer('amount')->nullable();
			$table->string('unit')->nullable();

			$table->boolean('isPurchased')->default(0);
			$table->boolean('status')->default(1);

			$table->timestamps();

			$table->index('preference_id');
			$table->index(['preference_id', 'status']);
			$table->index(['preference_id', 'isPurchased']);
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('grocery_lists');
	}
};
