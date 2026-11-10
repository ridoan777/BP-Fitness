<?php

namespace App\Enums;

enum CRUDActionEnums: string
{
	case CREATED = 'created';
	case UPDATED = 'updated';
	case ACTIVATED = 'activated';
	case DEACTIVATED = 'deactivated';
	case DELETED = 'deleted';
}
