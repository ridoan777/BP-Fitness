<x-app-layout>

	@php
		$breadCrumbs = [
			['label' => 'Categories', 'icon' => 'layers'],
			['label' => 'All Workouts', 'url' => 'backend_workout_course_index', 'icon' => 'dumbbell'],
			['label' => 'Lessons', 'url' => 'backend_workout_lesson_index', 'icon' => 'dumbbell'],
		];
	 @endphp

	<x-indicators.breadcrumb :crumbs="$breadCrumbs" />

	<!-- Start Main Widgets -->
	<x-indicators.page_header_widget pageName="All Workout Course Categories" />
	<!-- End Main Widgets -->


	<!-- Alert Component -->
	<x-indicators.alert-component message="Form Submission" />
	<!-- Alert Component -->

	@php
		$ORDER_IN_PAGE = 1;
		$ACCORDION_1_VISIBILITY = ''; // hidden or ''
	@endphp

	<!-- ---------------- APP AREA Starts ---------------- -->

	@include('Admin.sidebar.Workout_courses.Categories.create')
	<!-- ------------------------ -->
	<div class="p-3">
		<!-- --------------------------------- -->
		<section
			class="displayArea mx-auto my-5 rounded-lg border-none bg-white p-4 shadow-md shadow-gray-400 dark:bg-neutral-700 dark:shadow-none">
			<div class="my-5">
				<div class="overflow-x-auto">
					<table id="dataTableDisplay" class="dataTableDisplay min-w-full text-sm bg-white rounded-xl shadow-lg">
						<thead class="text-xs text-gray-200 uppercase bg-gray-600 dark:bg-neutral-500">
							<tr>
								<th>SN</th>
								<th>DB ID</th>
								<th class="min-w-16">UID</th>
								<th class="min-w-24">Name</th>
								<th>Description</th>
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

		<x-ui_items.modals.form-modal id="datatable-click-view-modal" title="Workout Category" modalWidth="max-w-5xl">
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
					url: "{{ route('backend_workout_category_datatable') }}",
					type: 'POST',
					headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
				},
				columns: [
					{ data: 'SN', className: 'px-3 py-2 text-center' },
					{ data: 'id', className: 'px-3 py-2 text-center' },
					{ data: 'uid', className: 'px-3 py-2 text-center' },
					{ data: 'name', className: 'px-3 py-2 text-left' },
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