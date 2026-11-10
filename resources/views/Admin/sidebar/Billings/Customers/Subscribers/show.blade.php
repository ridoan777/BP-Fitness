<x-app-layout>
   @php
      $breadCrumbs = [
         ['label' => 'Customers', 'url' => 'backend_billings_customers_index', 'icon' => 'userTrippleLine'],
         ['label' => 'Subscribers', 'url' => 'backend_billings_customers_subscribers_index', 'icon' => 'userTripple'],
         ['label' => 'Subscriber', 'icon' => 'userSingle'],
      ];

      $user = $subscription?->subscribedAppUserRelatingBackTo_user;
      $tier = $subscription?->subscriberAppRelatingBackTo_Tier;
      $entitlements = $subscription?->entitlement_id ?? [];
      $paymentStatusBadge = \App\Helpers\Ui\DatatableHelper::paymentEnums($subscription?->payment_status);
      $subscriptionStatusBadge = \App\Helpers\Ui\DatatableHelper::subEnums($subscription?->sub_status);
      $userAvatar = $user?->avatar
         ? \Illuminate\Support\Facades\Storage::url($user->avatar)
         : asset('site_assets/dummies/dummy_man.webp');
      $zones = \App\Helpers\Ui\ConvertDynamicTimezone::handle();
   @endphp

   <x-indicators.breadcrumb :crumbs="$breadCrumbs" />

   <x-indicators.page_header_widget pageName="Subscriber: {{ $user?->name ?? 'N/A' }}" />

   <x-indicators.alert-component message="Modification" />

   <div class="px-8 py-4 grid grid-cols-1 gap-4">

      <section
         class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 shadow-sm shadow-slate-200/60 dark:border-slate-800 dark:bg-slate-900/70 dark:shadow-none">
         <div class="border-b border-slate-200 bg-slate-100/80 px-4 py-3 dark:border-slate-800 dark:bg-slate-900/90">
            <div class="flex_between_center">
               <div>
                  <h4 class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">
                     Personal Details</h4>
                  <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">Quick identity overview for this
                     subscriber.</p>
               </div>
               <a href="{{ $user ? route('backend_single_user', $user->id) : '#' }}"
                  class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 transition hover:-translate-y-0.5 hover:border-slate-300 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:border-slate-600 dark:hover:bg-slate-700">
                  {!! \App\Helpers\IconPack::eye(['class' => 'iconPackItem w-4 h-4 text-slate-500 dark:text-slate-300']) !!}
                  <span>Open profile</span>
               </a>
            </div>
         </div>
         <div class="grid gap-5 px-4 py-4 md:grid-cols-[auto_minmax(0,1fr)] md:items-start">
            <a href="{{ $user ? route('backend_single_user', $user->id) : '#' }}"
               class="group relative block w-fit shrink-0">
               <div
                  class="absolute -inset-1 rounded-[1.65rem] bg-linear-to-br from-slate-400 via-slate-200 to-cyan-300 opacity-70 blur-md transition duration-300 group-hover:opacity-100 dark:from-slate-700 dark:via-slate-600 dark:to-cyan-700">
               </div>
               <div
                  class="relative overflow-hidden rounded-[1.65rem] border border-white/80 bg-white p-1 shadow-lg shadow-slate-200/60 transition duration-300 group-hover:-translate-y-0.5 dark:border-slate-700 dark:bg-slate-800 dark:shadow-none">
                  <img src="{{ $userAvatar }}" alt="avatar"
                     class="h-44 w-44 rounded-[1.25rem] object-cover ring-1 ring-slate-200 dark:ring-slate-700">
                  <div
                     class="absolute inset-x-3 bottom-3 rounded-2xl bg-slate-950/65 px-3 py-2 text-center text-xs font-medium tracking-wide text-white backdrop-blur-sm transition group-hover:bg-slate-950/75">
                     Tap to open user profile
                  </div>
               </div>
            </a>

            <div class="space-y-4 text-slate-700 dark:text-slate-200">
               <div class="flex flex-wrap items-center gap-2">
                  <span
                     class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700 ring-1 ring-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-700">
                     DB ID: {{ $user?->id ?? 'N/A' }}
                  </span>
                  <span
                     class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700 ring-1 ring-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-700">
                     Role: {{ $user?->user_role ?? 'N/A' }}
                  </span>
                  <span
                     class="rounded-full bg-cyan-100 px-3 py-1 text-xs font-medium text-cyan-700 ring-1 ring-cyan-200 dark:bg-cyan-500/15 dark:text-cyan-300 dark:ring-cyan-400/30">
                     {{ $subscription?->provider ? ucfirst($subscription->provider) : 'N/A' }}
                  </span>
               </div>

               <div class="grid gap-3 md:grid-cols-2">
                  <div
                     class="rounded-2xl border border-slate-200 bg-white px-4 py-3 dark:border-slate-700 dark:bg-slate-800">
                     <p class="text-[11px] uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">Name</p>
                     <p class="mt-1 text-base font-semibold text-slate-900 dark:text-slate-100">
                        {{ $user?->name ?? 'N/A' }}</p>
                  </div>
                  <div
                     class="rounded-2xl border border-slate-200 bg-white px-4 py-3 dark:border-slate-700 dark:bg-slate-800">
                     <p class="text-[11px] uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">Email</p>
                     <p class="mt-1 text-base font-medium text-slate-700 dark:text-slate-200">
                        {{ $user?->email ?? 'N/A' }}</p>
                  </div>
                  <div
                     class="rounded-2xl border border-slate-200 bg-white px-4 py-3 dark:border-slate-700 dark:bg-slate-800">
                     <p class="text-[11px] uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">Phone</p>
                     <p class="mt-1 text-base font-medium text-slate-700 dark:text-slate-200">
                        {{ $user?->phone ?? 'N/A' }}</p>
                  </div>
                  <div
                     class="rounded-2xl border border-slate-200 bg-white px-4 py-3 dark:border-slate-700 dark:bg-slate-800">
                     <p class="text-[11px] uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">Subscriber
                        Status</p>
                     <div class="mt-1">{!! \App\Helpers\Ui\DatatableHelper::subEnums($subscription?->sub_status) !!}
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>

      <section
         class="rounded-xl border border-slate-200 bg-slate-50 p-4 shadow-sm shadow-slate-200/60 dark:border-slate-800 dark:bg-slate-900/70 dark:shadow-none">
         <h4 class="mb-2 text-slate-900 dark:text-slate-100">Subscription Summary</h4>
         <div class="wrapperGroup">
            <p class="title">Subscription DB ID</p>
            <b class="dark:text-gray-100">:</b>
            <p class="value">{{ $subscription?->id ?? 'N/A' }}</p>
         </div>
         <div class="wrapperGroup">
            <p class="title">Subscription ID</p>
            <b class="dark:text-gray-100">:</b>
            <p class="value">{{ $subscription?->rc_subscription_id ?? 'N/A' }}</p>
         </div>
         <div class="wrapperGroup">
            <p class="title">Provider</p>
            <b class="dark:text-gray-100">:</b>
            <p class="value">{{ ucfirst($subscription?->provider ?? 'N/A') }}</p>
         </div>
         <div class="wrapperGroup">
            <p class="title">Store</p>
            <b class="dark:text-gray-100">:</b>
            <p class="value">{{ $subscription?->store ?? 'N/A' }}</p>
         </div>
         <div class="wrapperGroup">
            <p class="title">Payment Status</p>
            <b class="dark:text-gray-100">:</b>
            <p class="value">
               {!! $paymentStatusBadge !!}</p>
         </div>
         <div class="wrapperGroup">
            <p class="title">Subscription Status</p>
            <b class="dark:text-gray-100">:</b>
            <p class="value">{!! $subscriptionStatusBadge !!}</p>
         </div>
         <div class="wrapperGroup">
            <p class="title">Amount</p>
            <b class="dark:text-gray-100">:</b>
            <p class="value">${{ number_format((float)($subscription?->amount ?? 0), 2) }}</p>
         </div>
         <div class="wrapperGroup">
            <p class="title">Duration</p>
            <b class="dark:text-gray-100">:</b>
            <p class="value">{{ $subscription?->duration ?? 'N/A' }} day(s)</p>
         </div>
         <div class="wrapperGroup">
            <p class="title">Iteration</p>
            <b class="dark:text-gray-100">:</b>
            <p class="value">{{ $subscription?->iteration ?? 'N/A' }}</p>
         </div>
         <div class="wrapperGroup">
            <p class="title">Status</p>
            <b class="dark:text-gray-100">:</b>
            <p class="value">{{ $subscription?->status ? 'Active' : 'Inactive' }}</p>
         </div>
      </section>

      <section
         class="rounded-xl border border-slate-200 bg-slate-50 p-4 shadow-sm shadow-slate-200/60 dark:border-slate-800 dark:bg-slate-900/70 dark:shadow-none">
         <div class="flex_between_center">
            <h4 class="text-slate-900 dark:text-slate-100">Tier/Package Summary</h4>
            <a href="{{ $tier ? route('backend_subscription_tier_show', $tier->id) : '#' }}">
               {!! \App\Helpers\IconPack::eye(['class' => 'iconPackItem w-5 h-5 text-slate-500 dark:text-slate-300']) !!}
            </a>
         </div>
         <div class="wrapperGroup">
            <p class="title">Tier Database ID</p>
            <b class="dark:text-gray-100">:</b>
            <p class="value">{{ $tier?->id ?? 'N/A' }}</p>
         </div>
         <div class="wrapperGroup">
            <p class="title">Platform</p>
            <b class="dark:text-gray-100">:</b>
            <p class="value">{{ ucfirst($tier?->platform ?? 'N/A') }}</p>
         </div>
         <div class="wrapperGroup">
            <p class="title">Tier Name</p>
            <b class="dark:text-gray-100">:</b>
            <p class="value">{{ $tier?->name ?? 'N/A' }}</p>
         </div>
         <div class="wrapperGroup">
            <p class="title">Duration</p>
            <b class="dark:text-gray-100">:</b>
            <p class="value">{{ $tier?->duration ?? 'N/A' }} day(s)</p>
         </div>
         <div class="wrapperGroup">
            <p class="title">Starting Price</p>
            <b class="dark:text-gray-100">:</b>
            <p class="value">${{ number_format((float)($tier?->starting_price ?? 0), 2) }}</p>
         </div>
         <div class="wrapperGroup">
            <p class="title">Final Price</p>
            <b class="dark:text-gray-100">:</b>
            <p class="value">${{ number_format((float)($tier?->final_price ?? 0), 2) }}</p>
         </div>
         <div class="wrapperGroup">
            <p class="title">Benefits</p>
            <b class="dark:text-gray-100">:</b>
            <span class="value">{!! $tier?->benefits ?? 'N/A' !!}</span>
         </div>
         <div class="wrapperGroup">
            <p class="title">Stripe Product ID</p>
            <b class="dark:text-gray-100">:</b>
            <p class="value">{{ $tier?->stripe_product_id ?? 'N/A' }}</p>
         </div>
         <div class="wrapperGroup">
            <p class="title">Stripe Price ID</p>
            <b class="dark:text-gray-100">:</b>
            <p class="value">{{ $tier?->stripe_price_id ?? 'N/A' }}</p>
         </div>
      </section>

      <section
         class="rounded-xl border border-slate-200 bg-slate-50 p-4 shadow-sm shadow-slate-200/60 dark:border-slate-800 dark:bg-slate-900/70 dark:shadow-none">
         <h4 class="mb-2 text-slate-900 dark:text-slate-100">App Subscription Details</h4>
         <div class="wrapperGroup">
            <p class="title">Entitlement</p>
            <b class="dark:text-gray-100">:</b>
            <p class="value">
               @if (is_array($entitlements))
                  {{ implode(', ', $entitlements) ?: 'N/A' }}
               @else
                  {{ $entitlements ?? 'N/A' }}
               @endif
            </p>
         </div>
         <div class="wrapperGroup">
            <p class="title">RevenueCat Product ID</p>
            <b class="dark:text-gray-100">:</b>
            <p class="value">{{ $subscription?->rc_product_id ?? 'N/A' }}</p>
         </div>
         <div class="wrapperGroup">
            <p class="title">Subscription ID</p>
            <b class="dark:text-gray-100">:</b>
            <p class="value">{{ $subscription?->rc_subscription_id ?? 'N/A' }}</p>
         </div>
         <div class="wrapperGroup">
            <p class="title">RevenueCat Transaction ID</p>
            <b class="dark:text-gray-100">:</b>
            <p class="value">{{ $subscription?->rc_transaction_id ?? 'N/A' }}</p>
         </div>
         <div class="wrapperGroup">
            <p class="title">RevenueCat Event ID</p>
            <b class="dark:text-gray-100">:</b>
            <p class="value">{{ $subscription?->rc_event_id ?? 'N/A' }}</p>
         </div>
         <div class="wrapperGroup">
            <p class="title">RevenueCat User ID</p>
            <b class="dark:text-gray-100">:</b>
            <p class="value">{{ $subscription?->rc_user_id ?? 'N/A' }}</p>
         </div>
         <div class="wrapperGroup">
            <p class="title">Original RC User ID</p>
            <b class="dark:text-gray-100">:</b>
            <p class="value">{{ $subscription?->rc_ori_user_id ?? 'N/A' }}</p>
         </div>
         <div class="wrapperGroup">
            <p class="title">Country</p>
            <b class="dark:text-gray-100">:</b>
            <p class="value">{{ $subscription?->country ?? 'N/A' }}</p>
         </div>
         <div class="wrapperGroup">
            <p class="title">Currency</p>
            <b class="dark:text-gray-100">:</b>
            <p class="value">{{ $subscription?->currency ?? 'N/A' }}</p>
         </div>
         <div class="wrapperGroup">
            <p class="title">Purchased At</p>
            <b class="dark:text-gray-100">:</b>
            <p class="value">
               {{ $subscription?->purchased_at ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $subscription->getRawOriginal('purchased_at'), 'UTC')->timezone($zones['zone'])->format('d M Y, H:i') . ' (UTC' . $zones['offset'] . ')' : 'N/A' }}
            </p>
         </div>
         <div class="wrapperGroup">
            <p class="title">Expires At</p>
            <b class="dark:text-gray-100">:</b>
            <p class="value">
               {{ $subscription?->expire_at ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $subscription->getRawOriginal('expire_at'), 'UTC')->timezone($zones['zone'])->format('d M Y, H:i') . ' (UTC' . $zones['offset'] . ')' : 'N/A' }}
            </p>
         </div>
         <div class="wrapperGroup">
            <p class="title">Renewal At</p>
            <b class="dark:text-gray-100">:</b>
            <p class="value">
               {{ $subscription?->renewal_at ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $subscription->getRawOriginal('renewal_at'), 'UTC')->timezone($zones['zone'])->format('d M Y, H:i') . ' (UTC' . $zones['offset'] . ')' : 'N/A' }}
            </p>
         </div>
         <div class="wrapperGroup">
            <p class="title">Cancelled At</p>
            <b class="dark:text-gray-100">:</b>
            <p class="value">
               {{ $subscription?->cancelled_at ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $subscription->getRawOriginal('cancelled_at'), 'UTC')->timezone($zones['zone'])->format('d M Y, H:i') . ' (UTC' . $zones['offset'] . ')' : 'N/A' }}
            </p>
         </div>
      </section>

      <section
         class="rounded-xl border border-slate-200 bg-slate-50 p-4 shadow-sm shadow-slate-200/60 dark:border-slate-800 dark:bg-slate-900/70 dark:shadow-none">
         <h4 class="mb-2 text-slate-900 dark:text-slate-100">Notes</h4>
         <div class="wrapperGroup items-start">
            <p class="title">System Note</p>
            <b class="dark:text-gray-100">:</b>
            <p class="value">{!! $subscription?->system_note ?? 'N/A' !!}</p>
         </div>
      </section>

      <section
         class="rounded-xl border border-slate-200 bg-slate-50 p-4 shadow-sm shadow-slate-200/60 dark:border-slate-800 dark:bg-slate-900/70 dark:shadow-none grid grid-cols-2 gap-4">
         <h4 class="mb-2 text-slate-900 dark:text-slate-100 col-span-2">Audit</h4>
         <div class="wrapperGroup">
            <p class="title">Created At</p>
            <b class="dark:text-gray-100">:</b>
            <p class="value">
               {{ $subscription?->created_at ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $subscription->getRawOriginal('created_at'), 'UTC')->timezone($zones['zone'])->format('d M Y, H:i') . ' (UTC' . $zones['offset'] . ')' : 'N/A' }}
            </p>
         </div>
         <div class="wrapperGroup">
            <p class="title">Updated At</p>
            <b class="dark:text-gray-100">:</b>
            <p class="value">
               {{ $subscription?->updated_at ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $subscription->getRawOriginal('updated_at'), 'UTC')->timezone($zones['zone'])->format('d M Y, H:i') . ' (UTC' . $zones['offset'] . ')' : 'N/A' }}
            </p>
         </div>
      </section>

   </div>

</x-app-layout>

<style scoped>
   .title {
      min-width: 260px !important;
      color: #475569;
      font-weight: 500;
      line-height: 1.35rem;
   }

   .dark .title {
      color: #cbd5e1;
   }

   .value {
      width: 100%;
      margin: 2px 0;
      padding: 0.55rem 0.75rem;
      border-radius: 0.75rem;
      background-color: #e2e8f0;
      color: #0f172a;
      border: 1px solid #cbd5e1;
      overflow-wrap: anywhere;
      line-height: 1.4rem;
   }

   .dark .value {
      background-color: #1e293b;
      color: #e2e8f0;
      border-color: #334155;
   }

   .wrapperGroup {
      display: grid;
      grid-template-columns: 260px 12px minmax(0, 1fr);
      align-items: center;
      gap: 0.5rem 0.75rem;
      margin-top: 0.5rem;
   }
</style>