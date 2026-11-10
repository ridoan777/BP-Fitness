<x-app-layout>

	@php
		$breadCrumbs = [
			['label' => 'All Categories', 'url' => 'backend_product_category_index', 'icon' => 'layers'],
			['label' => 'Variant Types', 'url' => 'backend_product_variant_type_index', 'icon' => 'layers'],
			['label' => 'Variant Items', 'url' => 'backend_product_variant_item_index', 'icon' => 'layers'],
			['label' => 'Product', 'url' => 'backend_product_item_create', 'icon' => 'plus'],
			['label' => 'Product Items'],
		];
	 @endphp

	<x-indicators.breadcrumb :crumbs="$breadCrumbs" />

	<!-- Start Main Widgets -->
	<x-indicators.page_header_widget pageName="All E-Store Product Items" />
	<!-- End Main Widgets -->


	<!-- Alert Component -->
	<x-indicators.alert-component message="Form Submission" />
	<!-- Alert Component -->

	@php
		$ORDER_IN_PAGE = 1;
		$ACCORDION_1_VISIBILITY = ''; // hidden or ''
	@endphp

	<!-- ---------------- APP AREA Starts ---------------- -->
	<div class="hs-accordion-group">

		<x-ui_items.accordion.accordion-destroy />

		<div id="hs-destroy-heading-two"
			class="hs-accordion-to-destroy hs-accordion hs-accordion-active:border-gray-200 PrelineAccordionItemParent">

			<x-ui_items.accordion.accordion_toggle_button toggleLabel="Baseline Product Items (Non-Sellable)" />

			<div id="hs-destroy-collapse-two"
				class="hs-accordion-content {{ $ACCORDION_1_VISIBILITY }} w-full overflow-hidden transition-[height] duration-300"
				role="region" aria-labelledby="hs-destroy-heading-two">
				<div class="p-3">
					<!-- --------------------------------- -->
					<section
						class="displayArea mx-auto my-5 rounded-lg border-none bg-white p-4 shadow-md shadow-gray-400 dark:bg-neutral-700 dark:shadow-none">
						<div class="my-5">
							@include('Admin.sidebar.Products.Product_items.filter')
							<div class="overflow-x-auto">
								<form action="{{ route('backend_product_item_bulk_delete') }}" method="POST">
									@csrf
									<x-ui_items.datatable.datatable-btn-group exportRoute="backend_projects_spreadsheet_export"
										importRoute="backend_projects_excel_import" spreadsheetButton="false" importButton="false"
										pdfButton="false" deleteButton="true" />
									<!------->
									<table id="dataTableDisplay"
										class="dataTableDisplay min-w-full text-sm bg-white rounded-xl shadow-lg">
										<thead class="text-xs text-gray-200 uppercase bg-gray-600 dark:bg-neutral-500">
											<tr class="text-center">
												<th>SN</th>
												<th>DB ID</th>
												<th class="max-w-32 text-center">UID</th>
												<th class="w-32">Category</th>
												<th class="min-w-40">Product</th>
												<th class="w-24 text-center">Total Sellables</th>
												<th class="min-w-32">Price Range</th>
												<th class="min-w-32">Variants</th>
												<th>Status</th>
												<th>Created At</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
								</form>
							</div>
						</div>
				</div>
				</section>
			</div>
		</div>
	</div>
	<!-- ---------------- APP AREA Starts ---------------- -->

	<!----------- DATATABLE ----------->
	<script>
		$(function () {
			window.dataTableDisplay = $('#dataTableDisplay').DataTable({
				processing: true,
				serverSide: true,
				responsive: true,
				ordering: true,
				scrollX: true,
				ajax: {
					url: "{{ route('backend_product_item_datatable') }}",
					type: 'POST',
					headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
					data: function (data) {
						data.category_id = $('#filter_category_id').val();
						data.status = $('#filter_status').val();
						data.min_price = $('#filter_min_price').val();
						data.max_price = $('#filter_max_price').val();
						data.variant_type_id = $('#filter_variant_type_id').val();
						data.has_sellables = $('#filter_has_sellables').val();
					}
				},
				columns: [
					{ data: 'SN', className: 'px-3 py-2 text-center' },
					{ data: 'id', className: 'px-3 py-2 text-center' },
					{ data: 'uid', className: 'max-w-32 px-3 py-2 text-center text-xs text-gray-500' },
					{ data: 'category_name', className: 'w-32 px-3 py-2 text-center' },
					{ data: 'product_name', className: 'px-3 py-2 text-center' },
					{ data: 'total_sellables', className: 'px-3 py-2 text-center' },
					{ data: 'price_range', className: 'px-3 py-2 text-center' },
					{ data: 'variants_summary', className: 'px-3 py-2 text-center' },
					{ data: 'status_label', className: 'px-3 py-2 text-center', orderable: false, searchable: false },
					{ data: 'created_at_formatted', className: 'px-3 py-2 text-center' },
					{ data: 'actions', className: 'no-row-click px-3 py-2 text-center', orderable: false, searchable: false }
				],
				pageLength: 25,
				createdRow: function (row, data) {
					dtApplyRowEvents(row, data);
				}
			});
			$('#applyProductFilters').on('click', function () {
				window.dataTableDisplay.ajax.reload();
			});

			$('#resetProductFilters').on('click', function () {
				$('#filter_category_id').val('');
				$('#filter_status').val('');
				$('#filter_min_price').val('');
				$('#filter_max_price').val('');
				$('#filter_variant_type_id').val('');
				$('#filter_has_sellables').val('');

				window.dataTableDisplay.ajax.reload();
			});
		});
	</script>
	<!----------- DATATABLE ----------->



</x-app-layout>