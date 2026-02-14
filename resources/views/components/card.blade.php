@props([
    'icon' => null, // Expects a lucide icon name e.g., 'users', 'file-text'
    'title',
    'description' => null,
    'href' => null
])

@php
    $tag = $href ? 'a' : 'div';
@endphp

<{{ $tag }}
    @if($href) href="{{ $href }}" @endif
    {{ $attributes->merge(['class' => 'group relative block bg-white border border-gray-200/80 rounded-2xl shadow-sm hover:border-indigo-300 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden']) }}
>
    <div class="p-6">
        <div class="flex items-start gap-5">
            @if($icon)
            <div class="flex-shrink-0">
                <div class="w-14 h-14 bg-gray-100 group-hover:bg-indigo-100 rounded-xl flex items-center justify-center transition-all duration-300">
                    {{-- Dynamically render the lucide icon component --}}
                    <x-dynamic-component :component="'lucide-' . $icon" class="w-7 h-7 text-gray-500 group-hover:text-indigo-600 transition-colors duration-300" />
                </div>
            </div>
            @endif

            <div class="flex-1">
                <h3 class="text-lg font-bold text-gray-900">
                    {{ $title }}
                </h3>

                @if($description)
                <p class="mt-1 text-sm text-gray-600">
                    {{ $description }}
                </p>
                @endif
            </div>

            @if($href)
                <div class="flex-shrink-0 self-center">
                     <x-lucide-arrow-right class="w-5 h-5 text-gray-400 group-hover:text-indigo-500 transition-transform duration-300 group-hover:translate-x-1" />
                </div>
            @endif
        </div>

        @if(isset($slot) && !$slot->isEmpty())
        <div class="mt-4">
            {{ $slot }}
        </div>
        @endif
    </div>

    {{-- Decorative bottom border --}}
    <div class="absolute bottom-0 left-0 h-1 w-full bg-indigo-500 scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
</{{ $tag }}>
