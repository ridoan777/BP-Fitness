<x-app-layout>

	@php
		$breadCrumbs = [
			['label' => 'Categories', 'url' => 'backend_workout_category_index', 'icon' => 'layers'],
			['label' => 'Workout Courses', 'url' => 'backend_workout_course_index', 'icon' => 'dumbbell'],
			['label' => 'Lessons', 'icon' => 'dumbbell'],
		];
	 @endphp

	<x-indicators.breadcrumb :crumbs="$breadCrumbs" />

	<!-- Start Main Widgets -->
	<x-indicators.page_header_widget pageName="All Workout Lessons" />
	<!-- End Main Widgets -->


	<!-- Alert Component -->
	<x-indicators.alert-component message="Form Submission" />
	<!-- Alert Component -->

	@php
		$ORDER_IN_PAGE = 1;
		$ACCORDION_1_VISIBILITY = ''; // hidden or ''
	@endphp

	<!-- ---------------- APP AREA Starts ---------------- -->

	@include('Admin.sidebar.Workout_courses.Lessons.create')
	<!-- ------------------------ -->
	<div class="p-3">
		<!-- --------------------------------- -->
		<section
			class="displayArea mx-auto my-5 rounded-lg border-none bg-white p-4 shadow-md shadow-gray-400 dark:bg-neutral-700 dark:shadow-none">
			<div class="my-5">
				<form action="{{ route('backend_workout_lesson_bulk_delete') }}" method="POST">
					@csrf
					<x-ui_items.datatable.datatable-btn-group exportRoute="backend_projects_spreadsheet_export"
						importRoute="backend_projects_excel_import" spreadsheetButton="false" importButton="false"
						pdfButton="false" deleteButton="true" />
					<!------->
					<table id="dataTableDisplay" class="dataTableDisplay min-w-full text-sm bg-white rounded-xl shadow-lg">
						<thead class="text-xs text-gray-200 uppercase bg-gray-600 dark:bg-neutral-500">
							<tr>
								<th class="no-row-click dt-body-center">
									<input id="select_all" type="checkbox"
										class="w-4 h-4 rounded border-gray-400 cursor-pointer checked:bg-blue-600 checked:border-blue-600">
								</th>
								<th>SN</th>
								<th class="min-w-8">DB ID</th>
								<th class="min-w-8">UID</th>
								<th class="min-w-6">Thumbnail</th>
								<th class="min-w-24">Title</th>
								<th class="min-w-40">Category</th>
								<th class="min-w-40">Course</th>
								<th class="min-w-16">Meta</th>
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
	<!----------- SHOW-PAGE MODAL ----------->
	<button type="button" id="datatableViewModalTrigger" data-modal-target="datatable-click-view-modal"
		data-modal-toggle="datatable-click-view-modal" class="hidden"></button>

	<x-ui_items.modals.form-modal id="datatable-click-view-modal" title="Workout Lessons"
		modalWidth="max-w-5xl 2xl:max-w-7xl">
		<section id="datatableViewModalContent"></section>
	</x-ui_items.modals.form-modal>
	<!----------- SHOW-PAGE MODAL ----------->
	
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
					url: "{{ route('backend_workout_lesson_datatable') }}",
					type: 'POST',
					headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
				},
				columns: [
					{ data: 'checkbox', className: 'px-3 py-2 text-center', orderable: false, searchable: false },
					{ data: 'SN', className: 'text-center' },
					{ data: 'id', className: 'text-center' },
					{ data: 'uid', className: 'px-3 py-2 text-center' },
					{ data: 'thumbnail', className: 'px-3 py-2 text-center' },
					{ data: 'lesson_title', className: 'px-3 py-2 text-left' },
					{ data: 'category_name', className: 'px-3 py-2 text-center' },
					{ data: 'course_name', className: 'px-3 py-2 text-center' },
					{ data: 'meta', className: 'px-3 py-2 text-center' },
					{ data: 'description_short', className: 'px-3 py-2 text-left' },
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