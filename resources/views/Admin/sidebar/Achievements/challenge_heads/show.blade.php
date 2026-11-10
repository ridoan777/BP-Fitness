<div class="p-4">
	<form method="POST" action="{{ route('backend_achievement_head_store') }}" class="rounded-lg p-2 dark:bg-neutral-700"
		enctype="multipart/form-data">
		@csrf

		<input type="hidden" name="flag" class="smallInput" value="update">
		<input type="hidden" name="id" class="smallInput" value="{{ $singleHead?->id }}">

		<div class="mb-5 mt-2">
			<label for="title" class="inputLabelMedium">Title
				<span class="text-red-500">*</span>
			</label>
			<input type="text" name="title" class="mediumInput" placeholder="max 255-characters"
				value="{{ old('title', $singleHead?->title) }}">
		</div>

		<div class="mb-5 mt-2">
			<label for="type" class="inputLabelMedium">Type
				<span class="text-red-500">*</span>
			</label>
			<select name="type" class="dropdownSelectInputs">
				@foreach (\App\Domains\Achievements\Enums\AchievementTypeEnums::cases() as $type)
					<option value="{{ $type->value }}" @selected(old('type', $singleHead?->type?->value ?? $singleHead?->type) === $type->value)>
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
				placeholder="0" value="{{ old('required_value', $singleHead?->required_value) }}">
		</div>

		<div class="mb-5 mt-2">
			<label for="points" class="inputLabelMedium">Reward Points
				<span class="grayLabel">(how much points users will get if completed)</span>
			</label>
			<input type="number" name="points" class="mediumInput" step="1"
				placeholder="optional" value="{{ old('points', $singleHead?->points) }}">
		</div>

		<div class="mb-5 mt-2">
			<label for="status" class="inputLabelMedium">Active Status</label>
			<select name="status" class="dropdownSelectInputs">
				<option value="1" @selected(old('status', $singleHead?->status) == 1)>Active</option>
				<option value="0" @selected(old('status', $singleHead?->status) === 0 || old('status', $singleHead?->status) === '0')>Inactive</option>
			</select>
		</div>

		<button type="submit" class="saveButton my-4">Update Challenge Head</button>
	</form>
</div>
