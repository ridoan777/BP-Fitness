<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

	public function up(): void
	{
		Schema::create('meal_preferences', function (Blueprint $table) {
			$table->id();

			$table->string('preference_uid')->unique();
			$table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
			$table->foreignId('subscriber_id')->nullable()->constrained('subscription_app_users')->nullOnDelete();

			$table->unsignedTinyInteger('duration');

			$table->string('phase')->nullable()->comment('null, processing, completed, failed');

			$table->string('goal');
			$table->string('gender');
			$table->unsignedTinyInteger('age');

			$table->float('weight_kg');
			$table->float('height_cm');

			$table->unsignedTinyInteger('workout');

			$table->json('prefer_exercise')->nullable();
			$table->json('nutrition')->nullable();
			$table->json('medical')->nullable();
			
			$table->string('sleep')->nullable();
			
			$table->json('planning')->nullable();
			$table->json('metadata')->nullable();
			$table->string('ai_model')->nullable();

			$table->unsignedInteger('total_energy_kcal')->nullable();

			$table->boolean('status')->default(1);

			$table->timestamps();

			$table->index(['user_id', 'status']);
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('meal_preferences');
	}
};
