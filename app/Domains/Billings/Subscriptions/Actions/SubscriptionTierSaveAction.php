<?php

namespace App\Domains\Billings\Subscriptions\Actions;

use App\Helpers\FileManagement;
use App\Domains\Billings\Subscriptions\Models\SubscriptionTier;
use App\Models\Users\User;

class SubscriptionTierSaveAction
{
	public function execute(array $validated, ?SubscriptionTier $existingTier = null, $TIER_UID, ?User $USER = null, ?bool $isNewRecord = false): SubscriptionTier
	{
		$DISK_FOLDER = config('filesystems.default');
		// ------------ FILE HANDLING ------------
		$IMAGE_FOLDER = 'Billings/Subscriptions/tiers/';

		if (isset($validated['image']) || (isset($validated['remove_image']) && $validated['remove_image'])) {
			$validated['image'] = FileManagement::handleFile(
				$validated['image'] ?? null,
				$TIER_UID,
				$existingTier->image ?? null,
				$IMAGE_FOLDER,
				$DISK_FOLDER,
				(bool)($validated['remove_image'] ?? false)
			);
		} else {
			$validated['image'] = ['path' => $existingTier->image ?? null];
		}
		// ------------ FILE HANDLING ------------

		return SubscriptionTier::updateOrCreate(
			['id' => $validated['id'] ?? null],
			[
				'tier_uid' => $TIER_UID,
				'platform' => $validated['platform'],

				'name' => $validated['name'],
				'duration' => $validated['duration'] ?? null,

				'starting_price' => $validated['starting_price'],
				'final_price' => $validated['final_price'],

				// 'slogan' => $validated['slogan'] ?? null,
				// 'price_label' => $validated['price_label'] ?? null,

				'benefits' => $validated['benefits'] ?? null,
				'description' => $validated['description'] ?? null,

				'google_product_id' => $validated['google_product_id'] ?? null,
				'apple_product_id' => $validated['apple_product_id'] ?? null,
				'google_entitlement' => $validated['google_entitlement'] ?? null,
				'apple_entitlement' => $validated['apple_entitlement'] ?? null,
				'google_rest_api_id' => $validated['google_rest_api_id'] ?? null,
				'apple_rest_api_id' => $validated['apple_rest_api_id'] ?? null,

				'stripe_product_id' => $validated['stripe_product_id'] ?? null,
				'stripe_price_id' => $validated['stripe_price_id'] ?? null,

				'image' => $validated['image']['path'] ?? null,

				'status' => $validated['status'] ?? 1,

				'creator_id' => $isNewRecord ? $USER?->id : ($existingTier?->creator_id ?? null),
				'last_modifier_id' => $isNewRecord ? null : $USER?->id,
			]
		);
	}
}
