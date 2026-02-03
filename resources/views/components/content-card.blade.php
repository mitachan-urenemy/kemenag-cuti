@props([
    'title' => null,
    'subtitle' => null,
    'icon' => null,
    'action' => null,
    'footer' => null,
    'padding' => true,
])

{{-- Card Container --}}
<div {{ $attributes->merge(['class' => 'w-full h-full flex flex-col bg-white border border-gray-200/60 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] rounded-xl overflow-hidden transition-all duration-300 hover:shadow-[0_10px_15px_-3px_rgba(0,0,0,0.05)]']) }}>

    {{-- Header Section --}}
    @if ($title || $icon || isset($action))
        <div class="px-4 py-4 border-b border-gray-100 flex-shrink-0 sm:px-6 sm:py-5 bg-gray-50/30">
            <div class="flex flex-wrap items-center justify-between gap-3 sm:gap-4">

                {{-- Title, Subtitle, and Icon --}}
                <div class="flex items-center gap-3 sm:gap-4 min-w-0"> {{-- min-w-0 agar text panjang truncated --}}
                    @if($icon)
                        <div class="flex-shrink-0 w-10 h-10 sm:w-11 sm:h-11 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center ring-1 ring-indigo-100">
                            <x-dynamic-component :component="'lucide-' . $icon" class="w-5 h-5 sm:w-6 sm:h-6" />
                        </div>
                    @endif

                    <div class="min-w-0">
                        @if($title)
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 truncate">{{ $title }}</h3>
                        @endif
                        @if($subtitle)
                            <p class="text-xs sm:text-sm text-gray-500 mt-0.5 sm:mt-1 leading-relaxed">{{ $subtitle }}</p>
                        @endif
                    </div>
                </div>

                {{-- Action Slot --}}
                @if (isset($action))
                    <div class="flex-shrink-0 ml-auto">
                        {{ $action }}
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- Body Section (Main Slot) --}}
    <div @class([
        'flex-grow overflow-y-auto',
        'p-4 sm:p-6' => $padding
    ])>
        {{ $slot }}
    </div>

    {{-- Footer Section --}}
    @if (isset($footer))
        <div class="px-4 py-3 bg-gray-50/80 border-t border-gray-100 flex-shrink-0 sm:px-6 sm:py-4">
            {{ $footer }}
        </div>
    @endif
</div>
