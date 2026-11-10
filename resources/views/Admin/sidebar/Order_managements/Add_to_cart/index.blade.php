<x-app-layout>

	@php
		$breadCrumbs = [
			['label' => 'Sellable Items', 'url' => 'backend_product_item_index', 'icon' => 'boxes'],
			['label' => 'Add-to-carts'],
			['label' => 'Orders', 'url' => 'backend_estore_orders_index', 'icon' => 'cart'],
		];
	 @endphp

	<x-indicators.breadcrumb :crumbs="$breadCrumbs" />

	<!-- Start Main Widgets -->
	<x-indicators.page_header_widget pageName="All Add-to-Carts" />
	<!-- End Main Widgets -->


	<!-- Alert Component -->
	<x-indicators.alert-component message="Form Submission" />
	<!-- Alert Component -->

	@php
		$ORDER_IN_PAGE = 1;
		$ACCORDION_1_VISIBILITY = ''; // hidden or ''
	@endphp

	<!-- ---------------- APP AREA Starts ---------------- -->

	<div class="p-3">
		<!-- --------------------------------- -->
		<section
			class="displayArea mx-auto my-5 rounded-lg border-none bg-white p-4 shadow-md shadow-gray-400 dark:bg-neutral-700 dark:shadow-none">
			<div class="my-5">
				<div class="overflow-x-auto">
					<form action="{{ route('backend_my_stats_bulk_delete') }}" method="POST">
						@csrf
						<x-ui_items.datatable.datatable-btn-group deleteButton="true" />
						<!------->
						<table id="dataTableDisplay"
							class="dataTableDisplay min-w-full text-sm bg-white rounded-xl shadow-lg">
							<thead class="text-xs text-gray-200 uppercase bg-gray-600 dark:bg-neutral-500">
								<tr>
									<th class="no-row-click dt-body-center">
										<input id="select_all" type="checkbox"
											class="w-4 h-4 rounded border-gray-400 cursor-pointer checked:bg-blue-600 checked:border-blue-600">
									</th>
									<th>SN</th>
									<th>DB ID</th>
									<th>UID</th>
									<th class="min-w-40">Product Variant</th>
									<th class="">Phase</th>
									<th class="">Buyer</th>
									<th class="min-w-18">Quantity</th>
									<th class="min-w-32" title="Prices when the item cart was added">Legacy Amounts</th>
									<th class="min-w-32" title="Current prices of the cart items. The final payable amount.">Current Amounts</th>
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
		</section>
		<!----------- SHOW-PAGE MODAL ----------->
		<button type="button" id="datatableViewModalTrigger" data-modal-target="datatable-click-view-modal"
			data-modal-toggle="datatable-click-view-modal" class="hidden"></button>

		<x-ui_items.modals.show-modal id="datatable-click-view-modal" title="Add-to-cart Item" modalWidth="max-w-7xl">
			<section id="datatableViewModalContent"></section>
		</x-ui_items.modals.show-modal>
		<!----------- SHOW-PAGE MODAL ----------->
	</div>

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
					url: "{{ route('backend_estore_add_to_cart_datatable') }}",
					type: 'POST',
					headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
				},
				order: [[1, 'desc']],
				columns: [
					{ data: 'checkbox', className: 'no-row-click dt-body-center', orderable: false, searchable: false },
					{ data: 'SN', className: 'px-3 py-2 text-center' },
					{ data: 'id', className: 'px-3 py-2 text-center' },
					{ data: 'uid', className: 'max-w-32 px-3 py-2 text-center text-xs text-gray-500' },
					{ data: 'product_variant', className: 'px-3 py-2 text-center' },
					{ data: 'phase', className: 'px-3 py-2 text-center' },
					{ data: 'buyer', className: 'px-3 py-2 text-center' },
					{ data: 'item_quantity', className: 'px-3 py-2 text-center' },
					{ data: 'legacy_amounts', className: 'min-w-32 px-3 py-2 text-center' },
					{ data: 'current_amounts', className: 'min-w-32 px-3 py-2 text-center' },
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