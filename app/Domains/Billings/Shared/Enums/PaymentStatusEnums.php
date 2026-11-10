<?php

namespace App\Domains\Billings\Shared\Enums;

// we are retrieving payment_status from Stripe. This enum is working only as a fallback or payment amount mismatch.

enum PaymentStatusEnums: string
{
	case UNPAID = 'unpaid';
	case PAID = 'paid';
	case PENDING = 'pending';
	case FAILED = 'failed';
	case REFUNDED = 'refunded';
	case PARTIALLY_REFUNDED = 'partially_refunded';

	public function label(): string
	{
		return match ($this) {
			self::UNPAID => 'Unpaid',
			self::PAID => 'Paid',
			self::PENDING => 'Pending',
			self::FAILED => 'Failed',
			self::REFUNDED => 'Refunded',
			self::PARTIALLY_REFUNDED => 'Partially Refunded',
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

	public static function accessGrantedValues(): array
	{
		return [
			self::PAID->value,
		];
	}

	public static function accessDeniedValues(): array
	{
		return [
			self::UNPAID->value,
			self::PENDING->value,
			self::FAILED->value,
			self::REFUNDED->value,
			self::PARTIALLY_REFUNDED->value,
		];
	}

	public static function default(self $paymentStatus = self::UNPAID): string
	{
		return $paymentStatus->value;
	}
}
/*
	USE CASE-1: migration/default:
		$table->string('payment_status')->default(PaymentStatusEnums::default())->comment(implode('|', PaymentStatusEnums::values()));

	USE CASE-2: dropdown:
		$paymentStatuses = PaymentStatusEnums::options();

	USE CASE-3: validation:
		Rule::in(PaymentStatusEnums::values())

	USE CASE-4: direct value:
		PaymentStatusEnums::PAID->value
	
	USE CASE-5: granted:
		PaymentStatusEnums::accessGrantedValues()
*/
