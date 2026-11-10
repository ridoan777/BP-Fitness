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
		Schema::create('workout_galleries', function (Blueprint $table) {
			$table->id();
			$table->foreignId('lesson_id')->constrained('workout_lessons')->cascadeOnDelete();

			$table->string('filetype')->default('gallery')->comment('?|gallery');

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
		Schema::dropIfExists('workout_galleries');
	}
};
