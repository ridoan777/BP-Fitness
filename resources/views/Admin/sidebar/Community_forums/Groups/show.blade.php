<!----------- Announcement Banner ----------->
<x-ui_items.banners.announcement_banner rolesToShow="admin,super_admin"
    message="⚠️Be Careful! These Forum Group records are connected to Forum Members and Messages. Deleting or modifying will also affect any children records in the relationship tree."
    bgColor="bg-yellow-300" />
<!----------- End Announcement Banner ----------->

@php
    $zones = \App\Helpers\Ui\ConvertDynamicTimezone::handle();
    $creator = $singleGroup?->groupRelatonWith_Creator;
    $lastModifier = $singleGroup?->groupRelatonWith_LastModifier;

    $metaInfo = [
        [
            'label' => 'CREATOR',
            'user' => $creator,
            'date' => $singleGroup?->created_at,
            'dateLabel' => 'Created At',
        ],
        [
            'label' => 'LAST MODIFIER',
            'user' => $lastModifier,
            'date' => $singleGroup?->updated_at,
            'dateLabel' => 'Updated At',
        ],
    ];
@endphp

<section class="mt-4 rounded-lg p-4 dark:bg-neutral-700">

    <div class="mb-12 p-4 grid grid-cols-3 gap-4 border border-gray-400 rounded-lg shadow-lg">
        <h5 class="col-span-3 text-blue-800 dark:text-blue-400 text-center">BASIC INFORMATION</h5>

        <div class="col-span-1 my-1">
            <label class="inputLabelMedium min-w-24 m-0">DB ID:</label>
            <input type="text" class="normalInput" value="{{ $singleGroup?->id ?? null }}" readonly disabled>
        </div>

        <div class="col-span-2 my-1">
            <label class="inputLabelMedium min-w-24 m-0">UID: <span class="grayLabel">(Read-only)</span></label>
            <input type="text" class="normalInput" value="{{ $singleGroup?->group_uid ?? null }}" readonly disabled>
        </div>
    </div>

    <div class="mb-12 p-4 border border-gray-400 rounded-lg shadow-lg">

        <form method="POST" action="{{ route('backend_forum_group_store') }}" enctype="multipart/form-data"
            class="grid grid-cols-1 gap-6">
            @csrf

            <input type="hidden" name="id" value="{{ $singleGroup->id }}">
            <input type="hidden" name="flag" value="update">

            <h5 class="text-blue-800 dark:text-blue-400 text-center uppercase">Group Information</h5>

            <div class="grid grid-cols-2 gap-4 mt-4">

                <div>
                    <label class="inputLabelMedium">Select Category <span class="text-red-500">*</span></label>
                    <select name="category_id" class="dropdownSelectInputs" required>
                        <option value="">-- Choose Category --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ $singleGroup->category_id == $category->id ? 'selected' : '' }}>
                                [{{ $category->id }}]-{{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="inputLabelMedium">Is Premium Group?</label>
                    <select name="is_premium" class="dropdownSelectInputs">
                        <option value="1" {{ $singleGroup->is_premium == 1 ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ $singleGroup->is_premium == 0 ? 'selected' : '' }}>No</option>
                    </select>
                </div>

            </div>

            <div>
                <label class="inputLabelMedium">Group Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" class="mediumInput" value="{{ $singleGroup->name }}" required>
            </div>

            <div>
                <label for="description" class="inputLabelMedium">Description <span
                        class="text-sm text-gray-400 font-light"> (Max 2000 characters. Use Ctrl+Shift+V for safe
                        pasting)</span></label>
                <textarea class="summernote" name="description">{{ $singleGroup->description }}</textarea>
            </div>

            <div>
                <label class="inputLabelMedium"> Feature Image <span class="text-sm text-gray-400 font-light">(Max 2
                        MB | Allowed formats: jpeg,png,jpg,webp | Best orientation: Landscape)</span></label>

                <input type="file" id="showPageDropify" name="feature_image" class="dropify"
                    @if ($singleGroup?->feature_image) data-default-file="{{ Storage::url($singleGroup->feature_image) }}" @endif />

                <input type="hidden" name="remove_image" id="remove_image" value="0">
            </div>

            <div>
                <label class="inputLabelMedium">Status</label>
                <select name="status" class="dropdownSelectInputs">
                    <option value="1" {{ $singleGroup->status == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $singleGroup->status == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="flex_between_center">
                <button type="submit" class="showButton my-4">Update</button>
            </div>

        </form>

        <form action="{{ route('backend_forum_group_delete', $singleGroup->id) }}" method="POST"
            class="p-0 relative flex_end_center">
            @csrf
            @method('DELETE')

            <button type="submit" class="deleteButton"
                onclick="return confirm('Are you sure you want to delete this forum group?')">
                {!! \App\Helpers\IconPack::trash(['class' => 'iconPackItem w-4 h-4 text-gray-100']) !!}
            </button>
        </form>

    </div>

    <div class="mb-5 p-4 grid grid-cols-1 lg:grid-cols-2 gap-6 border border-gray-400 rounded-lg shadow-lg">

        @foreach ($metaInfo as $item)
            <div class="p-4 border border-gray-200 rounded-lg">

                <h6 class="mb-4 font-semibold text-gray-700">{{ $item['label'] }}</h6>

                @if ($item['user'])
                    <div class="flex gap-4">

                        <a href="{{ route('backend_single_user', $item['user']->id) }}" target="_blank"
                            class="w-24 shrink-0">

                            <img class="w-24 h-24 rounded-lg object-cover hover:scale-105 transition"
                                src="{{ $item['user']?->avatar ? Storage::url($item['user']?->avatar) : asset('site_assets/dummies/dummy_man.webp') }}">
                        </a>

                        <div class="flex-1 space-y-3">

                            <input type="text" class="normalInput w-full" value="{{ $item['user']?->name ?? '' }}"
                                readonly disabled>

                            @php
                                $formattedDate = $item['date']
                                    ? $item['date']->timezone($zones['zone'])->format('d-M-Y h:i:s A') .
                                        ' (UTC' .
                                        $zones['offset'] .
                                        ')'
                                    : '';
                            @endphp

                            <input type="text" class="normalInput w-full text-xs" value="{{ $formattedDate }}"
                                readonly disabled>

                        </div>
                    </div>
                @else
                    <div class="w-full h-full flex_xy_center">
                        <h4 class="text-gray-400 text-center">
                            No {{ strtolower($item['label']) }} Yet
                        </h4>
                    </div>
                @endif

            </div>
        @endforeach

    </div>
</section>

<script>
    (function() {
        const el = $('#showPageDropify');
        if (!el.length || typeof $.fn.dropify === 'undefined') return;

        const instance = el.data('dropify');
        if (instance) instance.destroy();

        el.dropify();

        setTimeout(function() {
            el.on('dropify.afterClear', function() {
                $('#remove_image').val('1');
            });
        }, 0);
    })();
</script>
