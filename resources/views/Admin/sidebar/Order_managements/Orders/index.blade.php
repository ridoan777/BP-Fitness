<x-app-layout>

	@php
		$breadCrumbs = [
			['label' => 'Sellable Items', 'url' => 'backend_product_item_index', 'icon' => 'boxes'],
			['label' => 'Add-to-carts', 'url' => 'backend_estore_add_to_cart_index', 'icon' => 'cart'],
			['label' => 'Orders'],
		];
	 @endphp

	<x-indicators.breadcrumb :crumbs="$breadCrumbs" />

	<!-- Start Main Widgets -->
	<x-indicators.page_header_widget pageName="Order List" />
	<!-- End Main Widgets -->


	<!-- Alert Component -->
	<x-indicators.alert-component message="Form Submission" />
	<!-- Alert Component -->

	@php
		$ORDER_IN_PAGE = 1;
		$ACCORDION_1_VISIBILITY = ''; // hidden or ''
	@endphp
	<x-ui_items.banners.announcement_banner rolesToShow="admin,super_admin" message="Deleting an order does not restore its stock. Make sure to take a note of the available quantity and reconcile accordingly."
	bgColor="bg-yellow-300" textColor="text-gray-800" />

	<!-- ---------------- APP AREA Starts ---------------- -->
	<div class="flex_end_center">
		<a href="{{ route('backend_estore_orders_delete_reqs') }}" class="indexButton">Delete Reqs</a>
		@include('Admin.sidebar.Order_managements.Orders.create', compact('customers', 'productSkus', 'availableCarts', 'manualOrderItems'))
	</div>
	<div class="px-3">
		<!-- --------------------------------- -->
		<section
			class="displayArea mx-auto my-5 rounded-lg border-none bg-white p-4 shadow-md shadow-gray-400 dark:bg-neutral-700 dark:shadow-none">
			<div class="my-5">
				@include('Admin.sidebar.Order_managements.Orders.filter')
				<div class="overflow-x-auto">
					<form action="{{ route('backend_estore_orders_bulk_delete') }}" method="POST">
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
									<th class="min-w-40">Customer</th>
									<th class="min-w-40">Product(s)</th>
									<th class="">Order Phase</th>
									<th class="">Payment</th>
									<th class="">Delivery</th>
									<th class="min-w-20">Amounts</th>
									<th class="min-w-20">Dates</th>
									<th class="min-w-26">System Note</th>
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

		<x-ui_items.modals.show-modal id="datatable-click-view-modal" title="Order Details" modalWidth="max-w-7xl">
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
				order: [[13, 'desc']],
				ajax: {
					url: "{{ route('backend_estore_orders_datatable') }}",
					type: 'POST',
					headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
					data: function (data) {
						data.order_status = $('#filter_order_status').val();
						data.payment_status = $('#filter_payment_status').val();
						data.delivery_status = $('#filter_delivery_status').val();
						data.min_payable_amount = $('#filter_min_payable_amount').val();
						data.max_payable_amount = $('#filter_max_payable_amount').val();
					}
				},
				columns: [
					{ data: 'checkbox', className: 'no-row-click dt-body-center', orderable: false, searchable: false },
					{ data: 'SN', className: 'px-3 py-2 text-center' },
					{ data: 'id', className: 'px-3 py-2 text-center' },
					{ data: 'uid', className: 'max-w-32 px-3 py-2 text-center text-xs text-gray-500' },
					{ data: 'customer', className: 'px-3 py-2 text-center' },
					{ data: 'product', className: 'px-3 py-2 text-center' },
					{ data: 'order_phase', className: 'px-3 py-2 text-center' },
					{ data: 'payment', className: 'px-3 py-2 text-center' },
					{ data: 'delivery', className: 'px-3 py-2 text-center' },
					{ data: 'amounts', className: 'min-w-32 px-3 py-2 text-center' },
					{ data: 'dates', className: 'min-w-32 px-3 py-2 text-center' },
					{ data: 'system_note', className: 'px-3 py-2 text-center' },
					{ data: 'status_label', className: 'px-3 py-2 text-center', orderable: false, searchable: false },
					{ data: 'created_at_formatted', className: 'px-3 py-2 text-center' },
					{ data: 'actions', className: 'no-row-click px-3 py-2 text-center', orderable: false, searchable: false }
				],
				pageLength: 25,
				createdRow: function (row, data) {
					dtApplyRowEvents(row, data);
				}
			});

			$('#applyOrderFilters').on('click', function () {
				window.dataTableDisplay.ajax.reload();
			});

			$('#resetOrderFilters').on('click', function () {
				$('#filter_order_status').val('');
				$('#filter_payment_status').val('');
				$('#filter_delivery_status').val('');
				$('#filter_min_payable_amount').val('');
				$('#filter_max_payable_amount').val('');

				window.dataTableDisplay.ajax.reload();
			});
		});
	</script>
	<!----------- DATATABLE ----------->


	<!-- ---------------- APP AREA Starts ---------------- -->

</x-app-layout>