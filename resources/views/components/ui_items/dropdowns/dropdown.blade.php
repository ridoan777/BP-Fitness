@props([
    'items' => [],
])

<!-- Three-Dots -->
<div class="navbarDropdownDiv hs-dropdown">
    <button type="button" class="navbarDropdownButton">
        {!! \App\Helpers\IconPack::dotsVertical(['class' => 'w-5 h-5 text-gray-500']) !!}
    </button>

    <div class="hs-dropdown-menu hs-dropdown-open:opacity-100 duration z-11 hidden w-32 rounded-xl border border-gray-200 bg-white opacity-0 shadow-lg transition-[opacity,margin]
        before:absolute before:-top-4 before:inset-s- before:h-5 before:w-full
        dark:border-neutral-700 dark:bg-neutral-950"
        role="menu">
        <div class="space-y-0.5 p-1">

            @foreach ($items as $item)
                <!------------- REDIRECTION ------------->
                @if (!empty($item['type']) && ($item['type'] === 'link' || $item['type'] === 'redirection'))
                    <a href="{{ $item['route'] }}" class="navbarDropdownMenuButton">

                        @if (!empty($item['icon']))
                            {!! \App\Helpers\IconPack::{$item['icon']}(['class' => 'iconPackItem w-3 h-3 text-green-500']) !!}
                        @endif

                        {{ $item['label'] }}
                    </a>

                    <!------------- DELETE ------------->
                @elseif(!empty($item['type']) && $item['type'] === 'delete')
                    <form action="{{ $item['route'] }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this item?')">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            class="focus:outline-hidden flex w-full items-center gap-x-3 rounded-lg px-2 py-1.5 text-sm text-red-600 hover:bg-red-50 dark:text-red-500 dark:hover:bg-red-500/20">

                            @if (!empty($item['icon']))
                                {!! \App\Helpers\IconPack::{$item['icon']}(['class' => 'iconPackItem w-3 h-3 text-red-500']) !!}
                            @endif

                            {{ $item['label'] }}
                        </button>
                    </form>

                    <!------------- FORM POSTS ------------->
					 @elseif(!empty($item['type']) && $item['type'] === 'post')
                    <form action="{{ $item['route'] }}" method="POST">
                        @csrf
                        <button type="submit" class="navbarDropdownMenuButton">
                            @if (!empty($item['icon']))
                                {!! \App\Helpers\IconPack::{$item['icon']}(['class' => 'iconPackItem w-3 h-3 text-green-500']) !!}
                            @endif

                            {{ $item['label'] }}
                        </button>
                    </form>
                @else
                    <div
                        class="px-2 py-2 rounded-lg bg-gray-50 dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700">

                        <p class="text-xs font-semibold text-gray-700 dark:text-neutral-300 flex gap-4">
									@if (!empty($item['icon']))
										{!! \App\Helpers\IconPack::{$item['icon']}(['class' => 'iconPackItem w-3 h-3 text-green-500']) !!}
									@endif
                            {{ $item['label'] ?? $item['title'] ?? null }}
                        </p>
                        <p class="text-2xs text-gray-500 dark:text-neutral-400 text-center">
                            {{ $item['body'] ?? 'No additional details available.' }}
                        </p>

                    </div>
                @endif
            @endforeach

        </div>
    </div>
</div>

{{-- 
	USE CASE:
		<x-ui_items.dropdowns.dropdown :id="$message->id" :items="[
			['type' => 'redirection', 'label' => 'Report', 'route' => route('backend_forum_group_report', $message->id), 'icon' => 'userCancel'],
			['type' => 'delete', 'label' => 'Delete', 'route' => route('backend_forum_message_delete', $message->id), 'icon' => 'trash'],
			['label' => 'Details', 'body' => 'ID: ' . $message->id, 'icon' => 'layers'],
		]" />

 --}}