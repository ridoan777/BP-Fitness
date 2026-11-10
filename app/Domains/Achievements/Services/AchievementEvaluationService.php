<?php

namespace App\Domains\Achievements\Services;

use App\Domains\Achievements\Models\{AchievementRecord, AchievementHead};

class AchievementEvaluationService
{
	public function handle(int $userId)
	{
		// ------------------------------ LOGIN STREAK ------------------------------
		$loginAchievementService = new UserLoginStreakService();
		$loginAchievementService->countStreak($userId);
		// ------------------------------ LOGIN STREAK ------------------------------

		// ------------------------------ LESSON COMPLETION ------------------------------
		$lessonAchievementService = new UnlockLessonAchievement();
		$lessonAchievementService->handle($userId);
		// ------------------------------ LESSON COMPLETION ------------------------------

		// ------------------------------ COMMUNITY MSSG ------------------------------
		$forumMssgAchievement = new CommunityMessageAchievement();
		$forumMssgAchievement->handle($userId);
		// ------------------------------ COMMUNITY MSSG ------------------------------

		// ------------------------------ E-STORE PRODUCT ------------------------------
		$storeAchievementService = new EStoreAchievement();
		$storeAchievementService->product($userId);
		$storeAchievementService->purchase($userId);
		// ------------------------------ E-STORE PRODUCT ------------------------------

		$achievements = AchievementRecord::with([
			'earnerRelatonWith_AchievementHead:id,title,type,required_value,points,status',
		])->where('user_id', $userId)->status(1)->get();

		if ($achievements->isEmpty()){
			return null;
		}

		$achievements->transform(function ($item) {
			$item->achievement_details = $item->earnerRelatonWith_AchievementHead;
			$item->makeHidden(['created_at', 'updated_at']);
			$item->unsetRelation('earnerRelatonWith_AchievementHead');
			return $item;
		});

		return $achievements;
	}
}

/*
	// ------------------------------ ACHIEVEMENT UPDATE ------------------------------
	$loginAchievementService = new UserLoginStreakService();
	$loginAchievementService->handle($validated, $request, $userId);
	// ------------------------------ ACHIEVEMENT UPDATE ------------------------------
*/
