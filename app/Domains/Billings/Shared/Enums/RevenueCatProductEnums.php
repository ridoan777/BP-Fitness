<?php

namespace App\Domains\Billings\Shared\Enums;

// this enum maps RevenueCat product identifiers to internal subscription plans.

enum RevenueCatProductEnums: string
{
	case MONTHLY = 'monthly';
	case BI_ANNUALLY = 'bi_annually';
	case YEARLY = 'yearly';

	public function label(): string
	{
		return match ($this) {
			self::MONTHLY => 'Monthly',
			self::BI_ANNUALLY => 'Bi-annually',
			self::YEARLY => 'Yearly',
		};
	}

	public function planName(): string
	{
		return $this->value;
	}

	public function identifiers(): array
	{
		return match ($this) {
			self::MONTHLY => ['bf_premium_month:revenue2026', 'rc_2026_1m'],
			self::BI_ANNUALLY => ['rc_100_6m'],
			self::YEARLY => ['bf_premium_year:revenue2026', 'rc_2026_1y'],
		};
	}

	public function details(?string $matchedProductId = null): array
	{
		return ['name' => $this->planName(), 'identifier' => $matchedProductId ?? $this->identifiers()[0]];
	}

	public static function resolve(?string $productId): ?self
	{
		if (!$productId)
			return null;

		$productId = trim($productId);

		foreach (self::cases() as $case) {
			if (in_array($productId, $case->identifiers(), true))
				return $case;
		}

		return null;
	}

	public static function values(): array
	{
		return array_merge(...array_map(fn($case) => $case->identifiers(), self::cases()));
	}

	public static function options(): array
	{
		return array_map(fn($case) => ['value' => $case->identifiers()[0], 'label' => $case->label()], self::cases());
	}
}