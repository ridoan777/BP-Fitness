<section>
	<div class="px-6 flex justify-end">
		<button type="button" data-modal-target="hs-form-modal" data-modal-toggle="hs-form-modal"
			class="darkButton">{!! \App\Helpers\IconPack::plus(['class' => 'iconPackItem w-3 h-3 ']) !!} Create
		</button>
	</div>
	<!--  -->

	<x-ui_items.modals.form-modal id="hs-form-modal" title="Create A Workout Course" cancelText="Cancel"
		modalWidth="w-6xl">
		<form method="POST" action="{{ route('backend_workout_course_store') }}"
			class="rounded-lg p-2 dark:bg-neutral-700" enctype="multipart/form-data">
			@csrf
			<input type="hidden" name="flag" class="smallInput" value="create">

			<div class="">

				<div class="grid grid-cols-3 gap-4">
					<div class="col-span-1">
						<label class="inputLabelMedium">Select Category <span class="text-red-500">*</span></label>
						<select name="category_id" class="dropdownSelectInputs" required>
							<option value="">-- Choose Category --</option>
							@foreach($categories as $category)
								<option value="{{ $category->id }}">[{{ $category->id }}]-{{ $category->name }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-span-2">
						<label class="inputLabelMedium">Course ID
							<span class="text-red-500 text-sm">(Must be unique, no space)</span>
							<span class="grayLabel">(Better keep it blank. Then an UID will be generated automatically.)</span>
						</label>
						<input type="text" name="course_uid" class="normalInput" placeholder="UID is a unique identifier. Cannot be changed later. 32-chars max">
					</div>

				</div>

				<div class="mb-5 mt-2">
					<label class="inputLabelMedium">Course Title <span class="text-red-500">*</span></label>
					<input type="text" name="title" class="mediumInput" placeholder="max 255 characters" required>
				</div>

				<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

					<div class="mb-5 mt-2">
						<label class="inputLabelMedium">Course Duration
							<span class="grayLabel">(e.g. 4 weeks, 10 days, etc.)</span>
						</label>
						<input type="text" name="course_duration" class="normalInput" placeholder="Max 255 characters">
					</div>

					<div class="mb-5 mt-2">
						<label class="inputLabelMedium">Is This Course Premium?</label>
						<select name="is_premium" class="dropdownSelectInputs">
							<option value="1" selected>Yes</option>
							<option value="0">No (Anyone can access this)</option>
						</select>
					</div>

				</div>

				<div class="mb-5 mt-2">
					<label class="inputLabelMedium">Description</label>
					<textarea class="summernote" name="description" id="description"></textarea>
				</div>

				<div class="mb-5 mt-2">
					<label for="feature_image" class="inputLabelMedium">Feature Image<span
							class="text-sm text-gray-400 font-light"> (Max 2 MB)</span></label>
					<input type="file" id="imageDropify" name="feature_image" class="dropify" data-height="150" />
					<input type="hidden" name="remove_image" id="remove_image" value="0">
				</div>
				<!-- -->
				{{-- <div class="my-5">
					<label for="video_gallery" class="inputLabelMedium">Gallery
						<span class="text-sm text-gray-400 font-light"> (Multiple allowed) (Max 1 GB each) (Only one file will
							be previewed)</span>
					</label>
					<input type="file" name="video_gallery[]" class="p-10 filepond border" data-height="150" multiple />

					<div id="filepond-error"></div>
				</div> --}}

				<div class="mb-5 mt-2">
					<label class="inputLabelMedium">Status</label>
					<select name="status" class="dropdownSelectInputs">
						<option value="1" selected>Active</option>
						<option value="0">Inactive</option>
					</select>
				</div>

			</div>

			<button type="submit" class="saveButton my-4">Create Course</button>
		</form>
	</x-ui_items.modals.form-modal>
</section>