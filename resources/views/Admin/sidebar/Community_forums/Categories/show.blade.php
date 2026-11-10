<!----------- Announcement Banner ----------->
<x-ui_items.banners.announcement_banner rolesToShow="admin,super_admin"
	message="⚠️Be Careful! These Forum Category records are connected to Forum Groups. Deleting or modifying will also affect any children records in the relationship tree."
	bgColor="bg-yellow-300" />
<!----------- End Announcement Banner ----------->
@php
$zones = \App\Helpers\Ui\ConvertDynamicTimezone::handle();
$creator = $singleCategory?->categoryRelatonWith_Creator;
$lastModifier = $singleCategory?->categoryRelatonWith_LastModifier;
@endphp

<section class="mt-4 rounded-lg p-4 dark:bg-neutral-700">

	<div class="mb-12 p-4 grid grid-cols-2 gap-4 border border-gray-400 rounded-lg shadow-lg">
		<h5 class="col-span-2 text-blue-800 text-center">BASIC INFORMATION</h5>
		<div class="col-span-1 my-1">
			<label class="inputLabelMedium min-w-24 m-0">DB ID:</label>
			<input type="text" class="normalInput" value="{{ $singleCategory?->id ?? null }}" readonly disabled>
		</div>

		<div class="col-span-1 my-1">
			<label class="inputLabelMedium min-w-24 m-0">UID:</label>
			<input type="text" class="normalInput" value="{{ $singleCategory?->category_uid ?? null }}" readonly disabled>
		</div>
	</div>

	<div class="mb-12 p-4 border border-gray-400 rounded-lg shadow-lg">

		<form method="POST" action="{{ route('backend_forum_category_store') }}" class="" enctype="multipart/form-data">
			@csrf

			<!--- IDENTIFIER HIDDEN INPUTS -->
			<input type="hidden" name="id" class="smallInput" value="{{ $singleCategory->id }}">
			<input type="hidden" name="flag" class="smallInput" value="update">
			<!--- IDENTIFIER HIDDEN INPUTS -->
			<h5 class="col-span-2 text-blue-800 text-center">CATEGORY INFORMATION</h5>
			<!-- -->
			<div class="mb-5 mt-2">
				<label for="name" class="inputLabelMedium">Forum Category Name
					<span class="text-red-500">*</span>
				</label>
				<input type="text" name="name" class="mediumInput" placeholder="max 255-characters"
					value="{{ $singleCategory?->name }}">
			</div>
			<!--  -->
			<div class="mb-5 mt-2">
				<label for="description" class="inputLabelMedium">Description
					<span class="text-sm text-gray-400 font-light"> (Max 2000 characters. Use Ctrl+Shift+V for safe
						pasting)</span>
				</label>
				<textarea class="summernote" name="description"> {{ $singleCategory?->description ?? null }}
				</textarea>
			</div>
			<!-- -->
			<div class="mb-5 mt-2">
				<label for="status" class="inputLabelMedium">Active Status</label>
				<select name="status" class="dropdownSelectInputs">
					<option value="1" {{ $singleCategory?->status == 1 ? 'selected' : '' }}>Active</option>
					<option value="0" {{ $singleCategory?->status == 0 ? 'selected' : '' }}>Inactive</option>
				</select>
			</div>

			<div class="flex_between_center">
				<button type="submit" class="showButton my-4">Update</button>
			</div>
		</form>
		<form action="{{ route('backend_forum_category_delete', $singleCategory->id) }}" method="POST"
			class="p-0 relative flex_end_center">
			@csrf
			@method('DELETE')
			<button type="submit" class="deleteButton"
				onclick="return confirm('Are you sure you want to delete this forum category?')">
				{!! \App\Helpers\IconPack::trash(['class' => 'iconPackItem w-4 h-4 text-gray-100']) !!}
			</button>
		</form>
	</div>

	<div class="mb-5 p-4 grid grid-cols-1 lg:grid-cols-2 gap-6 border border-gray-400 rounded-lg shadow-lg">

		@foreach ([['label' => 'CREATOR', 'user' => $creator, 'date' => $singleCategory?->created_at, 'dateLabel' => 'Created At'], ['label' => 'LAST MODIFIER', 'user' => $lastModifier, 'date' => $singleCategory?->updated_at, 'dateLabel' => 'Updated At']] as $item)
			<div class="p-4 border border-gray-200 rounded-lg">

				<h6 class="mb-4 font-semibold text-gray-700">{{ $item['label'] }}</h6>
				@if ($item['user'])
					<div class="flex gap-4">
						<a href="{{ route('backend_single_user', $item['user']->id) }}" target="_blank" class="w-24 shrink-0">
							<img class="w-24 h-24 rounded-lg object-cover hover:scale-105 transition"
								src="{{ $item['user']?->avatar ? Storage::url($item['user']?->avatar) : asset('site_assets/dummies/dummy_man.webp') }}"
								alt="{{ $item['user']?->name }}">
						</a>

						<div class="flex-1 space-y-3">
							<div class="flex items-center gap-1">
								<input type="text" class="normalInput w-full" value="{{ $item['user']?->name ?? '' }}" readonly
									disabled>
							</div>

							<div class="flex items-center gap-1">
								<input type="text" class="normalInput w-full"
									value="Role: {{ ucfirst($item['user']?->user_role ?? '') }}" readonly disabled>
							</div>

							<div class="flex items-center gap-1">
								<input type="text" class="normalInput w-full text-xs" value="{{ $item['date']
					? $item['date']->timezone($zones['zone'])->format('d-M-Y h:i:s A') . ' (UTC' . $zones['offset'] . ')'
					: '' }}" readonly disabled>
							</div>
						</div>
					</div>
				@else
					<div class="w-full h-full flex_xy_center">
						<h4 class="text-gray-400 text-center">No {{ strtolower($item['label']) }} Yet</h4>
					</div>
				@endif

			</div>
		@endforeach

	</div>
</section>
