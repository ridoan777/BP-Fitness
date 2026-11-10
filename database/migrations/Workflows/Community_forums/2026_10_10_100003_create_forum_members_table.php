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
        Schema::create('forum_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('forum_groups')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->string('member_uid')->unique();

            $table->boolean('is_active')->default(1)->comment('membership status');
            
            $table->boolean('status')->default(1);

            $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete()->comment('user table');
            $table->foreignId('last_modifier_id')->nullable()->constrained('users')->nullOnUpdate()->comment('user table');

            $table->timestamps();

            $table->unique(['group_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_members');
    }
};
