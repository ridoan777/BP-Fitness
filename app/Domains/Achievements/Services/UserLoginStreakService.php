<?php

namespace App\Domains\Achievements\Services;

use App\Domains\Achievements\Models\{AchievementRecord, AchievementHead};
use App\Models\Users\UserAuthActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserLoginStreakService
{
	public function handle(array $validated, Request $request, $USER)
	{
		if (!$USER)
			return null;

		$currentToken = $USER->currentAccessToken();
		$timeZone = $validated['timezone'] ?? $USER->timezone ?? "America/New_York";

		$activityDate = now('UTC')->timezone($timeZone)->toDateString();

		$activity = UserAuthActivity::updateOrCreate(
			[
				'user_id' => $USER->id,
				'activity_date' => $activityDate,
			],
			[
				'activity_type' => $validated['activity_type'],
				'personal_access_token_id' => ($request->bearerToken() && $currentToken instanceof PersonalAccessToken) ? $currentToken->id : null,
				'email' => $USER->email,
				'user_role' => $USER->user_role ?? null,
				'timezone' => $timeZone,
				'channel' => $request->bearerToken() ? 'api' : 'web',
				'guard' => $request->bearerToken() ? 'sanctum' : 'web',
				'ip_address' => $request->ip() ?? null,
				'user_agent' => $request->userAgent() ?? null,
				'device_id' => $validated['device_id'] ?? null,
				'device_name' => $validated['device_name'] ?? null,
				'platform' => $validated['platform'] ?? null,
				'app_version' => $validated['app_version'] ?? null,
				'status' => 1,
			]
		);

		return $activity;
	}

	public function countStreak(int $userId)
	{
		$activityDates = UserAuthActivity::userId($userId)
			->status(1)
			->activityType('login', 'daily_active')
			->select('activity_date')
			->distinct()
			->orderBy('activity_date')
			->pluck('activity_date')
			->map(fn($date) => Carbon::parse($date)->startOfDay())
			->values();

		if ($activityDates->isEmpty())
			return [];

		$threeDayAchievement = AchievementHead::type('auth_activity')->where('required_value', 3)->status(1)->first();
		$sevenDayAchievement = AchievementHead::type('auth_activity')->where('required_value', 7)->status(1)->first();

		$earnedAchievements = [];
		$carry = [];
		$previousDate = null;

		$dateArray = $activityDates->map(fn($activityDate) => $activityDate->toDateString());

		// dd($dateArray);

		foreach ($activityDates as $activityDate) {
			if ($previousDate && !$previousDate->copy()->addDay()->isSameDay($activityDate)) {
				if (count($carry) >= 3 && $threeDayAchievement) {
					$earnedAchievements[] = AchievementRecord::updateOrCreate(
						[
							'user_id' => $userId,
							'achievement_id' => $threeDayAchievement->id,
							'unlocked_at' => $carry[2]->copy()->startOfDay(),
						],
						[
							'points_earned' => $threeDayAchievement->points ?? 0,
							'status' => 1,
						]
					);
				}

				$carry = [];
			}

			$carry[] = $activityDate;
			$previousDate = $activityDate;

			if (count($carry) === 7) {
				if ($sevenDayAchievement) {
					$earnedAchievements[] = AchievementRecord::updateOrCreate(
						[
							'user_id' => $userId,
							'achievement_id' => $sevenDayAchievement->id,
							'unlocked_at' => $carry[6]->copy()->startOfDay(),
						],
						[
							'points_earned' => $sevenDayAchievement->points ?? 0,
							'status' => 1,
						]
					);
				}

				$carry = [];
				$previousDate = null;
			}
		}

		if (count($carry) >= 3 && $threeDayAchievement) {
			$earnedAchievements[] = AchievementRecord::updateOrCreate(
				[
					'user_id' => $userId,
					'achievement_id' => $threeDayAchievement->id,
					'unlocked_at' => $carry[2]->copy()->startOfDay(),
				],
				[
					'points_earned' => $threeDayAchievement->points ?? 0,
					'status' => 1,
				]
			);
		}

		return [
			'login_details' => $dateArray,
			'login_streaks' => $earnedAchievements
		];
	}
}

/*
	// ------------------------------ ACHIEVEMENT UPDATE ------------------------------
	$loginAchievementService = new UserLoginStreakService();
	$loginAchievementService->handle($validated, $request, $USER);
	// ------------------------------ ACHIEVEMENT UPDATE ------------------------------
*/
