@php
    $user = $message->messageRelatonWith_Creator;
    $avatar = \App\Helpers\Ui\GetUrlSafeFiles::alignAvatar($user, true);
    $reply = $message->replyRelatingBackTo_Message;
@endphp

@php
    $usernameColor = match ($user?->gender) {
        'male' => 'text-blue-500',
        'female' => 'text-pink-500',
        default => 'text-gray-500 dark:text-neutral-400',
    };
@endphp

<div id="message-{{ $message->id }}" class="message-row flex justify-start" data-message-id="{{ $message->id }}">

    <div class="max-w-[85%] flex gap-3">

        <img src="{{ $avatar }}" class="w-10 h-10 rounded-full border shrink-0">

        <div>

            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-sm font-semibold dark:text-neutral-200">{{ $user?->name }}</span>
                    <span class="text-xs {{ $usernameColor }}">{{ '@' . $user?->username }}</span>
                    <span
                        class="text-2xs text-gray-400 dark:text-neutral-500">{{ $message->created_at?->diffForHumans() }}</span>
                    <span
                        class="text-2xs text-gray-300 dark:text-neutral-600">({{ $message->created_at?->format('D-M-Y h:i:s A') }})</span>
                </div>
                <div class="flex items-center gap-2 mb-1 italic">
                    <span class="text-2xs text-gray-400 dark:text-neutral-400">DB: [{{ $message?->id }}]</span>
                    <span class="text-2xs text-gray-400 dark:text-neutral-400">{{ $message?->message_uid }}</span>
                </div>

            </div>

            <div class="bg-gray-100 dark:bg-neutral-800 rounded-2xl px-4 py-3 shadow">

                @if ($reply)
                    <a href="#message-{{ $reply->id }}"
                        class="block mb-2 p-2 bg-gray-200 dark:bg-neutral-700 rounded appBorder">
                        <p class="text-xs font-semibold text-gray-500 dark:text-neutral-400">Replying To</p>
                        <p class="text-xs dark:text-neutral-300">
                            {{ \Illuminate\Support\Str::limit($reply->message, 100) }}</p>
                    </a>
                @endif

                @if ($message->message)
                    <div class="flex_between_center">
                        <p class="whitespace-pre-wrap dark:text-neutral-200">{{ $message->message }}</p>

                        <x-ui_items.dropdowns.dropdown :id="$message->id" :items="[
                            ['type' => 'redirection', 'label' => 'Report', 'route' => route('backend_forum_group_report', $message->id), 'icon' => 'userCancel'],
                            ['type' => 'delete', 'label' => 'Delete', 'route' => route('backend_forum_message_delete', $message->id), 'icon' => 'trash'],
                        ]" />

                    </div>
                @endif

                @include('Admin.sidebar.Community_forums.Groups.partials.gallery_generate', [
                    'message' => $message,
                ])

            </div>

        </div>

    </div>

</div>