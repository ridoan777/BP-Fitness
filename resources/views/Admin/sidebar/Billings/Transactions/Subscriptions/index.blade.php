<x-app-layout>

	@php
		$breadCrumbs = [
			['label' => 'Tiers', 'url' => 'backend_subscription_tiers_index', 'icon' => 'dollarSack'],
			['label' => 'Customers', 'url' => 'backend_billings_customers_index', 'icon' => 'userTrippleLine'],
			['label' => 'Transactions', 'url' => 'backend_transactions_index', 'icon' => 'dollarSack'],
			['label' => 'Subscriptions', 'icon' => 'recurring'],
		];
	 @endphp

	<x-indicators.breadcrumb :crumbs="$breadCrumbs" />

	<!-- Start Main Widgets -->
	<x-indicators.page_header_widget pageName="Transactions: From Subscriptions" />
	<!-- End Main Widgets -->

	<!-- Alert Component -->
	<x-indicators.alert-component message="Form Submission" />
	<!-- Alert Component -->

	<!-- -------------------------------- APP AREA Starts -------------------------------- -->
	<div class="px-6 py-2">

		<div class="">
			<form action="{{ route('backend_billings_trx_sub_bulk_delete') }}" method="POST">
				@csrf
				<x-ui_items.datatable.datatable-btn-group exportRoute="backend_projects_spreadsheet_export"
					importRoute="backend_projects_excel_import" spreadsheetButton="false" importButton="false"
					pdfButton="false" deleteButton="true" />
				<!------->
				<table id="dataTableDisplay"
					class="dataTableDisplay min-w-full text-sm border border-gray-300 bg-white rounded-xl shadow-lg">
					<thead class="text-xs text-gray-700 uppercase bg-gray-100 rounded-t-xl">
						<tr>
							<th class="no-row-click dt-body-center">
								<input id="select_all" type="checkbox"
									class="w-4 h-4 rounded border-gray-400 cursor-pointer checked:bg-blue-600 checked:border-blue-600">
							</th>
							<th>SN</th>
							<th>DB ID</th>
							<th class="min-w-32">TRX ID (Ori. Trx ID)</th>
							<th class="min-w-44">User</th>
							<th class="min-w-32 text-center">Store</th>
							<th>Type</th>
							<th>Trx Phase</th>
							<th>Amount</th>
							<th class="min-w-26">System Note</th>
							<th>Status</th>
							<th class="min-w-24">Created At</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</form>
		</div>
	</div>
	<!----------- SHOW-PAGE MODAL ----------->
	<button type="button" id="datatableViewModalTrigger" data-modal-target="datatable-click-view-modal"
		data-modal-toggle="datatable-click-view-modal" class="hidden"></button>

	<x-ui_items.modals.show-modal id="datatable-click-view-modal" title="Subscription Transaction Details" modalWidth="max-w-7xl">
		<section id="datatableViewModalContent"></section>
	</x-ui_items.modals.show-modal>
	<!----------- SHOW-PAGE MODAL ----------->

	<!-- -------------------------------- APP AREA Ends -------------------------------- -->

	<!------------- DATATABLE SCRIPT ------------->
	<script>
		$(function () {
			window.dataTableDisplay = $('#dataTableDisplay').DataTable({
				processing: true,
				serverSide: true,
				responsive: true,
				ordering: true,
				scrollX: true,
				order: [[11, 'desc']],
				ajax: {
					url: "{{ route('backend_billings_trx_sub_bulk_datatable') }}",
					type: 'POST',
					headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
				},
				columns: [
					{ data: 'checkbox', orderable: false, searchable: false },
					{ data: 'SN', orderable: true, searchable: true },
					{ data: 'id', orderable: true, searchable: true },
					{ data: 'transaction_id_display', orderable: true, searchable: true },
					{ data: 'user', orderable: true, searchable: true },
					{ data: 'store', orderable: true, searchable: true },
					{ data: 'trx_type', orderable: true, searchable: true },
					{ data: 'phase', orderable: true, searchable: true },
					{ data: 'amount_display', orderable: true, searchable: true },
					{ data: 'system_note', orderable: true, searchable: true },
					{ data: 'status_label', orderable: true, searchable: true },
					{ data: 'created_at_formatted', orderable: true, searchable: true },
					{ data: 'actions', orderable: false, searchable: false }
				],
				pageLength: 25,
				createdRow: function (row, data) {
					dtApplyRowEvents(row, data);
				}
			});
		})
	</script>
	<!------------- DATATABLE SCRIPT ------------->


</x-app-layout>