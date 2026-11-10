<?php

namespace App\Domains\E_Store\Products\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Attributes\{Table, Fillable, Appends};

#[Table('product_galleries')]
#[Fillable(['product_item_id', 'sku_sellable_id', 'filetype', 'filename', 'filepath', 'metadata', 'status', 'creator_id', 'last_modifier_id'])]
class ProductGallery extends Model
{
	protected function casts(): array
	{
		return [
			'product_item_id' => 'integer',
			'sku_sellable_id' => 'integer',
			'metadata' => 'array',
			'status' => 'integer',
			'creator_id' => 'integer',
			'last_modifier_id' => 'integer',
		];
	}

	public function galleryRelatingBackTo_ProductItem()
	{
		return $this->belongsTo(ProductItem::class, 'product_item_id', 'id');
	}

	public function galleryRelatingBackTo_SkuSellable()
	{
		return $this->belongsTo(ProductSkuSellable::class, 'sku_sellable_id', 'id');
	}

	// ----------------- QUERY SCOPING -----------------
	public function scopeProductItem($query, int $product_item_id)
	{
		return $query->where('product_item_id', $product_item_id);
	}
	
	public function scopeSkuSellableId($query, int $sku_sellable_id)
	{
		return $query->where('sku_sellable_id', $sku_sellable_id);
	}
	
	public function scopeFiletype($query, string $filetype)
	{
		// featured/gallery
		return $query->where('filetype', $filetype);
	}
	
	public function scopeStatus($query, $status)
	{
		return $query->where('status', $status);
	}
	// ----------------- QUERY SCOPING -----------------


	// ----------------- URL FRIENDLY IMAGE -----------------
	protected $appends = ['filepath_url'];

	public function getFilepathUrlAttribute()
	{
		if ($this->filepath) {
			return Storage::url($this->filepath);
		}
		return null;
	}
	// ----------------- URL FRIENDLY IMAGE -----------------
}