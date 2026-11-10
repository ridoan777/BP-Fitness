@php
	$user = $userAchievements->first()?->earnerRelatonWith_User;
	$totalChallenges = $userAchievements->count();
	$totalPoints = (int) $userAchievements->sum('points_earned');
@endphp

<div class="p-4">
	<section class="rounded-lg border border-gray-200 p-4 dark:border-neutral-600">
		<h4 class="text-sm font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">User Details</h4>

		<div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
			<div class="rounded-lg border border-gray-100 p-4 dark:border-neutral-700">
				<p class="text-xs uppercase tracking-wider text-gray-500">User</p>
				<div class="mt-3 flex items-center gap-3">
					<img src="{{ $user?->avatar_url ?? asset('site_assets/dummies/dummy_man.webp') }}"
						alt="{{ $user?->name ?? 'User' }}" class="h-14 w-14 rounded-lg object-cover">
					<div>
						<p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $user?->name ?? 'N/A' }}</p>
						<p class="text-xs text-gray-500 dark:text-gray-400">{{ $user?->email ?? 'N/A' }}</p>
						<p class="text-xs text-gray-500 dark:text-gray-400">UID: {{ $user?->user_uid ?? 'N/A' }}</p>
					</div>
				</div>
			</div>

			<div class="rounded-lg border border-gray-100 p-4 dark:border-neutral-700">
				<p class="text-xs uppercase tracking-wider text-gray-500">Summary</p>
				<div class="mt-3 space-y-2 text-sm text-gray-700 dark:text-gray-200">
					<p>User ID: {{ $user?->id ?? 'N/A' }}</p>
					<p>Username: {{ $user?->username ?? 'N/A' }}</p>
					<p>Phone: {{ $user?->phone ?? 'N/A' }}</p>
					<p>Total Challenges: {{ number_format($totalChallenges) }}</p>
					<p>Total Points Earned: {{ number_format($totalPoints) }}</p>
				</div>
			</div>
		</div>
	</section>

	<section class="mt-5 rounded-lg border border-gray-200 p-4 dark:border-neutral-600">
		<div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
			<h4 class="text-sm font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Achievement Records</h4>
			<form method="POST" action="{{ route('backend_achievement_earner_record_bulk_delete') }}"
				onsubmit="return confirm('Delete selected achievement records?');">
				@csrf
				<input type="hidden" name="ids" id="selectedAchievementRecordIds">
				<button type="submit"
					class="rounded-lg bg-red-600 px-3 py-2 text-xs font-medium text-white hover:bg-red-700">
					Delete Selected
				</button>
			</form>
		</div>

		<div class="mt-4 overflow-x-auto">
			<table class="min-w-full text-sm text-gray-700 dark:text-gray-200">
				<thead>
					<tr class="border-b border-gray-200 text-center text-xs uppercase tracking-wider text-gray-500 dark:border-neutral-700 dark:text-gray-400">
						<th class="px-3 py-2">
							<input type="checkbox" id="selectAllAchievementRecords"
								class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
								onchange="
									document.querySelectorAll('.achievement-record-check').forEach((checkbox) => checkbox.checked = this.checked);
									document.getElementById('selectedAchievementRecordIds').value = Array.from(document.querySelectorAll('.achievement-record-check:checked')).map((checkbox) => checkbox.value).join(',');
								">
						</th>
						<th class="px-3 py-2">#</th>
						<th class="px-3 py-2">DB ID</th>
						<th class="px-3 py-2">Achievement</th>
						<th class="px-3 py-2">Type</th>
						<th class="px-3 py-2">Required Value</th>
						<th class="px-3 py-2">Points Earned</th>
						<th class="px-3 py-2">Unlocked At</th>
						<th class="px-3 py-2">Status</th>
						<th class="px-3 py-2">Delete</th>
					</tr>
				</thead>
				<tbody>
					@forelse ($userAchievements as $index => $record)
						<tr class="border-b border-gray-100 dark:border-neutral-800">
							<td class="px-3 py-3">
								<input type="checkbox" name="record_ids[]" value="{{ $record->id }}"
									class="achievement-record-check h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
									onchange="
										document.getElementById('selectedAchievementRecordIds').value = Array.from(document.querySelectorAll('.achievement-record-check:checked')).map((checkbox) => checkbox.value).join(',');
										document.getElementById('selectAllAchievementRecords').checked = document.querySelectorAll('.achievement-record-check').length > 0 && Array.from(document.querySelectorAll('.achievement-record-check')).every((checkbox) => checkbox.checked);
									">
							</td>
							<td class="px-3 py-3">{{ $index + 1 }}</td>
							<td class="px-3 py-3">{{ $record->id }}</td>
							<td class="px-3 py-3">{{ $record->earnerRelatonWith_AchievementHead?->title ?? 'N/A' }}</td>
							<td class="px-3 py-3">
								{{ $record->earnerRelatonWith_AchievementHead?->type?->label() ?? $record->earnerRelatonWith_AchievementHead?->type ?? 'N/A' }}
							</td>
							<td class="px-3 py-3">{{ $record->earnerRelatonWith_AchievementHead?->required_value ?? 'N/A' }}</td>
							<td class="px-3 py-3">{{ number_format((int) $record->points_earned) }}</td>
							<td class="px-3 py-3">{{ $record->unlocked_at?->format('d M Y, H:i') ?? 'N/A' }}</td>
							<td class="px-3 py-3">
								@if ($record->status)
									<span class="text-green-600 font-medium">Active</span>
								@else
									<span class="text-red-600 font-medium">Inactive</span>
								@endif
							</td>
							<td class="px-3 py-3">
								<form method="POST" action="{{ route('backend_achievement_earner_record_delete', $record->id) }}"
									onsubmit="return confirm('Delete this achievement record?');">
									@csrf
									@method('DELETE')
									<button type="submit"
										class="rounded bg-red-600 px-2 py-1 text-xs font-medium text-white hover:bg-red-700">
										Delete
									</button>
								</form>
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="9" class="px-3 py-4 text-center text-gray-500 dark:text-gray-400">No achievement records found.</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</section>
</div>

<style>
	td{
		text-align: center;
	}
</style>