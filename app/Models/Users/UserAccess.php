<?php

namespace App\Models\Users;

use App\Helpers\Errors\ExceptionHandling;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\{Table, Fillable};

#[Table('user_accesses')]
#[Fillable(['user_id', 'email', 'user_role', 'join_ban', 'chat_ban', 'gallery_ban', 'store_ban', 'view_ban', 'account_hold', 'admin_note', 'ban_expiry', 'status'
])]

class UserAccess extends Model
{
	protected $casts = [
		'user_id' => 'integer',

		'join_ban' => 'boolean',
		'chat_ban' => 'boolean',
		'gallery_ban' => 'boolean',
		'store_ban' => 'boolean',
		'view_ban' => 'boolean',
		'account_hold' => 'boolean',

		'ban_expiry' => 'datetime',

		'status' => 'boolean',
	];

	// ----------------- RELATIONSHIPS -----------------
	public function userAccessRelationWith_User()
	{
		return $this->belongsTo(User::class, 'accused_id', 'id');
	}
	// ----------------- RELATIONSHIPS -----------------

	// ----------------- QUERY SCOPING -----------------
	public function scopeUserId($query, $user_id)
	{
		return $query->where('user_id', $user_id);
	}

	public function scopeExpiry($query, $ban_expiry)
	{
		return $query->where('ban_expiry', $ban_expiry);
	}

	public function scopeStatus($query, $status)
	{
		return $query->where('status', $status);
	}
	// ----------------- QUERY SCOPING -----------------

	// ----------------- ACCESS CHECKING -----------------
	public function isExpired(): bool
	{
		return $this->ban_expiry && $this->ban_expiry->isPast();
	}

	public function getBanExpiryDate(): ?string
	{
		return $this->ban_expiry?->format('d M Y, h:i A');
	}

	public function isChatBanned(): bool
	{
		if (!$this->status) return false;
		if (!$this->chat_ban) return false;
		if ($this->isExpired()) return false;
		return true;
	}

	public function isGalleryBanned(): bool
	{
		return $this->status && $this->gallery_ban && !$this->isExpired();
	}

	public function isStoreBanned(): bool
	{
		return $this->status && $this->store_ban && !$this->isExpired();
	}

	public function isJoinBanned(): bool
	{
		return $this->status && $this->join_ban && !$this->isExpired();
	}

	public function isViewBanned(): bool
	{
		return $this->status && $this->view_ban && !$this->isExpired();
	}

	public function isAccountOnHold(): bool
	{
		return $this->status && $this->account_hold && !$this->isExpired();
	}
	// ----------------- ACCESS CHECKING -----------------

	// ----------------- ACCESS GUARDS -----------------
	private function banGuard(bool $isBanned, string $context): void
	{
		if (!$isBanned) return;

		$expiry = $this->getBanExpiryDate();
		$expiryMssg = $expiry ? " Ban will lift at {$expiry}." : null;
		ExceptionHandling::bailout(403, "Your {$context} access is disabled!{$expiryMssg}");
	}

	public function chatBanGuard(): void
	{
		$this->banGuard($this->isChatBanned(), 'messaging');
	}

	public function galleryBanGuard(): void
	{
		$this->banGuard($this->isGalleryBanned(), 'gallery');
	}

	public function storeBanGuard(): void
	{
		$this->banGuard($this->isStoreBanned(), 'store');
	}

	public function joinBanGuard(): void
	{
		$this->banGuard($this->isJoinBanned(), 'joining');
	}

	public function viewBanGuard(): void
	{
		$this->banGuard($this->isViewBanned(), 'viewing');
	}

	public function accountHoldGuard(): void
	{
		$this->banGuard($this->isAccountOnHold(), 'account');
	}
	// ----------------- ACCESS GUARDS -----------------
}
/*
	USE CASE:
		$access = $USER->userRelationWith_UserAccess;

			$access?->joinBanGuard();
			$access?->galleryBanGuard();
			$access?->storeBanGuard();
			$access?->viewBanGuard();
			$access?->accountHoldGuard();
*/