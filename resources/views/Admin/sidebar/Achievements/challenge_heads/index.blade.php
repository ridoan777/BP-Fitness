<x-app-layout>

	@php
		$breadCrumbs = [
			['label' => 'Achievement Badges', 'url' => 'backend_achievement_badge_index', 'icon' => 'ribbon'],
			['label' => 'Challenegs', 'icon' => 'ribbon'],
			['label' => 'Records', 'url' => 'backend_achievement_earner_index', 'icon' => 'ribbon'],
		];
	@endphp

	<x-indicators.breadcrumb :crumbs="$breadCrumbs" />

	<x-indicators.page_header_widget pageName="Challenge Heads" />

	<x-indicators.alert-component message="Form Submission" />

	<div class="p-3">
		<section
			class="displayArea mx-auto my-5 rounded-lg border-none bg-white p-4 shadow-md shadow-gray-400 dark:bg-neutral-700 dark:shadow-none">

			<div class="grid grid-cols-3 gap-4">
				@include('Admin.sidebar.Achievements.challenge_heads.create')
				<div
					class="rounded-lg bg-gray-50 px-4 py-3 dark:bg-neutral-800 text-center">
					<p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400">Point Pool: 
						<span id="pointPollValue" class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">Loading...</span>
					</p>
				</div>
				<div></div>
			</div>


			<div class="my-5">
				<div class="overflow-x-auto">
					<form action="{{ route('backend_achievement_head_bulk_delete') }}" method="POST">

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
									<th class="min-w-40">Title</th>
									<th class="min-w-40">Type</th>
									<th class="min-w-30">Required Value</th>
									<th class="min-w-30">Reward Points</th>
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
		function refreshPointPool() {
			fetch("{{ route('backend_achievement_head_point_poll') }}?t=" + Date.now(), {
				cache: 'no-store',
				headers: {
					'Accept': 'application/json',
					'X-Requested-With': 'XMLHttpRequest'
				}
			})
				.then(response => response.json())
				.then(result => {
					const value = typeof result?.data === 'number' ? result.data.toLocaleString() : '0';
					document.getElementById('pointPollValue').textContent = value;
				})
				.catch(() => {
					document.getElementById('pointPollValue').textContent = '0';
				});
		}

		$(function () {
			refreshPointPool();

			$('#dataTableDisplay').on('draw.dt', function () {
				refreshPointPool();
			});
		});
	</script>

	<script>
		$(function () {
			window.dataTableDisplay = $('#dataTableDisplay').DataTable({
				processing: true,
				serverSide: true,
				responsive: true,
				ordering: true,
				scrollX: true,
				ajax: {
					url: "{{ route('backend_achievement_head_datatable') }}",
					type: 'POST',
					headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
				},
				order: [[8, 'desc']],
				columns: [
					{ data: 'checkbox', className: 'no-row-click dt-body-center', orderable: false, searchable: false },
					{ data: 'SN', className: 'px-3 py-2 text-center', orderable: false, searchable: false },
					{ data: 'id', className: 'px-3 py-2 text-center', orderable: true, searchable: true },
					{ data: 'title', className: 'px-3 py-2 text-center', orderable: true, searchable: true },
					{ data: 'type_display', className: 'px-3 py-2 text-center', orderable: true, searchable: true },
					{ data: 'required_value', className: 'px-3 py-2 text-center', orderable: true, searchable: true },
					{ data: 'reward', className: 'px-3 py-2 text-center', orderable: true, searchable: true },
					{ data: 'status_label', className: 'px-3 py-2 text-center', orderable: true, searchable: false },
					{ data: 'created_at_formatted', className: 'px-3 py-2 text-center', orderable: true, searchable: false },
					{ data: 'actions', className: 'px-3 py-2 text-center no-row-click', orderable: false, searchable: false }
				],
				pageLength: 25,
				createdRow: function (row, data) {
					dtApplyRowEvents(row, data);
				}
			});
		});
	</script>

</x-app-layout>