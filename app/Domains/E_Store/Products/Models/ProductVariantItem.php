<?php

namespace App\Domains\E_Store\Products\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\{Table, Fillable};
use NunoMaduro\LaravelSluggable\Attributes\Sluggable;

#[Table('product_variant_items')]
#[Fillable(['variant_item_uid', 'variant_type_id', 'name', 'value', 'description', 'status'])]

#[Sluggable(from: ['name'], onUpdating: true, separator: '-', maxLength: 40)]

class ProductVariantItem extends Model
{
	protected function casts(): array
	{
		return [
			'variant_type_id' => 'integer',
			'status' => 'integer',
		];
	}

	// ----------------- RELATIONSHIP -----------------
	public function variantItemRelatingBackTo_Type()
	{
		return $this->belongsTo(ProductVariantType::class, 'variant_type_id', 'id');
	}
	// ----------------- RELATIONSHIP -----------------

	// ----------------- QUERY SCOPING -----------------
	public function scopeVariantTypeId($query, int $variant_type_id)
	{
		return $query->where('variant_type_id', $variant_type_id);
	}

	public function scopeStatus($query, int $status)
	{
		return $query->where('status', $status);
	}
	// ----------------- QUERY SCOPING -----------------

	// ----------------- SLUG OPERATION -----------------
	public function redirectIfSlugMismatch(string $slug)
	{
		if ($this->slug !== $slug) {
			return redirect()->route('backend_product_variant_item_show', [
				'singleProductVariantItem' => $this->id,
				'slug'                     => $this->slug,
			]);
		}

		return null;
	}
	// ----------------- SLUG OPERATION -----------------
}
/*
	USE SLUG:
		if ($redirect = $productVariantItem->redirectIfSlugMismatch($slug)) return $redirect;
*/
