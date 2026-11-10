<x-app-layout>

	@php
		$breadCrumbs = [
			['label' => 'Categories', 'url' => 'backend_forum_category_index', 'icon' => 'layers'],
			['label' => 'Forums/Groups', 'icon' => 'layers'],
			['label' => 'All Reports', 'url' => 'backend_disputes_report_index', 'icon' => 'noMessage'],
		];
	 @endphp

	<x-indicators.breadcrumb :crumbs="$breadCrumbs" />

	<!-- Start Main Widgets -->
	<x-indicators.page_header_widget pageName="All Forum Groups" />
	<!-- End Main Widgets -->


	<!-- Alert Component -->
	<x-indicators.alert-component message="Form Submission" />
	<!-- Alert Component -->

	@php
		$ORDER_IN_PAGE = 1;
		$ACCORDION_1_VISIBILITY = ''; // hidden or ''
	@endphp

	<!-- ---------------- APP AREA Starts ---------------- -->

	@include('Admin.sidebar.Community_forums.Groups.create')
	<!-- ------------------------ -->
	<div class="p-3">
		<!-- --------------------------------- -->
		<section
			class="displayArea mx-auto my-5 rounded-lg border-none bg-white p-4 shadow-md shadow-gray-400 dark:bg-neutral-700 dark:shadow-none">
			<div class="my-5">
				<form action="{{ route('backend_forum_group_bulk_delete') }}" method="POST">
					@csrf
					<x-ui_items.datatable.datatable-btn-group exportRoute="backend_projects_spreadsheet_export"
						importRoute="backend_projects_excel_import" spreadsheetButton="false" importButton="false"
						pdfButton="false" deleteButton="true" />
					<!------->
					<table id="dataTableDisplay" class="dataTableDisplay min-w-full text-sm bg-white rounded-xl shadow-lg">
						<thead class="text-xs text-gray-200 uppercase bg-gray-600 dark:bg-neutral-500">
							<tr>
								<th class="no-row-click w-10 text-center">
									<input type="checkbox" id="selectAll"
										class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
								</th>
								<th class="w-8">SN</th>
								<th class="w-12">DB ID</th>
								<th class="w-16">UID</th>
								<th class="min-w-6">Thumbnail</th>
								<th class="min-w-24">Title</th>
								<th class="min-w-40">Category</th>
								<th class="min-w-16">Type</th>
								<th>Members|Mssgs</th>
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

	<x-ui_items.modals.form-modal id="datatable-click-view-modal" title="Forum Group" modalWidth="max-w-7xl">
		<section id="datatableViewModalContent"></section>
	</x-ui_items.modals.form-modal>
	<!----------- SHOW-PAGE MODAL ----------->
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
					url: "{{ route('backend_forum_group_datatable') }}",
					type: 'POST',
					headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
				},
				columns: [
					{ data: 'checkbox', className: 'px-3 py-2 text-center', orderable: false, searchable: false },
					{ data: 'SN', className: 'text-center' },
					{ data: 'id', className: 'text-center' },
					{ data: 'uid', className: 'px-3 py-2 text-center' },
					{ data: 'thumbnail', className: 'px-3 py-2 text-center' },
					{ data: 'group_name', className: 'px-3 py-2 text-left' },
					{ data: 'category_name', className: 'px-3 py-2 text-center' },
					{ data: 'course_type', className: 'px-3 py-2 text-center' },
					{ data: 'member_mssg', className: 'px-3 py-2 text-center' },
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
</x-app-layout>