<section
	class="productSellableItemBlock my-2 grid grid-cols-6 gap-x-4 p-4 border border-gray-400 rounded-lg"
	data-variant-index="0">

	<div class="col-span-1 my-4">
		<label class="inputLabelMedium">#</label>
		<input type="text" class="normalInput variantSerial" value="1" readonly>
	</div>

	<div class="col-span-5 my-4">
		<label class="inputLabelMedium">SKU
			<span class="text-orange-500 text-sm">(Must be unique)</span>
			<span class="grayLabel">(Better keep it blank. Then an UID will be generated
				automatically.)</span>
		</label>
		<input type="text" name="variants[0][product_sku_uid]" class="normalInput">
	</div>

	<div class="col-span-6 my-4">
		<label class="inputLabelMedium">Variant Product Item Title
			<span class="text-red-500">*</span>
		</label>
		<input type="text" name="variants[0][name]" class="normalInput" placeholder="max 255-characters">
	</div>

	<div class="col-span-6 my-4">
		<label class="inputLabelMedium">Variant Product Description 
			<span class="grayLabel">(Optional) (Max 2000 characters) (This is the details each single variant will have)</span>
		</label>
		<textarea class="summernote" name="variants[0][description]"></textarea>
	</div>

	<div class="variantItemsWrapper col-span-6 my-4 grid grid-cols-12 gap-4">
		<label class="inputLabelMedium col-span-12">Add variant types
			<span class="grayLabel">(Optional)</span>
		</label>
		<div class="typeItemBlock col-span-6 grid grid-cols-12 gap-4 items-start" data-item-index="0">
			<div class="col-span-5">
				<select name="variants[0][items][0][variant_type_id]"
					class="singleSelectWithSearch dropdownSelectInputs variantTypeSelect"
					data-dropdown-position="absolute"
					data-variant-items-url="{{ route('api_estore_fetch_variant_items_by_type', ['variantType' => '__ID__']) }}"
					data-variant-items-placeholder="Select a variant item">
					<option value="">Type</option>

					@foreach ($variantTypes as $type)
						<option value="{{ $type->id }}" class="text-xs">[{{ $type->id }}] | {{ $type->name }}
						</option>
					@endforeach
				</select>
			</div>

			<div class="col-span-5">
				<select name="variants[0][items][0][variant_item_id]"
					class="singleSelectWithSearch dropdownSelectInputs variantItemSelect"
					data-dropdown-position="absolute">
					<option value="">Item</option>
				</select>
			</div>

			<div class="col-span-2">
				<button type="button" class="removeTypeItemButton px-2 hover:bg-gray-200 py-2 rounded">
					{!! \App\Helpers\IconPack::cross(['class' => 'iconPackItem w-5 h-5 text-gray-500 pointer-events-none']) !!}
				</button>
			</div>
		</div>
	</div>

	<div class="col-span-6 flex justify-end">
		<button type="button" class="addTypeButton bg-gray-700 text-white text-xs px-2 py-1 rounded">
			+ Add Type
		</button>
	</div>

	<div class="col-span-2 mt-8 mb-4">
		<label class="inputLabelMedium">Stock<span class="text-red-500">*</span></label>
		<input type="text" name="variants[0][stock]" class="normalInput" value="0">
	</div>

	<div class="col-span-2 mt-8 mb-4">
		<label class="inputLabelMedium">Starting price<span class="text-red-500">*</span></label>
		<input type="text" name="variants[0][starting_price]" class="normalInput" value="0">
	</div>

	<div class="col-span-2 mt-8 mb-4 items-center">
		<label class="inputLabelMedium">Final price<span class="text-red-500">*</span></label>
		<input type="text" name="variants[0][final_price]" class="normalInput" value="0">
	</div>

	<input type="hidden" name="variants[0][status]" value="1">

	<div class="col-span-6 my-4">
		<label class="inputLabelMedium">Variant Images <span
				class="grayLabel">(Optional) (Multiple allowed) (Max 2 MB each) (For faster loading, use smaller files with webp format) (Only one file will be previewed)</span><span class="text-sm text-yellow-500 font-light"> (allowed formats: jpeg,png,jpg,webp)</span></label>
		<input type="file" name="variants[0][gallery_file][]" class="dropify variantGalleryDropify"
			data-height="100" accept="image/*" multiple>
	</div>

	<div class="col-span-4 mt-8 mb-4 flex items-center">
		<input type="radio" name="default_variant_index" value="0" id="default_variant_0"
			class="defaultVariantRadio w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
		<label for="default_variant_0" class="inputLabelMedium my-0 mx-2">Set as Default</label>
	</div>

	<div class="col-span-2 flex justify-end items-center">
		<button type="button" class="removeVariantButton bg-red-700 text-white text-xs px-3 py-2 rounded">
			Remove Variant
		</button>
	</div>

</section>