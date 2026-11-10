<?php

namespace App\Domains\Meals\Services;

use App\Helpers\ApiJsonReturnHelper;
use App\Helpers\Auth\OwnershipAuthCheck;
use App\Helpers\Errors\ExceptionHandling;
use App\Helpers\Errors\LoggerAccess;
use App\Models\Workflows\Meals\MealPlan;
use App\Models\Workflows\Meals\MealPreference;
use Illuminate\Http\Request;
use Throwable;

class MealTrackerService
{
   public function __construct(
      protected ?Request $request,
   ) {
   }
   // -----------------------------------------------------------

   public function handle()
   {
      $validated = $this->request->validate([
         'single_meal_plan_id' => 'required|integer|exists:meal_plans,id',
         'isComplete' => 'required|integer|in:0,1',
      ]);

      $USER = $this->request->user();
      $TARGET_MEAL_PLAN = MealPlan::findOrFail((int)$validated['single_meal_plan_id']);
      $TARGET_PREFERENCE = MealPreference::findOrFail((int)$TARGET_MEAL_PLAN->preference_id);

      OwnershipAuthCheck::ownerVsOwner($TARGET_PREFERENCE->user_id, $USER->id);

      $TARGET_MEAL_PLAN->update([
         'isComplete' => (int) $validated['isComplete']
      ]);

      return ApiJsonReturnHelper::handle(true, 200, "Meal-plan tracking record has been changed sucessfully!", $TARGET_MEAL_PLAN);
   }
   // -----------------------------------------------------------

   public function calorieStatus($TARGET_PREFERENCE)
   {
      $completedMeals = MealPlan::preferenceId($TARGET_PREFERENCE->id)->isComplete(1)->get();

      $totalConsumedKcal = $completedMeals->sum(function ($meal) {
         return (int) ($meal?->macro_nutrients['Energy'] ?? $meal?->macro_nutrients['energy'] ?? 0);
      });

      return [
         'total_goal_kcal' => $TARGET_PREFERENCE->total_energy_kcal,
         'total_consumed_kcal' => $totalConsumedKcal,
         'remaining_kcal' => max(0, $TARGET_PREFERENCE->total_energy_kcal - $totalConsumedKcal),
      ];
   }
}