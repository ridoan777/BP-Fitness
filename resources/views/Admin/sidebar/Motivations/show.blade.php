<div class="p-4">
    <form method="POST" action="{{ route('backend_motivation_store') }}" class="rounded-lg p-2 dark:bg-neutral-700"
        enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="flag" class="smallInput" value="update">
        <input type="hidden" name="id" class="smallInput" value="{{ $motivation?->id }}">

        <div>
            <div class="mb-5 mt-2">
                <label for="title" class="inputLabelMedium">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" class="mediumInput" placeholder="max 255-characters"
                    value="{{ old('title', $motivation?->title) }}">
            </div>

            <div class="mb-5 mt-2">
                <label for="description" class="inputLabelMedium">Description <span
                        class="text-sm text-gray-400 font-light"> (Max 2000 characters. Use Ctrl+Shift+V for safe
                        pasting)</span>
                </label>
                <textarea class="summernote" name="description" id="description">{{ old('description', $motivation?->description) }}</textarea>
            </div>

            <div class="mb-5 mt-2">
                <label for="status" class="inputLabelMedium">Active Status</label>
                <select name="status" class="dropdownSelectInputs">
                    <option value="1" {{ old('status', $motivation?->status) == 1 ? 'selected' : '' }}>Active
                    </option>
                    <option value="0" {{ old('status', $motivation?->status) == 0 ? 'selected' : '' }}>Inactive
                    </option>
                </select>
            </div>
        </div>

        <button type="submit" class="saveButton my-4">Update Motivation</button>
    </form>
</div>

<style>
    td {
        text-align: center;
    }
</style>
