<section>
	<div class="px-6 flex justify-end">
		<button type="button" data-modal-target="hs-form-modal" data-modal-toggle="hs-form-modal"
			class="darkButton">{!! \App\Helpers\IconPack::plus(['class' => 'iconPackItem w-3 h-3 ']) !!} Create
		</button>
	</div>
	<!--  -->
	
	<x-ui_items.modals.form-modal id="hs-form-modal" title="Create Product Category" cancelText="Cancel" message="">
		<form method="POST" action="{{ route('backend_product_category_store') }}" class="rounded-lg p-2 dark:bg-neutral-700"
			enctype="multipart/form-data">
			@csrf
	
			<!--- IDENTIFIER HIDDEN INPUTS -->
			<input type="hidden" name="flag" class="smallInput" value="create">
			<!--- IDENTIFIER HIDDEN INPUTS -->
	
			<div>
	
				<!-- -->
				<div class="mb-5 mt-2">
					<label for="name" class="inputLabelMedium">Category Name
						<span class="text-red-500">*</span>
					</label>
					<input type="text" name="name" class="mediumInput" placeholder="max 255-characters">
				</div>
				<!--  -->
				<div class="mb-5 mt-2">
					<label for="description" class="inputLabelMedium">Description
						<span class="text-sm text-gray-400 font-light"> (Max 2000 characters. Use
							Ctrl+Shift+V
							for safe pasting)</span>
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
	
			<button type="submit" class="saveButton my-4">Create Category</button>
		</form>
	</x-ui_items.modals.form-modal>
</section>