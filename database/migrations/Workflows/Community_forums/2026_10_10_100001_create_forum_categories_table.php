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
        Schema::create('forum_categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable()->unique();
            $table->string('category_uid')->unique();

            $table->string('name',);
            $table->text('description')->nullable();
            
            $table->boolean('status')->default(1);
            
            $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete()->comment('user table');
            $table->foreignId('last_modifier_id')->nullable()->constrained('users')->nullOnUpdate()->comment('user table');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_categories');
    }
};
