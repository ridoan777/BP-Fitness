<?php

namespace App\Domains\Meals\Enums;

enum MealPlanPhaseEnums: string
{
	case IN_PROGRESS = 'in_progress';
	case COMPLETED = 'completed';
	case CANCELLED = 'cancelled';

	// ----------------- LABEL -----------------
	public function label(): string
	{
		return match($this) {
			self::IN_PROGRESS => 'In Progress',
			self::COMPLETED => 'Completed',
			self::CANCELLED => 'Cancelled',
		};
	}
}