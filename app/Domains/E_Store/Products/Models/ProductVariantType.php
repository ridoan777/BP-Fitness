<?php

namespace App\Domains\E_Store\Products\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\{Table, Fillable};
use NunoMaduro\LaravelSluggable\Attributes\Sluggable;

#[Table('product_variant_types')]
#[Fillable(['variant_type_uid', 'name', 'description', 'status'])]

#[Sluggable(from: ['name'], onUpdating: true, separator: '-', maxLength: 40)]

class ProductVariantType extends Model
{
	protected function casts(): array
	{
		return [
			'status' => 'integer',
		];
	}

	// ----------------- QUERY SCOPING -----------------
	public function scopeStatus($query, $status)
	{
		return $query->where('status', $status);
	}
	// ----------------- QUERY SCOPING -----------------

	// ----------------- SLUG OPERATION -----------------
	public function redirectIfSlugMismatch(string $slug)
	{
		if ($this->slug !== $slug) {
			return redirect()->route('backend_product_variant_type_show', [
				'singleProductVariantType' => $this->id,
				'slug' => $this->slug,
			]);
		}

		return null;
	}
	// ----------------- SLUG OPERATION -----------------
}
/*
	USE SLUG:
		if ($redirect = $productVariantType->redirectIfSlugMismatch($slug)) return $redirect;
*/
