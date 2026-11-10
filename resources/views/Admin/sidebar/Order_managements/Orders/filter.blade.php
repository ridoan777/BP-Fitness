<div
	class="mb-4 grid grid-cols-12 gap-4 rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-neutral-600 dark:bg-neutral-800">

	<div class="col-span-12 md:col-span-3">
		<label class="inputLabelMedium">Order Status</label>
		<select id="filter_order_status" class="dropdownSelectInputs">
			<option value="">All Order Status</option>
			@foreach ($orderStatuses ?? [] as $status)
				<option value="{{ $status['value'] }}">{{ $status['label'] }}</option>
			@endforeach
		</select>
	</div>

	<div class="col-span-12 md:col-span-3">
		<label class="inputLabelMedium">Payment Status</label>
		<select id="filter_payment_status" class="dropdownSelectInputs">
			<option value="">All Payment Status</option>
			@foreach ($paymentStatuses ?? [] as $status)
				<option value="{{ $status['value'] }}">{{ $status['label'] }}</option>
			@endforeach
		</select>
	</div>

	<div class="col-span-12 md:col-span-2">
		<label class="inputLabelMedium">Delivery Status</label>
		<select id="filter_delivery_status" class="dropdownSelectInputs">
			<option value="">All Delivery Status</option>
			@foreach ($deliveryStatuses ?? [] as $status)
				<option value="{{ $status['value'] }}">{{ $status['label'] }}</option>
			@endforeach
		</select>
	</div>

	<div class="col-span-12 md:col-span-2">
		<label class="inputLabelMedium">Minimum Payable Amount</label>
		<input type="number" id="filter_min_payable_amount" class="normalInput" min="0" step="0.01" placeholder="0.00">
	</div>

	<div class="col-span-12 md:col-span-2">
		<label class="inputLabelMedium">Maximum Payable Amount</label>
		<input type="number" id="filter_max_payable_amount" class="normalInput" min="0" step="0.01" placeholder="999.00">
	</div>
	
	<div class="col-span-12 md:col-span-9 flex"></div>

	<div class="col-span-12 md:col-span-3 space-x-4 flex_end_center">
		<button type="button" id="applyOrderFilters" class="bg-indigo-700 text-white text-sm px-4 py-2 rounded">
			Apply Filters
		</button>
		<button type="button" id="resetOrderFilters" class="bg-gray-600 text-white text-sm px-4 py-2 rounded">
			Reset
		</button>
	</div>

</div>
