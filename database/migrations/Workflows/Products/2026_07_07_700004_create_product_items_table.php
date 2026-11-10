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
        Schema::create('product_items', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable()->unique();
            $table->string('product_item_uid')->unique();
            $table->foreignId('category_id')->nullable()->constrained('product_categories')->nullOnDelete();

            $table->string('name');
            $table->text('description')->nullable();

            $table->boolean('status')->default(1);

            $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('last_modifier_id')->nullable()->constrained('users')->nullOnUpdate();
            $table->timestamps();

            $table->comment('This is the parent table of each base-product irrespective of their different variants and sellable versions.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_items');
    }
};
