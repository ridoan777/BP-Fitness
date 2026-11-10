<div
	class="mb-4 grid grid-cols-12 gap-4 rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-neutral-600 dark:bg-neutral-800">

	<div class="col-span-12 md:col-span-3">
		<label class="inputLabelMedium">Category</label>
		<select id="filter_category_id" class="dropdownSelectInputs">
			<option value="">All Categories</option>
			@foreach ($categories ?? [] as $category)
				@php /** @var \App\Domains\E_Store\Products\Models\ProductCategory $category */ @endphp
				<option value="{{ $category->id }}">{{ $category->name }}</option>
			@endforeach
		</select>
	</div>

	<div class="col-span-12 md:col-span-3">
		<label class="inputLabelMedium">Status</label>
		<select id="filter_status" class="dropdownSelectInputs">
			<option value="">All Status</option>
			<option value="1">Active</option>
			<option value="0">Inactive</option>
		</select>
	</div>

	<div class="col-span-12 md:col-span-3">
		<label class="inputLabelMedium">Minimum Price</label>
		<input type="number" id="filter_min_price" class="normalInput" min="0" step="0.01" placeholder="0.00">
	</div>

	<div class="col-span-12 md:col-span-3">
		<label class="inputLabelMedium">Maximum Price</label>
		<input type="number" id="filter_max_price" class="normalInput" min="0" step="0.01" placeholder="999.00">
	</div>

	<div class="col-span-12 md:col-span-3">
		<label class="inputLabelMedium">Variant Type</label>
		<select id="filter_variant_type_id" class="dropdownSelectInputs">
			<option value="">All Variant Types</option>
			@php /** @var \App\Domains\E_Store\Products\Models\ProductVariantType $variantTypes */ @endphp
			@foreach ($variantTypes ?? [] as $type)
				<option value="{{ $type->id }}">{{ $type->name }}</option>
			@endforeach
		</select>
	</div>

	<div class="col-span-12 md:col-span-3">
		<label class="inputLabelMedium">Has Sellables</label>
		<select id="filter_has_sellables" class="dropdownSelectInputs">
			<option value="">Any</option>
			<option value="1">Has Sellables</option>
			<option value="0">No Sellables</option>
		</select>
	</div>

	<div class="col-span-12 md:col-span-3 flex items-end">
		<button type="button" id="applyProductFilters" class="bg-indigo-700 text-white text-sm px-4 py-2 rounded">
			Apply Filters
		</button>
	</div>

	<div class="col-span-12 md:col-span-3 flex items-end">
		<button type="button" id="resetProductFilters" class="bg-gray-600 text-white text-sm px-4 py-2 rounded">
			Reset
		</button>
	</div>
</div>