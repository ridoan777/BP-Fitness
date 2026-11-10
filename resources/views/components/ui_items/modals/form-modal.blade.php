@props([
    'id' => 'popup-modal',
    'title' => 'Confirm Deletion',
    'message' => '',
    'confirmText' => 'Delete',
    'modalWidth' => 'w-5xl',
    'cancelText' => 'Cancel',
    'confirmColor' => 'bg-red-600 hover:bg-red-800 focus:ring-red-300 dark:focus:ring-red-800',
])

<div id="{{ $id }}" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-90 justify-center items-center md:inset-0 max-h-full bg-[#000000A8] dark:bg-[#000000BF] mt-0!">
    <div class="relative {{ $modalWidth }} max-h-[calc(100%-16px)]">
        <div class="relative bg-gray-100 rounded-lg shadow dark:bg-neutral-800">
            <button type="button" data-modal-hide="{{ $id }}"
                class="absolute top-3 inset-e-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="p-5">

                <h2 class="mb-2 text-xl text-center font-semibold text-gray-900 dark:text-white">{{ $title }}
                </h2>
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">{{ $message }}</h3>

                {{ $slot }}

            </div>
        </div>
    </div>
