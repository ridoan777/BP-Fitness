<?php

namespace App\Domains\Meals\Actions;

use App\Domains\Billings\Subscriptions\Models\SubscriptionAppUser;
use App\Models\Workflows\Meals\MealPreference;

class MealPreferenceAction
{
	public function execute(array $validated, $PREFERENCE_UID, $USER)
	{
		$subscriber = SubscriptionAppUser::userId($USER->id)->active()->subStatus('running', 'on-trial')->latest()->first();

		$mealPreference = MealPreference::updateOrCreate(
			[
				'preference_uid' => $PREFERENCE_UID,
			],
			[
				'user_id' => $USER->id,
				'subscriber_id' => $subscriber?->id ?? null,

				'duration' => $validated['duration'],

				'goal' => $validated['goal'],
				'gender' => $validated['gender'],
				'age' => $validated['age'],

				'weight_kg' => $validated['weight_kg'],
				'height_cm' => $validated['height_cm'],

				'workout' => $validated['workout'],

				'prefer_exercise' => $validated['prefer_exercise'] ?? null,
				'sleep' => $validated['sleep'] ?? null,

				'nutrition' => $validated['nutrition'] ?? null,
				'medical' => $validated['medical'] ?? null,

				'status' => $validated['status'] ?? 1,
			]
		);

		return $mealPreference;
	}
}

/*
USER CASE:
	use App\Actions\Billings\SubscriptionTierSaveAction;
	public function store(TierValidationRequest $request, MealPreferenceAction $action)
	{
		... ... ...
		$mealPreference = $action->execute($validated, $PREFERENCE_UID, $USER);
	}
*/