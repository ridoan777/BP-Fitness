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
        Schema::create('achievement_heads', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('type')->default('community_engagement');
            $table->unsignedInteger('required_value');
            $table->integer('points')->nullable();
            
            $table->boolean('status')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievement_heads');
    }
};
