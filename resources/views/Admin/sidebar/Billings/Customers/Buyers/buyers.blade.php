<x-app-layout>
	@php
		$breadCrumbs = [
			['label' => 'Customers', 'url' => 'backend_billings_customers_index', 'icon' => 'userTrippleLine'],
			['label' => 'Buyers', 'icon' => 'userTripple'],
		];
	@endphp

	<x-indicators.breadcrumb :crumbs="$breadCrumbs" />

	<x-indicators.page_header_widget pageName="Customers: All Payers/Buyers" />

	<x-indicators.alert-component message="Form Submission" />

	<div class="px-6 py-2">
		<table id="dataTableDisplay" class="dataTableDisplay min-w-full rounded-xl bg-white text-sm shadow-lg">
			<thead class="text-xs uppercase bg-gray-600 text-gray-200 dark:bg-neutral-500">
				<tr>
					<th>SN</th>
					<th class="min-w-44">Buyers</th>
					<th>Total Orders</th>
					<th class="min-w-16">Amount Spent</th>
					<th class="min-w-44">Subscription Details</th>
					<th>Last Order</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>

	<script>
		$(function () {
			window.dataTableDisplay = $('#dataTableDisplay').DataTable({
				processing: true,
				serverSide: true,
				searching: true,
				responsive: true,
				ordering: true,
				scrollX: true,
				order: [[3, 'desc']],
				ajax: {
					url: "{{ route('backend_billings_customer_buyer_datatable') }}",
					type: 'POST',
					headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
				},
				columns: [
					{ data: 'SN', className: 'px-3 py-2 text-center', orderable: false, searchable: false },
					{ data: 'buyers', className: 'px-3 py-2 text-center', orderable: true, searchable: true },
					{ data: 'total_orders', className: 'px-3 py-2 text-center', orderable: true, searchable: true },
					{ data: 'amount_spent', className: 'px-3 py-2 text-center', orderable: true, searchable: true },
					{ data: 'subscription_details', className: 'px-3 py-2 text-left', orderable: true, searchable: true },
					{ data: 'last_order', className: 'px-3 py-2 text-center', orderable: true, searchable: true },
				],
				pageLength: 25,
				createdRow: function (row, data) {
					dtApplyRowEvents(row, data);
				}
			});
		});
	</script>
</x-app-layout>
