<?php

namespace App\Domains\Billings\Shared\Enums;

// this enum is used for payment gateway provider values.

enum GatewayProviderEnums: string
{
	case STRIPE = 'stripe';
	case REVENUECAT = 'revenuecat';

	public function label(): string
	{
		return match ($this) {
			self::STRIPE => 'Stripe',
			self::REVENUECAT => 'RevenueCat',
		};
	}

	public static function values(): array
	{
		return array_map(fn($case) => $case->value, self::cases());
	}

	public static function options(): array
	{
		return array_map(fn($case) => ['value' => $case->value, 'label' => $case->label()], self::cases());
	}

	public static function default(self $provider = self::REVENUECAT): string
	{
		return $provider->value;
	}
}
/*
	USE CASE-1: migration/default:
		$table->string('provider')->default(GatewayProviderEnums::default())->comment(implode('|', GatewayProviderEnums::values()));

	USE CASE-2: dropdown:
		$gatewayProviders = GatewayProviderEnums::options();

	USE CASE-3: validation:
		Rule::in(GatewayProviderEnums::values())

	USE CASE-4: direct value:
		GatewayProviderEnums::REVENUECAT->value
*/