<x-app-layout>

	@php
		$breadCrumbs = [
			['label' => 'Customers', 'url' => 'backend_billings_customers_index', 'icon' => 'userTrippleLine'],
			['label' => 'Subscribers', 'icon' => 'userTripple'],
			['label' => 'Transactions', 'url' => 'backend_transactions_index', 'icon' => 'dollarSack'],
		];
	 @endphp

	<x-indicators.breadcrumb :crumbs="$breadCrumbs" />

	<!-- Start Main Widgets -->
	<x-indicators.page_header_widget pageName="Customers: All Subscriptions" />
	<!-- End Main Widgets -->

	<!-- Alert Component -->
	<x-indicators.alert-component message="Form Submission" />
	<!-- Alert Component -->

	<!-- -------------------------------- APP AREA Starts -------------------------------- -->
	<div class="px-6 py-2">

		<div class="">
			<form action="{{ route('backend_billings_customer_subscription_bulk_delete') }}" method="POST">
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
							<th class="min-w-32">Sub ID (Ori. Trx ID)</th>
							<th class="min-w-44">User</th>
							<th class="min-w-32">Tier</th>
							<th class="min-w-32 text-center">Store</th>
							<th>Payment Status</th>
							<th>Sub Status</th>
							<th>Amount</th>
							<th>Duration (days)</th>
							<th class="min-w-32">Next Renewal</th>
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
	<!-- --------------------------------- -->

	<!-- -------------------------------- APP AREA Starts -------------------------------- -->

	<!------------- DATATABLE SCRIPT ------------->
	<script>
		$(function () {
			window.dataTableDisplay = $('#dataTableDisplay').DataTable({
				processing: true,
				serverSide: true,
				responsive: true,
				ordering: true,
				scrollX: true,
				ajax: {
					url: "{{ route('backend_billings_customer_subscription_datatable') }}",
					type: 'POST',
					headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
				},
				columns: [
					{ data: 'checkbox', orderable: false, searchable: false },
					{ data: 'SN', orderable: true, searchable: true },
					{ data: 'id', orderable: true, searchable: true },
					{ data: 'subscription_id_display', orderable: true, searchable: true },
					{ data: 'user', orderable: true, searchable: true },
					{ data: 'tier', orderable: true, searchable: true },
					{ data: 'store', orderable: true, searchable: true },
					{ data: 'payment_status', orderable: true, searchable: true },
					{ data: 'sub_status', orderable: true, searchable: true },
					{ data: 'amount_display', orderable: true, searchable: true },
					{ data: 'duration_data', orderable: true, searchable: true },
					{ data: 'next_renewal_at', className: 'text-xs', orderable: true, searchable: true },
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
