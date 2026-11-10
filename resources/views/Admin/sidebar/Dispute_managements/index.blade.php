<x-app-layout>

	@php
		$breadCrumbs = [
			['label' => 'Categories', 'url' => 'backend_forum_category_index', 'icon' => 'layers'],
			['label' => 'Forums/Groups', 'url' => 'backend_forum_group_index', 'icon' => 'layers'],
			['label' => 'All Reports', 'icon' => 'noMessage'],
		];
	 @endphp

	<x-indicators.breadcrumb :crumbs="$breadCrumbs" />
	<!-- ---------------- APP AREA Starts ---------------- -->

	<!-- Start Main Widgets -->
	<x-indicators.page_header_widget pageName="All Reports" />
	<!-- End Main Widgets -->


	<!-- Alert Component -->
	<x-indicators.alert-component message="Form Submission" />
	<!-- Alert Component -->

	@php
		$ORDER_IN_PAGE = 1;
		$ACCORDION_1_VISIBILITY = ''; // hidden or ''
	 @endphp

	<!---------------------------------------------------------------------------------------------- accordion -->
	<div class="p-3">
		<!-- --------------------------------- -->
		<section
			class="displayArea mx-auto my-5 rounded-lg border-none bg-white p-4 shadow-md shadow-gray-400 dark:bg-neutral-700 dark:shadow-none">
			<div class="my-5">
				<div class="overflow-x-auto">
					<form action="{{ route('backend_dispute_report_bulk_delete') }}" method="POST">
						@csrf
						<x-ui_items.datatable.datatable-btn-group exportRoute="backend_projects_spreadsheet_export"
							importRoute="backend_projects_excel_import" spreadsheetButton="false" importButton="false"
							pdfButton="false" deleteButton="true" />
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
									<th class="w-24">Ticket</th>
									<th>Victim/Reporter</th>
									<th>Accused/Reported</th>
									<th>Section</th>
									<th>Content</th>
									<th>Phase</th>
									<th>Label</th>
									<th>Description</th>
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
		<!-- --------------------------------- -->
	</div>

	<!----------- SHOW-PAGE MODAL ----------->
	<button type="button" id="datatableViewModalTrigger" data-modal-target="datatable-click-view-modal"
		data-modal-toggle="datatable-click-view-modal" class="hidden"></button>

	<x-ui_items.modals.form-modal id="datatable-click-view-modal" title="Workout Lessons"
		modalWidth="max-w-5xl 2xl:max-w-7xl">
		<section id="datatableViewModalContent"></section>
	</x-ui_items.modals.form-modal>
	<!----------- SHOW-PAGE MODAL ----------->

	<!----------- DATATABLE ----------->
	<script>
		$(function () {
			window.dataTableDisplay = $('#dataTableDisplay').DataTable({
				processing: true,
				serverSide: true,
				responsive: true,
				ordering: true,
				scrollX: true,
				autoWidth: false,
				order: [[12, 'desc']],
				ajax: {
					url: '{{ route('backend_disputes_reports_datatable') }}',
					type: 'POST',
					headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
				},
				columns: [
					{ data: 'checkbox', className: 'px-3 py-2 text-center', orderable: false, searchable: false },
					{ data: 'SN', className: 'px-3 py-2 text-center' },
					{ data: 'id', className: 'px-3 py-2 text-center' },
					{ data: 'ticket', className: 'min-w-16 text-center' },
					{ data: 'victim', className: 'px-3 py-2 text-left', orderable: false },
					{ data: 'accused', className: 'px-3 py-2 text-left', orderable: false },
					{ data: 'section', className: 'px-3 py-2 text-center' },
					{ data: 'content', className: 'px-3 py-2 text-left' },
					{ data: 'phase', className: 'px-3 py-2 text-center', orderable: false },
					{ data: 'issue_label', className: 'px-3 py-2 text-left' },
					{ data: 'description', className: 'px-3 py-2 text-left' },
					{ data: 'status_label', className: 'px-3 py-2 text-center' },
					{ data: 'created_at_formatted', className: 'px-3 py-2 text-center' },
					{ data: 'actions', className: 'px-3 py-2 text-center no-row-click', orderable: false, searchable: false }
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



	<!-- ---------------- APP AREA Starts ---------------- -->

</x-app-layout>