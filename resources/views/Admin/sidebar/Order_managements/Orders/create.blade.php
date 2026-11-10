<section>

	<div class="px-6 flex justify-end">
		<button type="button" data-modal-target="hs-order-create-modal" data-modal-toggle="hs-order-create-modal"
			class="darkButton">
			+ Create Order
		</button>
	</div>

	<x-ui_items.modals.form-modal id="hs-order-create-modal" title="Create Manual Order" cancelText="Cancel"
		modalWidth="w-7xl">
		<div class="p-4">
			<div>
				<p class="text-center grayLabel">(Manual orders do not create any transaction records)</p>
			</div>
			<div>
				<div class="mb-5 mt-2 grid grid-cols-2 gap-4">
					<div>
						<label class="inputLabelMedium">ID</label>
						<p class="normalInput bg-gray-200 dark:bg-neutral-700">Auto Generated</p>
					</div>
					<div>
						<label class="inputLabelMedium">Order UID</label>
						<p class="normalInput bg-gray-200 dark:bg-neutral-700">Auto Generated</p>
					</div>
				</div>

				<form method="POST" action="{{ route('backend_estore_orders_store') }}"
					class="rounded-lg p-2 dark:bg-neutral-700" enctype="multipart/form-data">
					@csrf

					<section>
						<!------------- CUSTOMER + DATE ------------->
						<div class="grid grid-cols-2 gap-4">
							<div class="mb-5 mt-2">
								<label class="inputLabelMedium">Customer</label>
								<select name="customer_id" class="singleSelectWithSearch dropdownSelectInputs"
									data-dropdown-position="absolute">
									<option value="">Select Customer</option>
									@foreach ($customers as $customer)
										<option value="{{ $customer->id }}" @selected(old('customer_id') == $customer->id)>
											{{ $customer->name }} | {{ $customer->email }} | {{ strtoupper($customer->user_role) }}
										</option>
									@endforeach
								</select>
							</div>
							<div class="mb-5 mt-2">
								<div>
									<label class="inputLabelMedium">Order Placing Date</label>
									<input type="datetime-local" name="placed_at" value="{{ old('placed_at') }}"
										class="normalInput bg-gray-50 dark:bg-neutral-500" />
								</div>
							</div>
						</div>
						<!------------- CUSTOMER + DATE ------------->

						<p class="text-sm text-gray-400">Selecting Order Phase 'Processing' or 'Completed' will mark the cart items 'converted as order'.</p>

						<!------------- ORDER + PAYMENT + DELIVERY + CURRENCY ------------->
						<div class="mb-5 mt-2 grid grid-cols-4 gap-4">
							<div>
								<label class="inputLabelMedium">Order Phase</label>
								<select name="order_status" class="normalInput bg-gray-50 dark:bg-neutral-500">
									@foreach (\App\Domains\E_Store\Shoppings\Enums\OrderStatusEnums::options() as $option)
										<option value="{{ $option['value'] }}" @selected(old('order_status', \App\Domains\E_Store\Shoppings\Enums\OrderStatusEnums::PENDING->value ?? null) === $option['value'])>
											{{ $option['label'] }}
										</option>
									@endforeach
								</select>
							</div>
							<div>
								<label class="inputLabelMedium">Payment</label>
								<select name="payment_status" class="normalInput bg-gray-50 dark:bg-neutral-500">
									@foreach (\App\Domains\Billings\Shared\Enums\PaymentStatusEnums::options() as $option)
										<option value="{{ $option['value'] }}" @selected(old('payment_status') === $option['value'])>
											{{ $option['label'] }}
										</option>
									@endforeach
								</select>
							</div>
							<div>
								<label class="inputLabelMedium">Delivery</label>
								<select name="delivery_status" class="normalInput bg-gray-50 dark:bg-neutral-500">
									@foreach (\App\Domains\E_Store\Shoppings\Enums\DeliveryStatusEnums::options() as $option)
										<option value="{{ $option['value'] }}" @selected(old('delivery_status') === $option['value'])>
											{{ $option['label'] }}
										</option>
									@endforeach
								</select>
							</div>
							<div>
								<label class="inputLabelMedium">Currency</label>
								<select name="currency" class="normalInput bg-gray-50 dark:bg-neutral-500">
									@foreach (\App\Domains\Billings\Shared\Enums\CurrencyEnums::options() as $option)
										<option value="{{ $option['value'] }}" @selected(old('currency') === $option['value'])>
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
								<input type="text" name="promo_code" value="{{ old('promo_code') }}"
									class="normalInput bg-gray-50 dark:bg-neutral-500" />
							</div>
							<div>
								<label class="inputLabelMedium">Promo Discount Amount</label>
								<input type="number" step="0.01" name="promo_discount_amount"
									value="{{ old('promo_discount_amount', 0) }}"
									class="normalInput bg-gray-50 dark:bg-neutral-500 order-promo-discount-input" />
							</div>
							<div>
								<label class="inputLabelMedium">Total Discount Amount</label>
								<input type="number" step="0.01" name="total_discount_amount"
									value="{{ old('total_discount_amount', 0) }}"
									class="normalInput bg-gray-50 dark:bg-neutral-500 order-total-discount-input" />
							</div>
						</div>
						<!------------- PROMO + DISCOUNTS ------------->

						<!------------- TOTALS & REFUNDS ------------->
						<div class="mb-5 mt-2 grid grid-cols-3 gap-4">
							<div>
								<label class="inputLabelMedium">Sub-Total Amount</label>
								<input type="number" step="0.01" name="subtotal_amount" value="{{ old('subtotal_amount') }}"
									class="normalInput bg-gray-200 dark:bg-neutral-500 order-subtotal-input" readonly/>
							</div>
							<div>
								<label class="inputLabelMedium">Payable Amount</label>
								<input type="number" step="0.01" name="payable_amount" value="{{ old('payable_amount') }}"
									class="normalInput bg-gray-200 dark:bg-neutral-500 order-payable-input" readonly/>
							</div>
							<div>
								<label class="inputLabelMedium">Refunded Amount</label>
								<input type="number" step="0.01" name="refunded_amount" value="{{ old('refunded_amount', 0) }}"
									class="normalInput bg-gray-50 dark:bg-neutral-500" />
							</div>
						</div>
						<!------------- TOTALS & REFUNDS ------------->

						<!------------- ADD PRODUCT ------------->
						<div class="mb-5 mt-2 p-4 bg-gray-50 dark:bg-neutral-600 rounded-lg">
							<div class="mb-4 flex items-center justify-between gap-4">
								<label class="inputLabelMedium">Products/Order Items
									<span class="grayLabel">(product variants & cart items are mutually exclusive. Select only one in a single order item record.)</span>
								</label>
								<div class="flex items-center gap-2">
									<button type="button" class="showButton py-2 px-3" id="resetManualOrderItems">
										{!! \App\Helpers\IconPack::recurring(['class' => 'iconPackItem w-5 h-5']) !!}
									</button>
									<button type="button" class="showButton py-2 px-4" id="addManualOrderItemRow">Add
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
									<tbody class="bg-white dark:bg-neutral-700" id="manualOrderItemsTableBody">
										@foreach ($manualOrderItems as $index => $item)
											<tr
												class=" manual-order-item-row">
												<td class="px-4 py-3">
													<select name="items[{{ $index }}][sku_sellable_id]"
														class="singleSelectWithSearch dropdownSelectInputs manual-order-sku-select"
														data-dropdown-position="absolute">
														<option value="">Select Product Sellable SKU</option>
														@foreach ($productSkus as $sku)
															<option value="{{ $sku->id }}"
																data-final-price="{{ number_format((float)($sku->final_price ?? 0), 2, '.', '') }}"
																@selected(($item['sku_sellable_id'] ?? null) == $sku->id)>
																{{ $sku->name }} | {{ $sku->product_sku_uid }} | Stock:{{ $sku->stock }} | ${{ number_format((float)($sku->final_price ?? 0), 2) }}
															</option>
														@endforeach
													</select>

												</td>
												<td class="px-4 py-3">
													<select name="items[{{ $index }}][cart_item_id]"
														class="singleSelectWithSearch dropdownSelectInputs manual-order-cart-select"
														data-dropdown-position="absolute">
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
																{{ $linkedSku?->name ?? 'N/A' }} | Stock:{{ $linkedSku->stock }}  |
																Qty: {{ $cart->quantity ?? 1 }} |
																${{ number_format((float)($linkedSku?->final_price ?? $cart->legacy_rate ?? 0), 2) }}
															</option>
														@endforeach
													</select>
												</td>
												<td class="px-4 py-3">
													<input type="number" min="1" name="items[{{ $index }}][quantity]"
														value="{{ $item['quantity'] ?? 1 }}"
														class="normalInput bg-gray-50 dark:bg-neutral-500 manual-order-qty-input" />
												</td>
												<td class="px-4 py-3">
													<input type="number" step="0.01" name="items[{{ $index }}][current_rate]"
														value="{{ $item['current_rate'] ?? null }}" readonly
														class="normalInput bg-gray-200 dark:bg-neutral-500 manual-order-current-rate-input" />
												</td>
												<td class="px-4 py-3">
													<input type="number" step="0.01" name="items[{{ $index }}][current_discount]"
														value="{{ $item['current_discount'] ?? 0 }}"
														class="normalInput bg-gray-50 dark:bg-neutral-500 manual-order-current-discount-input" />
												</td>
												<td class="px-4 py-3">
													<input type="number" step="0.01" name="items[{{ $index }}][current_subtotal_price]"
														value="{{ $item['current_subtotal_price'] ?? null }}"
														class="normalInput bg-gray-200 dark:bg-neutral-700 manual-order-current-subtotal-input"
														readonly />
												</td>
												<td class="px-4 py-3">
													<input type="number" step="0.01" name="items[{{ $index }}][current_final_price]"
														value="{{ $item['current_final_price'] ?? null }}"
														class="normalInput bg-gray-200 dark:bg-neutral-700 manual-order-current-final-input"
														readonly />
												</td>
												<td class="px-4 py-3">
													<button type="button" class="py-2 px-3 removeManualOrderItemRow">
														{!! \App\Helpers\IconPack::cross(['class' => 'iconPackItem w-5 h-5 text-red-500']) !!}
													</button>
												</td>
											</tr>
											<tr class="border-b last:border-b-0 border-gray-300 align-top dark:border-neutral-600 manual-order-item-notes-row">
												<td colspan="8" class="px-4 pb-3">
													<div class="grid grid-cols-2 gap-2 py-2">
														<textarea name="items[{{ $index }}][customer_note]"
															class="normalInput bg-gray-50 dark:bg-neutral-500 min-h-20"
															placeholder="Customer note for this product">{{ $item['customer_note'] ?? null }}</textarea>

														<textarea name="items[{{ $index }}][system_note]"
															class="normalInput bg-gray-50 dark:bg-neutral-500 min-h-20"
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
								<textarea class="summernote bg-gray-50 dark:bg-neutral-500"
									name="customer_note">{!! old('customer_note') !!}</textarea>
							</div>

							<div class="mb-5 mt-2">
								<label for="system_note" class="inputLabelMedium">System Note For The Order</label>
								<textarea class="summernote bg-gray-50 dark:bg-neutral-500"
									name="system_note">{!! old('system_note') !!}</textarea>
							</div>

							<div class="mb-5 mt-2">
								<label for="admin_note" class="inputLabelMedium">Admin Note For The Order</label>
								<textarea class="summernote bg-gray-50 dark:bg-neutral-500"
									name="admin_note">{!! old('admin_note') !!}</textarea>
							</div>
						</div>
						<!------------- NOTES ------------->

						<div class="flex justify-end">
							<button type="submit" class="saveButton">Create Order</button>
						</div>
					</section>
				</form>
			</div>
		</div>
	</x-ui_items.modals.form-modal>

	<template id="manualOrderItemRowTemplate">
		<tr class="manual-order-item-row">
			<td class="px-4 py-3">
				<select name="items[__INDEX__][sku_sellable_id]"
					class="singleSelectWithSearch dropdownSelectInputs manual-order-sku-select"
					data-dropdown-position="absolute">
					<option value="">Select Product SKU</option>
					@foreach ($productSkus as $sku)
						<option value="{{ $sku->id }}"
							data-final-price="{{ number_format((float)($sku->final_price ?? 0), 2, '.', '') }}">
							{{ $sku->name }} | {{ $sku->product_sku_uid }} | Stock:{{ $sku->stock }}  |
							{{ $sku->skuRelatingBackTo_ProductItem?->name ?? 'N/A' }} |
							${{ number_format((float)($sku->final_price ?? 0), 2) }}
						</option>
					@endforeach
				</select>
			</td>
			<td class="px-4 py-3">
				<select name="items[__INDEX__][cart_item_id]"
					class="singleSelectWithSearch dropdownSelectInputs manual-order-cart-select"
					data-dropdown-position="absolute">
					<option value="">Select Cart Item</option>
					@foreach ($availableCarts as $cart)
						@php
							$linkedSku = $productSkus->firstWhere('id', $cart->sku_sellable_id);
						@endphp
						<option value="{{ $cart->id }}"
							data-cart-qty="{{ $cart->quantity ?? 1 }}"
							data-current-rate="{{ number_format((float)($linkedSku?->final_price ?? $cart->legacy_rate ?? 0), 2, '.', '') }}">
							{{ $cart->cart_item_uid }} |
							{{ $linkedSku?->name ?? 'N/A' }} | Stock:{{ $linkedSku->stock }}  |
							Qty: {{ $cart->quantity ?? 1 }} |
							${{ number_format((float)($linkedSku?->final_price ?? $cart->legacy_rate ?? 0), 2) }}
						</option>
					@endforeach
				</select>
			</td>
			<td class="px-4 py-3">
				<input type="number" min="1" name="items[__INDEX__][quantity]" value="1"
					class="normalInput bg-gray-50 dark:bg-neutral-500 manual-order-qty-input" />
			</td>
			<td class="px-4 py-3">
				<input type="number" step="0.01" name="items[__INDEX__][current_rate]"
					class="normalInput bg-gray-200 dark:bg-neutral-500 manual-order-current-rate-input" readonly />
			</td>
			<td class="px-4 py-3">
				<input type="number" step="0.01" name="items[__INDEX__][current_discount]" value="0"
					class="normalInput bg-gray-50 dark:bg-neutral-500 manual-order-current-discount-input" />
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
				<button type="button" class="py-2 px-3 removeManualOrderItemRow">
					{!! \App\Helpers\IconPack::cross(['class' => 'iconPackItem w-5 h-5 text-red-500']) !!}
				</button>
			</td>
		</tr>
		<tr class="border-b last:border-b-0 border-gray-300 align-top dark:border-neutral-600 manual-order-item-notes-row">
			<td colspan="8" class="px-4 pb-3">
				<div class="grid grid-cols-2 gap-2 p-2">
					<textarea name="items[__INDEX__][customer_note]"
						class="normalInput bg-gray-50 dark:bg-neutral-500 min-h-20" placeholder="Customer note for this product"></textarea>
					<textarea name="items[__INDEX__][system_note]"
						class="normalInput bg-gray-50 dark:bg-neutral-500 min-h-20" placeholder="System note for this order item"></textarea>
				</div>
			</td>
		</tr>
	</template>

	<script>
		(function () {
			const tableBody = document.getElementById('manualOrderItemsTableBody');
			const addRowButton = document.getElementById('addManualOrderItemRow');
			const resetRowsButton = document.getElementById('resetManualOrderItems');
			const rowTemplate = document.getElementById('manualOrderItemRowTemplate');
			const subtotalInput = document.querySelector('.order-subtotal-input');
			const totalDiscountInput = document.querySelector('.order-total-discount-input');
			const promoDiscountInput = document.querySelector('.order-promo-discount-input');
			const payableInput = document.querySelector('.order-payable-input');
			const footerSubtotal = document.querySelector('.manual-order-footer-subtotal');
			const footerPayable = document.querySelector('.manual-order-footer-payable');

			if (!tableBody || !addRowButton || !resetRowsButton || !rowTemplate) {
				return;
			}

			let itemIndex = tableBody.querySelectorAll('.manual-order-item-row').length;

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
					qtyInput.readOnly = false;
					qtyInput.classList.toggle('bg-gray-200', false);
					qtyInput.classList.toggle('bg-gray-50', true);
				}

				if (currentRateInput) {
					currentRateInput.value = selectedOption ? formatAmount(selectedPrice) : '';
				}

				if (currentDiscountInput) {
					currentDiscountInput.value = 0;
					currentDiscountInput.readOnly = false;
					currentDiscountInput.classList.toggle('bg-gray-200', false);
					currentDiscountInput.classList.toggle('bg-gray-50', true);
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
					qtyInput.readOnly = !!selectedOption;
					qtyInput.classList.toggle('bg-gray-200', !!selectedOption);
					qtyInput.classList.toggle('bg-gray-50', !selectedOption);
				}

				if (currentRateInput) {
					currentRateInput.value = selectedOption ? formatAmount(selectedCurrentRate) : '';
				}

				if (currentDiscountInput) {
					currentDiscountInput.value = 0;
					currentDiscountInput.readOnly = !!selectedOption;
					currentDiscountInput.classList.toggle('bg-gray-200', !!selectedOption);
					currentDiscountInput.classList.toggle('bg-gray-50', !selectedOption);
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

			addRowButton.addEventListener('click', appendNewRow);
			resetRowsButton.addEventListener('click', resetManualOrderItems);

			tableBody.addEventListener('click', function (event) {
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
				if (event.target.closest('.manual-order-item-row')) {
					recalculateOrderTotals();
				}
			});

			if (promoDiscountInput) {
				promoDiscountInput.dataset.previousValue = formatAmount(parseAmount(promoDiscountInput.value));

				promoDiscountInput.addEventListener('input', function () {
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
				totalDiscountInput.addEventListener('input', recalculateOrderTotals);
			}

			initSingleSelectDropdowns(document.getElementById('hs-order-create-modal'));
			tableBody.querySelectorAll('.manual-order-item-row').forEach(function (row) {
				syncInitialRowSelection(row);
			});
			recalculateOrderTotals();
		})();
	</script>
</section>
