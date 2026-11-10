	<!----------- Announcement Banner ----------->
	<x-ui_items.banners.announcement_banner rolesToShow="admin,super_admin"
		message="⚠️Be Careful! These Variant type records are connected to Variant items, Products, etc. Deleting or modifying will also affect any children records in the relationship tree." bgColor="bg-yellow-300" />
	<!----------- End Announcement Banner ----------->

<form method="POST" action="{{ route('backend_product_variant_type_store') }}"
	class="rounded-lg p-4 dark:bg-neutral-700" enctype="multipart/form-data">
	@csrf

	<!--- IDENTIFIER HIDDEN INPUTS -->
	<input type="hidden" name="id" class="smallInput" value="{{ $singleProductVariantType->id }}">
	<input type="hidden" name="flag" class="smallInput" value="update">
	<!--- IDENTIFIER HIDDEN INPUTS -->

	<div>
		<!-- -->
		<div class="mb-5 mt-2">
			<label for="name" class="inputLabelMedium">Variant Type Name
				<span class="text-red-500">*</span>
			</label>
			<input type="text" name="name" class="mediumInput" placeholder="max 255-characters" value="{{ $singleProductVariantType?->name }}">
		</div>
		<!--  -->
		<div class="mb-5 mt-2">
			<label for="description" class="inputLabelMedium">Description
				<span class="text-sm text-gray-400 font-light"> (Max 2000 characters. Use Ctrl+Shift+V for safe
					pasting)</span>
			</label>
			<textarea class="summernote" name="description"> {{ $singleProductVariantType?->description ?? null }}
			</textarea>
		</div>

		<!-- -->

		<div class="mb-5 mt-2">
			<label for="status" class="inputLabelMedium">Active Status</label>
			<select name="status" class="dropdownSelectInputs">
				<option value="1" {{ $singleProductVariantType?->status == 1 ? 'selected' : '' }}>Active</option>
				<option value="0" {{ $singleProductVariantType?->status == 0 ? 'selected' : '' }}>Inactive</option>
			</select>
		</div>

	</div>

	<button type="submit" class="showButton my-4">Show</button>
</form>
