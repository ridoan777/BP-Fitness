<x-app-layout>

	@php
		$breadCrumbs = [
			['label' => 'Journey List', 'url' => 'backend_journeys_index', 'icon' => 'globe'],
			['label' => 'Single Journey'],
		];
	@endphp

	<x-indicators.breadcrumb :crumbs="$breadCrumbs" />

	<!-- Start Main Widgets -->
	<x-indicators.page_header_widget pageName="Journey: {{ $journey?->journey_uid ?? null }}" />
	<!-- End Main Widgets -->

	<!-- Alert Component -->
	<x-indicators.alert-component message="Modification" />
	<!-- Alert Component -->

	@php
		$ACCORDION_1_VISIBILITY = ''; // hidden or ''
	@endphp
	<!-- ---------------- APP AREA Starts ---------------- -->


	<!------------ BASIC INFO ------------>
	<div class="bg-gray-100 dark:bg-neutral-700 shadow-lg rounded-lg p-6 space-y-4">
		<div>
			<h3 class="mb-8">NOTE DETAILS</h3>
			<button type="button" data-modal-target="hs-delete-modal" data-modal-toggle="hs-delete-modal"
				data-delete-url="{{ route('backend_journeys_delete', $journey?->id) }}" class="deleteButton">
				{!! \App\Helpers\IconPack::trash(['class' => 'iconPackItem w-3 h-3 text-gray-100']) !!}
			</button>

		</div>

		<div class="grid grid-cols-12 gap-4">
			<P class="col-span-2 font-bold">DB ID:</P>
			<p class="col-span-10">{{ $journey?->id ?? null }}</p>
		</div>
		<!----->
		<div class="grid grid-cols-12 gap-4">
			<P class="col-span-2 font-bold">DB UID:</P>
			<p class="col-span-10">{{ $journey?->journey_uid ?? null }}</p>
		</div>
		<!----->
		<div class="grid grid-cols-12 gap-4">
			<P class="col-span-2 font-bold self-center">Note:</P>
			<p class="col-span-10">{!! $journey?->text ?? null !!}</p>
		</div>
		<!----->
		<div class="grid grid-cols-12 gap-4">
			<P class="col-span-2 font-bold self-center">Image:</P>
			<a href="{{ $journey?->image ? Storage::url($journey->image) : asset('site_assets/dummies/dummy_no_file.jpg') }}"
				target="_blank" rel="noopener" class="col-span-10 block">
				<img
					src="{{ $journey?->image ? Storage::url($journey?->image) : asset('site_assets/dummies/dummy_no_file.jpg'); }}"
					alt="" class="w-2/3 rounded-lg cursor-pointer hover:scale-105 block">
			</a>
		</div>
	</div>
	<!------------ BASIC INFO ------------>

	<!------------ USER INFO ------------>
	<div class="bg-gray-100 dark:bg-neutral-700 shadow-lg rounded-lg p-6 space-y-4">
		<h3 class="mb-8">USER INFORMATION</h3>

		<div class="grid grid-cols-4 gap-8">
			<div class="col-span-1">
				@php
					$getUser = $journey?->journeyRelatingBackTo_User;
					$getAvatar = $getUser ? $getUser?->avatar : null;
					if ($getAvatar) {
						$avatar = Storage::url($getAvatar);
					} else {
						$avatar = asset('site_assets/dummies/dummy_man.webp');
					}
				 @endphp
				<a href="{{ route('backend_single_user', $getUser?->id) }}" target="_blank" rel="noopener">
					<img src="{{ $avatar }}" alt="{{ $getAvatar ?? 'no-avatar' }}"
						class="rounded-full cursor-pointer hover:scale-105">
				</a>
			</div>
			<div class="col-span-3 space-y-4">
				<div class="grid grid-cols-12 gap-4">
					<P class="col-span-3 font-bold">DB ID:</P>
					<p class="col-span-9">{{ $getUser?->id ?? null }}</p>
				</div>
				<!----->
				<div class="grid grid-cols-12 gap-4">
					<P class="col-span-3 font-bold">DB UID:</P>
					<p class="col-span-9">{{ $getUser?->user_uid ?? null }}</p>
				</div>
				<!----->
				<div class="grid grid-cols-12 gap-4">
					<P class="col-span-3 font-bold">Landlord Name:</P>
					<p class="col-span-9">{{ $getUser?->name ?? null }}</p>
				</div>
				<!----->
				<div class="grid grid-cols-12 gap-4">
					<P class="col-span-3 font-bold">Email:</P>
					<p class="col-span-9">{{ $getUser?->email ?? null }}</p>
				</div>
				<!----->
				<div class="grid grid-cols-12 gap-4">
					<P class="col-span-3 font-bold">Phone:</P>
					<p class="col-span-9">{{ $getUser?->phone ?? null }}</p>
				</div>
				<!----->
				<div class="grid grid-cols-12 gap-4">
					<P class="col-span-3 font-bold">Address:</P>
					<p class="col-span-9">
						{{ $getUser?->street ? $getUser?->street . ', ' : null }}{{ $getUser?->city ? $getUser?->city : null
						}}{{ $getUser?->zip ? '-' . $getUser?->zip : null }}{{ $getUser?->country ? ', ' . $getUser?->country
						: null }}
					</p>
				</div>
				<!----->
			</div>
		</div>

	</div>
	<!------------ LANDLORD/OWNER INFO ------------>

	<!-- ---------------- APP AREA Starts ---------------- -->
	<x-ui_items.modals.delete-modal id="hs-delete-modal" title="Confirm Deletion" confirmText="Yes, delete it"
		cancelText="Cancel" message="Are you sure you want to delete? This action cannot be undone" />

</x-app-layout>


<style>
	p {
		padding: 16px;
		border-radius: 8px;
		background-color: #e5e7eb;
	}

	.dark p {
		color: #e5e7eb;
		background-color: #737373;
	}
</style>