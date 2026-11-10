<?php

namespace App\Http\Controllers\Users;

use App\Domains\Achievements\Services\UserLoginStreakService;
use App\Helpers\ApiJsonReturnHelper;
use App\Helpers\Errors\ExceptionHandling;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Throwable;

class UserAuthActivityController extends Controller
{
	public function store(Request $request, UserLoginStreakService $loginSteak)
	{
		try {
			$validated = $request->validate([
				'activity_type' => 'required|string|in:daily_active,login,login_revoked,other',
				'timezone' => 'required|string|timezone',
				'device_id' => 'nullable|string|max:255',
				'device_name' => 'nullable|string|max:255',
				'platform' => 'nullable|string|max:100',
				'app_version' => 'nullable|string|max:100',
			]);

			$USER = $request->user();
			
			$activity = $loginSteak->handle($validated, $request, $USER);

			return ApiJsonReturnHelper::handle(true, 200, "User activity has been recorded successfully!", $activity);
		} catch (Throwable $e) {
			return ExceptionHandling::handle('recording user activity', $e);
		}
	}
}
