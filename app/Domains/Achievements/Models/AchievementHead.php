<?php

namespace App\Domains\Achievements\Models;

use App\Domains\Achievements\Enums\AchievementTypeEnums;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\{Table, Fillable, Hidden};

#[Table('achievement_heads')]
#[Fillable(['title', 'type', 'required_value', 'points', 'status'])]
#[Hidden(['created_at', 'updated_at'])]

class AchievementHead extends Model
{
	protected function casts(): array
	{
		return [
			'type' => AchievementTypeEnums::class,
			'required_value' => 'integer',
			'points' => 'integer',
			'status' => 'integer',
		];
	}

	// ----------------- QUERY SCOPING -----------------
	public function scopeType($query, string $type)
	{
		return $query->where('type', $type);
	}
	
	public function scopeStatus($query, int $status)
	{
		return $query->where('status', $status);
	}
	// ----------------- QUERY SCOPING -----------------
}
