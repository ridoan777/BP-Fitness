<section>
	<div class="px-6 flex justify-start">
		<button type="button" data-modal-target="hs-form-modal" data-modal-toggle="hs-form-modal"
			class="darkButton">{!! \App\Helpers\IconPack::plus(['class' => 'iconPackItem w-3 h-3 ']) !!} Create
		</button>
	</div>

	<x-ui_items.modals.form-modal id="hs-form-modal" title="Create Achievement Badge" cancelText="Cancel" message="">
		<form method="POST" action="{{ route('backend_achievement_badge_store') }}" class="rounded-lg p-4 dark:bg-neutral-800"
			enctype="multipart/form-data">
			@csrf

			<input type="hidden" name="flag" class="smallInput" value="create">
			<input type="hidden" name="remove_image" id="remove_image" value="0">

			<div class="mb-5 mt-2">
				<label for="name" class="inputLabelMedium">Badge Name
					<span class="text-red-500">*</span>
					<span class="grayLabel">(e.g., Bronze, Gold, etc.)</span>
				</label>
				<input type="text" name="name" class="mediumInput" placeholder="max 255-characters"
					value="{{ old('name') }}">
			</div>

			<div class="mb-5 mt-2">
				<label for="points_required" class="inputLabelMedium">Points required to earn this
					<span class="text-red-500">*</span>
					<span class="grayLabel">(Integer number only)</span>
				</label>
				<input type="number" name="points_required" class="mediumInput" min="0" step="1"
					placeholder="0" value="{{ old('points_required') }}">
			</div>

			<div class="mb-5 mt-2">
				<label for="image" class="inputLabelMedium">Badge Image
					<span class="text-sm text-gray-400 font-light">(Max 2 MB)</span>
				</label>
				<input type="file" id="imageDropify" name="image" class="dropify" data-height="150" accept="image/*" />
			</div>

			<div class="mb-5 mt-2">
				<label for="status" class="inputLabelMedium">Active Status</label>
				<select name="status" class="dropdownSelectInputs">
					<option value="1" @selected(old('status', 1) == 1)>Active</option>
					<option value="0" @selected(old('status') === '0')>Inactive</option>
				</select>
			</div>

			<button type="submit" class="saveButton my-4">Create Badge</button>
		</form>
	</x-ui_items.modals.form-modal>
</section>
