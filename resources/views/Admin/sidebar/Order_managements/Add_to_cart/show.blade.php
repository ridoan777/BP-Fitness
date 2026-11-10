<div class="p-4">
	<div>

		<!-- Basic -->
		<div class="mb-5 mt-2 grid grid-cols-2 gap-4">
			<div>
				<label class="inputLabelMedium">ID</label>
				<p class="normalInput bg-gray-100 dark:bg-neutral-700">{{ $singleAddToCart->id }}</p>
			</div>
			<div>
				<label class="inputLabelMedium">Cart UID</label>
				<p class="normalInput bg-gray-100 dark:bg-neutral-700">{{ $singleAddToCart->cart_item_uid }}</p>
			</div>
		</div>

		<!-- Product Variant (Display Only) -->
		<div class="mb-5 mt-2">
			<label class="inputLabelMedium">Product Variant Title</label>
			<p class="normalInput bg-gray-100 dark:bg-neutral-700">
				{{ $singleAddToCart?->cartRelatingBackTo_ProductSku?->name ?? 'N/A' }}
			</p>
		</div>

		<!-- Buyer (Display Only) -->
		<div class="mb-5 mt-2">
			<label class="inputLabelMedium">Buyer</label>
			<p class="normalInput bg-gray-100 dark:bg-neutral-700">
				{{ $singleAddToCart?->cartRelatingBackTo_User?->name ?? 'N/A' }}
			</p>
		</div>

		<!-- Quantity -->
		<div class="mb-5 mt-2 grid grid-cols-2 gap-4">
			<div>
				<label class="inputLabelMedium">Quantity</label>
				<p class="normalInput bg-gray-100 dark:bg-neutral-700">{{ $singleAddToCart?->quantity ?? 'N/A' }}
				</p>
			</div>
			<div> <label class="inputLabelMedium">Phase</label>
				<span
					class="px-2 py-1 text-xs rounded-full {{ $singleAddToCart->order_conversion ? 'bg-green-400 text-white' : 'bg-orange-400 text-white' }}"
					title="{{ $singleAddToCart->order_conversion ? 'cart item is ordered' : 'cart item is still idle waiting for order' }}">
					{{ $singleAddToCart->order_conversion ? 'Ordered' : 'Pending' }}
				</span>
			</div>
		</div>


		<!-- Legacy Amounts (Display Only) -->
		<div class="mb-5 mt-2 grid grid-cols-2 gap-4">
			<div>
				<label class="inputLabelMedium">Rate when added</label>
				<p class="normalInput bg-gray-100 dark:bg-neutral-700">
					{{ isset($singleAddToCart->legacy_rate) ? "$" . number_format($singleAddToCart->legacy_rate, 2) : 'N/A' }}
				</p>
			</div>
			<div>
				<label class="inputLabelMedium">Legacy total price</label>
				<p class="normalInput bg-gray-100 dark:bg-neutral-700">
					{{ isset($singleAddToCart->legacy_total_price) ? "$" . number_format($singleAddToCart->legacy_total_price, 2) : 'N/A' }}
				</p>
			</div>
		</div>

		<!-- Current Amounts (Display Only) -->
		<div class="mb-5 mt-2 grid grid-cols-2 gap-4">
			<div>
				<label class="inputLabelMedium">Rate Today</label>
				<p class="normalInput bg-gray-100 dark:bg-neutral-700">
					{{ isset($singleAddToCart?->cartRelatingBackTo_ProductSku) ? "$" . number_format($singleAddToCart->cartRelatingBackTo_ProductSku?->final_price, 2) : 'N/A' }}
				</p>
			</div>
			<div>
				<label class="inputLabelMedium">Total current price
					<span class="grayLabel">(Users will pay this)</span>
				</label>
				<p class="normalInput bg-gray-100 dark:bg-neutral-700">
					{{ isset($singleAddToCart?->cartRelatingBackTo_ProductSku) ? "$" . number_format($singleAddToCart->cartRelatingBackTo_ProductSku?->final_price * $singleAddToCart->quantity, 2) : 'N/A' }}
				</p>
			</div>
		</div>

		<!-- Created At (Display Only) -->

		<div class="mb-5 mt-2 grid grid-cols-2 gap-4">
			<div class="mb-5 mt-2">
				<label class="inputLabelMedium">Created At</label>
				<p class="normalInput bg-gray-100 dark:bg-neutral-700">{{ $singleAddToCart->created_at }}</p>
			</div>
			<div class="mb-5 mt-2">
				<label class="inputLabelMedium">Last updated At</label>
				<p class="normalInput bg-gray-100 dark:bg-neutral-700">{{ $singleAddToCart->updated_at }}</p>
			</div>
		</div>

	</div>
</div>