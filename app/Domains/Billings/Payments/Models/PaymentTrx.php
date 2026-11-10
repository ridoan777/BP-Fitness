<?php

namespace App\Domains\Billings\Payments\Models;

use App\Domains\Billings\Shared\Enums\TrxPhaseEnums;
use App\Domains\Billings\Shared\Enums\PaymentStatusEnums;
use App\Domains\E_Store\Shoppings\Models\ProductOrder;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\{Table, Fillable, Hidden};

#[Table('billing_payment_transactions')]
#[Fillable(['payment_source_type', 'payment_source_id', 'user_id', 'trx_uid', 'ss_user_id', 'ss_user_email', 'payment_status', 'trx_phase', 'currency', 'trx_amount', 'stripe_fee', 'exchange_rate', 'takeaway_amount', 'card_details', 'customer_details', 'passed_metadata', 'payment_intent', 'event_ids', 'stripe_checkout_session_id', 'system_note', 'status'])]

// #[Hidden(['payer', 'last4', 'brand', 'expiry', 'email', 'name', 'event_id', 'stripe_checkout_session_id', 'payment_intent'])]

class PaymentTrx extends Model
{
	protected function casts(): array
	{
		return [
			'payment_source_id' => 'integer',
			'user_id' => 'integer',
			'ss_user_id' => 'integer',
			'trx_amount' => 'integer',	// actual paid amount by user
			'stripe_fee' => 'integer',
			'exchange_rate' => 'float',
			'takeaway_amount' => 'integer',
			'payment_status' => PaymentStatusEnums::class,
			'trx_phase' => TrxPhaseEnums::class,
			'card_details' => 'array',
			'customer_details' => 'array',
			'passed_metadata' => 'array',
			'event_ids' => 'array',
			'status' => 'integer',
		];
	}

	// ----------------- RELATIONSHIPS -----------------
	public function paymentSource()
	{
		return $this->morphTo(__FUNCTION__, 'payment_source_type', 'payment_source_id');
	}

	public function trxRelatingBackTo_User()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function trxRelatingBackTo_SnapshotUser()
	{
		return $this->belongsTo(User::class, 'ss_user_id');
	}

	public function trxRelatingBackTo_ProductOrder()
	{
		return $this->belongsTo(ProductOrder::class, 'payment_source_id', 'id')
			->where('payment_source_type', ProductOrder::class);
	}
	// ----------------- RELATIONSHIPS -----------------

	// ----------------- LEGACY COMPATIBILITY -----------------
	public function getPaidAmountAttribute()
	{
		return $this->trx_amount;
	}

	public function setPaidAmountAttribute($value)
	{
		$this->attributes['trx_amount'] = $value;
	}
	// ----------------- LEGACY COMPATIBILITY -----------------

	// ----------------- QUERY SCOPING -----------------
	public function scopePaymentSourceType($query, string $payment_source_type)
	{
		return $query->where('payment_source_type', $payment_source_type);
	}

	public function scopePaymentSourceId($query, int $payment_source_id)
	{
		return $query->where('payment_source_id', $payment_source_id);
	}

	public function scopeTrxUid($query, string $trx_uid)
	{
		return $query->where('trx_uid', $trx_uid);
	}

	public function scopeUserId($query, int $user_id)
	{
		return $query->where('user_id', $user_id);
	}

	public function scopeCheckoutSessionId($query, string $id)
	{
		return $query->where('stripe_checkout_session_id', $id);
	}

	public function scopePaymentIntent($query, string $intent)
	{
		return $query->where('payment_intent', $intent);
	}

	public function scopeStatus($query, $status)
	{
		return $query->where('status', $status);
	}
	// ----------------- QUERY SCOPING -----------------
}
