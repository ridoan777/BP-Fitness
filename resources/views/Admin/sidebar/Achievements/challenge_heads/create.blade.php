<section>
	<div class="px-6 flex justify-start">
		<button type="button" data-modal-target="hs-form-modal" data-modal-toggle="hs-form-modal"
			class="darkButton">{!! \App\Helpers\IconPack::plus(['class' => 'iconPackItem w-3 h-3 ']) !!} Create
		</button>
	</div>

	<x-ui_items.modals.form-modal id="hs-form-modal" title="Create Challenge Head" cancelText="Cancel" message="">
		<form method="POST" action="{{ route('backend_achievement_head_store') }}" class="rounded-lg p-2 dark:bg-neutral-700"
			enctype="multipart/form-data">
			@csrf

			<input type="hidden" name="flag" class="smallInput" value="create">

			<div class="mb-5 mt-2">
				<label for="title" class="inputLabelMedium">Title
					<span class="text-red-500">*</span>
				</label>
				<input type="text" name="title" class="mediumInput" placeholder="max 255-characters"
					value="{{ old('title') }}">
			</div>

			<div class="mb-5 mt-2">
				<label for="type" class="inputLabelMedium">Type
					<span class="text-red-500">*</span>
				</label>
				<select name="type" class="dropdownSelectInputs">
					@foreach (\App\Domains\Achievements\Enums\AchievementTypeEnums::cases() as $type)
						<option value="{{ $type->value }}" @selected(old('type', \App\Domains\Achievements\Enums\AchievementTypeEnums::default()) === $type->value)>
							{{ $type->label() }}
						</option>
					@endforeach
				</select>
			</div>

			<div class="mb-5 mt-2">
				<label for="required_value" class="inputLabelMedium">Required Value
					<span class="text-red-500">*</span>
					<span class="grayLabel">(value needed for this challenge, like value=3 for 3-day streak, value=25 for 25 message target, value=30 for $30 purchase)</span>
				</label>
				<input type="number" name="required_value" class="mediumInput" min="0" step="1"
					placeholder="0" value="{{ old('required_value') }}">
			</div>

			<div class="mb-5 mt-2">
				<label for="points" class="inputLabelMedium">Reward Points
					<span class="grayLabel">(how much points users will get if completed)</span>
				</label>
				<input type="number" name="points" class="mediumInput" step="1"
					placeholder="optional" value="{{ old('points') }}">
			</div>

			<div class="mb-5 mt-2">
				<label for="status" class="inputLabelMedium">Active Status</label>
				<select name="status" class="dropdownSelectInputs">
					<option value="1" @selected(old('status', 1) == 1)>Active</option>
					<option value="0" @selected(old('status') === '0')>Inactive</option>
				</select>
			</div>

			<button type="submit" class="saveButton my-4">Create Challenge Head</button>
		</form>
	</x-ui_items.modals.form-modal>
</section>
