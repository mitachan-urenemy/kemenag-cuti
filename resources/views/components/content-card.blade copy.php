@props([
    'title' => null,
    'subtitle' => null,
    'icon' => null, // Expects lucide icon name
    'action' => null,
    'footer' => null,
    'padding' => true,
])

<div {{ $attributes->merge(['class' => 'bg-white border border-gray-200/80 shadow-sm rounded-xl overflow-hidden']) }}>
    {{-- Header Section --}}
    @if ($title || $icon || isset($action))
    <div class="px-6 py-5 border-b border-gray-200/80">
        <div class="flex flex-wrap items-center justify-between gap-4">
            {{-- Title, Subtitle, and Icon --}}
            <div class="flex items-center gap-4">
                @if($icon)
                    <div class="flex-shrink-0 w-11 h-11 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center">
                        <x-dynamic-component :component="'lucide-' . $icon" class="w-6 h-6" />
                    </div>
                @endif
                <div>
                    @if($title)
                        <h3 class="text-lg font-bold text-gray-800">{{ $title }}</h3>
                    @endif
                    @if($subtitle)
                        <p class="text-sm text-gray-500 mt-1">{{ $subtitle }}</p>
                    @endif
                </div>
            </div>

            {{-- Action Slot --}}
            @if (isset($action))
                <div class="flex-shrink-0">
                    {{ $action }}
                </div>
            @endif
        </div>
    </div>
    @endif

    {{-- Body Section (Main Slot) --}}
    <div @class(['p-6' => $padding])>
        {{ $slot }}
    </div>

    {{-- Footer Section --}}
    @if (isset($footer))
        <div class="px-6 py-4 bg-gray-50/70 border-t border-gray-200/80">
            {{ $footer }}
        </div>
    @endif
</div>
