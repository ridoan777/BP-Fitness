<?php

namespace App\Domains\Achievements\Services;

use App\Domains\Achievements\Models\{AchievementRecord, AchievementHead};
use App\Domains\Community_forums\Models\ForumMessage;

class CommunityMessageAchievement
{
	public function handle(int $userId)
	{
		$communityMessageCount = ForumMessage::userId($userId)->status(1)->count();

		$achievements = AchievementHead::type('community_engagement')->where('required_value', '<=', $communityMessageCount)->status(1)->get();

		$forums = [];
		foreach ($achievements as $achievement) {
			$forums[] = AchievementRecord::updateOrCreate([
				'user_id' => $userId,
				'achievement_id' => $achievement->id,
			], [
				'points_earned' => $achievement->points ?? 0,
				'unlocked_at' => now(),
			]);
		}

		return $forums;
	}
}

/*
	// ------------------------------ ACHIEVEMENT UPDATE ------------------------------
	$forumMssgAchievement = new CommunityMessageAchievement();
	$forumMssgAchievement->handle($USER->id);
	// ------------------------------ ACHIEVEMENT UPDATE ------------------------------
*/
