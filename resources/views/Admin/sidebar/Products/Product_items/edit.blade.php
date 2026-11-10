<x-app-layout>

	@php
		$breadCrumbs = [
			['label' => 'All Categories', 'url' => 'backend_product_category_index', 'icon' => 'layers'],
			['label' => 'Variant Types', 'url' => 'backend_product_variant_type_index', 'icon' => 'layers'],
			['label' => 'Variant Items', 'url' => 'backend_product_variant_item_index', 'icon' => 'layers'],
			['label' => 'Product Items', 'url' => 'backend_product_item_index', 'icon' => 'boxes'],
			['label' => 'Edit Product Item'],
		];

		$productGallery = $singleProductItem->productItemRelatonWith_Gallery ?? collect();
		$skuItems = $singleProductItem->productItemRelatonWith_SkuSellables ?? collect();

		$featureFile = $productGallery->firstWhere('filetype', 'featured')?->filepath ?? null;

		if ($skuItems->isEmpty()) {
			$skuItems = collect([null]);
		}
	@endphp

	<x-indicators.breadcrumb :crumbs="$breadCrumbs" />

	<x-indicators.page_header_widget pageName="Edit Product Item" />

	<x-indicators.alert-component message="Form Submission" />

	<div class="mx-4 p-2 dark:bg-neutral-700 shadow rounded-lg">
		<form method="POST" action="{{ route('backend_product_item_store', $singleProductItem->id) }}"
			class="rounded-lg p-6 dark:bg-neutral-800" enctype="multipart/form-data">
			@csrf

			<input type="hidden" name="id" value="{{ $singleProductItem->id }}">
			<input type="hidden" name="flag" class="smallInput" value="update">

			<div id="deletedSkuContainer"></div>

			<div>
				<div class="p-4 grid grid-cols-2 gap-4 rounded-lg border border-red-200">
					<div class="mb-5 mt-2">
						<label class="inputLabelMedium">Select Product Category
							<span class="text-red-500">*</span>
						</label>

						<select name="category_id" class="singleSelectWithSearch dropdownSelectInputs"
							data-dropdown-position="absolute">
							<option value="">Select a product category</option>

							@foreach ($categories as $category)
								<option value="{{ $category->id }}" @selected(old('category_id', $singleProductItem->category_id) == $category->id)>
									[{{ $category->id }}] | {{ $category->name }}
								</option>
							@endforeach
						</select>
					</div>

					<div class="mb-5 mt-2">
						<label class="inputLabelMedium">Product ID
							<span class="text-red-500 text-sm">(Must be unique)</span>
							<span class="grayLabel">(Better keep the existing UID unchanged.)</span>
						</label>

						<input type="text" name="product_item_uid" class="normalInput"
							value="{{ old('product_item_uid', $singleProductItem->product_item_uid) }}"
							placeholder="max 255-characters">
					</div>
				</div>

				<div class="my-4 p-4 rounded-lg border border-blue-200">
					<div class="col-span-1 flex justify-center">
						<h4 class="my-1 py-2 px-4 rounded-lg bg-indigo-900 dark:bg-indigo-700 text-gray-200">Edit Details</h4>
					</div>

					<div class="mb-5 mt-2">
						<label class="inputLabelMedium">Product Name/Title
							<span class="text-red-500">*</span>
						</label>

						<input type="text" name="name" class="mediumInput" value="{{ old('name', $singleProductItem->name) }}"
							placeholder="max 255-characters">
					</div>

					<div class="mb-5 mt-2">
						<label class="inputLabelMedium">Product Description
							<span class="text-sm text-gray-400 font-light">(Optional)(Max 2000 characters. Use Ctrl+Shift+V for
								safe pasting)</span>
						</label>

						<textarea class="summernote" name="description"
							id="description">{{ old('description', $singleProductItem->description) }}</textarea>
					</div>
				</div>

				<div class="my-4 p-4 rounded-lg border border-green-200">
					<div class="col-span-1 flex justify-center">
						<h4 class="my-1 py-2 px-4 rounded-lg bg-indigo-900 dark:bg-indigo-700 text-gray-200">Edit Variants
						</h4>
					</div>

					@foreach ($skuItems as $variantIndex => $sku)
						@php
							$selectedVariantItems = $sku?->skuRelatonWith_VariantItems ?? collect();

							if ($selectedVariantItems->isEmpty()) {
								$selectedVariantItems = collect([null]);
							}

							$skuGallery = $sku?->skuRelationWith_Gallery ?? collect();
						@endphp

						<section
							class="productSellableItemBlock my-2 grid grid-cols-6 gap-x-4 p-4 border bg-blue-50 dark:bg-neutral-700 border-gray-400 rounded-lg"
							data-variant-index="{{ $variantIndex }}">

							<input type="hidden" name="variants[{{ $variantIndex }}][product_sku_id]" class="productSkuIdInput"
								value="{{ $sku?->id }}">
							<input type="hidden" name="variants[{{ $variantIndex }}][status]"
								value="{{ old("variants.$variantIndex.status", $sku?->status ?? 1) }}">

							<div class="col-span-1 my-4">
								<label class="inputLabelMedium">#</label>
								<input type="text" class="normalInput variantSerial bg-blue-900 text-white" value="Variant: {{ $variantIndex + 1 }}" readonly>
							</div>

							<div class="col-span-1 my-4">
								<label class="inputLabelMedium">SKU ID:</label>
								<input type="text" class="normalInput variantSerial" value="{{ $sku->id }}" readonly>
							</div>

							<div class="col-span-4 my-4">
								<label class="inputLabelMedium">SKU
									<span class="text-orange-500 text-sm">(Must be unique)</span>
									<span class="grayLabel">(Better keep it blank only for new SKUs.)</span>
								</label>

								<input type="text" name="variants[{{ $variantIndex }}][product_sku_uid]" class="normalInput"
									value="{{ old("variants.$variantIndex.product_sku_uid", $sku?->product_sku_uid) }}">
							</div>

							<div class="col-span-6 my-4">
								<label class="inputLabelMedium">Variant Product Item Title
									<span class="text-red-500">*</span>
								</label>

								<input type="text" name="variants[{{ $variantIndex }}][name]" class="normalInput"
									value="{{ old("variants.$variantIndex.name", $sku?->name) }}" placeholder="max 255-characters">
							</div>

							<div class="col-span-6 my-4">
								<label class="inputLabelMedium">Variant Product Description
									<span class="grayLabel">(Optional) (Max 2000 characters) (This is the detail each single variant
										will have)</span>
								</label>

								<textarea class="summernote"
									name="variants[{{ $variantIndex }}][description]">{{ old("variants.$variantIndex.description", $sku?->description) }}</textarea>
							</div>

							<div class="variantItemsWrapper col-span-6 my-4 grid grid-cols-12 gap-4">
								<label class="inputLabelMedium col-span-12">Add variant types
									<span class="grayLabel">(Optional)</span>
								</label>

								@foreach ($selectedVariantItems as $itemIndex => $selectedItem)
									<div class="typeItemBlock col-span-6 grid grid-cols-12 gap-4 items-start"
										data-item-index="{{ $itemIndex }}">
										<div class="col-span-5">
											<select name="variants[{{ $variantIndex }}][items][{{ $itemIndex }}][variant_type_id]"
												class="singleSelectWithSearch dropdownSelectInputs variantTypeSelect"
												data-dropdown-position="absolute"
												data-variant-items-url="{{ route('api_estore_fetch_variant_items_by_type', ['variantType' => '__ID__']) }}"
												data-variant-items-placeholder="Select a variant item">

												<option value="">Type</option>

												@foreach ($variantTypes as $type)
													<option value="{{ $type->id }}"
														@selected((int)old("variants.$variantIndex.items.$itemIndex.variant_type_id", $selectedItem?->variant_type_id) === (int)$type->id)>
														[{{ $type->id }}] | {{ $type->name }}
													</option>
												@endforeach
											</select>
										</div>

										<div class="col-span-5">
											<select name="variants[{{ $variantIndex }}][items][{{ $itemIndex }}][variant_item_id]"
												class="singleSelectWithSearch dropdownSelectInputs variantItemSelect"
												data-dropdown-position="absolute">

												<option value="">Item</option>

												@if ($selectedItem)
													<option value="{{ $selectedItem->id }}" selected>
														[{{ $selectedItem->id }}] | {{ $selectedItem->name }}
													</option>
												@endif
											</select>
										</div>

										<div class="col-span-2">
											<button type="button" class="removeTypeItemButton px-2 hover:bg-gray-200 py-2 rounded">
												{!! \App\Helpers\IconPack::cross(['class' => 'iconPackItem w-5 h-5 text-gray-500 pointer-events-none']) !!}
											</button>
										</div>
									</div>
								@endforeach
							</div>

							<div class="col-span-6 flex justify-end">
								<button type="button" class="addTypeButton bg-gray-700 text-white text-xs px-2 py-1 rounded">
									+ Add Type
								</button>
							</div>

							<div class="col-span-2 mt-8 mb-4">
								<label class="inputLabelMedium">Stock<span class="text-red-500">*</span></label>
								<input type="text" name="variants[{{ $variantIndex }}][stock]" class="normalInput"
									value="{{ old("variants.$variantIndex.stock", $sku?->stock ?? 0) }}">
							</div>

							<div class="col-span-2 mt-8 mb-4">
								<label class="inputLabelMedium">Starting price<span class="text-red-500">*</span></label>
								<input type="text" name="variants[{{ $variantIndex }}][starting_price]" class="normalInput"
									value="{{ old("variants.$variantIndex.starting_price", $sku?->starting_price ?? 0) }}">
							</div>

							<div class="col-span-2 mt-8 mb-4 items-center">
								<label class="inputLabelMedium">Final price<span class="text-red-500">*</span></label>
								<input type="text" name="variants[{{ $variantIndex }}][final_price]" class="normalInput"
									value="{{ old("variants.$variantIndex.final_price", $sku?->final_price ?? 0) }}">
							</div>

							<div class="col-span-6 my-4">
								<label class="inputLabelMedium">Variant Images
									<span class="grayLabel">(Optional) (Multiple allowed) (Max 2 MB each) (For faster loading, use
										smaller files with webp format) (Only one file will be previewed)</span>
									<span class="text-sm text-yellow-500 font-light"> (allowed formats: jpeg,png,jpg,webp)</span>
								</label>

								<input type="file" name="variants[{{ $variantIndex }}][gallery_file][]"
									class="dropify variantGalleryDropify" data-height="100" accept="image/*" multiple>

								@if ($skuGallery->isNotEmpty())
									<div class="existingSkuGalleryArea mt-3 grid grid-cols-2 md:grid-cols-4 gap-3">
										@foreach ($skuGallery as $gallery)
											<div class="rounded border border-gray-200 dark:border-neutral-600 p-2">
												<img src="{{ $gallery->filepath_url }}" class="w-full h-20 object-cover rounded"
													alt="{{ $gallery->filename }}">

												<label class="mt-2 flex items-center gap-2 text-xs text-gray-600 dark:text-gray-300">
													<input type="checkbox" name="variants[{{ $variantIndex }}][remove_gallery_ids][]"
														value="{{ $gallery->id }}">
													[{{ $gallery?->id }}]-Remove
												</label>
											</div>
										@endforeach
									</div>
								@endif
							</div>

							<div class="col-span-4 mt-8 mb-4 flex items-center">
								<input type="radio" name="default_variant_index" value="{{ $variantIndex }}"
									id="default_variant_{{ $variantIndex }}"
									class="defaultVariantRadio w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
									@checked((int)old('default_variant_index', $sku?->featured ? $variantIndex : -1) === $variantIndex)>

								<label for="default_variant_{{ $variantIndex }}" class="inputLabelMedium my-0 mx-2">Set as
									Default</label>
							</div>

							<div class="col-span-2 flex justify-end items-center">
								<button type="button" class="removeVariantButton bg-red-700 text-white text-xs px-3 py-2 rounded">
									Remove Variant
								</button>
							</div>

						</section>
					@endforeach

					<div class="my-4">
						<button type="button" id="addVariantButton"
							class="bg-indigo-700 text-white text-sm px-2 py-1 rounded">
							+ Add Variant
						</button>
					</div>
				</div>

				<div class="my-4 p-4 grid grid-cols-12 gap-x-4 rounded-lg border border-violet-200">
					<div class="col-span-12 flex justify-center">
						<h4 class="my-1 py-2 px-4 rounded-lg bg-indigo-900 dark:bg-indigo-700 text-gray-200">Edit Product
							Gallery</h4>
					</div>

					<div class="col-span-6 my-4">
						<label for="featured_file" class="inputLabelMedium">Feature Image<span
								class="text-sm text-gray-400 font-light"> (Max 2 MB)</span><span class="text-sm text-yellow-500 font-light"> (allowed formats: jpeg,png,jpg,webp)</span></span></label>

						<input type="hidden" name="featured_file_id"
							value="{{ $productGallery->firstWhere('filetype', 'featured')?->id}}">
						<input type="file" id="imageDropify" name="featured_file" class="dropify" data-height="150" @if ($featureFile) data-default-file="{{ $featureFile ? Storage::url($featureFile) : null }}" @endif />
						<input type="hidden" name="remove_image" id="remove_image" value="0">

					</div>

					<div class="col-span-6 my-4">
						<label class="inputLabelMedium">Gallery Images
							<span class="text-sm text-gray-400 font-light">(Multiple allowed) (Max 2 MB each) (Only one file
								will be previewed)</span>
						</label>

						<input type="file" id="galleryDropify" name="gallery_file[]" class="dropify" data-height="150"
							accept="image/*" multiple>
					</div>

					@if ($productGallery->isNotEmpty())
						<div class="col-span-12 mt-4">
							<label class="inputLabelMedium">Existing Product Gallery</label>

							<div class="grid grid-cols-2 md:grid-cols-5 gap-3 mt-2">
								@foreach ($productGallery->where('filetype', '!=', 'featured') as $gallery)
									<div class="rounded border border-gray-200 dark:border-neutral-600 p-2">
										<img src="{{ $gallery->filepath_url }}" class="w-full h-24 object-cover rounded"
											alt="{{ $gallery->filename }}">

										<div class="mt-2 text-xs text-gray-500">
											[{{ $gallery?->id }}]-{{ $gallery->filetype }}
										</div>

										<label class="mt-2 flex items-center gap-2 text-xs text-gray-600 dark:text-gray-300">
											<input type="checkbox" name="remove_product_gallery_ids[]" value="{{ $gallery->id }}">
											Remove
										</label>
									</div>
								@endforeach
							</div>
						</div>
					@endif
				</div>

				<div class="mb-5 mt-2">
					<label class="inputLabelMedium">Active Status</label>

					<select name="status" class="dropdownSelectInputs">
						<option value="1" @selected(old('status', $singleProductItem->status) == 1)>Active</option>
						<option value="0" @selected(old('status', $singleProductItem->status) == 0)>Inactive</option>
					</select>
				</div>
			</div>

			<button type="submit" class="saveButton my-4" data-confirm-submit="hs-confirm-modal">Update</button>
			<button type="button" data-modal-target="hs-confirm-modal" data-modal-toggle="hs-confirm-modal" class="hidden"></button>

			<x-ui_items.modals.confirm-modal id="hs-confirm-modal" title="Product update" confirmText="Yes, update"
				confirmColor="bg-indigo-600 hover:bg-indigo-800 focus:ring-indigo-300 dark:focus:ring-indigo-800"
				cancelText="Cancel" message="Are you sure you want to update this product? Effective immediately!" />
		</form>
	</div>

</x-app-layout>

<script>
	document.addEventListener('DOMContentLoaded', function () {
		const variantContainer = document.querySelector('.my-4.p-4.rounded-lg.border.border-green-200');
		const addVariantButton = document.getElementById('addVariantButton');
		const deletedSkuContainer = document.getElementById('deletedSkuContainer');

		function destroySingleSelectDropdowns(scope) {
			scope.querySelectorAll('select.singleSelectWithSearch').forEach(function (select) {
				const nextElement = select.nextElementSibling;

				if (nextElement && nextElement.classList.contains('multiselect-dropdown')) {
					nextElement.remove();
				}

				select.removeAttribute('data-init');
				select.style.display = '';

				if (select.refreshSingleSelectDropdown) {
					delete select.refreshSingleSelectDropdown;
				}
			});
		}

		function initSingleSelectDropdowns() {
			if (typeof SingleSelectDropdown === 'function') {
				SingleSelectDropdown();
			}
		}

		function refreshSingleSelect(select) {
			if (!select) {
				return;
			}

			if (typeof select.refreshSingleSelectDropdown === 'function') {
				select.refreshSingleSelectDropdown();
				return;
			}

			destroySingleSelectDropdowns(select.parentElement);
			initSingleSelectDropdowns();
		}

		function cleanClonedSummernote(scope) {
			if (typeof window.jQuery === 'undefined') {
				return;
			}

			window.jQuery(scope).find('.note-editor').remove();

			window.jQuery(scope).find('textarea.summernote').each(function () {
				window.jQuery(this)
					.removeAttr('style')
					.removeData()
					.val('');
			});
		}

		function cleanClonedDropify(scope) {
			if (typeof window.jQuery === 'undefined') {
				return;
			}

			window.jQuery(scope).find('.dropify-wrapper').each(function () {
				const wrapper = window.jQuery(this);
				const input = wrapper.find('input.dropify').first();

				if (input.length) {
					wrapper.before(input);
				}

				wrapper.remove();
			});

			window.jQuery(scope).find('input.dropify').each(function () {
				window.jQuery(this).val('').removeAttr('id').removeData('dropify');
			});
		}

		function initClonedDropify(scope) {
			if (typeof window.jQuery === 'undefined' || typeof window.jQuery.fn.dropify === 'undefined') {
				return;
			}

			window.jQuery(scope).find('input.dropify').dropify();
		}

		function cleanClonedExistingData(block) {
			const skuIdInput = block.querySelector('.productSkuIdInput');

			if (skuIdInput) {
				skuIdInput.value = '';
			}

			block.querySelectorAll('.existingSkuGalleryArea').forEach(function (area) {
				area.remove();
			});

			const wrapper = block.querySelector('.variantItemsWrapper');
			const itemBlocks = wrapper ? wrapper.querySelectorAll('.typeItemBlock') : [];

			itemBlocks.forEach(function (itemBlock, index) {
				if (index > 0) {
					itemBlock.remove();
				}
			});
		}

		function reindexVariantBlock(block, variantIndex) {
			block.dataset.variantIndex = variantIndex;

			const serial = block.querySelector('.variantSerial');

			if (serial) {
				serial.value = variantIndex + 1;
			}

			block.querySelectorAll('[name]').forEach(function (input) {
				input.name = input.name.replace(/variants\[\d+\]/g, `variants[${variantIndex}]`);
			});

			block.querySelectorAll('.typeItemBlock').forEach(function (itemBlock, itemIndex) {
				reindexTypeItemBlock(itemBlock, variantIndex, itemIndex);
			});

			const defaultRadio = block.querySelector('.defaultVariantRadio');
			const defaultLabel = block.querySelector('label[for^="default_variant_"]');

			if (defaultRadio) {
				defaultRadio.value = variantIndex;
				defaultRadio.id = `default_variant_${variantIndex}`;
			}

			if (defaultLabel) {
				defaultLabel.setAttribute('for', `default_variant_${variantIndex}`);
			}
		}

		function reindexTypeItemBlock(itemBlock, variantIndex, itemIndex) {
			itemBlock.dataset.itemIndex = itemIndex;

			const typeSelect = itemBlock.querySelector('.variantTypeSelect');
			const itemSelect = itemBlock.querySelector('.variantItemSelect');

			if (typeSelect) {
				typeSelect.name = `variants[${variantIndex}][items][${itemIndex}][variant_type_id]`;
			}

			if (itemSelect) {
				itemSelect.name = `variants[${variantIndex}][items][${itemIndex}][variant_item_id]`;
			}
		}

		function resetSelect(select) {
			select.innerHTML = select.classList.contains('variantItemSelect')
				? '<option value="">Item</option>'
				: select.innerHTML;

			select.value = '';

			Array.from(select.options).forEach(function (option, index) {
				option.selected = index === 0;
			});
		}

		function clearBlockValues(block) {
			block.querySelectorAll('input, textarea, select').forEach(function (field) {
				if (field.type === 'hidden') {
					if (field.classList.contains('productSkuIdInput')) {
						field.value = '';
					}

					return;
				}

				if (field.classList.contains('variantSerial')) {
					return;
				}

				if (field.tagName === 'SELECT') {
					resetSelect(field);
					return;
				}

				if (field.classList.contains('defaultVariantRadio')) {
					field.checked = false;
					return;
				}

				field.value = '';
			});
		}

		function resetTypeItemBlock(block) {
			block.querySelectorAll('select').forEach(function (select) {
				resetSelect(select);
			});
		}

		function refreshVariantBlocksIndex() {
			document.querySelectorAll('.productSellableItemBlock').forEach(function (block, index) {
				reindexVariantBlock(block, index);
			});
		}

		function refreshTypeItemBlocksIndex(wrapper) {
			const variantBlock = wrapper.closest('.productSellableItemBlock');
			const variantIndex = Number(variantBlock.dataset.variantIndex);

			wrapper.querySelectorAll('.typeItemBlock').forEach(function (itemBlock, itemIndex) {
				reindexTypeItemBlock(itemBlock, variantIndex, itemIndex);
			});
		}

		function setItemOptions(itemSelect, items, placeholder) {
			itemSelect.innerHTML = '';

			const defaultOption = document.createElement('option');
			defaultOption.value = '';
			defaultOption.textContent = placeholder;

			itemSelect.appendChild(defaultOption);

			items.forEach(function (item) {
				const option = document.createElement('option');

				option.value = item.id;

				if (item.value) {
					option.textContent = `[${item.id}] | ${item.name} | ${item.value}`;
				} else {
					option.textContent = `[${item.id}] | ${item.name}`;
				}

				itemSelect.appendChild(option);
			});

			refreshSingleSelect(itemSelect);
		}

		function setItemMessage(itemSelect, message) {
			itemSelect.innerHTML = '';

			const option = document.createElement('option');
			option.value = '';
			option.textContent = message;

			itemSelect.appendChild(option);

			refreshSingleSelect(itemSelect);
		}

		function loadVariantItems(typeSelect) {
			const typeItemBlock = typeSelect.closest('.typeItemBlock');

			if (!typeItemBlock) {
				return;
			}

			const itemSelect = typeItemBlock.querySelector('.variantItemSelect');

			if (!itemSelect) {
				return;
			}

			const selectedTypeId = typeSelect.value;
			const urlTemplate = typeSelect.dataset.variantItemsUrl;
			const placeholder = typeSelect.dataset.variantItemsPlaceholder || 'Select a variant item';

			if (!selectedTypeId || !urlTemplate) {
				setItemOptions(itemSelect, [], placeholder);
				return;
			}

			setItemMessage(itemSelect, 'Loading...');

			fetch(urlTemplate.replace('__ID__', selectedTypeId), {
				method: 'GET',
				headers: {
					'Accept': 'application/json',
					'X-Requested-With': 'XMLHttpRequest',
				},
			})
				.then(function (response) {
					if (!response.ok) {
						throw new Error('Variant items could not be loaded.');
					}

					return response.json();
				})
				.then(function (responseData) {
					const items = Array.isArray(responseData)
						? responseData
						: responseData.data || [];

					setItemOptions(itemSelect, items, placeholder);
				})
				.catch(function () {
					setItemMessage(itemSelect, 'Failed to load options');
				});
		}

		addVariantButton.addEventListener('click', function () {
			const blocks = document.querySelectorAll('.productSellableItemBlock');
			const firstBlock = blocks[0];
			const newIndex = blocks.length;
			const clone = firstBlock.cloneNode(true);

			destroySingleSelectDropdowns(clone);
			cleanClonedSummernote(clone);
			cleanClonedDropify(clone);
			cleanClonedExistingData(clone);
			clearBlockValues(clone);
			reindexVariantBlock(clone, newIndex);

			variantContainer.insertBefore(clone, addVariantButton.parentElement);

			initSingleSelectDropdowns();
			initClonedDropify(clone);

			if (typeof window.initSummernote === 'function') {
				window.initSummernote(clone);
			}
		});

		document.addEventListener('click', function (event) {
			const addTypeButton = event.target.closest('.addTypeButton');
			const removeTypeButton = event.target.closest('.removeTypeItemButton');
			const removeVariantButton = event.target.closest('.removeVariantButton');

			if (addTypeButton) {
				const variantBlock = addTypeButton.closest('.productSellableItemBlock');
				const wrapper = variantBlock.querySelector('.variantItemsWrapper');
				const firstItemBlock = wrapper.querySelector('.typeItemBlock');
				const variantIndex = Number(variantBlock.dataset.variantIndex);
				const itemIndex = wrapper.querySelectorAll('.typeItemBlock').length;
				const clone = firstItemBlock.cloneNode(true);

				destroySingleSelectDropdowns(clone);
				resetTypeItemBlock(clone);

				wrapper.appendChild(clone);

				refreshTypeItemBlocksIndex(wrapper);
				initSingleSelectDropdowns();

				return;
			}

			if (removeTypeButton) {
				const wrapper = removeTypeButton.closest('.variantItemsWrapper');
				const itemBlocks = wrapper.querySelectorAll('.typeItemBlock');

				if (itemBlocks.length <= 1) {
					return;
				}

				removeTypeButton.closest('.typeItemBlock').remove();
				refreshTypeItemBlocksIndex(wrapper);

				return;
			}

			if (removeVariantButton) {
				const blocks = document.querySelectorAll('.productSellableItemBlock');

				if (blocks.length <= 1) {
					return;
				}

				const block = removeVariantButton.closest('.productSellableItemBlock');
				const skuIdInput = block.querySelector('.productSkuIdInput');

				if (skuIdInput && skuIdInput.value) {
					const deletedInput = document.createElement('input');

					deletedInput.type = 'hidden';
					deletedInput.name = 'deleted_sku_ids[]';
					deletedInput.value = skuIdInput.value;

					deletedSkuContainer.appendChild(deletedInput);
				}

				block.remove();
				refreshVariantBlocksIndex();
			}
		});

		document.addEventListener('change', function (event) {
			const typeSelect = event.target.closest('.variantTypeSelect');

			if (!typeSelect) {
				return;
			}

			loadVariantItems(typeSelect);
		});
	});
</script>