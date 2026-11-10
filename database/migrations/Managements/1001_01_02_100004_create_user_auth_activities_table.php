<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// route: routes\System\Auth_activities\api.auth_activities.php

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('user_auth_activities', function (Blueprint $table) {
			$table->id();

			$table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
			$table->foreignId('personal_access_token_id')->nullable()->constrained('personal_access_tokens')->nullOnDelete();

			$table->string('email')->nullable()->comment('snapshot');
			$table->string('user_role')->nullable()->comment('snapshot');

			$table->string('activity_type')->comment('daily_active|login|login_revoked|other');
			$table->date('activity_date')->nullable()->comment('user-local date for daily_active');
			$table->string('timezone')->nullable();

			$table->string('channel')->nullable()->comment('api|web');
			$table->string('guard')->nullable()->comment('e.g. sanctum, web');

			$table->string('ip_address', 45)->nullable()->comment('e.g. 127.0.0.1');
			$table->text('user_agent')->nullable()->comment('e.g. Mozilla/5.0');

			$table->string('device_id')->nullable()->comment('e.g. 8f4c2a10-6b11-4e7e-9f21-abc123def456');
			$table->string('device_name')->nullable()->comment('e.g. iPhone 15 Pro');
			$table->string('platform')->nullable()->comment('e.g. ios, android, web');
			$table->string('app_version')->nullable()->comment('e.g. 1.0.0');

			$table->text('system_note')->nullable();

			$table->boolean('status')->default(1);
			$table->timestamps();

			$table->unique(['user_id', 'activity_type', 'activity_date'], 'user_auth_daily_activity_unique');
			$table->index(['user_id', 'activity_date']);
			$table->index(['activity_type', 'activity_date']);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('user_auth_activities');
	}
};