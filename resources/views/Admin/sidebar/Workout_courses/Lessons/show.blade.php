<!----------- Announcement Banner ----------->
<x-ui_items.banners.announcement_banner rolesToShow="admin,super_admin"
	message="⚠️Be Careful! These Workout Lessons records are connected to Workout Galleries. Deleting or modifying will also affect any children records in the relationship tree."
	bgColor="bg-yellow-300" />
<!----------- End Announcement Banner ----------->
@php
$zones = \App\Helpers\Ui\ConvertDynamicTimezone::handle();
$creator = $singleWorkoutLesson?->lessonRelatonWith_Creator;
$lastModifier = $singleWorkoutLesson?->lessonRelatonWith_LastModifier;
$lessonCategory = $singleWorkoutLesson?->lessonRelatingBackTo_Course?->courseRelatingBackTo_Category ?? null;
@endphp

<section class="mt-4 rounded-lg p-4 dark:bg-neutral-700">

	<div class="mb-12 p-4 grid grid-cols-3 gap-4 border border-gray-400 rounded-lg shadow-lg">
		<h5 class="col-span-3 text-blue-800 dark:text-blue-400 text-center">BASIC INFORMATION</h5>
		<div class="col-span-1 my-1">
			<label class="inputLabelMedium min-w-24 m-0">DB ID:</label>
			<input type="text" class="normalInput" value="{{ $singleWorkoutLesson?->id ?? null }}" readonly disabled>
		</div>

		<div class="col-span-2 my-1">
			<label class="inputLabelMedium min-w-24 m-0">Lesson UID: <span class="grayLabel">(Read-only)</span></label>
			<input type="text" class="normalInput" value="{{ $singleWorkoutLesson?->lesson_uid ?? null }}" readonly
				disabled>
		</div>
	</div>

	<div class="mb-12 p-4 border border-gray-400 rounded-lg shadow-lg">

		<form method="POST" action="{{ route('backend_workout_lesson_store') }}" class="" enctype="multipart/form-data">
			@csrf

			<!--- IDENTIFIER HIDDEN INPUTS -->
			<input type="hidden" name="id" class="smallInput" value="{{ $singleWorkoutLesson->id }}">
			<input type="hidden" name="flag" class="smallInput" value="update">
			<!--- IDENTIFIER HIDDEN INPUTS -->
			<h5 class="col-span-2 text-blue-800 dark:text-blue-400 text-center uppercase">Course Information</h5>

			<div class="grid grid-cols-3 gap-4 mt-4">
				<div class="col-span-1">
					<label class="inputLabelMedium">Workout Category
						<span class="grayLabel"> (Read Only)</span>
					</label>
					<input type="text" name="lesson_uid" class="normalInput bg-gray-100"
						value="{{ $lessonCategory?->name ?? "N/A"}}" readonly disabled>
				</div>
				<div class="col-span-2">
					<label class="inputLabelMedium">Select Course <span class="text-red-500">*</span></label>
					<select name="course_id" class="dropdownSelectInputs" required>
						<option value="">-- Choose a choose --</option>
						@foreach($courses as $course)
						<option value="{{ $course->id }}" {{ $singleWorkoutLesson->course_id == $course->id ? 'selected' : '' }}>
							[{{ $course->id }}]-{{ $course->title }}
						</option>
						@endforeach
					</select>
				</div>
			</div>

			<div class="mb-5 mt-2">
				<label class="inputLabelMedium">Lesson Title <span class="text-red-500">*</span></label>
				<input type="text" name="title" class="normalInput" placeholder="max 255 characters"
					value="{{ $singleWorkoutLesson->title }}" required>
			</div>

			<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
				<div>
					<label class="inputLabelMedium">Lesson Duration <span class="grayLabel">(e.g. 4 weeks)</span></label>
					<input type="text" name="lesson_duration" class="normalInput"
						value="{{ $singleWorkoutLesson?->lesson_duration }}">
				</div>
				<div>
					<label class="inputLabelMedium">Calorie Burns <span class="grayLabel">(e.g. 120 kcal)</span></label>
					<input type="text" name="calorie_burns" class="normalInput"
						value="{{ $singleWorkoutLesson?->calorie_burns }}">
				</div>
				<div>
					<label class="inputLabelMedium">Sets <span class="grayLabel">(e.g. integer only)</span></label>
					<input type="text" name="sets" class="normalInput" value="{{ $singleWorkoutLesson?->sets }}">
				</div>
				<div>
					<label class="inputLabelMedium">Reps <span class="grayLabel">(e.g. integer only)</span></label>
					<input type="text" name="course_duration" class="normalInput" value="{{ $singleWorkoutLesson?->reps }}">
				</div>
			</div>

			<div class="mb-5 mt-2">
				<label class="inputLabelMedium">Description</label>
				<textarea class="summernote" name="description">{{ $singleWorkoutLesson->description }}</textarea>
			</div>

			<div class="mb-5 mt-2">
				<label class="inputLabelMedium">Instruction</label>
				<textarea class="summernote" name="instruction">{{ $singleWorkoutLesson->instruction }}</textarea>
			</div>

			<div class="mb-5 mt-2">
				<label for="feature_image" class="inputLabelMedium">Feature Image<span
						class="text-sm text-gray-400 font-light"> (Max 2 MB)</span></label>
				<input type="file" id="showPageDropify" name="feature_image" class="dropify" data-height="150" @if ($singleWorkoutLesson?->feature_image)
				data-default-file="{{ Storage::url($singleWorkoutLesson->feature_image) }}" @endif />

				<input type="hidden" name="remove_image" id="remove_image" value="0">
			</div>

			<div>
				<label for="videoGallery" class="inputLabelMedium">Video Gallery
					<span class="text-sm text-gray-400 font-light"> (Multiple allowed) (Max 1 GB each)</span>
				</label>
				@if (!empty($videoGallery))
				<div class="my-4 grid grid-cols-2 gap-4 items-stretch">
					@foreach ($videoGallery as $index => $item)

					<div class="p-4 rounded-lg flex flex-col justify-between gap-4 h-full border border-gray-300">

						<!--------- DYNAMCIALLY LOAD GALLERY FILES --------->
						@php
							$url = Storage::url($item?->filepath);
							$type = $item?->filetype ?? null;
							$ext = strtolower(pathinfo($item?->filepath ?? '', PATHINFO_EXTENSION));
							$name = $item?->metadata['basename'] ?? basename($item?->filepath);
						@endphp

						<!-- VIDEO  PREVIEW -->
						@if($type === 'video' || in_array($ext, ['mp4','mov','avi','mpeg','webm']))

						<video controls class="border border-gray-300 w-full mt-3"><source src="{{ $url }}"></video>
						<p class="py-2 text-sm appText">[{{ $item?->id }}]-{{ $name }}</p>

						<!-- IMAGE PREVIEW -->
						@elseif($type === 'image' || in_array($ext, ['jpg','jpeg','png','gif','webp','svg']))

							<img src="{{ $url }}" class="w-full mt-3 border border-gray-300 rounded" />
							<p class="py-2 text-sm appText">[{{ $item?->id }}]-{{ $name }}</p>
							
						<!-- EVERYTHING ELSE JUST LINK -->
						@else
							<div class="mt-3 p-3 border border-gray-300 rounded bg-gray-50 dark:bg-neutral-500 text-sm">
								<p class="mb-2 text-sm appText">[{{ $item?->id }}]-{{ $name }}</p>
								<a href="{{ $url }}" target="_blank" class="text-blue-400 underline">Open / Download File</a>
							</div>
						@endif
						<!--------- DYNAMCIALLY LOAD GALLERY FILES --------->
						
						<input type="hidden" name="edit_video_gallery[{{ $index }}][id]" value="{{ $item->id }}" />
						<input type="file" name="edit_video_gallery[{{ $index }}][replacement]" class="p-10 border dark:border-none filepond"
							data-height="150" />

						<label class="flex items-center gap-2 mt-2 cursor-pointer">
							<span class="text-sm text-gray-500 hover:text-red-300">Remove</span>

							<input type="hidden" name="edit_video_gallery[{{ $index }}][remove]" value="0">
							<input type="checkbox" name="edit_video_gallery[{{ $index }}][remove]" value="1"
								class="hidden peer">

							{!! \App\Helpers\IconPack::trash(['class' => 'iconPackItem w-5 h-5 text-gray-400 transition peer-checked:text-red-600 peer-checked:scale-110 hover:text-red-300']) !!}
						</label>
					</div>

					@endforeach
				</div>
				@endif
				<input type="file" name="video_gallery[]" class="p-10 filepond border dark:border-none" data-height="150" multiple />

				<div id="filepond-error"></div>
			</div>
			<!--------------->

			<div class="mb-5 mt-2">
				<label class="inputLabelMedium">Status</label>
				<select name="status" class="dropdownSelectInputs">
					<option value="1" {{ $singleWorkoutLesson->status == 1 ? 'selected' : '' }}>Active</option>
					<option value="0" {{ $singleWorkoutLesson->status == 0 ? 'selected' : '' }}>Inactive</option>
				</select>
			</div>

			<div class="flex_between_center">
				<button type="submit" class="showButton my-4">Update Lesson</button>
			</div>
		</form>
		<form action="{{ route('backend_workout_lesson_delete', $singleWorkoutLesson->id) }}" method="POST"
			class="p-0 relative flex_end_center">
			@csrf
			@method('DELETE')
			<button type="submit" class="deleteButton"
				onclick="return confirm('Are you sure you want to delete this workout lesson?')">
				{!! \App\Helpers\IconPack::trash(['class' => 'iconPackItem w-4 h-4 text-gray-100']) !!}
			</button>
		</form>
	</div>

	<div class="mb-5 p-4 grid grid-cols-1 lg:grid-cols-2 gap-6 border border-gray-400 rounded-lg shadow-lg">

		@foreach ([['label' => 'CREATOR', 'user' => $creator, 'date' => $singleWorkoutLesson?->created_at, 'dateLabel' =>
		'Created At'], ['label' => 'LAST MODIFIER', 'user' => $lastModifier, 'date' => $singleWorkoutLesson?->updated_at,
		'dateLabel' => 'Updated At']] as $item)
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

<!----------- DROPIFY ----------->
<script>
	(function() {
		const el = $('#showPageDropify');

		if (!el.length || typeof $.fn.dropify === 'undefined') return;

		const instance = el.data('dropify');

		if (instance) instance.destroy();

		el.dropify();

		setTimeout(function() {
			el.on('dropify.afterClear', function() {
				console.log('DROPIFY CLEAR FIRED');
				$('#remove_image').val('1');
			});
		}, 0);
	})();
</script>
<!----------- DROPIFY ----------->