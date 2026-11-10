<div class="p-4">
	<form method="POST" action="{{ route('backend_achievement_badge_store') }}"
		class="rounded-lg p-4 dark:bg-neutral-800" enctype="multipart/form-data">
		@csrf

		<input type="hidden" name="flag" class="smallInput" value="update">
		<input type="hidden" name="id" class="smallInput" value="{{ $singleBadge?->id }}">
		<input type="hidden" name="remove_image" id="remove_image" value="0">

		<div class="mb-5 mt-2">
			<label for="name" class="inputLabelMedium">Badge Name
				<span class="text-red-500">*</span>
			</label>
			<input type="text" name="name" class="mediumInput" placeholder="max 255-characters"
				value="{{ old('name', $singleBadge?->name) }}">
		</div>

		<div class="mb-5 mt-2">
			<label for="points_required" class="inputLabelMedium">Points required to earn this
				<span class="text-red-500">*</span>
			</label>
			<input type="number" name="points_required" class="mediumInput" min="0" step="1" placeholder="0"
				value="{{ old('points_required', $singleBadge?->points_required) }}">
		</div>

		<div class="mb-5 mt-2">
			<label for="image" class="inputLabelMedium">Badge Image
				<span class="text-sm text-gray-400 font-light">(Max 2 MB)</span>
			</label>
			<input type="file" id="showPageDropify" name="image" class="dropify" data-height="150" @if ($singleBadge?->image) data-default-file="{{ Storage::url($singleBadge->image) }}" @endif />
		</div>

		<div class="mb-5 mt-2">
			<label for="status" class="inputLabelMedium">Active Status</label>
			<select name="status" class="dropdownSelectInputs">
				<option value="1" @selected(old('status', $singleBadge?->status) == 1)>Active</option>
				<option value="0" @selected(old('status', $singleBadge?->status) === 0 || old('status', $singleBadge?->status) === '0')>Inactive</option>
			</select>
		</div>

		<button type="submit" class="showButton my-4">Update</button>
	</form>
</div>
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