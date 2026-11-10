<x-app-layout>
   @php
      $breadCrumbs = [
         ['label' => 'Customers', 'url' => 'backend_billings_customers_index', 'icon' => 'userTrippleLine'],
         ['label' => 'Buyers', 'url' => 'backend_billings_customers_buyers_index', 'icon' => 'userTrippleLine'],
         ['label' => $buyer?->name ?? 'Buyer', 'icon' => 'dollar'],
      ];

      $userAvatar = $buyer?->avatar ? \Illuminate\Support\Facades\Storage::url($buyer->avatar) : asset('site_assets/dummies/dummy_man.webp');
      $zones = \App\Helpers\Ui\ConvertDynamicTimezone::handle();
      $totalOrders = $totalOrderCount ?? ($orders?->unique('order_uid')->count() ?? 0);
      $paidOrdersCount = $paidOrderCount ?? ($paidOrders?->unique('order_uid')->count() ?? 0);
      $totalSpent = $orders?->sum('paid_amount') ?? 0;
      $formatDate = function ($date) use ($zones) {
         return $date
            ? \Carbon\Carbon::parse($date, 'UTC')->timezone($zones['zone'])->format('d M Y, H:i') . ' (UTC' . $zones['offset'] . ')'
            : 'N/A';
      };
   @endphp

   <x-indicators.breadcrumb :crumbs="$breadCrumbs" />
   <x-indicators.page_header_widget pageName="Buyer: {{ $buyer?->name ?? 'N/A' }}" />
   <x-indicators.alert-component message="Buyer Details" />

   <div class="px-8 py-4 grid grid-cols-1 gap-4">
      <section class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 shadow-sm shadow-slate-200/60 dark:border-slate-800 dark:bg-slate-900/70 dark:shadow-none">
         <div class="border-b border-slate-200 bg-slate-100/80 px-4 py-3 dark:border-slate-800 dark:bg-slate-900/90">
            <div class="flex_between_center">
               <div>
                  <h4 class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Buyer Profile</h4>
                  <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">User who made paid e-store purchases.</p>
               </div>
               <a href="{{ $buyer ? route('backend_single_user', $buyer->id) : '#' }}" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 transition hover:-translate-y-0.5 hover:border-slate-300 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:border-slate-600 dark:hover:bg-slate-700">
                  {!! \App\Helpers\IconPack::eye(['class' => 'iconPackItem w-4 h-4 text-slate-500 dark:text-slate-300']) !!}
                  <span>Open profile</span>
               </a>
            </div>
         </div>

         <div class="grid gap-5 px-4 py-4 md:grid-cols-[auto_minmax(0,1fr)] md:items-start">
            <a href="{{ $buyer ? route('backend_single_user', $buyer->id) : '#' }}" class="group relative block w-fit shrink-0">
               <div class="absolute -inset-1 rounded-[1.65rem] bg-linear-to-br from-slate-400 via-slate-200 to-cyan-300 opacity-70 blur-md transition duration-300 group-hover:opacity-100 dark:from-slate-700 dark:via-slate-600 dark:to-cyan-700"></div>
               <div class="relative overflow-hidden rounded-[1.65rem] border border-white/80 bg-white p-1 shadow-lg shadow-slate-200/60 transition duration-300 group-hover:-translate-y-0.5 dark:border-slate-700 dark:bg-slate-800 dark:shadow-none">
                  <img src="{{ $userAvatar }}" alt="avatar" class="h-44 w-44 rounded-[1.25rem] object-cover ring-1 ring-slate-200 dark:ring-slate-700">
                  <div class="absolute inset-x-3 bottom-3 rounded-2xl bg-slate-950/65 px-3 py-2 text-center text-xs font-medium tracking-wide text-white backdrop-blur-sm transition group-hover:bg-slate-950/75">
                     Tap to open user profile
                  </div>
               </div>
            </a>

            <div class="space-y-4 text-slate-700 dark:text-slate-200">
               <div class="flex flex-wrap items-center gap-2">
                  <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700 ring-1 ring-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-700">DB ID: {{ $buyer?->id ?? 'N/A' }}</span>
                  <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700 ring-1 ring-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-700">Role: {{ $buyer?->user_role ?? 'N/A' }}</span>
                  <span class="rounded-full bg-cyan-100 px-3 py-1 text-xs font-medium text-cyan-700 ring-1 ring-cyan-200 dark:bg-cyan-500/15 dark:text-cyan-300 dark:ring-cyan-400/30">Total Orders: {{ $totalOrders }}</span>
                  <span class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-medium text-indigo-700 ring-1 ring-indigo-200 dark:bg-indigo-500/15 dark:text-indigo-300 dark:ring-indigo-400/30">Paid Orders: {{ $paidOrdersCount }}</span>
                  <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-700 ring-1 ring-emerald-200 dark:bg-emerald-500/15 dark:text-emerald-300 dark:ring-emerald-400/30">Spent: ${{ number_format((float)$totalSpent, 2) }}</span>
               </div>

               <div class="grid gap-3 md:grid-cols-2">
                  <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 dark:border-slate-700 dark:bg-slate-800">
                     <p class="text-[11px] uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">Name</p>
                     <p class="mt-1 text-base font-semibold text-slate-900 dark:text-slate-100">{{ $buyer?->name ?? 'N/A' }}</p>
                  </div>
                  <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 dark:border-slate-700 dark:bg-slate-800">
                     <p class="text-[11px] uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">Username</p>
                     <p class="mt-1 text-base font-medium text-slate-700 dark:text-slate-200">{{ $buyer?->username ?? 'N/A' }}</p>
                  </div>
                  <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 dark:border-slate-700 dark:bg-slate-800">
                     <p class="text-[11px] uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">Email</p>
                     <p class="mt-1 text-base font-medium text-slate-700 dark:text-slate-200">{{ $buyer?->email ?? 'N/A' }}</p>
                  </div>
                  <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 dark:border-slate-700 dark:bg-slate-800">
                     <p class="text-[11px] uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">Phone</p>
                     <p class="mt-1 text-base font-medium text-slate-700 dark:text-slate-200">{{ $buyer?->phone ?? 'N/A' }}</p>
                  </div>
               </div>
            </div>
         </div>
      </section>

      <section class="rounded-xl border border-slate-200 bg-slate-50 p-4 shadow-sm shadow-slate-200/60 dark:border-slate-800 dark:bg-slate-900/70 dark:shadow-none">
         <h4 class="mb-3 text-slate-900 dark:text-slate-100">All Orders</h4>

         <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-700">
            <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-700">
               <thead class="bg-slate-100 text-xs uppercase tracking-wide text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                  <tr>
                     <th class="px-4 py-3 text-left">Order</th>
                     <th class="px-4 py-3 text-left">Status</th>
                     <th class="px-4 py-3 text-left">Payment</th>
                     <th class="px-4 py-3 text-left">Amount</th>
                     <th class="px-4 py-3 text-left">Paid At</th>
                     <th class="px-4 py-3 text-left">Created At</th>
                  </tr>
               </thead>
               <tbody class="divide-y divide-slate-200 bg-white dark:divide-slate-800 dark:bg-slate-900">
                  @forelse($orders as $order)
                     <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/70">
                        <td class="px-4 py-3 font-medium text-slate-900 dark:text-slate-100">{{ $order->order_uid ?? 'N/A' }}</td>
                        <td class="px-4 py-3">{!! \App\Helpers\Ui\DatatableHelper::phaseColor($order->order_status?->value ?? $order->order_status) !!}</td>
                        <td class="px-4 py-3">{!! \App\Helpers\Ui\DatatableHelper::paymentEnums($order->payment_status) !!}</td>
                        <td class="px-4 py-3 text-slate-700 dark:text-slate-200">${{ number_format((float)($order->paid_amount ?? 0), 2) }}</td>
                        <td class="px-4 py-3 text-slate-700 dark:text-slate-200">
                           {{ $formatDate($order->paid_at) }}
                        </td>
                        <td class="px-4 py-3 text-slate-700 dark:text-slate-200">
                           {{ $formatDate($order->created_at) }}
                        </td>
                     </tr>
                  @empty
                     <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-slate-500 dark:text-slate-400">No paid orders found.</td>
                     </tr>
                  @endforelse
               </tbody>
            </table>
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
