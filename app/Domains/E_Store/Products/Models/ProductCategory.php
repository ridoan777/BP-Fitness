<?php

namespace App\Domains\E_Store\Products\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\{Table, Fillable, Hidden};
use NunoMaduro\LaravelSluggable\Attributes\Sluggable;

#[Table('product_categories')]
#[Fillable(['category_uid', 'name', 'description', 'creator_id', 'last_modifier_id', 'status'])]
#[Hidden(['creator_id', 'last_modifier_id'])]

#[Sluggable(from: ['name'], onUpdating: true, separator: '-', maxLength: 40)]

class ProductCategory extends Model
{
	protected function casts(): array
	{
		return [
			'creator_id' => 'integer',
			'last_modifier_id' => 'integer',
			'status' => 'integer',
		];
	}

	// ----------------- RELATIONSHIP -----------------
	public function categoryRelatonWith_ProductItems()
	{
		return $this->hasMany(ProductItem::class, 'category_id', 'id');
	}
	// ----------------- RELATIONSHIP -----------------

	// ----------------- QUERY SCOPING -----------------
	public function scopeStatus($query, int $status)
	{
		return $query->where('status', $status);
	}
	// ----------------- QUERY SCOPING -----------------

	// ----------------- SLUG OPERATION -----------------
	public function redirectIfSlugMismatch(string $slug)
	{
		if ($this->slug !== $slug) {
			return redirect()->route('backend_product_category_show', [
				'singleCategory' => $this->id,
				'slug' => $this->slug,
			]);
		}

		return null;
	}
	// ----------------- SLUG OPERATION -----------------
}
/*
	USE SLUG:
		if ($redirect = $productCategory->redirectIfSlugMismatch($slug)) return $redirect;
*/
