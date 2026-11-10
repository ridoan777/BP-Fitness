<?php

namespace App\Domains\Billings\Shared\Enums;

// this enum is used for subscription_app_users.sub_status values.

enum SubscriptionStatusEnums: string
{
	case REQUESTED = 'requested';
	case RUNNING = 'running';
	case SCHEDULED = 'scheduled';
	case PAUSED = 'paused';
	case NON_RENEWING = 'non_renewing';
	case BILLING_ISSUE = 'billing_issue';
	case PRODUCT_CHANGE = 'product_change';
	case CANCELLED = 'cancelled';
	case EXPIRED = 'expired';

	public function label(): string
	{
		return match ($this) {
			self::REQUESTED => 'Requested',
			self::RUNNING => 'Running',
			self::SCHEDULED => 'Scheduled',
			self::PAUSED => 'Paused',
			self::NON_RENEWING => 'Non Renewing',
			self::BILLING_ISSUE => 'Billing Issue',
			self::PRODUCT_CHANGE => 'Product Change',
			self::CANCELLED => 'Cancelled',
			self::EXPIRED => 'Expired',
		};
	}

	public static function accessGrantedValues(): array
	{
		return [
			self::RUNNING->value,
			self::SCHEDULED->value,
			self::PAUSED->value,
			self::NON_RENEWING->value,
			self::BILLING_ISSUE->value,
			self::CANCELLED->value,
			self::PRODUCT_CHANGE->value,
		];
	}

	public static function accessDeniedValues(): array
	{
		return [
			self::REQUESTED->value,
			self::EXPIRED->value,
		];
	}

	public static function values(): array
	{
		return array_map(fn($case) => $case->value, self::cases());
	}

	public static function options(): array
	{
		return array_map(fn($case) => ['value' => $case->value, 'label' => $case->label()], self::cases());
	}

	public static function default(self $subscriptionStatus = self::REQUESTED): string
	{
		return $subscriptionStatus->value;
	}
}
/*
	USE CASE-1: migration/default:
		$table->string('sub_status')->default(SubscriptionStatusEnums::default())->comment(implode('|', SubscriptionStatusEnums::values()));

	USE CASE-2: dropdown:
		$subscriptionStatuses = SubscriptionStatusEnums::options();

	USE CASE-3: validation:
		Rule::in(SubscriptionStatusEnums::values())

	USE CASE-4: direct value:
		SubscriptionStatusEnums::RUNNING->value
*/
