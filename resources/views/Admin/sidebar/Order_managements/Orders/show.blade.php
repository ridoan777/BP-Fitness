<div class="p-4" id="hs-order-show-modal-{{ $singleOrder->id }}">
	<div>
		@php
			$showModalId = 'hs-order-show-modal-' . $singleOrder->id;
			$tableBodyId = 'manualOrderItemsTableBody-' . $singleOrder->id;
			$rowTemplateId = 'manualOrderItemRowTemplate-' . $singleOrder->id;
			$enableEditButtonId = 'enableOrderEditing-' . $singleOrder->id;
			$addRowButtonId = 'addManualOrderItemRow-' . $singleOrder->id;
			$resetRowsButtonId = 'resetManualOrderItems-' . $singleOrder->id;
			$submitButtonId = 'submitOrderUpdate-' . $singleOrder->id;
		@endphp

		<div class="mb-5 flex justify-end">
			<button type="button" id="{{ $enableEditButtonId }}" class="darkButton">
				Enable Editing
			</button>
		</div>

		<div class="mb-5 mt-2 grid grid-cols-2 gap-4">
			<div>
				<label class="inputLabelMedium">ID</label>
				<p class="normalInput bg-gray-200 dark:bg-neutral-700">{{ $singleOrder->id }}</p>
			</div>
			<div>
				<label class="inputLabelMedium">Order UID</label>
				<p class="normalInput bg-gray-200 dark:bg-neutral-700">{{ $singleOrder->order_uid }}</p>
			</div>
		</div>

		<form method="POST" action="{{ route('backend_estore_orders_store', $singleOrder?->id) }}"
			class="rounded-lg p-2 dark:bg-neutral-700" enctype="multipart/form-data">
			@csrf

			<input type="hidden" name="id" value="{{ $singleOrder->id }}">
			<section>
				<!------------- CUSTOMER + DATE ------------->
				<div class="grid grid-cols-2 gap-4">
					<div class="mb-5 mt-2">
						<label class="inputLabelMedium">Customer</label>
						<select name="customer_id" class="singleSelectWithSearch dropdownSelectInputs" disabled
							data-order-editable="true" data-dropdown-position="absolute">
							<option value="">Select Customer</option>
							@foreach ($customers as $customer)
								<option value="{{ $customer->id }}" @selected(old('customer_id', $singleOrder?->customer_id) == $customer->id)>
									{{ $customer->name }} | {{ $customer->email }} | {{ strtoupper($customer->user_role) }}
								</option>
							@endforeach
						</select>
					</div>
					<div class="mb-5 mt-2">
						<div>
							<label class="inputLabelMedium">Order Placing Date</label>
							<input type="datetime-local" name="placed_at"
								value="{{ old('placed_at', $singleOrder?->placed_at?->format('Y-m-d\TH:i')) }}"
								class="normalInput bg-gray-50 dark:bg-neutral-500" disabled data-order-editable="true" />
						</div>
					</div>
				</div>
				<!------------- CUSTOMER + DATE ------------->

				<p class="text-sm text-gray-400">Selecting Order Phase 'Processing' or 'Completed' will mark the cart items 'converted as order'.</p>

				<!------------- ORDER + PAYMENT + DELIVERY + CURRENCY ------------->
				<div class="mb-5 mt-2 grid grid-cols-4 gap-4">
					<div>
						<label class="inputLabelMedium">Order Phase</label>
						<select name="order_status" class="normalInput bg-gray-50 dark:bg-neutral-500" disabled
							data-order-editable="true">
							@foreach (\App\Domains\E_Store\Shoppings\Enums\OrderStatusEnums::options() as $option)
								<option value="{{ $option['value'] }}" @selected(old('order_status', $singleOrder?->order_status?->value ?? $singleOrder?->order_status) === $option['value'])>
									{{ $option['label'] }}
								</option>
							@endforeach
						</select>
					</div>
					<div>
						<label class="inputLabelMedium">Payment</label>
						<select name="payment_status" class="normalInput bg-gray-50 dark:bg-neutral-500" disabled
							data-order-editable="true">
							@foreach (\App\Domains\Billings\Shared\Enums\PaymentStatusEnums::options() as $option)
								<option value="{{ $option['value'] }}" @selected(old('payment_status', $singleOrder?->payment_status?->value ?? $singleOrder?->payment_status) === $option['value'])>
									{{ $option['label'] }}
								</option>
							@endforeach
						</select>
					</div>
					<div>
						<label class="inputLabelMedium">Delivery</label>
						<select name="delivery_status" class="normalInput bg-gray-50 dark:bg-neutral-500" disabled
							data-order-editable="true">
							@foreach (\App\Domains\E_Store\Shoppings\Enums\DeliveryStatusEnums::options() as $option)
								<option value="{{ $option['value'] }}" @selected(old('delivery_status', $singleOrder?->delivery_status?->value ?? $singleOrder?->delivery_status) === $option['value'])>
									{{ $option['label'] }}
								</option>
							@endforeach
						</select>
					</div>
					<div>
						<label class="inputLabelMedium">Currency</label>
						<select name="currency" class="normalInput bg-gray-50 dark:bg-neutral-500" disabled
							data-order-editable="true">
							@foreach (\App\Domains\Billings\Shared\Enums\CurrencyEnums::options() as $option)
								<option value="{{ $option['value'] }}" @selected(old('currency', $singleOrder?->currency?->value ?? $singleOrder?->currency) === $option['value'])>
									{{ $option['label'] }}
								</option>
							@endforeach
						</select>
					</div>
				</div>
				<!------------- ORDER + PAYMENT + DELIVERY + CURRENCY ------------->

				<!------------- PROMO + DISCOUNTS ------------->
				<div class="mb-5 mt-2 grid grid-cols-3 gap-4">
					<div>
						<label class="inputLabelMedium">Promo Code</label>
						<input type="text" name="promo_code" value="{{ old('promo_code', $singleOrder?->promo_code) }}"
							class="normalInput bg-gray-50 dark:bg-neutral-500" disabled data-order-editable="true" />
					</div>
					<div>
						<label class="inputLabelMedium">Promo Discount Amount</label>
						<input type="number" step="0.01" name="promo_discount_amount"
							value="{{ old('promo_discount_amount', $singleOrder?->promo_discount_amount ?? 0) }}"
							class="normalInput bg-gray-50 dark:bg-neutral-500 order-promo-discount-input" disabled
							data-order-editable="true" />
					</div>
					<div>
						<label class="inputLabelMedium">Total Discount Amount</label>
						<input type="number" step="0.01" name="total_discount_amount"
							value="{{ old('total_discount_amount', $singleOrder?->total_discount_amount ?? 0) }}"
							class="normalInput bg-gray-50 dark:bg-neutral-500 order-total-discount-input" disabled
							data-order-editable="true" />
					</div>
				</div>
				<!------------- PROMO + DISCOUNTS ------------->

				<!------------- TOTALS & REFUNDS ------------->
				<div class="mb-5 mt-2 grid grid-cols-3 gap-4">
					<div>
						<label class="inputLabelMedium">Sub-Total Amount</label>
						<input type="number" step="0.01" name="subtotal_amount"
							value="{{ old('subtotal_amount', $singleOrder?->subtotal_amount) }}"
							class="normalInput bg-gray-200 dark:bg-neutral-500 order-subtotal-input" readonly disabled
							data-order-editable="true" />
					</div>
					<div>
						<label class="inputLabelMedium">Payable Amount</label>
						<input type="number" step="0.01" name="payable_amount"
							value="{{ old('payable_amount', $singleOrder?->payable_amount) }}"
							class="normalInput bg-gray-200 dark:bg-neutral-500 order-payable-input" readonly disabled
							data-order-editable="true" />
					</div>
					<div>
						<label class="inputLabelMedium">Refunded Amount</label>
						<input type="number" step="0.01" name="refunded_amount"
							value="{{ old('refunded_amount', $singleOrder?->refunded_amount ?? 0) }}"
							class="normalInput bg-gray-50 dark:bg-neutral-500" disabled data-order-editable="true" />
					</div>
				</div>
				<!------------- TOTALS & REFUNDS ------------->

				<div class="mb-5 mt-2 grid grid-cols-4 gap-4">
					<div class="mb-5 mt-2">
						<label class="inputLabelMedium">Paid At</label>
						<p class="normalInput bg-gray-200 dark:bg-neutral-700">
							{{ $singleOrder?->paid_at ? $singleOrder->paid_at->format('d-M-Y h:i A') : 'N/A' }}</p>
					</div>
					<div class="mb-5 mt-2">
						<label class="inputLabelMedium">Fulfilled At</label>
						<input type="datetime-local" name="fulfilled_at"
							value="{{ old('fulfilled_at', $singleOrder?->fulfilled_at?->format('Y-m-d\TH:i')) }}"
							class="normalInput bg-gray-50 dark:bg-neutral-500" disabled data-order-editable="true" />
					</div>
					<div class="mb-5 mt-2">
						<label class="inputLabelMedium">Cancelled At</label>
						<input type="datetime-local" name="cancelled_at"
							value="{{ old('cancelled_at', $singleOrder?->cancelled_at?->format('Y-m-d\TH:i')) }}"
							class="normalInput bg-gray-50 dark:bg-neutral-500" disabled data-order-editable="true" />
					</div>
					<div class="mb-5 mt-2">
						<label class="inputLabelMedium">Delete Req At</label>
						<input type="datetime-local" name="delete_req_at"
							value="{{ old('delete_req_at', $singleOrder?->delete_req_at?->format('Y-m-d\TH:i')) }}"
							class="normalInput bg-gray-50 dark:bg-neutral-500" disabled data-order-editable="true" />
					</div>
				</div>

				<!------------- ADD PRODUCT ------------->
				<div class="mb-5 mt-2 p-4 bg-gray-50 dark:bg-neutral-600 rounded-lg">
					<div class="mb-4 flex items-center justify-between gap-4">
						<label class="inputLabelMedium">Products/Order Items
							<span class="grayLabel">(product variants & cart items are mutually exclusive. Select only one in a single order item record.)</span>
						</label>
						<div class="flex items-center gap-2">
							<button type="button" class="showButton py-2 px-3" id="{{ $resetRowsButtonId }}" disabled
								data-order-editable-button="true">
								{!! \App\Helpers\IconPack::recurring(['class' => 'iconPackItem w-5 h-5']) !!}
							</button>
							<button type="button" class="showButton py-2 px-4" id="{{ $addRowButtonId }}" disabled
								data-order-editable-button="true">Add
								Product</button>
						</div>
					</div>
					<div class="overflow-x-auto rounded-lg border border-gray-300 dark:border-neutral-600">
						<table class="min-w-full text-sm text-gray-700 dark:text-gray-200">
							<thead class="manual-order-item-row bg-gray-200 text-gray-700 dark:bg-neutral-800 dark:text-gray-200">
								<tr class="">
									<th class="px-4 py-3 text-left min-w-72">Product's Sellable Variant</th>
									<th class="px-4 py-3 text-left min-w-72">Cart Item</th>
									<th class="px-4 py-3 text-left min-w-24">Qty</th>
									<th class="px-4 py-3 text-left min-w-28">Current Rate</th>
									<th class="px-4 py-3 text-left min-w-28">Current Discount</th>
									<th class="px-4 py-3 text-left min-w-28">Current Subtotal</th>
									<th class="px-4 py-3 text-left min-w-32">Grand Total</th>
									<th class="px-4 py-3 text-left min-w-20">Action</th>
								</tr>
							</thead>
							<tbody class="bg-white dark:bg-neutral-700" id="{{ $tableBodyId }}">
								@foreach ($manualOrderItems as $index => $item)
									<tr class="manual-order-item-row">
										<td class="px-4 py-3">
											<select name="items[{{ $index }}][sku_sellable_id]"
												class="singleSelectWithSearch dropdownSelectInputs manual-order-sku-select"
												disabled data-order-editable="true" data-dropdown-position="absolute">
												<option value="">Select Product Sellable SKU</option>
												@foreach ($productSkus as $sku)
													<option value="{{ $sku->id }}"
														data-final-price="{{ number_format((float)($sku->final_price ?? 0), 2, '.', '') }}"
														@selected(($item['sku_sellable_id'] ?? null) == $sku->id)>
														{{ $sku->name }} | {{ $sku->product_sku_uid }} | Stock: {{ $sku->stock }} | ${{ number_format((float)($sku->final_price ?? 0), 2) }}
													</option>
												@endforeach
											</select>
										</td>
										<td class="px-4 py-3">
											<select name="items[{{ $index }}][cart_item_id]"
												class="singleSelectWithSearch dropdownSelectInputs manual-order-cart-select"
												disabled data-order-editable="true" data-dropdown-position="absolute">
												<option value="">Select Cart Item</option>
												@foreach ($availableCarts as $cart)
													@php
														$linkedSku = $productSkus->firstWhere('id', $cart->sku_sellable_id);
													@endphp
													<option value="{{ $cart->id }}"
														data-cart-qty="{{ $cart->quantity ?? 1 }}"
														data-current-rate="{{ number_format((float)($linkedSku?->final_price ?? $cart->legacy_rate ?? 0), 2, '.', '') }}"
														@selected(($item['cart_item_id'] ?? null) == $cart->id)>
														{{ $cart->cart_item_uid }} |
														{{ $linkedSku?->name ?? 'N/A' }} | Stock: {{ $linkedSku->stock }}  |
														Qty: {{ $cart->quantity ?? 1 }} | Stock: {{ $linkedSku->stock }}   |
														${{ number_format((float)($linkedSku?->final_price ?? $cart->legacy_rate ?? 0), 2) }}
													</option>
												@endforeach
											</select>
										</td>
										<td class="px-4 py-3">
											<input type="number" min="1" name="items[{{ $index }}][quantity]"
												value="{{ $item['quantity'] ?? 1 }}"
												class="normalInput bg-gray-50 dark:bg-neutral-500 manual-order-qty-input"
												disabled data-order-editable="true" />
										</td>
										<td class="px-4 py-3">
											<input type="number" step="0.01" name="items[{{ $index }}][current_rate]"
												value="{{ $item['current_rate'] ?? null }}" readonly disabled
												class="normalInput bg-gray-200 dark:bg-neutral-500 manual-order-current-rate-input"
												data-order-editable="true" />
										</td>
										<td class="px-4 py-3">
											<input type="number" step="0.01" name="items[{{ $index }}][current_discount]"
												value="{{ $item['current_discount'] ?? 0 }}"
												class="normalInput bg-gray-50 dark:bg-neutral-500 manual-order-current-discount-input"
												disabled data-order-editable="true" />
										</td>
										<td class="px-4 py-3">
											<input type="number" step="0.01" name="items[{{ $index }}][current_subtotal_price]"
												value="{{ $item['current_subtotal_price'] ?? null }}"
												class="normalInput bg-gray-200 dark:bg-neutral-700 manual-order-current-subtotal-input"
												readonly disabled data-order-editable="true" />
										</td>
										<td class="px-4 py-3">
											<input type="number" step="0.01" name="items[{{ $index }}][current_final_price]"
												value="{{ $item['current_final_price'] ?? null }}"
												class="normalInput bg-gray-200 dark:bg-neutral-700 manual-order-current-final-input"
												readonly disabled data-order-editable="true" />
										</td>
										<td class="px-4 py-3">
											<button type="button" class="py-2 px-3 removeManualOrderItemRow" disabled
												data-order-editable-button="true">
												{!! \App\Helpers\IconPack::cross(['class' => 'iconPackItem w-5 h-5 text-red-500']) !!}
											</button>
										</td>
									</tr>
									<tr class="border-b last:border-b-0 border-gray-300 align-top dark:border-neutral-600 manual-order-item-notes-row">
										<td colspan="8" class="px-4 pb-3">
											<div class="grid grid-cols-2 gap-2 py-2">
												<textarea name="items[{{ $index }}][customer_note]"
													class="normalInput bg-gray-50 dark:bg-neutral-500 min-h-20" disabled
													data-order-editable="true"
													placeholder="Customer note for this product">{{ $item['customer_note'] ?? null }}</textarea>

												<textarea name="items[{{ $index }}][system_note]"
													class="normalInput bg-gray-50 dark:bg-neutral-500 min-h-20" disabled
													data-order-editable="true"
													placeholder="System note for this order item">{{ $item['system_note'] ?? null }}</textarea>
											</div>
										</td>
									</tr>
								@endforeach
							</tbody>
							<tfoot class="bg-gray-100 dark:bg-neutral-800">
								<tr class="border-b border-gray-400 dark:border-neutral-600">
									<td colspan="6" class="py-4 px-4 text-right font-medium">Calculated Sub-Total:</td>
									<td colspan="2" class="px-4 text-green-600 manual-order-footer-subtotal">$0.00</td>
								</tr>
								<tr>
									<td colspan="6" class="py-4 px-4 text-right font-medium">Calculated Payable:</td>
									<td colspan="2" class="px-4 text-blue-600 manual-order-footer-payable">$0.00</td>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
				<!------------- ADD PRODUCT ------------->

				<!------------- NOTES ------------->
				<div id="notesArea" class="mb-4 mt-2">
					<div class="mb-5 mt-2">
						<label for="customer_note" class="inputLabelMedium">Customer Note For The Order</label>
						<textarea class="summernote bg-gray-50 dark:bg-neutral-500" name="customer_note" disabled
							data-order-editable="true">{!! old('customer_note', $singleOrder?->customer_note) !!}</textarea>
					</div>

					<div class="mb-5 mt-2">
						<label for="system_note" class="inputLabelMedium">System Note For The Order</label>
						<textarea class="summernote bg-gray-50 dark:bg-neutral-500" name="system_note" disabled
							data-order-editable="true">{!! old('system_note', $singleOrder?->system_note) !!}</textarea>
					</div>

					<div class="mb-5 mt-2">
						<label for="admin_note" class="inputLabelMedium">Admin Note For The Order</label>
						<textarea class="summernote bg-gray-50 dark:bg-neutral-500" name="admin_note" disabled
							data-order-editable="true">{!! old('admin_note', $singleOrder?->admin_note) !!}</textarea>
					</div>

					<div class="mb-5 mt-2">
						<label class="inputLabelMedium">Status</label>
						<select name="status" class="normalInput bg-gray-50 dark:bg-neutral-500" disabled
							data-order-editable="true">
							<option value="1" @selected(old('status', $singleOrder?->status) == 1)>Active</option>
							<option value="0" @selected(old('status', $singleOrder?->status) == 0)>Inactive</option>
						</select>
					</div>
				</div>
				<!------------- NOTES ------------->

				<div class="flex justify-end">
					<button type="submit" id="{{ $submitButtonId }}" class="showButton hidden" disabled>
						Update Order
					</button>
				</div>
			</section>
		</form>

		<!------------- USER BASE -------------->
		@php
			$orderCustomer = $singleOrder?->orderRelatingBackTo_Customer;
			$orderCreator = $singleOrder?->orderRelatingBackTo_Creator;
			$orderLastModifier = $singleOrder?->orderRelatingBackTo_LastModifier;
		@endphp
		<div class="mb-5 mt-2 grid grid-cols-3 gap-4">
			<div class="mb-5 mt-2">
				<label class="inputLabelMedium">Customer</label>
				<a href="{{ $orderCustomer ? route('backend_single_user', $orderCustomer->id) : '#' }}"
					class="normalInput bg-gray-200 dark:bg-neutral-700 flex items-center gap-3">
					<img
						src="{{ $orderCustomer?->avatar ? \Illuminate\Support\Facades\Storage::url($orderCustomer->avatar) : asset('site_assets/dummies/dummy_man.webp') }}"
						alt="customer-avatar"
						class="h-12 w-12 rounded-full object-cover border border-gray-300 dark:border-neutral-500">
					<div>
						<div class="font-medium text-gray-800 dark:text-gray-100">{{ $orderCustomer?->name ?? 'N/A' }}</div>
						<div class="text-sm text-gray-500 dark:text-gray-400">{{ $orderCustomer?->email ?? 'N/A' }}</div>
						<div class="text-xs text-gray-500 dark:text-gray-400">UID: {{ $orderCustomer?->user_uid ?? 'N/A' }}
						</div>
						<div class="text-xs text-gray-500 dark:text-gray-400">Role: {{ $orderCustomer?->user_role ?? 'N/A' }}
						</div>
					</div>
				</a>
			</div>
			<div class="mb-5 mt-2">
				<label class="inputLabelMedium">Created By</label>
				<a href="{{ $orderCreator ? route('backend_single_user', $orderCreator->id) : '#' }}"
					class="normalInput bg-gray-200 dark:bg-neutral-700 flex items-center gap-3">
					<img
						src="{{ $orderCreator?->avatar ? \Illuminate\Support\Facades\Storage::url($orderCreator->avatar) : asset('site_assets/dummies/dummy_man.webp') }}"
						alt="creator-avatar"
						class="h-12 w-12 rounded-full object-cover border border-gray-300 dark:border-neutral-500">
					<div>
						<div class="font-medium text-gray-800 dark:text-gray-100">At: {{ $singleOrder?->created_at ?? 'N/A' }}</div>
						<div class="font-medium text-gray-800 dark:text-gray-100">{{ $orderCreator?->name ?? 'N/A' }}</div>
						<div class="text-sm text-gray-500 dark:text-gray-400">{{ $orderCreator?->email ?? 'N/A' }}</div>
						<div class="text-xs text-gray-500 dark:text-gray-400">UID: {{ $orderCreator?->user_uid ?? 'N/A' }}
						</div>
						<div class="text-xs text-gray-500 dark:text-gray-400">Role: {{ $orderCreator?->user_role ?? 'N/A' }}
						</div>
					</div>
				</a>
			</div>
			<div class="mb-5 mt-2">
				<label class="inputLabelMedium">Last Updated By</label>
				<a href="{{ $orderLastModifier ? route('backend_single_user', $orderLastModifier->id) : '#' }}"
					class="normalInput bg-gray-200 dark:bg-neutral-700 flex items-center gap-3">
					<img
						src="{{ $orderLastModifier?->avatar ? \Illuminate\Support\Facades\Storage::url($orderLastModifier->avatar) : asset('site_assets/dummies/dummy_man.webp') }}"
						alt="last-modifier-avatar"
						class="h-12 w-12 rounded-full object-cover border border-gray-300 dark:border-neutral-500">
					<div>
						<div class="font-medium text-gray-800 dark:text-gray-100">At: {{ $orderLastModifier ? $singleOrder?->updated_at : 'N/A' }}</div>
						<div class="font-medium text-gray-800 dark:text-gray-100">{{ $orderLastModifier?->name ?? 'N/A' }}
						</div>
						<div class="text-sm text-gray-500 dark:text-gray-400">{{ $orderLastModifier?->email ?? 'N/A' }}</div>
						<div class="text-xs text-gray-500 dark:text-gray-400">UID:
							{{ $orderLastModifier?->user_uid ?? 'N/A' }}</div>
						<div class="text-xs text-gray-500 dark:text-gray-400">Role:
							{{ $orderLastModifier?->user_role ?? 'N/A' }}</div>
					</div>
				</a>
			</div>
		</div>
		<!------------- USER BASE -------------->
	</div>
</div>

<template id="{{ $rowTemplateId }}">
	<tr class="manual-order-item-row">
		<td class="px-4 py-3">
			<select name="items[__INDEX__][sku_sellable_id]"
				class="singleSelectWithSearch dropdownSelectInputs manual-order-sku-select"
				data-order-editable="true" data-dropdown-position="absolute">
				<option value="">Select Product SKU</option>
				@foreach ($productSkus as $sku)
					<option value="{{ $sku->id }}"
						data-final-price="{{ number_format((float)($sku->final_price ?? 0), 2, '.', '') }}">
						{{ $sku->name }} | {{ $sku->product_sku_uid }} |
						{{ $sku->skuRelatingBackTo_ProductItem?->name ?? 'N/A' }} |
						${{ number_format((float)($sku->final_price ?? 0), 2) }}
					</option>
				@endforeach
			</select>
		</td>
		<td class="px-4 py-3">
			<select name="items[__INDEX__][cart_item_id]"
				class="singleSelectWithSearch dropdownSelectInputs manual-order-cart-select"
				data-order-editable="true" data-dropdown-position="absolute">
				<option value="">Select Cart Item</option>
				@foreach ($availableCarts as $cart)
					@php
						$linkedSku = $productSkus->firstWhere('id', $cart->sku_sellable_id);
					@endphp
					<option value="{{ $cart->id }}"
						data-cart-qty="{{ $cart->quantity ?? 1 }}"
						data-current-rate="{{ number_format((float)($linkedSku?->final_price ?? $cart->legacy_rate ?? 0), 2, '.', '') }}">
						{{ $cart->cart_item_uid }} |
						{{ $linkedSku?->name ?? 'N/A' }} |
						Qty: {{ $cart->quantity ?? 1 }} |
						${{ number_format((float)($linkedSku?->final_price ?? $cart->legacy_rate ?? 0), 2) }}
					</option>
				@endforeach
			</select>
		</td>
		<td class="px-4 py-3">
			<input type="number" min="1" name="items[__INDEX__][quantity]" value="1"
				class="normalInput bg-gray-50 dark:bg-neutral-500 manual-order-qty-input" data-order-editable="true" />
		</td>
		<td class="px-4 py-3">
			<input type="number" step="0.01" name="items[__INDEX__][current_rate]"
				class="normalInput bg-gray-200 dark:bg-neutral-500 manual-order-current-rate-input" readonly />
		</td>
		<td class="px-4 py-3">
			<input type="number" step="0.01" name="items[__INDEX__][current_discount]" value="0"
				class="normalInput bg-gray-50 dark:bg-neutral-500 manual-order-current-discount-input" data-order-editable="true" />
		</td>
		<td class="px-4 py-3">
			<input type="number" step="0.01" name="items[__INDEX__][current_subtotal_price]"
				class="normalInput bg-gray-200 dark:bg-neutral-700 manual-order-current-subtotal-input" readonly />
		</td>
		<td class="px-4 py-3">
			<input type="number" step="0.01" name="items[__INDEX__][current_final_price]"
				class="normalInput bg-gray-200 dark:bg-neutral-700 manual-order-current-final-input" readonly />
		</td>
		<td class="px-4 py-3">
			<button type="button" class="py-2 px-3 removeManualOrderItemRow" data-order-editable-button="true">
				{!! \App\Helpers\IconPack::cross(['class' => 'iconPackItem w-5 h-5 text-red-500']) !!}
			</button>
		</td>
	</tr>
	<tr class="border-b last:border-b-0 border-gray-300 align-top dark:border-neutral-600 manual-order-item-notes-row">
		<td colspan="8" class="px-4 pb-3">
			<div class="grid grid-cols-2 gap-2 p-2">
				<textarea name="items[__INDEX__][customer_note]"
					class="normalInput bg-gray-50 dark:bg-neutral-500 min-h-20" data-order-editable="true"
					placeholder="Customer note for this product"></textarea>
				<textarea name="items[__INDEX__][system_note]"
					class="normalInput bg-gray-50 dark:bg-neutral-500 min-h-20" data-order-editable="true"
					placeholder="System note for this order item"></textarea>
			</div>
		</td>
	</tr>
</template>

<script>
	(function () {
		const modalRoot = document.getElementById(@json($showModalId));
		if (!modalRoot) {
			return;
		}

		const tableBody = modalRoot.querySelector('#' + @json($tableBodyId));
		const addRowButton = modalRoot.querySelector('#' + @json($addRowButtonId));
		const resetRowsButton = modalRoot.querySelector('#' + @json($resetRowsButtonId));
		const rowTemplate = document.getElementById(@json($rowTemplateId));
		const enableEditButton = modalRoot.querySelector('#' + @json($enableEditButtonId));
		const submitButton = modalRoot.querySelector('#' + @json($submitButtonId));
		const subtotalInput = modalRoot.querySelector('.order-subtotal-input');
		const totalDiscountInput = modalRoot.querySelector('.order-total-discount-input');
		const promoDiscountInput = modalRoot.querySelector('.order-promo-discount-input');
		const payableInput = modalRoot.querySelector('.order-payable-input');
		const footerSubtotal = modalRoot.querySelector('.manual-order-footer-subtotal');
		const footerPayable = modalRoot.querySelector('.manual-order-footer-payable');
		const editableFields = modalRoot.querySelectorAll('[data-order-editable="true"]');

		if (!tableBody || !addRowButton || !resetRowsButton || !rowTemplate || !enableEditButton || !submitButton) {
			return;
		}

		let itemIndex = tableBody.querySelectorAll('.manual-order-item-row').length;
		let isEditMode = false;

		function parseAmount(value) {
			const parsed = parseFloat(value);
			return Number.isFinite(parsed) ? parsed : 0;
		}

		function formatAmount(value) {
			return Number(parseAmount(value)).toFixed(2);
		}

		function initSingleSelectDropdowns(scope) {
			if (!scope) {
				return;
			}

			scope.querySelectorAll('select.singleSelectWithSearch').forEach(function (select) {
				select.removeAttribute('data-init');
			});

			if (typeof SingleSelectDropdown === 'function') {
				SingleSelectDropdown();
			}
		}

		function refreshSingleSelect(select) {
			if (!select) {
				return;
			}

			if (typeof window.refreshCustomSelectDropdown === 'function') {
				window.refreshCustomSelectDropdown(select);
				return;
			}

			if (typeof select.refreshSingleSelectDropdown === 'function') {
				select.refreshSingleSelectDropdown();
			}
		}

		function toggleSummernoteEditors(shouldEnable) {
			if (!window.jQuery || typeof window.jQuery.fn?.summernote !== 'function') {
				return;
			}

			window.jQuery(modalRoot).find('textarea.summernote').each(function () {
				const editor = window.jQuery(this);

				try {
					editor.summernote(shouldEnable ? 'enable' : 'disable');
				} catch (error) {
					// keep modal usable even if editor state is not ready yet
				}
			});
		}

		function setEditMode(shouldEnable) {
			isEditMode = shouldEnable;

			editableFields.forEach(function (field) {
				field.disabled = !shouldEnable;
			});

			modalRoot.querySelectorAll('[data-order-editable-button="true"]').forEach(function (button) {
				button.disabled = !shouldEnable;
			});

			if (submitButton) {
				submitButton.disabled = !shouldEnable;
				submitButton.classList.toggle('hidden', !shouldEnable);
			}

			if (enableEditButton) {
				enableEditButton.classList.toggle('hidden', shouldEnable);
			}

			modalRoot.querySelectorAll('select.singleSelectWithSearch').forEach(function (select) {
				refreshSingleSelect(select);
			});

			toggleSummernoteEditors(shouldEnable);

			tableBody.querySelectorAll('.manual-order-item-row').forEach(function (row) {
				syncInitialRowSelection(row);
			});
		}

		function recalculateRow(row) {
			if (!row) {
				return;
			}

			const qtyInput = row.querySelector('.manual-order-qty-input');
			const currentRateInput = row.querySelector('.manual-order-current-rate-input');
			const currentDiscountInput = row.querySelector('.manual-order-current-discount-input');
			const currentSubtotalInput = row.querySelector('.manual-order-current-subtotal-input');
			const currentFinalInput = row.querySelector('.manual-order-current-final-input');

			const qty = Math.max(parseInt(qtyInput?.value || 1, 10) || 1, 1);
			const currentRate = parseAmount(currentRateInput?.value);
			const currentDiscount = parseAmount(currentDiscountInput?.value);

			const currentSubtotal = qty * currentRate;
			const currentFinal = Math.max(currentSubtotal - currentDiscount, 0);

			if (currentSubtotalInput) {
				currentSubtotalInput.value = formatAmount(currentSubtotal);
			}

			if (currentFinalInput) {
				currentFinalInput.value = formatAmount(currentFinal);
			}
		}

		function syncRowPricingFromSelectedSku(row) {
			if (!row) {
				return;
			}

			const cartSelect = row.querySelector('.manual-order-cart-select');
			const skuSelect = row.querySelector('.manual-order-sku-select');
			const selectedOption = skuSelect?.options[skuSelect.selectedIndex] ?? null;
			const selectedPrice = selectedOption ? parseAmount(selectedOption.getAttribute('data-final-price')) : 0;
			const qtyInput = row.querySelector('.manual-order-qty-input');
			const currentRateInput = row.querySelector('.manual-order-current-rate-input');
			const currentDiscountInput = row.querySelector('.manual-order-current-discount-input');

			if (qtyInput) {
				qtyInput.readOnly = !isEditMode ? true : false;
				qtyInput.classList.toggle('bg-gray-200', !isEditMode ? true : false);
				qtyInput.classList.toggle('bg-gray-50', !isEditMode ? false : true);
			}

			if (currentRateInput) {
				currentRateInput.value = selectedOption ? formatAmount(selectedPrice) : '';
			}

			if (currentDiscountInput) {
				currentDiscountInput.value = 0;
				currentDiscountInput.readOnly = !isEditMode ? true : false;
				currentDiscountInput.classList.toggle('bg-gray-200', !isEditMode ? true : false);
				currentDiscountInput.classList.toggle('bg-gray-50', !isEditMode ? false : true);
			}

			if (selectedOption && cartSelect) {
				cartSelect.value = '';
				refreshSingleSelect(cartSelect);
			}
		}

		function syncRowPricingFromSelectedCart(row) {
			if (!row) {
				return;
			}

			const cartSelect = row.querySelector('.manual-order-cart-select');
			const skuSelect = row.querySelector('.manual-order-sku-select');
			const selectedOption = cartSelect?.options[cartSelect.selectedIndex] ?? null;
			const selectedQty = selectedOption ? Math.max(parseInt(selectedOption.getAttribute('data-cart-qty') || 1, 10) || 1, 1) : 1;
			const selectedCurrentRate = selectedOption ? parseAmount(selectedOption.getAttribute('data-current-rate')) : 0;
			const qtyInput = row.querySelector('.manual-order-qty-input');
			const currentRateInput = row.querySelector('.manual-order-current-rate-input');
			const currentDiscountInput = row.querySelector('.manual-order-current-discount-input');

			if (qtyInput) {
				qtyInput.value = selectedOption ? selectedQty : 1;
				qtyInput.readOnly = selectedOption ? true : !isEditMode;
				qtyInput.classList.toggle('bg-gray-200', selectedOption ? true : !isEditMode);
				qtyInput.classList.toggle('bg-gray-50', selectedOption ? false : isEditMode);
			}

			if (currentRateInput) {
				currentRateInput.value = selectedOption ? formatAmount(selectedCurrentRate) : '';
			}

			if (currentDiscountInput) {
				currentDiscountInput.value = 0;
				currentDiscountInput.readOnly = selectedOption ? true : !isEditMode;
				currentDiscountInput.classList.toggle('bg-gray-200', selectedOption ? true : !isEditMode);
				currentDiscountInput.classList.toggle('bg-gray-50', selectedOption ? false : isEditMode);
			}

			if (selectedOption && skuSelect) {
				skuSelect.value = '';
				refreshSingleSelect(skuSelect);
			}
		}

		function syncInitialRowSelection(row) {
			if (!row) {
				return;
			}

			const cartSelect = row.querySelector('.manual-order-cart-select');
			const skuSelect = row.querySelector('.manual-order-sku-select');

			if (cartSelect?.value) {
				syncRowPricingFromSelectedCart(row);
				return;
			}

			if (skuSelect?.value) {
				syncRowPricingFromSelectedSku(row);
			}
		}

		function resetRowData(row) {
			if (!row) {
				return;
			}

			const notesRow = row.nextElementSibling?.classList.contains('manual-order-item-notes-row')
				? row.nextElementSibling
				: null;
			const skuSelect = row.querySelector('.manual-order-sku-select');
			const cartSelect = row.querySelector('.manual-order-cart-select');
			const qtyInput = row.querySelector('.manual-order-qty-input');
			const currentRateInput = row.querySelector('.manual-order-current-rate-input');
			const currentDiscountInput = row.querySelector('.manual-order-current-discount-input');
			const currentSubtotalInput = row.querySelector('.manual-order-current-subtotal-input');
			const currentFinalInput = row.querySelector('.manual-order-current-final-input');
			const customerNoteInput = notesRow?.querySelector('textarea[name*="[customer_note]"]');
			const systemNoteInput = notesRow?.querySelector('textarea[name*="[system_note]"]');

			if (skuSelect) {
				skuSelect.value = '';
				refreshSingleSelect(skuSelect);
			}

			if (cartSelect) {
				cartSelect.value = '';
				refreshSingleSelect(cartSelect);
			}

			if (qtyInput) {
				qtyInput.value = 1;
			}

			if (currentRateInput) {
				currentRateInput.value = '';
			}

			if (currentDiscountInput) {
				currentDiscountInput.value = 0;
			}

			if (currentSubtotalInput) {
				currentSubtotalInput.value = '0.00';
			}

			if (currentFinalInput) {
				currentFinalInput.value = '0.00';
			}

			if (customerNoteInput) {
				customerNoteInput.value = '';
			}

			if (systemNoteInput) {
				systemNoteInput.value = '';
			}
		}

		function resetManualOrderItems() {
			const itemRows = tableBody.querySelectorAll('.manual-order-item-row');
			if (!itemRows.length) {
				return;
			}

			itemRows.forEach(function (row, index) {
				if (index === 0) {
					resetRowData(row);
					return;
				}

				const notesRow = row.nextElementSibling;
				row.remove();
				if (notesRow?.classList.contains('manual-order-item-notes-row')) {
					notesRow.remove();
				}
			});

			recalculateOrderTotals();
		}

		function recalculateOrderTotals() {
			let subtotal = 0;
			let payable = 0;

			tableBody.querySelectorAll('.manual-order-item-row').forEach(function (row) {
				recalculateRow(row);
				subtotal += parseAmount(row.querySelector('.manual-order-current-subtotal-input')?.value);
				payable += parseAmount(row.querySelector('.manual-order-current-final-input')?.value);
			});

			const totalDiscount = parseAmount(totalDiscountInput?.value);
			const finalPayable = Math.max(payable - totalDiscount, 0);

			if (subtotalInput) {
				subtotalInput.value = formatAmount(subtotal);
			}

			if (payableInput) {
				payableInput.value = formatAmount(finalPayable);
			}

			if (footerSubtotal) {
				footerSubtotal.textContent = '$' + formatAmount(subtotal);
			}

			if (footerPayable) {
				footerPayable.textContent = '$' + formatAmount(finalPayable);
			}
		}

		function appendNewRow() {
			const html = rowTemplate.innerHTML.replaceAll('__INDEX__', itemIndex);
			tableBody.insertAdjacentHTML('beforeend', html);
			itemIndex++;

			const itemRows = tableBody.querySelectorAll('.manual-order-item-row');
			const newRow = itemRows[itemRows.length - 1] ?? null;
			initSingleSelectDropdowns(newRow);
			syncInitialRowSelection(newRow);
			recalculateOrderTotals();
		}

		enableEditButton.addEventListener('click', function () {
			setEditMode(true);
		});

		addRowButton.addEventListener('click', function () {
			if (!isEditMode) {
				return;
			}

			appendNewRow();
		});

		resetRowsButton.addEventListener('click', function () {
			if (!isEditMode) {
				return;
			}

			resetManualOrderItems();
		});

		tableBody.addEventListener('click', function (event) {
			if (!isEditMode) {
				return;
			}

			const removeButton = event.target.closest('.removeManualOrderItemRow');
			if (!removeButton) {
				return;
			}

			const rows = tableBody.querySelectorAll('.manual-order-item-row');
			if (rows.length === 1) {
				return;
			}

			const itemRow = removeButton.closest('.manual-order-item-row');
			const notesRow = itemRow?.nextElementSibling;
			itemRow?.remove();
			if (notesRow?.classList.contains('manual-order-item-notes-row')) {
				notesRow.remove();
			}
			recalculateOrderTotals();
		});

		tableBody.addEventListener('change', function (event) {
			if (!isEditMode) {
				return;
			}

			const row = event.target.closest('.manual-order-item-row');
			if (!row) {
				return;
			}

			if (event.target.classList.contains('manual-order-sku-select')) {
				syncRowPricingFromSelectedSku(row);
			}

			if (event.target.classList.contains('manual-order-cart-select')) {
				syncRowPricingFromSelectedCart(row);
			}

			recalculateOrderTotals();
		});

		tableBody.addEventListener('input', function (event) {
			if (!isEditMode) {
				return;
			}

			if (event.target.closest('.manual-order-item-row')) {
				recalculateOrderTotals();
			}
		});

		if (promoDiscountInput) {
			promoDiscountInput.dataset.previousValue = formatAmount(parseAmount(promoDiscountInput.value));

			promoDiscountInput.addEventListener('input', function () {
				if (!isEditMode) {
					return;
				}

				const previousPromoDiscount = parseAmount(promoDiscountInput.dataset.previousValue);
				const currentPromoDiscount = parseAmount(promoDiscountInput.value);
				const currentTotalDiscount = parseAmount(totalDiscountInput?.value);
				const nonPromoDiscount = Math.max(currentTotalDiscount - previousPromoDiscount, 0);
				const nextTotalDiscount = currentPromoDiscount + nonPromoDiscount;

				if (totalDiscountInput) {
					totalDiscountInput.value = formatAmount(nextTotalDiscount);
				}

				promoDiscountInput.dataset.previousValue = formatAmount(currentPromoDiscount);
				recalculateOrderTotals();
			});
		}

		if (totalDiscountInput) {
			totalDiscountInput.addEventListener('input', function () {
				if (!isEditMode) {
					return;
				}

				recalculateOrderTotals();
			});
		}

		initSingleSelectDropdowns(modalRoot);
		tableBody.querySelectorAll('.manual-order-item-row').forEach(function (row) {
			syncInitialRowSelection(row);
		});
		recalculateOrderTotals();
		setEditMode(false);
	})();
</script>

<style>
	td {
		text-align: center;
	}
</style>
