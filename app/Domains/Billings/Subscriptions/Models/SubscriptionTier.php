<?php

namespace App\Domains\Billings\Subscriptions\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Attributes\{Table, Fillable, Hidden, Appends};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Table('subscription_tiers')]

#[Fillable(['tier_uid', 'platform', 'name', 'duration', 'starting_price', 'final_price', 'benefits', 'description', 'google_product_id', 'apple_product_id', 'google_entitlement', 'apple_entitlement', 'google_rest_api_id', 'apple_rest_api_id', 'stripe_product_id', 'stripe_price_id', 'image', 'status', 'creator_id', 'last_modifier_id'])]

#[Hidden(['creator_id', 'last_modifier_id', 'google_product_id', 'apple_product_id', 'google_entitlement', 'apple_entitlement', 'google_rest_api_id', 'apple_rest_api_id', 'stripe_product_id', 'stripe_price_id'])]

#[Appends(['tier_image_url'])]

class SubscriptionTier extends Model
{
	# 'platform' =  mobile, web

	protected $casts = [
		'starting_price' => 'integer',
		'final_price' => 'integer',
		'duration' => 'integer',
		'status' => 'integer',
		'creator_id' => 'integer',
		'last_modifier_id' => 'integer',
	];

	// ----------------- MUTATOR + ACCESSOR -----------------
	protected function startingPrice(): Attribute
	{
		return Attribute::make(
			get: fn(?int $value) => $value !== null ? $value / 100 : null,
			set: fn(mixed $value) => $value !== null ? (int)round((float)$value * 100) : null,
		);
	}

	protected function finalPrice(): Attribute
	{
		return Attribute::make(
			get: fn(?int $value) => $value !== null ? $value / 100 : null,
			set: fn(mixed $value) => $value !== null ? (int)round((float)$value * 100) : null,
		);
	}
	// ----------------- MUTATOR + ACCESSOR -----------------

	// ----------------- QUERY SCOPING -----------------

	public function scopeProductId($query, $productId)
	{
		return $query->where('apple_product_id', $productId)
			->orWhere('google_product_id', $productId);
	}

	public function scopePlatform($query, $platform)
	{
		return $query->where('platform', $platform);
	}

	public function scopeStatus($query, $status)
	{
		return $query->where('status', $status);
	}
	// ----------------- QUERY SCOPING -----------------

	// ----------------- URL FRIENDLY IMAGE -----------------
	public function getTierImageUrlAttribute()
	{
		return $this->image ? Storage::url($this->image) : null;
	}
	// ----------------- URL FRIENDLY IMAGE -----------------
}
