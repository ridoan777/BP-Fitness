<?php

namespace App\Domains\Achievements\Controllers;

use App\Domains\Achievements\Models\{AchievementBadge, AchievementRecord, AchievementHead};
use App\Domains\Achievements\Services\{AchievementEvaluationService};
use App\Helpers\ApiJsonReturnHelper;
use App\Helpers\Errors\ExceptionHandling;
use App\Models\Users\UserAuthActivity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Throwable;

class ApiAchievementController extends Controller
{
	public function challenges(Request $request)
	{
		try {
			$challenges = AchievementHead::status(1)->orderBy('type')->paginate(20);

			return ApiJsonReturnHelper::handle(true, 200, "All challenges have been fetched sucessfully!", $challenges);

		} catch (Throwable $e) {
			return ExceptionHandling::handle('fetching users', $e);
		}
	}
	// -----------------------------------------------------------

	public function view(Request $request, AchievementEvaluationService $achievementEvaluation)
	{
		try {
			$USER = $request->user();

			// ------------------- EVALUATION -------------------
			$userAchievements = $achievementEvaluation->handle($USER->id);

			$achievementQuery = AchievementRecord::with([
				'earnerRelatonWith_AchievementHead:id,title,type,required_value,points,status',
			])->userId($USER->id)->status(1);
			// ------------------- EVALUATION -------------------

			// ------------------- POINTS-------------------
			$totalPoints = $userAchievements?->sum('points_earned') ?? 0;

			$currentBadge = AchievementBadge::select(['id', 'name', 'points_required', 'image'])->status(1)->where('points_required', '<=', $totalPoints)->orderByDesc('points_required')->first();

			$nextBadge = AchievementBadge::select(['id', 'name', 'points_required', 'image'])->status(1)->where('points_required', '>', $totalPoints)->orderBy('points_required')->first();
			// ------------------- POINTS-------------------

			$userAchievements = $achievementQuery->orderByDesc('unlocked_at')->paginate(20);

			$userAchievements->through(function ($achievement) {
				$achievement->achievement_details = $achievement->earnerRelatonWith_AchievementHead;
				$achievement->unsetRelation('earnerRelatonWith_AchievementHead');
				$achievement->makeHidden(['created_at', 'updated_at']);

				return $achievement;
			});

			return ApiJsonReturnHelper::handle(true, 200, "Achievements have been fetched successfully!", [
				'total_challenges_completed' => $userAchievements ? $userAchievements->count() : 0,
				'point_system' => [
					'total_point' => $totalPoints,
					'current_badge' => $currentBadge,
					'next_badge' => $nextBadge,
				],
				'achievement_list' => $userAchievements
			]);
		} catch (Throwable $e) {
			return ExceptionHandling::handle('fetching achievements', $e);
		}
	}
	// -----------------------------------------------------------

	public function loginStreaks(Request $request, AchievementEvaluationService $achievementEvaluation)
	{
		try {
			$USER = $request->user();

			$userAchievements = $achievementEvaluation->handle($USER->id);

			$activityDates = UserAuthActivity::userId($USER->id)->status(1)->activityType('login', 'daily_active')->select('activity_date')->distinct()->orderByDesc('activity_date')->paginate(60);

			$activityDates->getCollection()->transform(fn($activity) => $activity->activity_date->toDateString());

			return ApiJsonReturnHelper::handle(true, 200, "All user login activity records have been fetched successfully!", [
				'total_challenges_completed' => $userAchievements ? $userAchievements->count() : 0,
				'login_activities' => $activityDates,
			]);
		} catch (Throwable $e) {
			return ExceptionHandling::handle('fetching user login activity records', $e);
		}
	}
}
