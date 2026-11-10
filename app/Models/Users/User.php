<?php

namespace App\Models\Users;

use App\Models\Communication\Chatting\ChatMessage;
use App\Models\System\Settings\UserAppPreference;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Scout\Searchable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
	use HasApiTokens, HasFactory, Notifiable, Billable, HasRoles, Searchable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var list<string>
	 */
	protected $guard_name = 'web';

	protected $fillable = [
		'user_uid',	// it's uniqueULID, immutable always
		'username',	// it's uniqueUsername, editable by users

		'name',
		'user_role',	// super_admin | admin | enrolled
		'created_by',

		'gender',	// male, female, prefer_not
		'phone',
		'email',
		'email_verified_at',
		'password',

		'social_provider_id',
		'social_provider_name',
		'social_token',
		'social_refresh_token',

		'avatar',
		'about',

		'agreed_terms',
		'term_version',
		'timezone',

		'fcm_token',
		'status',
	];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var list<string>
	 */
	protected $hidden = [
		'password',
		'remember_token',
		'social_token',
		'social_refresh_token',
	];

	/**
	 * The attributes that should be cast.
	 *
	 * @return array<string, string>
	 */
	protected function casts(): array
	{
		return [
			'created_by' => 'integer',
			'email_verified_at' => 'datetime',
			'password' => 'hashed',
			'fcm_token' => 'array',
		];
	}

	// ----------------- THIRD-PARTY -----------------
	public function routeNotificationForFcm(): array|string|null
	{
		// for firebase Push notification
		return $this->fcm_token;	// single
		// return $this->getDeviceTokens();	// multicast
	}
	// ----------------- THIRD-PARTY -----------------

	// ----------------- ROLES -----------------
	public function hasRoleKey(string ...$keys): bool
	{
		$normalized = array_map('strtolower', $keys);
		return $this->roles()->whereIn('role_key', $normalized)->exists();
	}
	// ----------------- ROLES -----------------

	// ----------------- RELATIONSHIPS -----------------

	public function userRelationWith_UserAccess()
	{
		return $this->hasOne(UserAccess::class, 'user_id');
	}

	public function userRelationWith_AppPreference()
	{
		return $this->hasOne(UserAppPreference::class);
	}

	public function userRelationWith_SentMessages()
	{
		return $this->hasMany(ChatMessage::class, 'sender_id');
	}

	public function userRelationWith_ReceivedMessages()
	{
		return $this->hasMany(ChatMessage::class, 'receiver_id');
	}

	public function chatMessages()
	{
		return $this->hasMany(ChatMessage::class, 'sender_id', 'id')
			->orWhereColumn('chat_messages.receiver_id', 'users.id');
	}

	public function lastChatMessageWithUser(int $authId)
	{
		return $this->hasOne(ChatMessage::class, 'receiver_id', 'id')
			->where(function ($q) use ($authId) {
				$q->where(function ($qq) use ($authId) {
					$qq->where('sender_id', $authId)
						->where('receiver_id', $this->id);
				})->orWhere(function ($qq) use ($authId) {
					$qq->where('sender_id', $this->id)
						->where('receiver_id', $authId);
				});
			})
			->orderByDesc('id');
	}
	// ----------------- RELATIONSHIPS -----------------


	// ----------------- QUERY SCOPING -----------------

	public function scopeFilterRoleKey($query, $roleKey)
	{
		return $query->whereHas('roles', function ($q) use ($roleKey) {
			$q->where('role_key', $roleKey);
		});
	}

	public function scopeStatus($query, $status)
	{
		return $query->where('status', $status);
	}
	// ----------------- QUERY SCOPING -----------------


	// ----------------- URL FRIENDLY IMAGE -----------------
	protected $appends = ['avatar_url'];

	public function getAvatarUrlAttribute()
	{
		if (!$this->avatar) {
			return asset('site_assets/dummies/dummy_man.webp');
		}

		if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
			return $this->avatar;
		}

		return Storage::url($this->avatar);
	}
	// ----------------- URL FRIENDLY IMAGE -----------------
}
