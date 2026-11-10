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
		Schema::create('journeys', function (Blueprint $table) {
			$table->id();
			$table->string('journey_uid')->unique();
			$table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

			$table->text('text')->nullable();
			$table->string('image')->nullable();
			
			$table->boolean('status')->default(1);
			$table->timestamps();

			$table->index(['user_id', 'status']);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('journeys');
	}
};
