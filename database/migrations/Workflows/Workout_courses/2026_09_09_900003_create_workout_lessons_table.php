<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    //  This is the actual variant that a user buys

    public function up(): void
    {
        Schema::create('workout_lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->nullable()->constrained('workout_courses')->cascadeOnDelete();
            $table->string('slug')->nullable()->unique();
            $table->string('lesson_uid')->unique();

            $table->string('title');

            $table->string('lesson_duration')->nullable()->comment('use value+unit');
            $table->integer('calorie_burns')->nullable();
            $table->integer('sets')->nullable();
            $table->integer('reps')->nullable();

            $table->text('description')->nullable();
            $table->text('instruction')->nullable();

            $table->text('feature_image')->nullable();

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
        Schema::dropIfExists('workout_lessons');
    }
};
