<?php

namespace App\Domains\Achievements\Services;

use App\Domains\Achievements\Models\{AchievementRecord, AchievementHead};
use App\Domains\Billings\Shared\Enums\PaymentStatusEnums;
use App\Domains\E_Store\Shoppings\Enums\OrderStatusEnums;
use App\Domains\E_Store\Shoppings\Models\{ProductOrder, ProductOrderItem};

class EStoreAchievement
{
	public function product(int $userId)
	{
		$productCount = ProductOrderItem::whereHas('itemRelatingBackTo_Order', function ($query) use ($userId) {
			$query->customer($userId)->paymentStatus(PaymentStatusEnums::PAID->value)
				->whereIn('order_status', OrderStatusEnums::accessGrantedValues());
		})->sum('quantity');

		$achievements = AchievementHead::type('e_store_product')->where('required_value', '<=', (int)$productCount)->status(1)->get();

		$products = [];
		foreach ($achievements as $achievement) {
			$products[] = AchievementRecord::updateOrCreate([
				'user_id' => $userId,
				'achievement_id' => $achievement->id,
			], [
				'points_earned' => $achievement->points ?? 0,
				'unlocked_at' => now(),
			]);
		}

		return $products;
	}

	public function purchase(int $userId)
	{
		$purchaseAmount = (int)round(ProductOrder::customer($userId)->paymentStatus(PaymentStatusEnums::PAID->value)->whereIn('order_status', OrderStatusEnums::accessGrantedValues())->sum('paid_amount') / 100);

		$achievements = AchievementHead::type('e_store_purchase')->where('required_value', '<=', $purchaseAmount)->status(1)->get();

		$purchases = [];
		foreach ($achievements as $achievement) {
			$purchases[] = AchievementRecord::updateOrCreate([
				'user_id' => $userId,
				'achievement_id' => $achievement->id,
			], [
				'points_earned' => $achievement->points ?? 0,
				'unlocked_at' => now(),
			]);
		}

		return $purchases;
	}
}
/*
	// ------------------------------ ACHIEVEMENT UPDATE ------------------------------
	$storeAchievementService = new EStoreAchievement();
	$storeAchievementService->product($USER->id);
	$storeAchievementService->purchase($USER->id);
	// ------------------------------ ACHIEVEMENT UPDATE ------------------------------
*/
