<?php

namespace App\Domains\Achievements\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\{Table, Fillable, Appends};
use Illuminate\Support\Facades\Storage;

#[Table('achievement_badges')]
#[Fillable(['name', 'points_required', 'image', 'status'])]
#[Appends(['image_url'])]

class AchievementBadge extends Model
{
	protected function casts(): array
	{
		return [
			'points_required' => 'integer',
			'status' => 'integer',
		];
	}

	// ----------------- QUERY SCOPING -----------------
	public function scopePointsRequired($query, int $points_required)
	{
		return $query->where('points_required', $points_required);
	}
	
	public function scopeStatus($query, int $status)
	{
		return $query->where('status', $status);
	}
	// ----------------- QUERY SCOPING -----------------

	// ----------------- URL FRIENDLY IMAGE -----------------
	public function getImageUrlAttribute()
	{
		if ($this->image) {
			return Storage::url($this->image);
		}

		return null;
	}
	// ----------------- URL FRIENDLY IMAGE -----------------
}
