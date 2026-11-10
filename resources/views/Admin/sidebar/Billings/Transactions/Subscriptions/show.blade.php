<div class="p-4 text-slate-700 dark:text-slate-200">
	@php
		$zones = \App\Helpers\Ui\ConvertDynamicTimezone::handle();
		$trxUser = $transaction?->trxRelatingBackTo_User;
		$trxSubscription = $transaction?->trxRelatingBackTo_Subscription;
	@endphp

	<div class="mb-5 grid gap-4 md:grid-cols-2">
		<div
			class="rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm shadow-slate-200/50 dark:border-slate-800 dark:bg-slate-950 dark:shadow-none">
			<label class="inputLabelMedium text-slate-500 dark:text-slate-400">Transaction DB ID</label>
			<p
				class="mt-2 rounded-xl border border-slate-200 bg-slate-100 px-3 py-2 font-medium text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
				{{ $transaction?->id ?? 'N/A' }}
			</p>
		</div>
		<div
			class="rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm shadow-slate-200/50 dark:border-slate-800 dark:bg-slate-950 dark:shadow-none">
			<label class="inputLabelMedium text-slate-500 dark:text-slate-400">Transaction ID</label>
			<p
				class="mt-2 rounded-xl border border-slate-200 bg-slate-100 px-3 py-2 text-sm font-medium text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
				{{ $transaction?->transaction_id ?? 'N/A' }}
			</p>
		</div>
	</div>

	<section
		class="mb-4 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm shadow-slate-200/50 dark:border-slate-800 dark:bg-slate-950 dark:shadow-none">

		<div class="border-b border-slate-200 bg-slate-50/80 px-4 py-3 dark:border-slate-800 dark:bg-slate-900/80">
			<div class="flex_between_center">
				<div>
					<h4 class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">
						Transaction Summary</h4>
					<p class="mt-1 text-sm text-slate-600 dark:text-slate-300">Core event and settlement details.</p>
				</div>
				<span
					class="rounded-full bg-slate-900 px-3 py-1 text-xs font-medium text-white ring-1 ring-slate-700/40 dark:bg-slate-800 dark:text-slate-100">
					{{ $transaction?->trx_type ?? 'N/A' }}
				</span>
			</div>
		</div>

		<div class="grid gap-3 px-4 py-4 md:grid-cols-6">
			<div
				class="md:col-span-2 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-900">
				<p class="text-[11px] uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">Store</p>
				<p class="mt-1 text-sm font-medium text-slate-900 dark:text-slate-100">{{ $transaction?->store ?? 'N/A' }}
				</p>
			</div>
			<div
				class="md:col-span-2 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-900">
				<p class="text-[11px] uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">Phase</p>
				<div class="mt-1">{!! \App\Helpers\Ui\DatatableHelper::trxEnums($transaction?->phase) !!}</div>
			</div>
			<div
				class="md:col-span-2 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-900">
				<p class="text-[11px] uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">Status</p>
				<p
					class="mt-1 text-sm font-medium {{ $transaction?->status ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
					{{ $transaction?->status ? 'Active' : 'Inactive' }}
				</p>
			</div>

			<div
				class="col-span-2 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-900">
				<p class="text-[11px] uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">Currency</p>
				<p class="mt-1 text-sm font-medium text-slate-900 dark:text-slate-100">
					{{ $transaction?->currency ?? 'N/A' }}</p>
			</div>
			<div
				class="col-span-4 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-900">
				<p class="text-[11px] uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">Amount</p>
				<p class="mt-1 text-sm font-semibold text-emerald-600 dark:text-emerald-400">
					{{ $transaction?->currency ? $transaction->currency . ' ' : '' }}{{ number_format((float)($transaction?->amount ?? 0), 2) }}
				</p>
			</div>

			<div
				class="col-span-6 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-900">
				<p class="text-[11px] uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">Event ID</p>
				<p class="mt-1 break-all text-sm font-medium text-slate-900 dark:text-slate-100">
					{{ $transaction?->event_id ?? 'N/A' }}</p>
			</div>

			<div
				class="col-span-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-900">
				<p class="text-[11px] uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">Created At</p>
				<p class="mt-1 text-sm font-medium text-slate-900 dark:text-slate-100">
					{{ $transaction?->created_at ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $transaction->getRawOriginal('created_at'), 'UTC')->timezone($zones['zone'])->format('d M Y, H:i') . ' (UTC' . $zones['offset'] . ')' : 'N/A' }}
				</p>
			</div>
			<div
				class="col-span-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-900">
				<p class="text-[11px] uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">Updated At</p>
				<p class="mt-1 text-sm font-medium text-slate-900 dark:text-slate-100">
					{{ $transaction?->updated_at ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $transaction->getRawOriginal('updated_at'), 'UTC')->timezone($zones['zone'])->format('d M Y, H:i') . ' (UTC' . $zones['offset'] . ')' : 'N/A' }}
				</p>
			</div>
		</div>
	</section>

	<section
		class="mb-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm shadow-slate-200/50 dark:border-slate-800 dark:bg-slate-950 dark:shadow-none">
		<h4 class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Notes</h4>
		<div class="wrapperGroup items-start">
			<p class="title">System Note</p>
			<b class="dark:text-gray-100">:</b>
			<p class="value bg-slate-50 dark:bg-slate-900">{!! $transaction?->system_note ?? 'N/A' !!}</p>
		</div>
	</section>

	<section
		class="mb-4 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm shadow-slate-200/50 dark:border-slate-800 dark:bg-slate-950 dark:shadow-none">
		<div class="border-b border-slate-200 bg-slate-50/80 px-4 py-3 dark:border-slate-800 dark:bg-slate-900/80">
			<div class="flex_between_center">
				<div>
					<h4 class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">
						Subscription Details</h4>
					<p class="mt-1 text-sm text-slate-600 dark:text-slate-300">Linked subscription record for this
						transaction.</p>
				</div>
				<a
					href="{{ $trxSubscription ? route('backend_billings_customer_subscription_show', $trxSubscription->id) : '#' }}">
					{!! \App\Helpers\IconPack::eye(['class' => 'iconPackItem w-5 h-5 text-slate-500 dark:text-slate-300']) !!}
				</a>
			</div>
		</div>

		<div class="grid gap-3 px-4 py-4 md:grid-cols-2">
			<div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-900">
				<p class="text-[11px] uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">Subscription Row ID
				</p>
				<p class="mt-1 text-sm font-medium text-slate-900 dark:text-slate-100">{{ $trxSubscription?->id ?? 'N/A' }}
				</p>
			</div>
			<div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-900">
				<p class="text-[11px] uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">RC Subscription ID</p>
				<p class="mt-1 break-all text-sm font-medium text-slate-900 dark:text-slate-100">
					{{ $trxSubscription?->rc_subscription_id ?? 'N/A' }}</p>
			</div>
			<div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-900">
				<p class="text-[11px] uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">RC Event ID</p>
				<p class="mt-1 break-all text-sm font-medium text-slate-900 dark:text-slate-100">
					{{ $trxSubscription?->rc_event_id ?? 'N/A' }}</p>
			</div>
			<div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-900">
				<p class="text-[11px] uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">Subscription Status
				</p>
				<div class="mt-1">{!! \App\Helpers\Ui\DatatableHelper::subEnums($trxSubscription?->sub_status) !!}</div>
			</div>
			<div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-900">
				<p class="text-[11px] uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">Payment Status</p>
				<div class="mt-1">{!! \App\Helpers\Ui\DatatableHelper::paymentEnums($trxSubscription?->payment_status) !!}
				</div>
			</div>
		</div>
	</section>

	<section
		class="mb-4 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm shadow-slate-200/50 dark:border-slate-800 dark:bg-slate-950 dark:shadow-none">
		<div class="border-b border-slate-200 bg-slate-50/80 px-4 py-3 dark:border-slate-800 dark:bg-slate-900/80">
			<div class="flex_between_center">
				<div>
					<h4 class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">User
						Records</h4>
					<p class="mt-1 text-sm text-slate-600 dark:text-slate-300">Subscriber identity linked to this payment
						event.</p>
				</div>
				<a href="{{ $trxUser ? route('backend_single_user', $trxUser->id) : '#' }}">
					{!! \App\Helpers\IconPack::eye(['class' => 'iconPackItem w-5 h-5 text-slate-500 dark:text-slate-300']) !!}
				</a>
			</div>
		</div>

		<div class="grid gap-3 px-4 py-4 md:grid-cols-2">
			<div class="md:col-span-2">
				<a href="{{ $trxUser ? route('backend_single_user', $trxUser->id) : '#' }}"
					class="value flex items-center gap-3 transition hover:border-slate-300 dark:hover:border-slate-600">
					<img
						src="{{ $trxUser?->avatar ? \Illuminate\Support\Facades\Storage::url($trxUser->avatar) : asset('site_assets/dummies/dummy_man.webp') }}"
						alt="user-avatar"
						class="h-12 w-12 rounded-full border border-slate-300 object-cover dark:border-slate-600">
					<div class="min-w-0">
						<div class="font-medium text-slate-900 dark:text-slate-100">{{ $trxUser?->name ?? 'N/A' }}</div>
						<div class="text-xs text-slate-500 dark:text-slate-400">{{ $trxUser?->email ?? 'N/A' }}</div>
					</div>
				</a>
			</div>

			<div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-900">
				<p class="text-[11px] uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">User DB ID</p>
				<p class="mt-1 text-sm font-medium text-slate-900 dark:text-slate-100">{{ $trxUser?->id ?? 'N/A' }}</p>
			</div>
			<div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-900">
				<p class="text-[11px] uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">Name</p>
				<p class="mt-1 text-sm font-medium text-slate-900 dark:text-slate-100">{{ $trxUser?->name ?? 'N/A' }}</p>
			</div>
			<div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-900">
				<p class="text-[11px] uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">UID</p>
				<p class="mt-1 break-all text-sm font-medium text-slate-900 dark:text-slate-100">
					{{ $trxUser?->user_uid ?? 'N/A' }}</p>
			</div>
			<div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-900">
				<p class="text-[11px] uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">Username</p>
				<p class="mt-1 text-sm font-medium text-slate-900 dark:text-slate-100">{{ $trxUser?->username ?? 'N/A' }}
				</p>
			</div>
			<div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-900">
				<p class="text-[11px] uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">Email</p>
				<p class="mt-1 break-all text-sm font-medium text-slate-900 dark:text-slate-100">
					{{ $trxUser?->email ?? 'N/A' }}</p>
			</div>
			<div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-900">
				<p class="text-[11px] uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500">Phone</p>
				<p class="mt-1 text-sm font-medium text-slate-900 dark:text-slate-100">{{ $trxUser?->phone ?? 'N/A' }}</p>
			</div>
		</div>
	</section>

</div>

<style scoped>
	.title {
		min-width: 170px;
		color: #64748b;
		font-weight: 500;
		letter-spacing: 0.01em;
	}

	.dark .title {
		color: #94a3b8;
	}

	.value {
		width: 100%;
		margin: 4px 0;
		padding: 0.7rem 0.85rem;
		border-radius: 0.9rem;
		background-color: #f8fafc;
		border: 1px solid #e2e8f0;
		color: #0f172a;
		overflow-wrap: anywhere;
	}

	.dark .value {
		background-color: #0f172a;
		border-color: #1e293b;
		color: #e2e8f0;
	}

	.wrapperGroup {
		display: grid;
		grid-template-columns: 170px 12px minmax(0, 1fr);
		align-items: start;
		gap: 0.5rem 0.75rem;
	}
</style>