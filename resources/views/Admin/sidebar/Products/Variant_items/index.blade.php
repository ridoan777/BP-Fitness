<x-app-layout>

	@php
		$breadCrumbs = [
			['label' => 'All Categories', 'url' => 'backend_product_category_index', 'icon' => 'layers'],
			['label' => 'Variant Types', 'url' => 'backend_product_variant_type_index', 'icon' => 'layers'],
			['label' => 'Variant Items'],
			['label' => 'Product', 'url' => 'backend_product_item_create', 'icon' => 'plus'],
			['label' => 'Product Items', 'url' => 'backend_product_item_index', 'icon' => 'boxes'],
		];
	 @endphp

	<x-indicators.breadcrumb :crumbs="$breadCrumbs" />

	<!-- Start Main Widgets -->
	<x-indicators.page_header_widget pageName="All E-Store Variant Items" />
	<!-- End Main Widgets -->


	<!-- Alert Component -->
	<x-indicators.alert-component message="Form Submission" />
	<!-- Alert Component -->

	@php
		$ORDER_IN_PAGE = 1;
		$ACCORDION_1_VISIBILITY = ''; // hidden or ''
	@endphp

	<!-- ---------------- APP AREA Starts ---------------- -->

	@include('Admin.sidebar.Products.Variant_items.create')
	<!-- ------------------------ -->
	<div class="hs-accordion-group">

		<x-ui_items.accordion.accordion-destroy />

		<div id="hs-destroy-heading-two"
			class="hs-accordion-to-destroy hs-accordion hs-accordion-active:border-gray-200 PrelineAccordionItemParent">

			<x-ui_items.accordion.accordion_toggle_button toggleLabel="Lists of Available Product Variant Items" />

			<div id="hs-destroy-collapse-two"
				class="hs-accordion-content {{ $ACCORDION_1_VISIBILITY }} w-full overflow-hidden transition-[height] duration-300"
				role="region" aria-labelledby="hs-destroy-heading-two">
				<div class="p-3">
					<!-- --------------------------------- -->
					<section
						class="displayArea mx-auto my-5 rounded-lg border-none bg-white p-4 shadow-md shadow-gray-400 dark:bg-neutral-700 dark:shadow-none">
						<div class="my-5">
							<div class="overflow-x-auto">
								<table id="dataTableDisplay"
									class="dataTableDisplay min-w-full text-sm bg-white rounded-xl shadow-lg">
									<thead class="text-xs text-gray-200 uppercase bg-gray-600 dark:bg-neutral-500">
										<tr>
											<th>SN</th>
											<th>DB ID</th>
											<th class="max-w-24 text-center">UID</th>
											<th class="min-w-24">Name</th>
											<th class="min-w-24">Value</th>
											<th>Variant Type</th>
											<th class="min-w-32">Description</th>
											<th>Status</th>
											<th>Created At</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
					</section>
					<!----------- SHOW-PAGE MODAL ----------->
					<button type="button" id="datatableViewModalTrigger" data-modal-target="datatable-click-view-modal"
						data-modal-toggle="datatable-click-view-modal" class="hidden"></button>

					<x-ui_items.modals.form-modal id="datatable-click-view-modal" title="Product Variant Item"
						modalWidth="max-w-6xl">
						<section id="datatableViewModalContent"></section>
					</x-ui_items.modals.form-modal>
					<!----------- SHOW-PAGE MODAL ----------->
				</div>
			</div>
		</div>
		<!--  -->

	</div>

	<!---------------------------------------------------------------------------------------------- accordion -->


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
					url: "{{ route('backend_product_variant_item_datatable') }}",
					type: 'POST',
					headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
				},
				columns: [
					{ data: 'SN', className: 'px-3 py-2 text-center' },
					{ data: 'id', className: 'px-3 py-2 text-center' },
					{ data: 'uid', className: 'px-3 py-2 text-center text-xs text-gray-500' },
					{ data: 'name', className: 'px-3 py-2 text-center' },
					{ data: 'value', className: 'px-3 py-2 text-center' },
					{ data: 'variant_type_name', className: 'px-3 py-2 text-center' },
					{ data: 'description_short', className: 'px-3 py-2 text-center' },
					{ data: 'status_label', className: 'px-3 py-2 text-center', orderable: false, searchable: false },
					{ data: 'created_at_formatted', className: 'px-3 py-2 text-center' },
					{ data: 'actions', className: 'no-row-click px-3 py-2 text-center', orderable: false, searchable: false }
				],
				pageLength: 25,
				createdRow: function (row, data) {
					dtApplyRowEvents(row, data);
				}
			});
		});
	</script>
	<!----------- DATATABLE ----------->


	<!-- ---------------- APP AREA Starts ---------------- -->

</x-app-layout>