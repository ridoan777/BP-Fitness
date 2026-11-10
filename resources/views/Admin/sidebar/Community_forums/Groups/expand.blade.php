<x-app-layout>

	@php
		$breadCrumbs = [
			['label' => 'Categories', 'url' => 'backend_forum_category_index', 'icon' => 'layers'],
			['label' => 'Forums/Groups', 'url' => 'backend_forum_group_index', 'icon' => 'layers'],
			['label' => $singleGroup?->group_uid ?? 'Forum Details', 'icon' => 'layers'],
			['label' => 'All Reports', 'url' => 'backend_disputes_report_index', 'icon' => 'noMessage'],
		];
	 @endphp

	<x-indicators.breadcrumb :crumbs="$breadCrumbs" />

	<!-- Start Main Widgets -->
	<x-indicators.page_header_widget :pageName="$singleGroup?->name ?? 'Single Group'" />
	<!-- End Main Widgets -->


	<!-- Alert Component -->
	<x-indicators.alert-component message="Form Submission" />
	<!-- Alert Component -->

	@php
		$category = $singleGroup?->groupRelatingBackTo_Category;
	 @endphp

	<!-- ---------------- APP AREA Starts ---------------- -->

	<section class="displayArea w-full grid grid-cols-12 gap-4">

		<div class="p-2 col-span-3 appBorder rounded-lg">
			<h5 class="text-center font-semibold mb-2">Group Members ({{ $groupMembers->count() }})</h5>

			<input type="text" id="memberSearch" placeholder="Search members..."
				class="w-full mb-3 px-3 py-2 text-sm border rounded-lg dark:bg-neutral-900 dark:border-neutral-700">

			<div id="memberList" class="my-2 p-1 space-y-2 max-h-160 overflow-y-auto custom-scrollbar">

				@foreach ($groupMembers as $member)
							@php
								$memberUserDetail = $member?->memberRelatingBackTo_User;
								$userAvatar = \App\Helpers\Ui\GetUrlSafeFiles::alignAvatar($memberUserDetail, true);
							@endphp

							<div
								class="member-item p-2 border appBorder rounded-lg flex items-center gap-4 hover:bg-gray-100 dark:hover:bg-neutral-800"
								data-name="{{ strtolower($memberUserDetail?->name) }}"
								data-username="{{ strtolower($memberUserDetail?->username) }}"
								data-uid="{{ strtolower($member?->member_uid) }}">

								<a href="{{ route('backend_single_user', $memberUserDetail->id) }}"
									class="flex items-center gap-4 flex-1 min-w-0">

									<img src="{{ $userAvatar }}" alt="{{ $memberUserDetail?->name }}"
										class="h-14 w-14 rounded-full shrink-0">

									<div class="flex-1 min-w-0">
										<p class="text-sm font-semibold textColor dark:text-white truncate">
											{{ $memberUserDetail?->name }}
										</p>

										<p class="text-xs textColor truncate">
											{{ '@' . $memberUserDetail?->username }}
										</p>

										<p class="text-2xs textColor truncate">
											{{ $member?->member_uid }}
										</p>
									</div>

								</a>

								<div class="shrink-0">

									{{-- <x-ui_items.dropdowns.dropdown :items="[ ['type' => 'delete', 'label' => 'Delete', 'route' => route('backend_forum_message_delete', $memberUserDetail->id), 'icon' => 'trash']]" /> --}}
								</div>

							</div>


				@endforeach

			</div>
		</div>
		<!-- ------------------------ -->
		<div class="col-span-7 appBorder rounded-lg p-3">

			<h5 class="text-center mb-4">Messages ({{ $totalMessages ?? 0 }})</h5>

			<div id="messageContainer" class="space-y-4 max-h-160 overflow-y-auto">
				@foreach($messages as $message)
					@include('Admin.sidebar.Community_forums.Groups.partials.message_partial', ['message' => $message])
				@endforeach
			</div>
		</div>
		<!-- ------------------------ -->
		<div class="col-span-2 appBorder p-3 rounded-lg dark:bg-neutral-900">
			<h5 class="text-center font-semibold mb-4 dark:text-white">Group Details</h5>
			<div class="space-y-2 text-sm textColor dark:text-neutral-400">
				<p><span class="font-semibold dark:text-neutral-200">Name:</span> {{ $singleGroup->name }}</p>
				<p><span class="font-semibold dark:text-neutral-200">UID:</span> {{ $singleGroup->group_uid }}</p>
				<p><span class="font-semibold dark:text-neutral-200">Category:</span>
					{{ $category?->name ?? 'N/A' }}
				</p>
				<p><span class="font-semibold dark:text-neutral-200">Type:</span>
					{{ $singleGroup->isPremium ? 'Premium' : 'Free' }}</p>
				<p><span class="font-semibold dark:text-neutral-200">Status:</span>
					{{ $singleGroup->status ? 'Active' : 'Inactive' }}</p>
				<p><span class="font-semibold dark:text-neutral-200">Members:</span> {{ $groupMembers->count() }}</p>
				<p><span class="font-semibold dark:text-neutral-200">Messages:</span> {{ $totalMessages ?? 0 }}</p>
				<p><span class="font-semibold dark:text-neutral-200">Created:</span>
					{{ $singleGroup->created_at?->format('d M Y, h:i A') }}</p>
				<p><span class="font-semibold dark:text-neutral-200">Updated:</span>
					{{ $singleGroup->updated_at?->diffForHumans() }}</p>
				<p><span class="font-semibold dark:text-neutral-200">Creator:</span>
					{{ $singleGroup->groupRelatonWith_Creator?->name ?? 'System' }}</p>
				<p><span class="font-semibold dark:text-neutral-200">Last Modified:</span>
					{{ $singleGroup->groupRelatonWith_LastModifier?->name ?? 'N/A' }}</p>
			</div>
		</div>
	</section>
	<!-- ---------------- APP AREA Starts ---------------- -->

</x-app-layout>
<!----------- DYNAMICALLY LAZY LOAD OLDER MSSG ----------->

<script>
	document.addEventListener('DOMContentLoaded', function () {

		const container = document.getElementById('messageContainer');

		container.scrollTop = container.scrollHeight;

		let loading = false;
		let hasMore = true;

		container.addEventListener('scroll', async function () {

			if (loading || !hasMore) return;

			if (container.scrollTop > 50) return;

			loading = true;

			const first = document.querySelector('.message-row');
			if (!first) return;

			const beforeId = first.dataset.messageId;
			const oldHeight = container.scrollHeight;

			const res = await fetch(
				"{{ route('backend_forum_group_messages_older', $singleGroup->id) }}?before_id=" + beforeId
			);

			if (!res.ok) {
				console.error("Server error while loading messages");
				loading = false;
				return;
			}

			const data = await res.json();

			hasMore = data.hasMore;

			container.insertAdjacentHTML('afterbegin', data.html);

			setTimeout(() => {
				if (window.HSStaticMethods && window.HSStaticMethods.autoInit) {
					window.HSStaticMethods.autoInit();
				}
			}, 50);

			container.scrollTop = container.scrollHeight - oldHeight;

			loading = false;
		});
	});
</script>
<!----------- DYNAMICALLY LAZY LOAD OLDER MSSG ----------->

<!----------- USER SEARCHES ----------->

<script>
	document.addEventListener('DOMContentLoaded', function () {
		const input = document.getElementById('memberSearch');
		const items = document.querySelectorAll('.member-item');

		input.addEventListener('input', function () {
			const q = this.value.toLowerCase().trim();

			items.forEach(item => {
				const name = item.dataset.name || '';
				const username = item.dataset.username || '';
				const uid = item.dataset.uid || '';

				const match =
					name.includes(q) ||
					username.includes(q) ||
					uid.includes(q);

				item.style.display = match ? 'flex' : 'none';
			});
		});
	});
</script>
<!----------- USER SEARCHES ----------->