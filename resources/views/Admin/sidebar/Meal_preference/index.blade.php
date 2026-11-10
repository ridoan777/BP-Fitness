<x-app-layout>

	@php
		$breadCrumbs = [
			['label' => 'Preference List'],
		];
	 @endphp

	<x-indicators.breadcrumb :crumbs="$breadCrumbs" />

	<!-- Start Main Widgets -->
	<x-indicators.page_header_widget pageName="All Meal Preferences List" />
	<!-- End Main Widgets -->


	<!-- Alert Component -->
	<x-indicators.alert-component message="Form Submission" />
	<!-- Alert Component -->

	@php
		$ORDER_IN_PAGE = 1;
		$ACCORDION_1_VISIBILITY = ''; // hidden or ''
	@endphp

	<!-- -------------------------------- APP AREA Starts -------------------------------- -->
	<div class="px-6 py-2">

		<div class="">
			<form action="{{ route('backend_preference_bulk_delete') }}" method="POST">
				@csrf
				<x-ui_items.datatable.datatable-btn-group exportRoute="backend_projects_spreadsheet_export"
					importRoute="backend_projects_excel_import" spreadsheetButton="false" importButton="false"
					pdfButton="false" deleteButton="true" />
				<!------->
				<table id="dataTableDisplay"
					class="dataTableDisplay min-w-full text-sm bg-white rounded-xl shadow-lg">
					<thead class="text-xs text-gray-400 uppercase bg-gray-100 rounded-t-xl">
						<tr>
							<th class="no-row-click dt-body-center">
								<input id="select_all" type="checkbox"
									class="w-4 h-4 rounded border-gray-400 cursor-pointer checked:bg-blue-600 checked:border-blue-600">
							</th>
							<th>SN</th>
							<th>DB ID</th>
							<th class="min-w-20">UID</th>
							<th class="min-w-40">User</th>
							<th class="min-w-32">Subscription</th>
							<th>Duration</th>
							<th>Goal</th>
							<th>Age|Gender</th>
							<th>Height|Weight</th>
							<th>Medical</th>
							<th>Total Energy</th>
							<th>Created</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</form>
		</div>
	</div>
	<!-- --------------------------------- -->
	{{-- <x-ui_items.modals.save-file-modal id="hs-confirm-modal" title="Import File"
		message="Upload your Excel/CSV file to process." formAction="#" formMethod="POST" confirmText="Upload"
		cancelText="Cancel" instructionImg="site_assets/report_upload_guide/excel_projects_import.png" /> --}}

	<!-- -------------------------------- APP AREA Starts -------------------------------- -->

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
					url: "{{ route('backend_preference_datatable') }}",
					type: 'POST',
					headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
				},
				columns: [
					{ data: 'checkbox', className: 'no-row-click dt-body-center', orderable: false, searchable: false },
					{ data: 'SN', className: 'px-3 py-2 text-center' },
					{ data: 'id', className: 'px-3 py-2 text-center' },
					{ data: 'preference_uid', className: 'px-3 py-2 text-center' },
					{ data: 'user_block', className: 'px-3 py-2 text-left' },
					{ data: 'subscriber_block', className: 'px-3 py-2 text-left' },
					{ data: 'duration_label', className: 'px-3 py-2 text-center' },
					{ data: 'goal_label', className: 'px-3 py-2 text-center' },
					{ data: 'age_gender', className: 'px-3 py-2 text-center' },
					{ data: 'height_weight', className: 'px-3 py-2 text-center' },
					{ data: 'medical_label', className: 'px-3 py-2 text-center' },
					{ data: 'energy_label', className: 'px-3 py-2 text-center' },
					{ data: 'created_at_formatted', className: 'px-3 py-2 text-center' },
					{ data: 'actions', className: 'px-3 py-2 text-center', orderable: false, searchable: false }
				],
				pageLength: 25,
				createdRow: function (row, data) {
					dtApplyRowEvents(row, data);
				}
			});
		});
	</script>
	<!----------- DATATABLE ----------->


	<style>
		table thead th {
			text-align: center !important;
		}

		.dataTableDisplay tbody tr:nth-child(even) {
			background-color: #e5e7eb;
			/* bg-gray-200 */
		}

		.dataTableDisplay tbody tr:nth-child(odd) {
			background-color: #f3f4f6;
			/* bg-gray-100 */
		}
	</style>

</x-app-layout>