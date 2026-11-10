<!-- ----------- USER STAT ----------- -->
<div class="my-4 bg-gray-100 dark:bg-neutral-800 shadow-lg rounded-lg p-6 space-y-4">
	<h3 class="col-span-12 mb-8">USER STAT</h3>

	<div class="grid grid-cols-1 md:grid-cols-12 gap-4 text-sm">
	
		<div class="col-span-3 p-4 rounded-lg bg-white dark:bg-gray-700">
			<div class="text-gray-400">ID</div>
			<div class="font-semibold text-gray-900 dark:text-white">{{ $myStat->id }}</div>
		</div>
		<div class="col-span-4 p-4 rounded-lg bg-white dark:bg-gray-700">
			<div class="text-gray-400">UID</div>
			<div class="font-semibold text-gray-900 dark:text-white">{{ $myStat->stat_uid }}</div>
		</div>
		<div class="col-span-5 p-4 rounded-lg bg-white dark:bg-gray-700">
			<div class="text-gray-400">Property</div>
			<div class="font-semibold text-gray-900 dark:text-white">{{ ucfirst($myStat->property) }}</div>
		</div>
		<!---------->
		<div class="col-span-6 p-4 rounded-lg bg-white dark:bg-gray-700">
			<div class="text-gray-400">Created At:</div>
			<div class="font-semibold text-gray-900 dark:text-white">{{ $myStat->created_at?->format('M d, Y h:i A') ?? 'N/A' }}</div>
		</div>
		<div class="col-span-6 p-4 rounded-lg bg-white dark:bg-gray-700">
			<div class="text-gray-400">Last Updated At:</div>
			<div class="font-semibold text-gray-900 dark:text-white">{{ $myStat->updated_at?->format('M d, Y h:i A') ?? 'N/A' }}</div>
		</div>
		<!---------->
	
		<div class="col-span-6 p-4 rounded-lg bg-white dark:bg-gray-700">
			<div class="text-gray-400">Before</div>
			<div class="font-semibold text-orange-400">{{ $myStat?->before_value ?? 'N/A' }} {{ $myStat?->unit ?? '' }}</div>
			<div class="mt-4">
				<img src="{{ $myStat->before_file ? Storage::url($myStat->before_file) : asset('site_assets/dummies/dummy_no_file.webp') }}" alt="Before Image" class="w-full h-auto rounded-lg shadow-sm border border-gray-200">
			</div>
		</div>
	
		<div class="col-span-6 p-4 rounded-lg bg-white dark:bg-gray-700">
			<div class="text-gray-400">After</div>
			<div class="font-semibold text-blue-600">{{ $myStat?->after_value ?? 'N/A' }} {{ $myStat?->unit ?? '' }}</div>
			<div class="mt-4">
				<img src="{{ $myStat->after_file ? Storage::url($myStat->after_file) : asset('site_assets/dummies/dummy_no_file.jpg') }}" 
					 alt="After Image" 
					 class="w-full h-auto rounded-lg shadow-sm border border-gray-200">
			</div>
		</div>
		<!---------->

		<div class="col-span-12 p-4 rounded-lg bg-white dark:bg-gray-700 text-center">
			<div class="text-gray-400">Difference</div>
			<div class="font-semibold text-gray-900 dark:text-white">
				@php
					$before = (float) ($myStat->before_value ?? 0);
					$after = (float) ($myStat->after_value ?? 0);
					$diff = $after - $before;
				@endphp
				{{ $diff > 0 ? '+' : '' }}{{ $diff }} {{ $myStat?->unit ?? '' }}
			</div>
		</div>
	</div>
</div>
<!-- ----------- USER STAT ----------- -->

<!-- ----------- USER INFO ----------- -->
<div class="my-4 bg-gray-100 dark:bg-neutral-800 shadow-lg rounded-lg p-6 space-y-4">
	<h3 class="mb-8">USER INFORMATION</h3>

	<div class="grid grid-cols-4 gap-8">
		<div class="col-span-1">
			@php
				$getAvatar = $user?->avatar;
				if ($getAvatar) {
					$avatar = Storage::url($getAvatar);
				} else {
					$avatar = asset('site_assets/dummies/dummy_man.webp');
				}
			 @endphp
			<a href="{{ route('backend_single_user', $user?->id) }}" target="_blank" rel="noopener">
				<img src="{{ $avatar }}" alt="{{ $getAvatar ?? 'no-avatar' }}"
					class="rounded-full cursor-pointer hover:scale-105">
			</a>
		</div>
		<div class="col-span-3 space-y-2">
			<div class="grid grid-cols-12 gap-4">
				<P class="filledParagraph col-span-3 font-bold">DB ID:</P>
				<p class="filledParagraph col-span-9">{{ $user?->id ?? "N/A" }}</p>
			</div>
			<!----->
			<div class="grid grid-cols-12 gap-4">
				<P class="filledParagraph col-span-3 font-bold">DB UID:</P>
				<p class="filledParagraph col-span-9">{{ $user?->user_uid ?? "N/A" }}</p>
			</div>
			<!----->
			<div class="grid grid-cols-12 gap-4">
				<P class="filledParagraph col-span-3 font-bold">Username:</P>
				<p class="filledParagraph col-span-9">{{ $user?->username ?? "N/A" }}</p>
			</div>
			<!----->
			<div class="grid grid-cols-12 gap-4">
				<P class="filledParagraph col-span-3 font-bold">User Name:</P>
				<p class="filledParagraph col-span-9">{{ $user?->name ?? "N/A" }}</p>
			</div>
			<!----->
			<div class="grid grid-cols-12 gap-4">
				<P class="filledParagraph col-span-3 font-bold">Email:</P>
				<p class="filledParagraph col-span-9">{{ $user?->email ?? "N/A" }}</p>
			</div>
			<!----->
			<div class="grid grid-cols-12 gap-4">
				<P class="filledParagraph col-span-3 font-bold">Gender:</P>
				<p class="filledParagraph col-span-9">{{ $user?->gender ?? "N/A" }}</p>
			</div>
			<!----->
			<div class="grid grid-cols-12 gap-4">
				<P class="filledParagraph col-span-3 font-bold">Age:</P>
				<p class="filledParagraph col-span-9">{{ $user?->age ?? "N/A" }}</p>
			</div>
			<!----->
		</div>
	</div>

</div>
<!-- ----------- USER INFO ----------- -->

<!-- ----------- STYLE BLOCK ----------- -->
<style>
	.filledParagraph {
		padding: 12px;
		border-radius: 8px;
		background-color: #e5e7eb;
	}

	.dark .filledParagraph {
		color: #e5e7eb;
		background-color: #737373;
	}
</style>
<!-- ----------- STYLE BLOCK ----------- -->