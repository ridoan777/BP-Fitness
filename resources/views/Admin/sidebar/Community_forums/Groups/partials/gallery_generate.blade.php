@if($message->messageRelatonWith_Galleries->count())
   <div class="grid grid-cols-2 gap-2 mt-3">

      @if($message->messageRelatonWith_Galleries->count())
         <div class="col-span-2 grid grid-cols-3 gap-2 mt-3">

            @foreach($message->messageRelatonWith_Galleries as $gallery)

               @php
                  $meta = $gallery->metadata ?? [];
                  $type = $meta['mime'] ?? '';
                  $flags = $meta['mimetype'] ?? [];
               @endphp

               {{-- IMAGE --}}
               @if(($flags['is_image'] ?? false) || str_starts_with($type, 'image/'))

                  <a href="{{ $gallery->file_url }}" target="_blank">
                     <img src="{{ $gallery->file_url }}" class="rounded-lg w-full max-h-60 object-cover">
                  </a>

                  {{-- VIDEO --}}
               @elseif(($flags['is_video'] ?? false) || str_starts_with($type, 'video/'))

                  <video controls class="w-full rounded-lg">
                     <source src="{{ $gallery->file_url }}">
                  </video>

                  {{-- AUDIO --}}
               @elseif(($flags['is_audio'] ?? false) || str_starts_with($type, 'audio/'))

                  <audio controls class="w-full">
                     <source src="{{ $gallery->file_url }}">
                  </audio>

                  {{-- FILES (pdf, doc, zip, etc.) --}}
               @else

                  <a href="{{ $gallery->file_url }}" target="_blank"
                     class="flex items-center gap-2 rounded-lg p-3 text-xs dark:text-gray-200 bg-gray-200 dark:bg-neutral-800">

                     📎 {{ $meta['original_name'] ?? $gallery->filename }}
                     <span class="text-gray-400">({{ $meta['extension'] ?? '' }})</span>

                  </a>

               @endif

            @endforeach

         </div>
      @endif

   </div>
@endif