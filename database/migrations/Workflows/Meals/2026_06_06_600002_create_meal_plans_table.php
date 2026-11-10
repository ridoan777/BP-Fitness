<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

	public function up(): void
	{
		Schema::create('meal_plans', function (Blueprint $table) {
			$table->id();
			$table->string('meal_uid');

			$table->foreignId('preference_id')->constrained('meal_preferences')->cascadeOnDelete();

			$table->integer('iteration_no')->default(1)->comment("How many times meal was generated");

			$table->unsignedTinyInteger('duration');

			$table->unsignedTinyInteger('day');

			$table->string('period')->comment("breakfast|lunch|dinner"); // breakfast, lunch, dinner

			$table->string('title');
			$table->text('image_prompt')->nullable();
			$table->text('serving_details');

			$table->json('ingredients');
			$table->json('macro_nutrients');
			$table->json('grocery_list');

			$table->boolean('isComplete')->default(0);
			$table->boolean('status')->default(1);

			$table->timestamps();

			$table->index(['preference_id', 'day']);
			$table->index(['preference_id', 'day', 'period']);
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('meal_plans');
	}
};
