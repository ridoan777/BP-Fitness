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
        Schema::create('workout_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('workout_categories')->cascadeOnDelete();

            $table->string('slug')->nullable()->unique();
            $table->string('course_uid')->unique();
            $table->boolean('is_premium')->default(1);

            $table->string('title');
            
            $table->string('course_duration')->nullable()->comment('use value+unit');
            $table->text('description')->nullable();

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
        Schema::dropIfExists('workout_courses');
    }
};
