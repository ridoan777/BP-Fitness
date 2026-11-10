<?php

namespace App\Domains\Billings\Subscriptions\Models;

use App\Domains\Billings\Shared\Enums\{PaymentStatusEnums,SubscriptionStatusEnums};
use App\Domains\Billings\Subscriptions\Models\SubscriptionTier;
use Illuminate\Database\Eloquent\Attributes\{Table, Fillable, Hidden, Appends};
use Illuminate\Database\Eloquent\Model;
use App\Models\Users\User;

#[Table('subscription_app_users')]
#[Fillable(['user_id', 'tier_id', 'entitlement_id', 'provider', 'store', 'payment_status', 'sub_status', 'country', 'currency', 'amount', 'duration', 'iteration', 'system_note', 'status', 'rc_product_id', 'rc_subscription_id', 'rc_transaction_id', 'rc_event_id', 'rc_user_id', 'rc_ori_user_id', 'purchased_at', 'expire_at', 'renewal_at', 'cancelled_at'])]
#[Hidden(['rc_product_id', 'rc_subscription_id', 'rc_transaction_id', 'rc_event_id', 'rc_user_id', 'rc_ori_user_id'])]

class SubscriptionAppUser extends Model
{
	protected function casts(): array
	{
		return [
			'user_id' => 'integer',
			'tier_id' => 'integer',
			'iteration' => 'integer',
			'entitlement_id' => 'array',
			'payment_status' => PaymentStatusEnums::class,
			'sub_status' => SubscriptionStatusEnums::class,
			'purchased_at' => 'datetime',
			'expire_at' => 'datetime',
			'renewal_at' => 'datetime',
			'cancelled_at' => 'datetime',
			'rc_subscription_id' => 'string',	// unique on single subscription lifecycle
			'rc_transaction_id' => 'string',	// unique on each transaction
			'status' => 'integer',
		];
	}

	// ----------------- RELATIONSHIPS -----------------
	public function subscribedAppUserRelatingBackTo_user()	// they are subscribers
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function subscriberAppRelatingBackTo_Tier()
	{
		return $this->belongsTo(SubscriptionTier::class, 'tier_id');
	}
	// ----------------- RELATIONSHIPS -----------------

	// ----------------- QUERY SCOPING -----------------
	public function scopeActive($query)
	{
		return $query->whereIn('sub_status', SubscriptionStatusEnums::accessGrantedValues())
			->where(function ($query) {
				$query->whereNull('expire_at')->orWhere('expire_at', '>', now());
			});
	}

	public function scopeInactive($query)
	{
		return $query->where(function ($query) {
			$query->whereIn('sub_status', SubscriptionStatusEnums::accessDeniedValues())
				->orWhere(function ($query) {
					$query->whereNotNull('expire_at')->where('expire_at', '<=', now());
				});
		});
	}

	public function scopeSubStatus($query, string ...$statuses)
	{
		return $query->whereIn('sub_status', $statuses);
	}

	public function scopeRcEventId($query, ?string $rc_event_id)
	{
		if (!$rc_event_id)
			return $query;

		return $query->where('rc_event_id', $rc_event_id);
	}

	public function scopeUserId($query, int $user_id)
	{
		return $query->where('user_id', $user_id);
	}

	public function scopeProvider($query, $provider)
	{
		return $query->where('provider', $provider);
	}

	public function scopeStatus($query, $status)
	{
		return $query->where('status', $status);
	}
	// ----------------- QUERY SCOPING -----------------
}
