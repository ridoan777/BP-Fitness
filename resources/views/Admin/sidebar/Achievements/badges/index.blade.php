<x-app-layout>

	@php
		$breadCrumbs = [
			['label' => 'Achievement Badges', 'icon' => 'ribbon'],
			['label' => 'Challenegs', 'url' => 'backend_achievement_head_index', 'icon' => 'ribbon'],
			['label' => 'Records', 'url' => 'backend_achievement_earner_index', 'icon' => 'ribbon'],
		];
	@endphp

	<x-indicators.breadcrumb :crumbs="$breadCrumbs" />

	<x-indicators.page_header_widget pageName="Achievement Badges" />

	<x-indicators.alert-component message="Form Submission" />

	<div class="p-3">
		<section
			class="displayArea mx-auto my-5 rounded-lg border-none bg-white p-4 shadow-md shadow-gray-400 dark:bg-neutral-700 dark:shadow-none">

			@include('Admin.sidebar.Achievements.badges.create')
			
			<div class="my-5">
				<div class="overflow-x-auto">
					<form action="{{ route('backend_achievement_badge_bulk_delete') }}" method="POST">

						@csrf
						<x-ui_items.datatable.datatable-btn-group exportRoute="backend_projects_spreadsheet_export"
							importRoute="backend_projects_excel_import" spreadsheetButton="false" importButton="false"
							pdfButton="false" deleteButton="true" />

						<table id="dataTableDisplay"
							class="dataTableDisplay min-w-full rounded-xl bg-white text-sm shadow-lg">
							<thead class="rounded-t-xl bg-gray-600 dark:bg-gray-800 text-xs uppercase text-gray-400">
								<tr>
									<th class="no-row-click dt-body-center">
										<input id="select_all" type="checkbox"
											class="w-4 h-4 rounded border-gray-400 cursor-pointer checked:bg-blue-600 checked:border-blue-600">
									</th>
									<th>SN</th>
									<th>DB ID</th>
									<th class="min-w-40">Icon</th>
									<th class="min-w-40">Name</th>
									<th class="min-w-56">Points Req.</th>
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
		</section>
		<!----------- SHOW-PAGE MODAL ----------->
		<button type="button" id="datatableViewModalTrigger" data-modal-target="datatable-click-view-modal"
			data-modal-toggle="datatable-click-view-modal" class="hidden"></button>

		<x-ui_items.modals.show-modal id="datatable-click-view-modal" title="Achievement Badge Details"
			modalWidth="max-w-7xl">
			<section id="datatableViewModalContent"></section>
		</x-ui_items.modals.show-modal>
		<!----------- SHOW-PAGE MODAL ----------->
	</div>

	<script>
		$(function () {
			window.dataTableDisplay = $('#dataTableDisplay').DataTable({
				processing: true,
				serverSide: true,
				responsive: true,
				ordering: true,
				scrollX: true,
				ajax: {
					url: "{{ route('backend_achievement_badge_datatable') }}",
					type: 'POST',
					headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
				},
				// order: [[7, 'desc']],
				columns: [
					{ data: 'checkbox', className: 'no-row-click dt-body-center', orderable: false, searchable: false },
					{ data: 'SN', className: 'px-3 py-2 text-center' },
					{ data: 'id', className: 'px-3 py-2 text-center' },
					{ data: 'image_preview', className: 'px-3 py-2 text-center', orderable: false, searchable: false },
					{ data: 'name', className: 'px-3 py-2 text-center', orderable: true, searchable: true },
					{ data: 'points_required_label', className: 'px-3 py-2 text-center', orderable: true, searchable: true },
					{ data: 'status_label', className: 'px-3 py-2 text-center', orderable: true, searchable: false },
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

</x-app-layout>
