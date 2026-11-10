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
        Schema::create('my_stats', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable()->unique();
            $table->string('stat_uid')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            
            $table->string('property')->comment('weight,height');
            $table->string('unit')->default("N/A")->comment('kg,ft');

            $table->decimal('before_value')->nullable();
            $table->decimal('after_value')->nullable();

            $table->text('before_file')->nullable();
            $table->text('after_file')->nullable();
            
            $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('last_modifier_id')->nullable()->constrained('users')->nullOnUpdate();
            $table->boolean('status')->default(1);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('my_stats');
    }
};
