<div class="p-4 text-slate-700 dark:text-slate-200">
   @php
      $zones = \App\Helpers\Ui\ConvertDynamicTimezone::handle();
      $trxUser = $transaction?->trxRelatingBackTo_User;
      $snapshotUser = $transaction?->trxRelatingBackTo_SnapshotUser;
      $source = $transaction?->paymentSource;
      $isProductOrder = $transaction?->payment_source_type === \App\Domains\E_Store\Shoppings\Models\ProductOrder::class;
      $sourceType = $transaction?->payment_source_type ?? 'N/A';
      $sourceLabel = $transaction?->payment_source_type ? class_basename($transaction->payment_source_type) : 'N/A';

      $formatDate = function ($model, string $column) use ($zones) {
         if (!$model?->getRawOriginal($column)) {
            return 'N/A';
         }

         return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $model->getRawOriginal($column), 'UTC')
            ->timezone($zones['zone'])
            ->format('d M Y, H:i') . ' (UTC' . $zones['offset'] . ')';
      };

      $formatMoney = function ($amount, $currency = null, bool $amountIsCent = true) {
         if ($amount === null || $amount === '') {
            return 'N/A';
         }

         $value = $amountIsCent ? ((float) $amount) / 100 : (float) $amount;
         $currencyCode = match (true) {
            $currency instanceof \App\Domains\Billings\Shared\Enums\CurrencyEnums => $currency->code(),
            is_string($currency) && $currency !== '' => strtoupper($currency),
            default => null,
         };
         $prefix = $currencyCode ? $currencyCode . ' ' : '';

         return $prefix . number_format($value, 2);
      };

      $orderViewUrl = $productOrder
         ? route('backend_estore_orders_show', ['singleOrder' => $productOrder->id, 'slug' => $productOrder->order_uid])
         : null;

      $sectionClass = 'mb-4 overflow-hidden rounded-xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-950';
      $sectionHeadClass = 'flex items-start justify-between gap-3 border-b border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-900';
      $sectionTitleClass = 'text-sm font-extrabold uppercase  text-slate-500 dark:text-slate-400';
      $sectionTextClass = 'mt-1 text-sm text-slate-600 dark:text-slate-300';
      $cardClass = 'rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-700 dark:bg-slate-900';
      $labelClass = 'text-[11px] uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500';
      $valueClass = 'mt-2 text-sm font-medium text-slate-900 dark:text-slate-100 break-words';
      $jsonClass = 'mt-2 overflow-x-auto rounded-lg bg-slate-200 px-3 py-3 text-xs leading-5 text-slate-800 dark:bg-slate-800 dark:text-slate-100';
   @endphp

   <div class="mb-4 grid grid-cols-1 gap-3 md:grid-cols-2">
      <div class="{{ $cardClass }}">
         <p class="{{ $labelClass }}">Transaction DB ID</p>
         <p class="{{ $valueClass }}">{{ $transaction?->id ?? 'N/A' }}</p>
      </div>
      <div class="{{ $cardClass }}">
         <p class="{{ $labelClass }}">TRX UID</p>
         <p class="{{ $valueClass }}">{{ $transaction?->trx_uid ?? 'N/A' }}</p>
      </div>
   </div>

   <section class="{{ $sectionClass }}">
      <div class="{{ $sectionHeadClass }}">
         <div>
            <h4 class="{{ $sectionTitleClass }}">Vital Transaction Details</h4>
            <p class="{{ $sectionTextClass }}">Core payment status, amount, source and timestamps.</p>
         </div>
      </div>

      <div class="grid grid-cols-1 gap-3 px-4 py-4 md:grid-cols-6">
         <div class="{{ $cardClass }} md:col-span-2">
            <p class="{{ $labelClass }}">Payment Status</p>
            <div class="mt-2">{!! $transaction?->payment_status ? \App\Helpers\Ui\DatatableHelper::paymentEnums($transaction->payment_status?->value ?? $transaction->payment_status) : 'N/A' !!}</div>
         </div>
         <div class="{{ $cardClass }} md:col-span-2">
            <p class="{{ $labelClass }}">Transaction Phase</p>
            <div class="mt-2">{!! $transaction?->trx_phase ? \App\Helpers\Ui\DatatableHelper::trxEnums($transaction->trx_phase?->value ?? $transaction->trx_phase) : 'N/A' !!}</div>
         </div>
         <div class="{{ $cardClass }} md:col-span-2">
            <p class="{{ $labelClass }}">Record Status</p>
            <p class="mt-2 text-sm font-medium {{ $transaction?->status ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
               {{ $transaction?->status ? 'Active' : 'Inactive' }}
            </p>
         </div>

         <div class="{{ $cardClass }} md:col-span-2">
            <p class="{{ $labelClass }}">Currency</p>
            <p class="{{ $valueClass }}">
               {{ $transaction?->currency instanceof \App\Domains\Billings\Shared\Enums\CurrencyEnums ? $transaction->currency->code() : ($transaction?->currency ?? 'N/A') }}
            </p>
         </div>
         <div class="{{ $cardClass }} md:col-span-2">
            <p class="{{ $labelClass }}">TRX Amount</p>
            <p class="mt-2 text-sm font-semibold text-emerald-600 dark:text-emerald-400">
               {{ $formatMoney($transaction?->trx_amount, $transaction?->currency, true) }}
            </p>
         </div>
         <div class="{{ $cardClass }} md:col-span-2">
            <p class="{{ $labelClass }}">Payment Source</p>
            <p class="{{ $valueClass }}">{{ $sourceLabel }}</p>
         </div>

         <div class="{{ $cardClass }} md:col-span-4">
            <p class="{{ $labelClass }}">Payment Source Type</p>
            <p class="{{ $valueClass }}">{{ $sourceType }}</p>
         </div>
         <div class="{{ $cardClass }} md:col-span-2">
            <p class="{{ $labelClass }}">Payment Source ID</p>
            <p class="{{ $valueClass }}">{{ $transaction?->payment_source_id ?? 'N/A' }}</p>
         </div>

         <div class="{{ $cardClass }} md:col-span-3">
            <p class="{{ $labelClass }}">Created At</p>
            <p class="{{ $valueClass }}">{{ $formatDate($transaction, 'created_at') }}</p>
         </div>
         <div class="{{ $cardClass }} md:col-span-3">
            <p class="{{ $labelClass }}">Updated At</p>
            <p class="{{ $valueClass }}">{{ $formatDate($transaction, 'updated_at') }}</p>
         </div>
      </div>
   </section>

   @if($isProductOrder)
      <section class="{{ $sectionClass }}">
         <div class="{{ $sectionHeadClass }}">
            <div>
               <h4 class="{{ $sectionTitleClass }}">Order Details</h4>
               <p class="{{ $sectionTextClass }}">Brief e-store order summary from the morphed `ProductOrder` source.</p>
            </div>
         </div>

         @if($productOrder)
            <div class="grid grid-cols-1 gap-3 px-4 py-4 md:grid-cols-6">
               <div class="{{ $cardClass }} col-span-1">
                  <p class="{{ $labelClass }}">Order DB ID</p>
                  <p class="{{ $valueClass }}">{{ $productOrder->id ?? 'N/A' }}</p>
               </div>
               <div class="{{ $cardClass }} md:col-span-3">
                  <p class="{{ $labelClass }}">Order UID</p>
                  <p class="{{ $valueClass }}">{{ $productOrder->order_uid ?? 'N/A' }}</p>
               </div>
               <div class="{{ $cardClass }} md:col-span-2">
                  <p class="{{ $labelClass }}">Customer</p>
                  <p class="{{ $valueClass }}">{{ $productOrder?->orderRelatingBackTo_Customer?->name ?? 'N/A' }}</p>
               </div>

               <div class="{{ $cardClass }} md:col-span-2">
                  <p class="{{ $labelClass }}">Order Status</p>
                  <div class="mt-2">{!! $productOrder?->order_status ? \App\Helpers\Ui\DatatableHelper::phaseColor($productOrder->order_status?->value ?? $productOrder->order_status) : 'N/A' !!}</div>
               </div>
               <div class="{{ $cardClass }} md:col-span-2">
                  <p class="{{ $labelClass }}">Order Payment Status</p>
                  <div class="mt-2">{!! $productOrder?->payment_status ? \App\Helpers\Ui\DatatableHelper::paymentEnums($productOrder->payment_status?->value ?? $productOrder->payment_status) : 'N/A' !!}</div>
               </div>
               <div class="{{ $cardClass }} md:col-span-2">
                  <p class="{{ $labelClass }}">Delivery Status</p>
                  <p class="{{ $valueClass }}">{{ $productOrder?->delivery_status?->label() ?? $productOrder?->delivery_status?->value ?? 'N/A' }}</p>
               </div>

               <div class="{{ $cardClass }} md:col-span-2">
                  <p class="{{ $labelClass }}">Subtotal</p>
                  <p class="{{ $valueClass }}">{{ $formatMoney($productOrder?->subtotal_amount, $productOrder?->currency, false) }}</p>
               </div>
               <div class="{{ $cardClass }} md:col-span-2">
                  <p class="{{ $labelClass }}">Payable</p>
                  <p class="{{ $valueClass }}">{{ $formatMoney($productOrder?->payable_amount, $productOrder?->currency, false) }}</p>
               </div>
               <div class="{{ $cardClass }} md:col-span-2">
                  <p class="{{ $labelClass }}">Paid</p>
                  <p class="{{ $valueClass }}">{{ $formatMoney($productOrder?->paid_amount, $productOrder?->currency, false) }}</p>
               </div>

               <div class="{{ $cardClass }} md:col-span-2">
                  <p class="{{ $labelClass }}">Placed At</p>
                  <p class="{{ $valueClass }}">{{ $formatDate($productOrder, 'placed_at') }}</p>
               </div>
               <div class="{{ $cardClass }} md:col-span-2">
                  <p class="{{ $labelClass }}">Paid At</p>
                  <p class="{{ $valueClass }}">{{ $formatDate($productOrder, 'paid_at') }}</p>
               </div>
               <div class="{{ $cardClass }} md:col-span-2">
                  <p class="{{ $labelClass }}">Delete Requested At</p>
                  <p class="{{ $valueClass }}">{{ $formatDate($productOrder, 'delete_req_at') }}</p>
               </div>
            </div>

            <div class="px-4 pb-4">
               <div class="overflow-x-auto rounded-lg border border-slate-200 dark:border-slate-700">
                  <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-700">
                     <thead class="bg-slate-100 text-xs uppercase tracking-wide text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                        <tr>
                           <th class="px-3 py-3 text-left">Item</th>
                           <th class="px-3 py-3 text-left">Qty</th>
                           <th class="px-3 py-3 text-left">Rate</th>
                           <th class="px-3 py-3 text-left">Discount</th>
                           <th class="px-3 py-3 text-left">Final</th>
                           <th class="px-3 py-3 text-left">Status</th>
                        </tr>
                     </thead>
                     <tbody class="divide-y divide-slate-200 bg-white dark:divide-slate-800 dark:bg-slate-950">
                        @forelse($orderItems as $item)
                           @php
                              $sku = $item->itemRelatingBackTo_ProductSku;
                              $parentItem = $sku?->skuRelatingBackTo_ProductItem;
                           @endphp
                           <tr>
                              <td class="px-3 py-3 align-top">
                                 <p class="font-medium text-slate-900 dark:text-slate-100">{{ $sku?->name ?? data_get($item, 'product_details.name') ?? 'N/A' }}</p>
                                 <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Order Item: {{ $item->order_item_uid ?? 'N/A' }}</p>
                                 <p class="text-xs text-slate-500 dark:text-slate-400">SKU: {{ $sku?->product_sku_uid ?? 'N/A' }}</p>
                                 @if($parentItem)
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Product: {{ $parentItem->name ?? 'N/A' }}</p>
                                 @endif
                              </td>
                              <td class="px-3 py-3 align-top text-slate-700 dark:text-slate-200">{{ $item->quantity ?? 'N/A' }}</td>
                              <td class="px-3 py-3 align-top text-slate-700 dark:text-slate-200">{{ $formatMoney($item->current_rate, $productOrder?->currency, true) }}</td>
                              <td class="px-3 py-3 align-top text-slate-700 dark:text-slate-200">{{ $formatMoney($item->current_discount, $productOrder?->currency, true) }}</td>
                              <td class="px-3 py-3 align-top text-slate-700 dark:text-slate-200">{{ $formatMoney($item->current_final_price, $productOrder?->currency, true) }}</td>
                              <td class="px-3 py-3 align-top">
                                 <span class="{{ $item->status ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                    {{ $item->status ? 'Active' : 'Inactive' }}
                                 </span>
                              </td>
                           </tr>
                        @empty
                           <tr>
                              <td colspan="6" class="px-3 py-5 text-center text-slate-500 dark:text-slate-400">No order items found.</td>
                           </tr>
                        @endforelse
                     </tbody>
                  </table>
               </div>
            </div>
         @else
            <div class="px-4 py-4 text-sm text-rose-600 dark:text-rose-400">
               Payment source type points to `ProductOrder`, but no matching order row was found for this transaction.
            </div>
         @endif
      </section>
   @endif

   <section class="{{ $sectionClass }}">
      <div class="{{ $sectionHeadClass }}">
         <div>
            <h4 class="{{ $sectionTitleClass }}">User Details</h4>
            <p class="{{ $sectionTextClass }}">Current user on the left and stored snapshot values on the right.</p>
         </div>
      </div>

      <div class="grid grid-cols-1 gap-3 px-4 py-4 md:grid-cols-6">
         <div class="{{ $cardClass }} md:col-span-3">
            <div class="mb-3 flex items-start justify-between gap-3">
               <div>
                  <p class="{{ $labelClass }}">User</p>
                  <p class="{{ $valueClass }}">{{ $trxUser?->name ?? 'N/A' }}</p>
               </div>
               @if($trxUser)
                  <a href="{{ route('backend_single_user', $trxUser->id) }}" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200">
                     {!! \App\Helpers\IconPack::eye(['class' => 'iconPackItem w-4 h-4 text-slate-500 dark:text-slate-300']) !!}
                     <span>Open profile</span>
                  </a>
               @endif
            </div>

            <div class="flex items-start gap-4 rounded-lg border border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-800">
               <img
                  src="{{ $trxUser?->avatar ? \Illuminate\Support\Facades\Storage::url($trxUser->avatar) : asset('site_assets/dummies/dummy_man.webp') }}"
                  alt="user-avatar"
                  class="h-20 w-20 rounded-xl border border-slate-200 object-cover dark:border-slate-700">

               <div class="min-w-0 flex-1">
                  <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                     <div>
                        <p class="{{ $labelClass }}">User ID</p>
                        <p class="{{ $valueClass }}">{{ $transaction?->user_id ?? 'N/A' }}</p>
                     </div>
                     <div>
                        <p class="{{ $labelClass }}">UID</p>
                        <p class="{{ $valueClass }}">{{ $trxUser?->user_uid ?? 'N/A' }}</p>
                     </div>
                     <div>
                        <p class="{{ $labelClass }}">Username</p>
                        <p class="{{ $valueClass }}">{{ $trxUser?->username ?? 'N/A' }}</p>
                     </div>
                     <div>
                        <p class="{{ $labelClass }}">Name</p>
                        <p class="{{ $valueClass }}">{{ $trxUser?->name ?? 'N/A' }}</p>
                     </div>
                     <div>
                        <p class="{{ $labelClass }}">Email</p>
                        <p class="{{ $valueClass }}">{{ $trxUser?->email ?? 'N/A' }}</p>
                     </div>
                     <div>
                        <p class="{{ $labelClass }}">Phone</p>
                        <p class="{{ $valueClass }}">{{ $trxUser?->phone ?? 'N/A' }}</p>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <div class="{{ $cardClass }} md:col-span-3">
            <div class="mb-3">
               <p class="{{ $labelClass }}">Snapshot Details</p>
               <p class="{{ $sectionTextClass }}">Immutable user-details when the transaction was made.</p>
            </div>

            <div class="rounded-lg border border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-800">
               <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                  <div>
                     <p class="{{ $labelClass }}">Snapshot User ID</p>
                     <p class="{{ $valueClass }}">{{ $transaction?->ss_user_id ?? 'N/A' }}</p>
                  </div>
                  <div>
                     <p class="{{ $labelClass }}">Snapshot Email</p>
                     <p class="{{ $valueClass }}">{{ $transaction?->ss_user_email ?? 'N/A' }}</p>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>

   <section class="{{ $sectionClass }}">
      <div class="{{ $sectionHeadClass }}">
         <div>
            <h4 class="{{ $sectionTitleClass }}">Other Details</h4>
            <p class="{{ $sectionTextClass }}">Fee, exchange, source record and note related fields.</p>
         </div>
      </div>

      <div class="grid grid-cols-1 gap-3 px-4 py-4 md:grid-cols-6">
         <div class="{{ $cardClass }} md:col-span-2">
            <p class="{{ $labelClass }}">Stripe Fee</p>
            <p class="{{ $valueClass }}">{{ $formatMoney($transaction?->stripe_fee, $transaction?->currency, true) }}</p>
         </div>
         <div class="{{ $cardClass }} md:col-span-2">
            <p class="{{ $labelClass }}">Takeaway Amount</p>
            <p class="{{ $valueClass }}">{{ $formatMoney($transaction?->takeaway_amount, $transaction?->currency, true) }}</p>
         </div>
         <div class="{{ $cardClass }} md:col-span-2">
            <p class="{{ $labelClass }}">Exchange Rate</p>
            <p class="{{ $valueClass }}">{{ $transaction?->exchange_rate ?? 'N/A' }}</p>
         </div>
         <div class="{{ $cardClass }} md:col-span-6">
            <p class="{{ $labelClass }}">System Note</p>
            <p class="{{ $valueClass }}">{!! $transaction?->system_note ?? 'N/A' !!}</p>
         </div>
      </div>
   </section>

   <section class="{{ $sectionClass }}">
      <div class="{{ $sectionHeadClass }}">
         <div>
            <h4 class="{{ $sectionTitleClass }}">Stripe Extra Details</h4>
            <p class="{{ $sectionTextClass }}">Webhook payload fragments and Stripe-side identifiers stored against this transaction.</p>
         </div>
      </div>

      <div class="grid grid-cols-1 gap-3 px-4 py-4 md:grid-cols-6">
         <div class="{{ $cardClass }} md:col-span-6">
            <p class="{{ $labelClass }}">Event IDs</p>
            <pre class="{{ $jsonClass }}">{{ json_encode($transaction?->event_ids ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
         </div>

         <div class="{{ $cardClass }} md:col-span-3">
            <p class="{{ $labelClass }}">Checkout Session ID</p>
            <p class="{{ $valueClass }}">{{ $transaction?->stripe_checkout_session_id ?? 'N/A' }}</p>
         </div>
         <div class="{{ $cardClass }} md:col-span-3">
            <p class="{{ $labelClass }}">Payment Intent</p>
            <p class="{{ $valueClass }}">{{ $transaction?->payment_intent ?? 'N/A' }}</p>
         </div>

         <div class="{{ $cardClass }} md:col-span-6">
            <p class="{{ $labelClass }}">Passed Metadata (to Stripe)</p>
            <pre class="{{ $jsonClass }}">{{ json_encode($transaction?->passed_metadata ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
         </div>

         <div class="{{ $cardClass }} md:col-span-3">
            <p class="{{ $labelClass }}">Card Details (from Stripe)</p>
            <pre class="{{ $jsonClass }}">{{ json_encode($transaction?->card_details ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
         </div>
         <div class="{{ $cardClass }} md:col-span-3">
            <p class="{{ $labelClass }}">Customer Details (from Stripe)</p>
            <pre class="{{ $jsonClass }}">{{ json_encode($transaction?->customer_details ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
         </div>
      </div>
   </section>
</div>
