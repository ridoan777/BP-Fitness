<section>

	<div class="px-6 flex justify-end">
		<button type="button" data-modal-target="hs-form-modal" data-modal-toggle="hs-form-modal" class="darkButton">+ Create
		</button>
	</div>
	<!--  -->
	<x-ui_items.modals.form-modal id="hs-form-modal" title="Create Variant Items" cancelText="Cancel" message="">
		<form method="POST" action="{{ route('backend_product_variant_item_store') }}"
			class="rounded-lg p-4 dark:bg-neutral-800" enctype="multipart/form-data">
			@csrf
	
			<!--- IDENTIFIER HIDDEN INPUTS -->
			<input type="hidden" name="flag" class="smallInput" value="create">
			<!--- IDENTIFIER HIDDEN INPUTS -->
	
			<div>
				<!-- -->
				<div class="mb-5 mt-2">
					<select name="variant_type_id" class="singleSelectWithSearch dropdownSelectInputs"
						data-dropdown-position="absolute">
						<option value="">Select a parent variant type</option>
						@foreach ($variantTypes as $type)
							<option value="{{ $type->id }}">
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
					<input type="text" name="name" class="mediumInput" placeholder="max 255-characters">
				</div>
				<!--  -->
				<div class="mb-5 mt-2">
					<label for="value" class="inputLabelMedium">Add a value
						<span class="grayLabel">(E.g., type #FF0000 for red color)(Optional)</span>
					</label>
					<input type="text" name="value" class="mediumInput" placeholder="max 255-characters">
				</div>
				<!--  -->
				<div class="mb-5 mt-2">
					<label for="description" class="inputLabelMedium">Description
						<span class="text-sm text-gray-400 font-light">(Optional)(Max 2000 characters. Use Ctrl+Shift+V for safe
							pasting)</span>
					</label>
					<textarea class="summernote" name="description" id="description"></textarea>
				</div>
	
				<!-- -->
	
				<div class="mb-5 mt-2">
					<label for="status" class="inputLabelMedium">Active Status</label>
					<select name="status" class="dropdownSelectInputs">
						<option value="1" selected>Active</option>
						<option value="0">Inactive</option>
					</select>
				</div>
	
			</div>
	
			<button type="submit" class="saveButton my-4">Create</button>
		</form>
	</x-ui_items.modals.form-modal>
</section>