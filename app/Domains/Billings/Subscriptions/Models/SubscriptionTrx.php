<?php

namespace App\Domains\Billings\Subscriptions\Models;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Attributes\{Fillable, Table};
use Illuminate\Database\Eloquent\Model;

#[Table('billing_subscription_transactions')]
#[Fillable(['transaction_id', 'subscription_id', 'rc_app_user_id', 'user_id', 'store', 'phase', 'trx_type', 'currency', 'amount', 'system_note', 'event_id', 'status'])]

class SubscriptionTrx extends Model
{
	protected function casts(): array
	{
		return [
			'user_id' => 'integer',
			'amount' => 'float',
			'status' => 'integer',
		];
	}

	// ----------------- RELATIONSHIPS -----------------
	public function trxRelatingBackTo_User()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function trxRelatingBackTo_Subscription()
	{
		return $this->belongsTo(SubscriptionAppUser::class, 'subscription_id');
	}
	// ----------------- RELATIONSHIPS -----------------

	// ----------------- QUERY SCOPING -----------------

	public function scopeSubscriptionId($query, int $subscription_id)
	{
		return $query->where('subscription_id', $subscription_id);
	}

	public function scopeUserId($query, int $user_id)
	{
		return $query->where('user_id', $user_id);
	}

	public function scopeStatus($query, $status)
	{
		return $query->where('status', $status);
	}
	// ----------------- QUERY SCOPING -----------------
}
