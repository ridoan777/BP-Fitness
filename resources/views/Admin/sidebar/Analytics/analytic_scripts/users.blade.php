<!--------------- USERS::DOUGHNUT CHART --------------->

<script>
	fetch('/admin/charts/users/roles')
		.then(response => response.json())
		.then(res => {
			const labels = res.data.labels;
			const values = res.data.values;

			const ctx = document.getElementById('rolesPieChart');

			new Chart(ctx, {
				type: 'doughnut',
				data: {
					labels: labels,
					datasets: [{
						label: 'Users by Role',
						data: values,
						backgroundColor: [
							'rgb(75, 192, 192)',
							'rgb(115, 115, 195)',
							'rgb(255, 99, 132)',
							'rgb(255, 205, 86)',
						],
						hoverOffset: 4
					}]
				}
			});
		});
</script>
<!--------------- USERS::DOUGHNUT CHART --------------->

<!--------------- USERS::HORIZONTAL BAR CHART --------------->

<script>
	fetch('/admin/charts/users/roles')
		.then(response => response.json())
		.then(res => {
			const labels = res.data.labels;
			const values = res.data.values;
			const barCtx = document.getElementById('rolesBarChart');

			const colors = [
				'rgba(75, 192, 192, 0.75)',
				'rgba(115, 115, 195, 0.75)',
				'rgba(255, 99, 132, 0.75)',
				'rgba(255, 205, 86, 0.75)',
				// 'rgba(54, 162, 235, 0.75)',
				// 'rgba(153, 102, 255, 0.75)',
				// 'rgba(255, 159, 64, 0.75)',
			];

			const datasets = [];

			labels.forEach((role, index) => {
				let row = Array(labels.length).fill(0);
				row[index] = values[index];

				datasets.push({
					label: role,
					data: row,
					backgroundColor: colors[index % colors.length],
					borderColor: colors[index % colors.length],
					borderWidth: 2
				});
			});

			new Chart(barCtx, {
				type: 'bar',
				data: {
					labels: labels,
					datasets: datasets
				},
				options: {
					indexAxis: 'y',
					responsive: true,
					scales: {
						x: { beginAtZero: true }
					}
				}
			});
		});
</script>
<!--------------- USERS::HORIZONTAL BAR CHART --------------->