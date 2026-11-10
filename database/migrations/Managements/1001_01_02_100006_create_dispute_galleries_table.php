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
        Schema::create('dispute_galleries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('dispute_reports')->cascadeOnDelete();

            $table->string('filetype')->default('attachment')->comment('?|attachment');
            $table->string('filename')->nullable();
            $table->text('filepath')->comment('snapshot file of original');
            $table->json('metadata')->nullable();

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
        Schema::dropIfExists('dispute_galleries');
    }
};
