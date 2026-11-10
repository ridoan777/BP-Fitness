<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('workout_mtx_reactions', function (Blueprint $table) {
			$table->id();
			$table->foreignId('lesson_id')->constrained('workout_lessons')->cascadeOnDelete();
			$table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

			$table->boolean('is_liked')->default(1);
			$table->integer('last_like_count')->nullable();
			
			$table->boolean('status')->default(1);
			$table->timestamps();

			$table->unique(['lesson_id', 'user_id']);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('workout_mtx_reactions');
	}
};
