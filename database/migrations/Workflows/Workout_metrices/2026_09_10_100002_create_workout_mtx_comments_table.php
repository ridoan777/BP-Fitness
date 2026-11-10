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
		Schema::create('workout_mtx_comments', function (Blueprint $table) {
			$table->id();
			$table->foreignId('lesson_id')->constrained('workout_lessons')->cascadeOnDelete();
			$table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

			$table->foreignId('reply_to_comment')->nullable()->constrained('workout_mtx_comments')->nullOnDelete();

			$table->text('comment');
			
			$table->boolean('status')->default(1);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('workout_mtx_comments');
	}
};
