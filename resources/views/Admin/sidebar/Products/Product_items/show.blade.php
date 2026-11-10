<x-app-layout>

	@php
		$breadCrumbs = [
			['label' => 'All Categories', 'url' => 'backend_product_category_index', 'icon' => 'layers'],
			['label' => 'Variant Types', 'url' => 'backend_product_variant_type_index', 'icon' => 'layers'],
			['label' => 'Variant Items', 'url' => 'backend_product_variant_item_index', 'icon' => 'layers'],
			['label' => 'Product Items', 'url' => 'backend_product_item_index', 'icon' => 'boxes'],
			['label' => 'View Product Item'],
		];
	@endphp

	<x-indicators.breadcrumb :crumbs="$breadCrumbs" />

	<x-indicators.page_header_widget pageName="Product Item: {{ $singleProductItem->name }}" />

	<x-indicators.alert-component message="Form Submission" />

	@php
		$productGallery = $singleProductItem->productItemRelatonWith_Gallery;
		$featuredImage = $productGallery->firstWhere('filetype', 'featured') ?? $productGallery->first();

		$defaultSku = $skuItems->firstWhere('featured', 1) ?? $skuItems->first();

		$formatMoney = function ($value) {
			return $value !== null ? number_format((float)$value, 2) : 'N/A';
		};
	@endphp
	<!-- ---------------- APP AREA Starts ---------------- -->
	<div class="p-4 space-y-6">
		<div class="flex justify-end">
			<a href="{{ route('backend_product_item_edit', ['singleProductItem' => $singleProductItem->id, 'slug' => $singleProductItem->slug]) }}"
				class="saveButton">Edit</a>
		</div>

		{{-- PRODUCT HEADER --}}
		<div class="grid grid-cols-12 gap-4 rounded-lg border bg-gray-50 dark:bg-neutral-700 border-gray-200 dark:border-neutral-600 p-4">
			<div class="col-span-12 md:col-span-4">
				<div
					class="rounded-lg overflow-hidden border border-gray-200 dark:border-neutral-600 bg-gray-50 dark:bg-neutral-800">
					<img id="mainProductPreviewImage"
						src="{{ $featuredImage?->filepath_url ?? asset('site_assets/dummies/dummy_no_file.jpg') }}"
						alt="{{ $singleProductItem->name }}" class="w-full h-64 object-cover">
				</div>

				@if ($productGallery->isNotEmpty())
					<div class="mt-3 grid grid-cols-5 gap-2">
						@foreach ($productGallery as $gallery)
							<button type="button"
								class="productGalleryThumb rounded border border-gray-200 dark:border-neutral-600 overflow-hidden"
								data-image-url="{{ $gallery->filepath_url }}">
								<img src="{{ $gallery->filepath_url }}" alt="{{ $gallery->filename }}"
									class="w-full h-14 object-cover">
							</button>
						@endforeach
					</div>
				@endif
			</div>

			<div class="col-span-12 md:col-span-8 space-y-3">
				<div>
					<h2 class="text-xl font-semibold dark:text-gray-100">{{ $singleProductItem->name }}</h2>
					<p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $singleProductItem->product_item_uid }}</p>
				</div>

				<div class="grid grid-cols-2 gap-3 text-sm">
					<div>
						<span class="text-gray-500 dark:text-gray-400">DB ID:</span>
						<span class="dark:text-gray-100">{{ $singleProductItem->id }}</span>
					</div>

					<div>
						<span class="text-gray-500 dark:text-gray-400">Category:</span>
						<span
							class="dark:text-gray-100">{{ $singleProductItem->productItemRelatingBackTo_Category?->name ?? 'N/A' }}</span>
					</div>

					<div>
						<span class="text-gray-500 dark:text-gray-400">Slug:</span>
						<span class="dark:text-gray-100">{{ $singleProductItem->slug }}</span>
					</div>

					<div>
						<span class="text-gray-500 dark:text-gray-400">Status:</span>
						<span
							class="dark:text-gray-100">{{ (int)$singleProductItem->status === 1 ? 'Active' : 'Inactive' }}</span>
					</div>

					<div>
						<span class="text-gray-500 dark:text-gray-400">Created:</span>
						<span
							class="dark:text-gray-100">{{ $singleProductItem->created_at?->format('d M Y, H:i') ?? 'N/A' }}</span>
					</div>

					<div>
						<span class="text-gray-500 dark:text-gray-400">Updated:</span>
						<span
							class="dark:text-gray-100">{{ $singleProductItem->updated_at?->format('d M Y, H:i') ?? 'N/A' }}</span>
					</div>
				</div>

				<div>
					<h4 class="text-sm font-semibold dark:text-gray-100 mb-1">Description</h4>
					<div class="prose prose-sm max-w-none dark:prose-invert text-gray-700 dark:text-gray-200">
						{!! $singleProductItem->description ?: '<span class="text-gray-400">No description added.</span>' !!}
					</div>
				</div>
			</div>
		</div>

		{{-- VARIANT OPTIONS --}}
		<div class="rounded-lg border bg-gray-50 dark:bg-neutral-700 border-gray-200 dark:border-neutral-600 p-4">
			<h3 class="text-base font-semibold dark:text-gray-100 mb-3">Variant Options</h3>

			@if ($variantOptions->isEmpty())
				<p class="text-sm text-gray-500 dark:text-gray-400">No variant options added. This product behaves like a
					simple product.</p>
			@else
				<div class="space-y-4">
					@foreach ($variantOptions as $type)
						<div>
							<h4 class="text-sm font-semibold dark:text-gray-100 mb-2">{{ $type->name }}</h4>

							<div class="flex flex-wrap gap-2">
								@foreach ($type->items as $item)
									<span
										class="inline-flex items-center rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-100 px-3 py-1 text-xs">
										{{ $item->name }}
										@if ($item->value)
											<span class="ml-1 text-gray-500 dark:text-gray-300">({{ $item->value }})</span>
										@endif
									</span>
								@endforeach
							</div>
						</div>
					@endforeach
				</div>
			@endif
		</div>

		{{-- SELLABLE SKUS --}}
		<div class="rounded-lg border bg-gray-50 dark:bg-neutral-700 border-gray-200 dark:border-neutral-600 p-4">
			<div class="flex items-center justify-between mb-3">
				<h3 class="text-base font-semibold dark:text-gray-100">Sellable SKU Variants</h3>
				<span class="text-xs text-gray-500 dark:text-gray-400">Total: {{ $skuItems->count() }}</span>
			</div>

			@if ($skuItems->isEmpty())
				<p class="text-sm text-gray-500 dark:text-gray-400">No sellable SKU added.</p>
			@else
				<div class="space-y-4">
					@foreach ($skuItems as $sku)
						<div
							class="skuDisplayBlock rounded-lg border bg border-gray-200 dark:border-neutral-600 p-4 {{ (int)$sku->featured === 1 ? 'bg-green-50 dark:bg-green-900/20' : '' }}">
							<div class="grid grid-cols-12 gap-4">
								<div class="col-span-12 lg:col-span-8">
									<div class="flex items-center gap-2 mb-2">
										<p class="text-xs text-gray-400">DB: [{{ $sku?->id }}]</p>
										<h4 class="m-0 text-sm font-semibold dark:text-gray-100">{{ $sku->name }}</h4>

										@if ((int)$sku->featured === 1)
											<span class="rounded-full bg-green-600 text-white text-xs px-2 py-0.5">Default</span>
										@endif
									</div>

									<div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
										<div>
											<span class="text-gray-500 dark:text-gray-400">SKU ID:</span>
											<div class="text-xs dark:text-gray-100 break-all">{{ $sku->product_sku_uid }}</div>
										</div>

										<div>
											<span class="text-gray-500 dark:text-gray-400">Stock:</span>
											<div class="dark:text-gray-100">{{ $sku->stock }}</div>
										</div>

										<div>
											<span class="text-gray-500 dark:text-gray-400">Starting:</span>
											<div class="dark:text-gray-100">{{ $formatMoney($sku->starting_price) }}</div>
										</div>

										<div>
											<span class="text-gray-500 dark:text-gray-400">Final:</span>
											<div class="font-semibold dark:text-gray-100">{{ $formatMoney($sku->final_price) }}</div>
										</div>
									</div>

									@if ($sku->description)
										<div class="mt-3 text-sm text-gray-700 dark:text-gray-200">
											{!! $sku->description !!}
										</div>
									@endif

									<div class="mt-3">
										<h5 class="text-xs font-semibold text-gray-600 dark:text-gray-300 mb-2">Selected Variant Items
										</h5>

										@if ($sku->skuRelatonWith_VariantItems->isEmpty())
											<span class="text-xs text-gray-400">No variant item selected.</span>
										@else
											<div class="flex flex-wrap gap-2">
												@foreach ($sku->skuRelatonWith_VariantItems as $variantItem)
													<span
														class="inline-flex items-center rounded-full bg-gray-100 text-gray-800 dark:bg-neutral-700 dark:text-gray-100 px-3 py-1 text-xs">
														{{ $variantItem->variantItemRelatingBackTo_Type?->name ?? 'Type' }}:
														<span class="ml-1 font-semibold">{{ $variantItem->name }}</span>
													</span>
												@endforeach
											</div>
										@endif
									</div>
								</div>

								<div class="col-span-12 lg:col-span-4">
									<h5 class="text-xs font-semibold text-gray-600 dark:text-gray-300 mb-2">SKU Gallery</h5>

									@if ($sku->skuRelationWith_Gallery->isEmpty())
										<div
											class="rounded-lg border border-dashed border-gray-300 dark:border-neutral-600 p-4 text-center text-xs text-gray-400">
											No SKU images are found.
										</div>
									@else
										<div class="grid grid-cols-3 gap-2">
											@foreach ($sku->skuRelationWith_Gallery as $gallery)
												<button type="button"
													class="skuGalleryThumb rounded border border-gray-200 dark:border-neutral-600 overflow-hidden"
													data-image-url="{{ $gallery->filepath_url }}">
													<img src="{{ $gallery->filepath_url }}" alt="{{ $gallery->filename }}"
														class="w-full h-16 object-cover">
												</button>
											@endforeach
										</div>
									@endif
								</div>
							</div>
						</div>
					@endforeach
				</div>
			@endif
		</div>
	</div>
	<!-- ---------------- APP AREA Starts ---------------- -->

</x-app-layout>

<script>
	(function () {
		const previewImage = document.getElementById('mainProductPreviewImage');

		document.addEventListener('click', function (event) {
			const thumb = event.target.closest('.productGalleryThumb, .skuGalleryThumb');

			if (!thumb || !previewImage) {
				return;
			}

			const imageUrl = thumb.dataset.imageUrl;

			if (!imageUrl) {
				return;
			}

			previewImage.src = imageUrl;
		});
	})();
</script>