<x-app-layout>

	@php
		$breadCrumbs = [
			['label' => 'All Categories', 'url' => 'backend_product_category_index', 'icon' => 'layers'],
			['label' => $singleCategory?->category_uid ?? "Single Category"],
			['label' => 'Variant Types', 'url' => 'backend_product_variant_type_index', 'icon' => 'layers'],
			['label' => 'Variant Items', 'url' => 'backend_product_variant_item_index', 'icon' => 'layers'],
			['label' => 'Product', 'url' => 'backend_product_item_create', 'icon' => 'plus'],
			['label' => 'Product Items'],
		];
	 @endphp

	<x-indicators.breadcrumb :crumbs="$breadCrumbs" />
	<!-- ---------------- APP AREA Starts ---------------- -->

	<!-- Start Main Widgets -->
	<x-indicators.page_header_widget pageName="Edit Product Category" />
	<!-- End Main Widgets -->


	<!-- Alert Component -->
	<x-indicators.alert-component message="Category Modification" />
	<!-- Alert Component -->

	@php
		$ACCORDION_1_VISIBILITY = ''; // hidden or ''
	 @endphp

	<!---------------------------------------------------------------------------------------------- accordion -->
	<div class="hs-accordion-group">

		<x-ui_items.accordion.accordion-destroy />

		<div id="hs-destroy-heading-one"
			class="hs-accordion-to-destroy hs-accordion hs-accordion-active:border-gray-200 PrelineAccordionItemParent">

			<x-ui_items.accordion.accordion_toggle_button toggleLabel="Modify Category Details" />

			<div id="hs-destroy-collapse-one"
				class="hs-accordion-content {{ $ACCORDION_1_VISIBILITY }} w-full overflow-hidden transition-[height] duration-300"
				role="region" aria-labelledby="hs-destroy-heading-one">
				<div class="p-3">
					<!-- Form::Category-Edit -->
					<section
						class="displayArea mx-auto my-5 rounded-lg border-none bg-white p-4 shadow-md shadow-gray-400 dark:bg-neutral-700 dark:shadow-none">
						<div class="my-5">
							<form method="POST" action="{{ route('backend_product_category_store') }}"
								class="rounded-lg p-2 dark:bg-neutral-700" enctype="multipart/form-data">
								@csrf

								<!--- IDENTIFIER HIDDEN INPUTS -->
								<input type="hidden" name="id" class="smallInput" value="{{ $singleCategory->id }}">
								<input type="hidden" name="flag" class="smallInput" value="update">
								<!--- IDENTIFIER HIDDEN INPUTS -->

								<div>

									<!-- -->
									<div class="mb-5 mt-2">
										<label for="name" class="inputLabelMedium">Category Name
											<span class="text-red-500">*</span>
										</label>
										<input type="text" name="name" class="mediumInput" placeholder="max 255-characters"
											value="{{ $singleCategory->name }}">
									</div>
									<!--  -->
									<div class="mb-5 mt-2">
										<label for="description" class="inputLabelMedium">Description
											<span class="text-sm text-gray-400 font-light"> (Max 2000 characters. Use
												Ctrl+Shift+V
												for safe pasting)</span>
										</label>
										<textarea class="summernote" name="description" id="description">
											{{ $singleCategory?->description ?? null }}
										</textarea>
									</div>

									<!-- -->

									<div class="mb-5 mt-2">
										<label for="status" class="inputLabelMedium">Active Status</label>
										<select name="status" class="dropdownSelectInputs">
											<option value="1" {{ $singleCategory->status == 1 ? 'selected' : '' }}>Active</option>
											<option value="0" {{ $singleCategory->status == 0 ? 'selected' : '' }}>Inactive</option>
										</select>
									</div>

								</div>

								<button type="submit" class="showButton my-4">Update Category</button>
							</form>
						</div>
					</section>
					<!-- Form::Category-Edit -->
				</div>
			</div>
		</div>
	</div>
</x-app-layout>