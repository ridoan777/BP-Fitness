<?php

namespace App\Models\Users;

use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\{Table, Fillable};

#[Table('user_auth_activities')]
#[Fillable(['user_id', 'personal_access_token_id', 'email', 'user_role', 'activity_type', 'activity_date', 'timezone', 'channel', 'guard', 'ip_address', 'user_agent', 'device_id', 'device_name', 'platform', 'app_version', 'system_note', 'status'])]

class UserAuthActivity extends Model
{
	protected function casts(): array
	{
		return [
			'user_id' => 'integer',
			'personal_access_token_id' => 'integer',
			'activity_date' => 'date',
			'status' => 'boolean',
		];
	}

	// ----------------- RELATIONSHIPS -----------------
	public function userAuthActivityRelationWith_User()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function userAuthActivityRelationWith_PersonalAccessToken()
	{
		return $this->belongsTo(PersonalAccessToken::class, 'personal_access_token_id');
	}
	// ----------------- RELATIONSHIPS -----------------

	// ----------------- QUERY SCOPING -----------------
	public function scopeUserId($query, int $user_id)
	{
		return $query->where('user_id', $user_id);
	}

	public function scopePersonalAccessTokenId($query, int $personal_access_token_id)
	{
		return $query->where('personal_access_token_id', $personal_access_token_id);
	}

	public function scopeActivityType($query, string ...$activity_types)
	{
		return $query->whereIn('activity_type', $activity_types);
	}

	public function scopeActivityDate($query, $activity_date)
	{
		return $query->whereDate('activity_date', $activity_date);
	}

	public function scopeTimezone($query, string $timezone)
	{
		return $query->where('timezone', $timezone);
	}

	public function scopeChannel($query, string $channel)
	{
		return $query->where('channel', $channel);
	}

	public function scopeGuard($query, string $guard)
	{
		return $query->where('guard', $guard);
	}

	public function scopePlatform($query, string $platform)
	{
		return $query->where('platform', $platform);
	}

	public function scopeStatus($query, bool|int $status)
	{
		return $query->where('status', $status);
	}
	// ----------------- QUERY SCOPING -----------------
}