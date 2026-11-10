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
        Schema::create('product_variant_items', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable()->unique();
            $table->string('variant_item_uid')->unique();
            $table->foreignId('variant_type_id')->nullable()->constrained('product_variant_types')->nullOnDelete();

            $table->string('name',);
            $table->string('value')->nullable()->comment('e.g., color codes');
            $table->text('description')->nullable();

            $table->boolean('status')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variant_items');
    }
};
