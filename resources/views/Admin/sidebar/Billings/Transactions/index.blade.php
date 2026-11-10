<x-app-layout>

	@php
		$breadCrumbs = [
			['label' => 'Tiers', 'url' => 'backend_subscription_tiers_index', 'icon' => 'dollarSack'],
			['label' => 'Customers', 'url' => 'backend_billings_customers_index', 'icon' => 'userTrippleLine'],
			['label' => 'Transactions', 'icon' => 'dollarSack'],
		];
	 @endphp

	<x-indicators.breadcrumb :crumbs="$breadCrumbs" />

	<!-- Start Main Widgets -->
	<x-indicators.page_header_widget pageName="Money Flow Transactions" />
	<!-- End Main Widgets -->

	<!-- Alert Component -->
	<x-indicators.alert-component message="Form Submission" />
	<!-- Alert Component -->

	<!-- -------------------------------- APP AREA Starts -------------------------------- -->
	<div class="px-6 py-2 grid grid-cols-2 gap-4 ">
		<a href="{{ route('backend_billings_trx_sub_index') }}">
			<div class="p-4 text-center rounded-lg bg-emerald-900 hover:bg-emerald-700 shadow-lg">
				<h3 class="text-white">Subscriptions</h3>
				<p class="text-sm text-gray-300">For recurring payments</p>
			</div>
		</a>
		<a href="{{ route('backend_billings_trx_pay_index') }}">
			<div class="p-4 text-center rounded-lg bg-rose-900 hover:bg-orange-700 shadow-lg">
				<h3 class="text-white">Payments</h3>
				<p class="text-sm text-gray-300">For all non-recurring/one-time payments, e.g., E-Store orders</p>
			</div>
		</a>
	</div>

	<!-- -------------------------------- APP AREA Starts -------------------------------- -->

</x-app-layout>