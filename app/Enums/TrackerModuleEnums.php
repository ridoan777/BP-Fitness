<?php

namespace App\Enums;

enum TrackerModuleEnums: string
{
	case MEAL = 'meal';
	case LESSON = 'lesson';
	case LIKE = 'like';
	case COMMENT = 'comment';
}