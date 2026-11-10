<?php

namespace App\Domains\Billings\Shared\Enums;

enum TrxPhaseEnums: string
{
	case INITIATED = 'initiated';
	case COMPLETED = 'completed';
	case SETTLED = 'settled';
	case CANCELLED = 'cancelled';
	case FAILED = 'failed';

	// ----------------- LABEL -----------------
	public function label(): string
	{
		return match ($this) {
			self::INITIATED => 'Initiated',
			self::COMPLETED => 'Completed',
			self::SETTLED => 'Settled',
			self::CANCELLED => 'Cancelled',
			self::FAILED => 'Failed',
		};
	}

	// ----------------- VALUES -----------------
	public static function values(): array
	{
		return array_map(fn($case) => $case->value, self::cases());
	}

	// ----------------- OPTIONS -----------------
	public static function options(): array
	{
		return array_map(fn($case) => ['value' => $case->value, 'label' => $case->label()], self::cases());
	}

	// ----------------- GRANTED -----------------
	public static function accessGrantedValues(): array
	{
		return [
			self::COMPLETED->value,
			self::SETTLED->value,
		];
	}

	// ----------------- NOT-GRANTED -----------------
	public static function accessDeniedValues(): array
	{
		return [
			self::INITIATED->value,
			self::CANCELLED->value,
			self::FAILED->value,
		];
	}
	// ----------------- DEFAULT -----------------
	public static function default(self $phase = self::INITIATED): string
	{
		return $phase->value;
	}
}
/*
	USE CASE-1: value:
		$table->string('trx_phase')->default(TrxPhaseEnums::INITIATED->value);

	USE CASE-2: dropdown:
		$phases = TrxPhaseEnums::options();

	USE CASE-3: default:
		TrxPhaseEnums::default()

	USE CASE-4: granted:
		TrxPhaseEnums::accessGrantedValues()
*/
