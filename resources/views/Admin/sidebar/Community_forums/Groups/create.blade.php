<section>
    <div class="px-6 flex justify-end">
        <button type="button" data-modal-target="hs-form-modal" data-modal-toggle="hs-form-modal"
            class="darkButton">{!! \App\Helpers\IconPack::plus(['class' => 'iconPackItem w-3 h-3 ']) !!} Create
        </button>
    </div>
    <!--  -->

    <x-ui_items.modals.form-modal id="hs-form-modal" title="Create A Forum Group" cancelText="Cancel" modalWidth="w-6xl">

        <form method="POST" action="{{ route('backend_forum_group_store') }}" class="rounded-lg p-4 dark:bg-neutral-800"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="flag" class="smallInput" value="create">

            <div class="grid grid-cols-1 gap-4">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="inputLabelMedium">Select Category <span class="text-red-500">*</span></label>
                        <select name="category_id" class="dropdownSelectInputs" required>
                            <option value="">-- Choose Category --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">[{{ $category->id }}]-{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="inputLabelMedium">Is Premium Group?</label>
                        <select name="is_premium" class="dropdownSelectInputs">
                            <option value="1">Yes</option>
                            <option value="0" selected>No (Public Access)</option>
                        </select>
                    </div>
                </div>

                <div class="mb-5 mt-2">
                    <label class="inputLabelMedium">Group Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" class="mediumInput"
                        placeholder="Enter forum group name (max 255 characters)" required>
                </div>

                <div class="mb-5 mt-2">
                    <label for="description" class="inputLabelMedium">Description <span
                            class="text-sm text-gray-400 font-light"> (Max 2000 characters. Use Ctrl+Shift+V for safe
                            pasting)</span></label>
                    <textarea class="summernote" name="description" id="description"></textarea>
                </div>

                <div class="mb-5 mt-2">
                    <label class="inputLabelMedium"> Feature Image <span class="text-sm text-gray-400 font-light">(Max 2
                            MB | Allowed formats: jpeg,png,jpg,webp | Best orientation: Landscape)</span></label>
                    <input type="file" id="imageDropify" name="feature_image" class="dropify" data-height="150" />
                    <input type="hidden" name="remove_image" id="remove_image" value="0">
                </div>

                <div class="mb-5 mt-2">
                    <label class="inputLabelMedium">Status</label>
                    <select name="status" class="dropdownSelectInputs">
                        <option value="1" selected>Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>

            </div>

            <button type="submit" class="saveButton my-4">Create Group</button>
        </form>

    </x-ui_items.modals.form-modal>
</section>
