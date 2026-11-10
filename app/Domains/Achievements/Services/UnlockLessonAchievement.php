<?php

namespace App\Domains\Achievements\Services;

use App\Domains\Achievements\Models\{AchievementHead, AchievementRecord};
use App\Domains\Workout_courses\Models\LessonCompletion;

class UnlockLessonAchievement
{
	public function handle(int $userId)
	{
		$completedLessonCount = LessonCompletion::userId($userId)->count();

		$achievements = AchievementHead::type('lesson_completion')->where('required_value', '<=', $completedLessonCount)->status(1)->get();

		foreach ($achievements as $achievement) {
			AchievementRecord::updateOrCreate([
				'user_id' => $userId,
				'achievement_id' => $achievement->id,
			], [
				'points_earned' => $achievement->points ?? 0,
				'unlocked_at' => now(),
			]);
		}

		$userAchievements = AchievementRecord::userId($userId)->status(1)->get();

		return $userAchievements;
	}
}
/*
	// ------------------------------ ACHIEVEMENT UPDATE ------------------------------
	$lessonAchievementService = new UnlockLessonAchievement();
	$lessonAchievementService->handle($USER->id);
	// ------------------------------ ACHIEVEMENT UPDATE ------------------------------
*/
