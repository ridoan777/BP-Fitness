<?php

namespace App\Domains\E_Store\Products\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\{Table, Fillable,Hidden};
use NunoMaduro\LaravelSluggable\Attributes\Sluggable;

#[Table('product_items')]
#[Fillable(['product_item_uid', 'category_id', 'name', 'description', 'status', 'creator_id', 'last_modifier_id'])]
#[Hidden(['creator_id', 'last_modifier_id'])]

#[Sluggable(from: ['name'], onUpdating: true, separator: '-', maxLength: 40)]

class ProductItem extends Model
{
	protected function casts(): array
	{
		return [
			'category_id' => 'integer',
			'status' => 'integer',
			'creator_id' => 'integer',
			'last_modifier_id' => 'integer',
		];
	}

	// ----------------- RELATIONSHIP -----------------

	public function productItemRelatingBackTo_Category()
	{
		return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
	}

	public function productItemRelatonWith_Gallery()
	{
		return $this->hasMany(ProductGallery::class, 'product_item_id', 'id');
	}

	public function productItemRelatonWith_SkuSellables()
	{
		return $this->hasMany(ProductSkuSellable::class, 'product_item_id', 'id');
	}
	// ----------------- RELATIONSHIP -----------------

	// ----------------- QUERY SCOPING -----------------
	public function scopeCategoryId($query, int $category_id)
	{
		return $query->where('category_id', $category_id);
	}
	
	public function scopeStatus($query, $status)
	{
		return $query->where('status', $status);
	}
	// ----------------- QUERY SCOPING -----------------

	// ----------------- SLUG OPERATION -----------------
	public function redirectIfSlugMismatch(string $slug)
	{
		if ($this->slug !== $slug) {
			return redirect()->route('backend_product_item_show', [
				'singleProductItem' => $this->id,
				'slug' => $this->slug,
			]);
		}

		return null;
	}
	// ----------------- SLUG OPERATION -----------------
}
/*
	USE SLUG:
		if ($redirect = $productItem->redirectIfSlugMismatch($slug)) return $redirect;
*/
