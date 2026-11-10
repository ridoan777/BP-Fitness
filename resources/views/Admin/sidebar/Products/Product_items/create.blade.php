<x-app-layout>

	@php
		$breadCrumbs = [
			['label' => 'All Categories', 'url' => 'backend_product_category_index', 'icon' => 'layers'],
			['label' => 'Variant Types', 'url' => 'backend_product_variant_type_index', 'icon' => 'layers'],
			['label' => 'Variant Items', 'url' => 'backend_product_variant_item_index', 'icon' => 'layers'],
			['label' => '+ Product Items'],
			['label' => 'Product Items', 'url' => 'backend_product_item_index', 'icon' => 'boxes'],
		];
	 @endphp

	<x-indicators.breadcrumb :crumbs="$breadCrumbs" />

	<!-- Start Main Widgets -->
	<x-indicators.page_header_widget pageName="Create Baseline Product Items" />
	<!-- End Main Widgets -->


	<!-- Alert Component -->
	<x-indicators.alert-component message="Form Submission" />
	<!-- Alert Component -->

	@php
		$ORDER_IN_PAGE = 1;
		$ACCORDION_1_VISIBILITY = ''; // hidden or ''
	@endphp

	<!-- ---------------- APP AREA Starts ---------------- -->
	<div class="mx-4 p-2 dark:bg-neutral-700 shadow rounded-lg">
		<form method="POST" action="{{ route('backend_product_item_store') }}" class="rounded-lg p-6 dark:bg-neutral-800"
			enctype="multipart/form-data">
			@csrf

			<!--- IDENTIFIER HIDDEN INPUTS -->
			<input type="hidden" name="flag" class="smallInput" value="create">
			<!--- IDENTIFIER HIDDEN INPUTS -->

			<div>
				<!-- ------ -->
				<div class="p-4 grid grid-cols-2 gap-4 rounded-lg border border-red-200">
					<div class="mb-5 mt-2">
						<label for="name" class="inputLabelMedium">Select Product Category
							<span class="text-red-500">*</span>
						</label>
						<select name="category_id" class="singleSelectWithSearch dropdownSelectInputs"
							data-dropdown-position="absolute">
							<option value="">Select a parent variant type</option>
							@foreach ($categories as $category)
								<option value="{{ $category->id }}">
									[{{ $category->id }}] | {{ $category->name }}
								</option>
							@endforeach
						</select>
					</div>
					<!-- -->
					<div class="mb-5 mt-2">
						<label for="product_item_uid" class="inputLabelMedium">Product ID
							<span class="text-red-500 text-sm">(Must be unique)</span>
							<span class="grayLabel">(Better keep it blank. Then an UID will be generated automatically.)</span>
						</label>
						<input type="text" name="product_item_uid" class="normalInput" placeholder="max 255-characters">
					</div>
				</div>
				<!-- ------ -->

				<div class="my-4 p-4 rounded-lg border border-blue-200">
					<div class="col-span-1 flex justify-center">
						<h4 class="my-1 py-2 px-4 rounded-lg bg-indigo-900 dark:bg-indigo-700 text-gray-200">Create Details
						</h4>
					</div>
					<div class="mb-5 mt-2">
						<label for="name" class="inputLabelMedium">Product Name/Title
							<span class="text-red-500">*</span>
						</label>
						<input type="text" name="name" class="mediumInput" placeholder="max 255-characters">
					</div>
					<!-- -->
					<div class="mb-5 mt-2">
						<label for="description" class="inputLabelMedium">Product Description
							<span class="text-sm text-gray-400 font-light">(Optional)(Max 2000 characters. Use Ctrl+Shift+V for safe pasting)</span>
						</label>
						<textarea class="summernote" name="description" id="description"></textarea>
					</div>
				</div>
				<!-- ------ -->

				<div class="my-4 p-4 rounded-lg border border-green-200">
					<div class="col-span-1 flex justify-center">
						<h4 class="my-1 py-2 px-4 rounded-lg bg-indigo-900 dark:bg-indigo-700 text-gray-200">Create Variants
						</h4>
					</div>
					<!-- -->
					
					@include('Admin.sidebar.Products.Product_items.sku')

					<div class="my-4">
						<button type="button" id="addVariantButton"
							class="bg-indigo-700 text-white text-sm px-2 py-1 rounded">
							+ Add Variant
						</button>
					</div>
				</div>
				<!-- ------ -->

				<div class="my-4 p-4 grid grid-cols-12 gap-x-4 rounded-lg border border-violet-200">
					<div class="col-span-12 flex justify-center">
						<h4 class="my-1 py-2 px-4 rounded-lg bg-indigo-900 dark:bg-indigo-700 text-gray-200">Create Gallery
						</h4>
					</div>
					<!-- -->
					<div class="col-span-6 my-4">
						<label for="featured_file" class="inputLabelMedium">Feature Image<span
								class="text-sm text-gray-400 font-light"> (Max 2 MB)</span><span class="text-sm text-yellow-500 font-light"> (allowed formats: jpeg,png,jpg,webp)</span></span></label>
						<input type="file" id="imageDropify" name="featured_file" class="dropify" data-height="150" />
						<input type="hidden" name="remove_image" id="remove_image" value="0">
					</div>
					<!-- -->
					<div class="col-span-6 my-4">
						<label for="gallery_file" class="inputLabelMedium">Gallery Images <span
								class="text-sm text-gray-400 font-light">(Multiple allowed) (Max 2 MB each) (Only one file will
								be previewed)</span></label>
						<input type="file" id="galleryDropify" name="gallery_file[]" class="dropify" data-height="150"
							accept="image/*" multiple>
					</div>
				</div>
				<!-- ------ -->

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
	</div>

	<!-- ---------------- APP AREA Starts ---------------- -->

</x-app-layout>

<script>
	document.addEventListener('DOMContentLoaded', function () {
		const variantContainer = document.querySelector('.my-4.p-4.rounded-lg.border.border-green-200');
		const addVariantButton = document.getElementById('addVariantButton');

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

		// -------- summernote re-initialization --------
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
		// -------- summernote re-initialization --------

		// -------- dropify fix for variant gallery block --------
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
		// -------- dropify fix for variant gallery block --------

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

				removeVariantButton.closest('.productSellableItemBlock').remove();

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