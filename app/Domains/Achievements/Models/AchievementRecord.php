<?php

namespace App\Domains\Achievements\Models;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\{Table, Fillable};

#[Table('achievement_earner_users')]
#[Fillable(['user_id', 'achievement_id', 'points_earned', 'unlocked_at', 'status'])]

class AchievementRecord extends Model
{
	protected function casts(): array
	{
		return [
			'user_id' => 'integer',
			'achievement_id' => 'integer',
			'unlocked_at' => 'datetime',
			'points_earned' => 'integer',
			'status' => 'integer',
		];
	}

	// ----------------- RELATIONSHIP -----------------
	public function earnerRelatonWith_User()
	{
		return $this->belongsTo(User::class, 'user_id', 'id');
	}

	public function earnerRelatonWith_AchievementHead()
	{
		return $this->belongsTo(AchievementHead::class, 'achievement_id', 'id');
	}
	// ----------------- RELATIONSHIP -----------------


	// ----------------- QUERY SCOPING -----------------
	public function scopeUserId($query, int $user_id)
	{
		return $query->where('user_id', $user_id);
	}
	
	public function scopeAchievementId($query, int $achievement_id)
	{
		return $query->where('achievement_id', $achievement_id);
	}

	public function scopeStatus($query, int $status)
	{
		return $query->where('status', $status);
	}
	// ----------------- QUERY SCOPING -----------------
}
