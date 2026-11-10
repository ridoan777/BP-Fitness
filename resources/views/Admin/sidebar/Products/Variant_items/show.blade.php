<div class="p-4">
	<!----------- Announcement Banner ----------->
	<x-ui_items.banners.announcement_banner rolesToShow="admin,super_admin"
		message="⚠️Be Careful! These Variant item records are connected to Products, Pivot tables, etc. Deleting or modifying will also affect any children records in the relationship tree."
		bgColor="bg-yellow-300" />
	<!----------- End Announcement Banner ----------->

	<form method="POST" action="{{ route('backend_product_variant_item_store') }}"
		class="rounded-lg p-4 dark:bg-neutral-800" enctype="multipart/form-data">
		@csrf

		<!--- IDENTIFIER HIDDEN INPUTS -->
		<input type="hidden" name="flag" class="smallInput" value="update">
		<input type="hidden" name="id" class="smallInput" value="{{ $singleProductVariantItem->id }}">
		<!--- IDENTIFIER HIDDEN INPUTS -->

		<div>
			<!-- -->
			<div class="mb-5 mt-2">
				<select name="variant_type_id" class="singleSelectWithSearch dropdownSelectInputs"
					data-dropdown-position="absolute">
					<option value="">Select a parent variant type</option>
					@foreach ($variantTypes as $type)
						<option value="{{ $type->id }}" {{ $singleProductVariantItem->variant_type_id == $type->id ? 'selected' : '' }}>
							[{{ $type->id }}] | {{ $type->name }}
						</option>
					@endforeach
				</select>
			</div>
			<!-- -->
			<div class="mb-5 mt-2">
				<label for="name" class="inputLabelMedium">Variant Type Name
					<span class="text-red-500">*</span>
				</label>
				<input type="text" name="name" class="mediumInput" value="{{ $singleProductVariantItem->name }}"
					placeholder="max 255-characters">
			</div>
			<!--  -->
			<div class="mb-5 mt-2">
				<label for="value" class="inputLabelMedium">Add a value
					<span class="grayLabel">(E.g., type #FF0000 for red color)(Optional)</span>
				</label>
				<input type="text" name="value" class="mediumInput" value="{{ $singleProductVariantItem?->value ?? null }}"
					placeholder="max 255-characters">
			</div>
			<!--  -->
			<div class="mb-5 mt-2">
				<label for="description" class="inputLabelMedium">Description
					<span class="text-sm text-gray-400 font-light">(Optional)(Max 2000 characters. Use Ctrl+Shift+V for safe
						pasting)</span>
				</label>
				<textarea class="summernote" name="description"
					id="description_edit">{{ $singleProductVariantItem?->description ?? null }}</textarea>
			</div>

			<!-- -->

			<div class="mb-5 mt-2">
				<label for="status" class="inputLabelMedium">Active Status</label>
				<select name="status" class="dropdownSelectInputs">
					<option value="1" {{ $singleProductVariantItem->status == 1 ? 'selected' : '' }}>Active</option>
					<option value="0" {{ $singleProductVariantItem->status == 0 ? 'selected' : '' }}>Inactive</option>
				</select>
			</div>

		</div>

		<button type="submit" class="showButton my-4">Update</button>
	</form>
</div>