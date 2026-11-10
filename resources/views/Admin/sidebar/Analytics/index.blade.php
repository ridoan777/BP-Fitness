<x-app-layout>

	@php
		$breadCrumbs = [
			['label' => 'Annalytics'],
		];
	 @endphp

	<x-indicators.breadcrumb :crumbs="$breadCrumbs" />
	<!-- ---------------- APP AREA Starts ---------------- -->

	<!-- Start Main Widgets -->
	<x-indicators.page_header_widget pageName="Analytics Dashboard" />
	<!-- End Main Widgets -->

	<div class="p-4 grid grid-cols-1 gap-20">
		<!----------------------------------------------------------------------->
		<div class="p-4 grid grid-cols-4 gap-4 rounded-lg bg-gray-50 dark:bg-neutral-600">
			@include('Admin.sidebar.Analytics.widget')
		</div>

		<!--------- USER -------->
		<div class="rounded-lg bg-gray-50 dark:bg-neutral-600">
			<h2 class="my-4 text-center">Personnel(s)</h2>

			<div class="my-8 p-4 grid grid-cols-2 gap-4">
				<div class="col-span-1 px-12">
					<canvas id="rolesPieChart"></canvas>
				</div>
				<div class="col-span-1 flex_xy_center">
					<canvas id="rolesBarChart" height="100"></canvas>
				</div>
			</div>
		</div>
		<!--------- USER -------->

		<!--------- CATEGORY -------->
		<div class="rounded-lg bg-gray-50 dark:bg-neutral-600">
			<h2 class="my-4 text-center">Product(s)</h2>

			<div class="my-8 p-4 grid grid-cols-2 gap-4">
				<div class="col-span-1 flex_xy_center">
					<canvas id="productsBarChart" height="100"></canvas>
				</div>
				<div class="col-span-1 px-12">
					<canvas id="productsPieChart"></canvas>
				</div>
			</div>
		</div>
		<!--------- CATEGORY -------->

		<!----------------------------------------------------------------------->
	</div>


	<!-- ---------------- APP AREA Starts ---------------- -->

</x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!--------------- USERS::DOUGHNUT + BAR CHART --------------->
@include('Admin.sidebar.Analytics.analytic_scripts.users')
<!--------------- USERS::DOUGHNUT + BAR CHART --------------->

<!--------------- CATEGORIES::DOUGHNUT + BAR CHART --------------->
@include('Admin.sidebar.Analytics.analytic_scripts.product_categories')
<!--------------- CATEGORIES::DOUGHNUT + BAR CHART --------------->

<!--------------- DELIVERY DEADLINE::LINED CHART --------------->
<script>
	fetch('/admin/charts/deliveries/schedule-date')
		.then(response => response.json())
		.then(res => {

			const labels = res.data.labels;
			const values = res.data.values;
			const names = res.data.names;

			const maxValue = Math.max(...values) + 2;

			const ctx = document.getElementById('deliveryScheduledLineChart');

			new Chart(ctx, {
				type: 'line',
				data: {
					labels: labels,
					datasets: [{
						label: 'Delivery Closing',
						data: values,
						borderColor: 'rgba(75, 192, 192, 0.75)',
						backgroundColor: 'rgba(75, 192, 192, 0.25)',
						tension: 0.4,
						borderWidth: 3,
						pointRadius: 5,
						pointHoverRadius: 7,
					}]
				},

				options: {
					responsive: true,
					interaction: { mode: 'nearest', intersect: true },

					plugins: {
						tooltip: {
							callbacks: {
								label: function (ctx) {
									const month = ctx.label;
									const list = names[month] ?? [];
									let out = `Projects: ${list.length}`;
									list.forEach((p, i) => out += `\n${i + 1}. ${p}`);
									return out;
								}
							}
						},
						title: {
							display: true,
							text: 'Delivery Scheduled Dates (Monthly Closings)'
						}
					},

					scales: {
						x: {
							ticks: {
								callback: function (value) {
									const m = {
										"01": "Jan", "02": "Feb", "03": "Mar", "04": "Apr",
										"05": "May", "06": "Jun", "07": "Jul", "08": "Aug",
										"09": "Sep", "10": "Oct", "11": "Nov", "12": "Dec"
									};
									const raw = this.getLabelForValue(value);
									const [year, month] = raw.split('-');
									return m[month] + " " + year;
								}
							}
						},
						y: {
							beginAtZero: true,
							ticks: { stepSize: 1, precision: 0 },
							suggestedMax: maxValue
						},
						y1: {
							type: 'linear',
							position: 'right',
							beginAtZero: true,
							min: 0,
							max: Math.max(...values),
							ticks: { stepSize: 1, precision: 0 },
							grid: { drawOnChartArea: false },
						}
					}
				}
			});

		});
</script>

<!--------------- INVENTORY TOOLS::MIX-LINED CHART --------------->
<script>
	fetch('/admin/charts/inventory/tools/usage')
		.then(res => res.json())
		.then(res => {
			const labels = res.data.labels;
			const usageData = res.data.usage;
			const maintenanceData = res.data.maintenance;

			const maxValue = Math.max(
				...usageData,
				...maintenanceData
			);

			const ctx = document.getElementById('toolsMixLineChart');

			new Chart(ctx, {
				type: 'line',
				data: {
					labels: labels.map(date => {
						const [year, month] = date.split('-');
						const monthMap = {
							"01": "Jan", "02": "Feb", "03": "Mar", "04": "Apr",
							"05": "May", "06": "Jun", "07": "Jul", "08": "Aug",
							"09": "Sep", "10": "Oct", "11": "Nov", "12": "Dec"
						};
						return monthMap[month] + " " + year;
					}),
					datasets: [
						{
							label: 'Tools used',
							data: usageData,
							borderColor: 'rgb(255, 99, 132)',
							backgroundColor: 'rgba(255, 99, 132, 0.25)',
							borderWidth: 3,
							tension: 0.4,
							pointRadius: 5,
							pointHoverRadius: 7,
						},
						{
							label: 'Sent to Maintenance',
							data: maintenanceData,
							borderColor: 'rgb(54, 162, 235)',
							backgroundColor: 'rgba(54, 162, 235, 0.25)',
							borderWidth: 3,
							tension: 0.4,
							pointRadius: 5,
							pointHoverRadius: 7,
						}
					]
				},
				options: {
					responsive: true,
					plugins: {
						legend: { position: 'top' },
						title: {
							display: true,
							text: 'Tools Usage & Maintenance (Monthly)'
						}
					},
					scales: {
						y: {
							beginAtZero: true,
							ticks: { stepSize: 1, precision: 0 },
							suggestedMax: maxValue + 2
						}
					}
				}
			});
		});
</script>