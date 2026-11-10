<section>
	<div class="px-6 flex justify-end">
		<button type="button" data-modal-target="hs-form-modal" data-modal-toggle="hs-form-modal"
			class="darkButton">{!! \App\Helpers\IconPack::plus(['class' => 'iconPackItem w-3 h-3 ']) !!} Create
		</button>
	</div>
	<!--  -->

	<x-ui_items.modals.form-modal id="hs-form-modal" title="Create A Workout Lesson" cancelText="Cancel"
		modalWidth="w-5xl 2xl:w-6xl">
		<form method="POST" action="{{ route('backend_workout_lesson_store') }}"
			class="rounded-lg p-2 dark:bg-neutral-700" enctype="multipart/form-data">
			@csrf
			<input type="hidden" name="flag" class="smallInput" value="create">

			<div class="">

				<div class="grid grid-cols-3 gap-4">
					<div class="col-span-1">
						<label class="inputLabelMedium">Select Course <span class="text-red-500">*</span></label>
						<select name="course_id" class="dropdownSelectInputs" required>
							<option value="">-- Choose Course --</option>
							@foreach($courses as $course)
								<option value="{{ $course->id }}">[{{ $course->id }}]-{{ $course->title }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-span-2">
						<label class="inputLabelMedium">Lesson UID
							<span class="text-red-500 text-sm">(Must be unique, no space)</span>
							<span class="grayLabel">(Better keep it blank. Then an UID will be generated automatically.)</span>
						</label>
						<input type="text" name="lesson_uid" class="normalInput" placeholder="UID is a unique identifier. 32-chars max">
					</div>

				</div>

				<div class="mb-5 mt-2">
					<label class="inputLabelMedium">Lesson Title <span class="text-red-500">*</span></label>
					<input type="text" name="title" class="mediumInput" placeholder="max 255 characters" required>
				</div>

				<div class="grid grid-cols-2 gap-4">
					<div>
						<label class="inputLabelMedium">Lesson Duration
							<span class="grayLabel">(e.g. 15 mins, 30 mins, etc.)</span>
						</label>
						<input type="text" name="lesson_duration" class="normalInput" placeholder="Max 255 characters">
					</div>

					<div>
						<label class="inputLabelMedium">Calorie Burns
							<span class="grayLabel">(Only integer numbers for kcal or capital C)</span>
						</label>
						<input type="number" name="calorie_burns" class="normalInput" stap="1">
					</div>

					<div>
						<label class="inputLabelMedium">Sets
							<span class="grayLabel">(Only integer numbers, e.g. 3, 4)</span>
						</label>
						<input type="number" name="sets" class="normalInput">
					</div>

					<div>
						<label class="inputLabelMedium">Reps
							<span class="grayLabel">(Only integer numbers, e.g. 12, 15)</span>
						</label>
						<input type="text" name="reps" class="normalInput">
					</div>
				</div>

				<div class="mb-5 mt-2">
					<label class="inputLabelMedium">Lesson Description
						<span class="grayLabel"> (OPTIONAL! Max 2000 characters. Use Ctrl+Shift+V for safe pasting)</span>
					</label>
					<textarea class="summernote" name="description" id="description"></textarea>
				</div>

				<div class="mb-5 mt-2">
					<label class="inputLabelMedium">Lesson Instruction
						<span class="grayLabel"> (OPTIONAL! Max 2000 characters. Use Ctrl+Shift+V for safe pasting)</span>
					</label>
					<textarea class="summernote" name="instruction" id="instruction"></textarea>
				</div>

				<div class="mb-5 mt-2">
					<label for="feature_image" class="inputLabelMedium">Lesson Thumbnail<span
							class="text-sm text-gray-400 font-light"> (Max 2 MB)</span></label>
					<input type="file" id="imageDropify" name="feature_image" class="dropify" data-height="150" />
					<input type="hidden" name="remove_image" id="remove_image" value="0">
				</div>
				<!-- -->
				<div class="my-5">
					<label for="video_gallery" class="inputLabelMedium">Lesson Video Gallery
						<span class="text-sm text-gray-400 font-light"> (Multiple allowed) (Max 1 GB each) (Only one file will
							be previewed)</span>
					</label>
					<input type="file" name="video_gallery[]" class="p-10 filepond border" data-height="150" multiple />

					<div id="filepond-error"></div>
				</div>

				<div class="mb-5 mt-2">
					<label class="inputLabelMedium">Status</label>
					<select name="status" class="dropdownSelectInputs">
						<option value="1" selected>Active</option>
						<option value="0">Inactive</option>
					</select>
				</div>
			</div>

			<button type="submit" class="saveButton my-4">Create Lesson</button>
		</form>
	</x-ui_items.modals.form-modal>
</section>