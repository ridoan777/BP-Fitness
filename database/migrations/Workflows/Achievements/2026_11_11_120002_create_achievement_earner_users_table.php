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
        Schema::create('achievement_earner_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->foreignId('achievement_id')->constrained('achievement_heads')->cascadeOnDelete()->comment('achievement head id');
            $table->integer('points_earned')->default(0);
            $table->timestamp('unlocked_at')->nullable();

            $table->boolean('status')->default(1);
            $table->timestamps();

            $table->unique(['user_id', 'achievement_id', 'unlocked_at'], 'earner_user_achievement_unlocked_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievement_earner_users');
    }
};
