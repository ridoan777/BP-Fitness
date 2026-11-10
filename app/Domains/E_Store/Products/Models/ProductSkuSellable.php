<?php

namespace App\Domains\E_Store\Products\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\{Table, Fillable,Hidden};
use NunoMaduro\LaravelSluggable\Attributes\Sluggable;

#[Table('product_sku_sellables')]
#[Fillable(['product_sku_uid', 'product_item_id', 'name', 'description', 'stock', 'starting_price', 'final_price', 'featured', 'status', 'creator_id', 'last_modifier_id'])]
#[Hidden(['creator_id', 'last_modifier_id'])]

#[Sluggable(from: ['name'], onUpdating: true, separator: '-', maxLength: 40)]

class ProductSkuSellable extends Model
{
	protected function casts(): array
	{
		return [
			'product_item_id' => 'integer',
			'stock' => 'integer',
			'starting_price' => 'integer',
			'final_price' => 'integer',
			'featured' => 'integer',
			'status' => 'integer',
			'creator_id' => 'integer',
			'last_modifier_id' => 'integer',
		];
	}

	// ----------------- RELATIONSHIP -----------------

	public function skuRelatingBackTo_ProductItem()
	{
		return $this->belongsTo(ProductItem::class, 'product_item_id', 'id');
	}

	public function skuRelatonWith_VariantPivot()
	{
		return $this->hasMany(ProductSkuVariantPivot::class, 'product_sku_sellable_id', 'id');
	}

	public function skuRelatonWith_VariantItems()
	{
		return $this->belongsToMany(ProductVariantItem::class, 'product_sku_variant_pivots', 'product_sku_sellable_id', 'product_variant_item_id');
	}

	public function skuRelationWith_Gallery()
	{
		return $this->hasMany(ProductGallery::class, 'sku_sellable_id', 'id');
	}
	// ----------------- RELATIONSHIP -----------------

	// ----------------- QUERY SCOPING -----------------
	public function scopeProductId($query, int $product_item_id)
	{
		return $query->where('product_item_id', $product_item_id);
	}
	
	public function scopeStatus($query, $status)
	{
		return $query->where('status', $status);
	}
	// ----------------- QUERY SCOPING -----------------

	// ----------------- MUTATOR + ACCESSOR -----------------
	public function getStartingPriceAttribute($value)
	{
		return $value !== null ? $value / 100 : null;
	}

	public function setStartingPriceAttribute($value)
	{
		$this->attributes['starting_price'] = $value !== null ? (int)round($value * 100) : null;
	}

	public function getFinalPriceAttribute($value)
	{
		return $value !== null ? $value / 100 : null;
	}

	public function setFinalPriceAttribute($value)
	{
		$this->attributes['final_price'] = $value !== null ? (int)round($value * 100) : null;
	}
	// ----------------- MUTATOR + ACCESSOR -----------------

	// ----------------- SLUG OPERATION -----------------
	public function redirectIfSlugMismatch(string $slug)
	{
		if ($this->slug !== $slug) {
			return redirect()->route('backend_product_sku_sellable_show', [
				'singleProductSkuSellable' => $this->id,
				'slug'                     => $this->slug,
			]);
		}

		return null;
	}
	// ----------------- SLUG OPERATION -----------------
}
/*
	USE SLUG:
		if ($redirect = $productSkuSellable->redirectIfSlugMismatch($slug)) return $redirect;
*/
