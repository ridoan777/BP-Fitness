<?php

namespace App\Domains\E_Store\Products\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\{Table, Fillable, Hidden};

#[Table('product_sku_variant_pivots')]
#[Fillable(['product_sku_sellable_id', 'product_variant_item_id', 'base_product_item'])]
#[Hidden('base_product_item')]

class ProductSkuVariantPivot extends Model
{
	protected function casts(): array
	{
		return [
			'product_sku_sellable_id' => 'integer',
			'product_variant_item_id' => 'integer',
			'base_product_item' => 'integer',
		];
	}
}
