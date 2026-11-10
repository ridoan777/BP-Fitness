<?php

namespace App\Domains\Meals\Enums;

enum PreferenceCRUDEnums: string
{
	case CREATED = 'created';
	case UPDATED = 'updated';
	case DELETED = 'deleted';

	// ----------------- LABEL -----------------
	public function label(): string
	{
		return match($this) {
			self::CREATED => 'created',
			self::UPDATED => 'updated',
			self::DELETED => 'deleted',
		};
	}
}