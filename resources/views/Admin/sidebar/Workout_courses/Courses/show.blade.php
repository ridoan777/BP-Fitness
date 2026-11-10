<!----------- Announcement Banner ----------->
<x-ui_items.banners.announcement_banner rolesToShow="admin,super_admin"
	message="⚠️Be Careful! These Workout Course records are connected to Workout Lessons. Deleting or modifying will also affect any children records in the relationship tree."
	bgColor="bg-yellow-300" />
<!----------- End Announcement Banner ----------->
@php
$zones = \App\Helpers\Ui\ConvertDynamicTimezone::handle();
$creator = $singleCourse?->courseRelatonWith_Creator;
$lastModifier = $singleCourse?->courseRelatonWith_LastModifier;
@endphp

<section class="mt-4 rounded-lg p-4 dark:bg-neutral-700">

	<div class="mb-12 p-4 grid grid-cols-2 gap-4 border border-gray-400 rounded-lg shadow-lg">
		<h5 class="col-span-2 text-blue-800 dark:text-blue-400 text-center">BASIC INFORMATION</h5>
		<div class="col-span-1 my-1">
			<label class="inputLabelMedium min-w-24 m-0">DB ID:</label>
			<input type="text" class="normalInput" value="{{ $singleCourse?->id ?? null }}" readonly disabled>
		</div>

		<div class="col-span-1 my-1">
			<label class="inputLabelMedium min-w-24 m-0">UID: <span class="grayLabel">(Read-only)</span></label>
			<input type="text" class="normalInput" value="{{ $singleCourse?->course_uid ?? null }}" readonly disabled>
		</div>
	</div>

	<div class="mb-12 p-4 border border-gray-400 rounded-lg shadow-lg">

		<form method="POST" action="{{ route('backend_workout_course_store') }}" class="" enctype="multipart/form-data">
			@csrf

			<!--- IDENTIFIER HIDDEN INPUTS -->
			<input type="hidden" name="id" class="smallInput" value="{{ $singleCourse->id }}">
			<input type="hidden" name="flag" class="smallInput" value="update">
			<!--- IDENTIFIER HIDDEN INPUTS -->
			<h5 class="col-span-2 text-blue-800 dark:text-blue-400 text-center uppercase">Course Information</h5>

			<div class="grid grid-cols-3 gap-4 mt-4">
				<div class="col-span-1">
					<label class="inputLabelMedium">Select Category <span class="text-red-500">*</span></label>
					<select name="category_id" class="dropdownSelectInputs" required>
						<option value="">-- Choose Category --</option>
						@foreach($categories as $category)
							<option value="{{ $category->id }}" {{ $singleCourse->category_id == $category->id ? 'selected' : '' }}>
								[{{ $category->id }}]-{{ $category->name }}
							</option>
						@endforeach
					</select>
				</div>
				<div class="col-span-2">
					<label class="inputLabelMedium">Course UID <span class="grayLabel">(Read Only)</span></label>
					<input type="text" name="course_uid" class="normalInput bg-gray-100"
						value="{{ $singleCourse->course_uid }}" readonly disabled>
				</div>
			</div>

			<div class="mb-5 mt-2">
				<label class="inputLabelMedium">Course Title <span class="text-red-500">*</span></label>
				<input type="text" name="title" class="mediumInput" placeholder="max 255 characters"
					value="{{ $singleCourse->title }}" required>
			</div>

			<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
				<div class="mb-5 mt-2">
					<label class="inputLabelMedium">Course Duration <span class="grayLabel">(e.g. 4 weeks)</span></label>
					<input type="text" name="course_duration" class="normalInput"
						value="{{ $singleCourse->course_duration }}">
				</div>

				<div class="mb-5 mt-2">
					<label class="inputLabelMedium">Is This Course Premium?</label>
					<select name="is_premium" class="dropdownSelectInputs">
						<option value="1" {{ $singleCourse->is_premium == 1 ? 'selected' : '' }}>Yes</option>
						<option value="0" {{ $singleCourse->is_premium == 0 ? 'selected' : '' }}>No</option>
					</select>
				</div>
			</div>

			<div class="mb-5 mt-2">
				<label class="inputLabelMedium">Description</label>
				<textarea class="summernote" name="description" id="description">{{ $singleCourse->description }}</textarea>
			</div>

			<div class="mb-5 mt-2">
				<label for="feature_image" class="inputLabelMedium">Feature Image<span
						class="text-sm text-gray-400 font-light"> (Max 2 MB)</span></label>
				<input type="file" id="showPageDropify" name="feature_image" class="dropify" data-height="150" @if ($singleCourse?->feature_image) data-default-file="{{ Storage::url($singleCourse->feature_image) }}" @endif />

				<input type="hidden" name="remove_image" id="remove_image" value="0">
			</div>

			<div class="hidden my-5">
				<label for="videoGallery" class="inputLabelMedium">Video Gallery
					<span class="text-sm text-gray-400 font-light"> (Multiple allowed) (Max 1 GB each)</span>
				</label>
				{{-- @if($singleCourse->courseRelatonWith_Gallery->count() > 0)
				<div class="flex flex-wrap gap-2 mb-2">
					@foreach($singleCourse->courseRelatonWith_Gallery as $gallery)
					<div class="relative w-24 h-24 border rounded">
						<video src="{{ Storage::url($gallery->file_path) }}" class="w-full h-full object-cover"></video>
					</div>
					@endforeach
				</div>
				@endif --}}
				<input type="file" name="gallery_file[]" class="p-10 filepond border" data-height="150" multiple />
				<div id="filepond-error"></div>
			</div>

			<div class="mb-5 mt-2">
				<label class="inputLabelMedium">Status</label>
				<select name="status" class="dropdownSelectInputs">
					<option value="1" {{ $singleCourse->status == 1 ? 'selected' : '' }}>Active</option>
					<option value="0" {{ $singleCourse->status == 0 ? 'selected' : '' }}>Inactive</option>
				</select>
			</div>

			<div class="flex_between_center">
				<button type="submit" class="showButton my-4">Update Course</button>
			</div>
		</form>
		<form action="{{ route('backend_workout_course_delete', $singleCourse->id) }}" method="POST"
			class="p-0 relative flex_end_center">
			@csrf
			@method('DELETE')
			<button type="submit" class="deleteButton"
				onclick="return confirm('Are you sure you want to delete this workout category?')">
				{!! \App\Helpers\IconPack::trash(['class' => 'iconPackItem w-4 h-4 text-gray-100']) !!}
			</button>
		</form>
	</div>

	<div class="mb-5 p-4 grid grid-cols-1 lg:grid-cols-2 gap-6 border border-gray-400 rounded-lg shadow-lg">

		@foreach ([['label' => 'CREATOR', 'user' => $creator, 'date' => $singleCourse?->created_at, 'dateLabel' => 'Created At'], ['label' => 'LAST MODIFIER', 'user' => $lastModifier, 'date' => $singleCourse?->updated_at, 'dateLabel' => 'Updated At']] as $item)
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
<script>
(function () {
	const el = $('#showPageDropify');

	if (!el.length || typeof $.fn.dropify === 'undefined') return;

	const instance = el.data('dropify');

	if (instance) instance.destroy();

	el.dropify();

	setTimeout(function () {
		el.on('dropify.afterClear', function () {
			console.log('DROPIFY CLEAR FIRED');
			$('#remove_image').val('1');
		});
	}, 0);
})();
</script>