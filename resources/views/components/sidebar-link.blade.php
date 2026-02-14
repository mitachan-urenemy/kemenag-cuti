@props(['active', 'href', 'icon'])

@php
    $classes = $active ?? false
        ? 'group flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 bg-white text-green-primary shadow-lg'
        : 'group flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 text-white hover:bg-white/10 hover:translate-x-1';

    $iconClasses = $active ?? false
        ? 'h-5 w-5 flex-shrink-0 text-green-primary'
        : 'h-5 w-5 flex-shrink-0 text-white/80';
@endphp

<a href="{{ $href }}"
   :class="sidebarOpen ? 'justify-start' : 'justify-center'"
   title="{{ $slot }}"
   {{ $attributes->merge(['class' => $classes]) }}>

    <x-dynamic-component :component="'lucide-'.$icon" class="{{ $iconClasses }}" />

    <span
        x-show="sidebarOpen"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 transform -translate-x-2"
        x-transition:enter-end="opacity-100 transform translate-x-0"
        class="ml-3 truncate"
    >
        {{ $slot }}
    </span>

    @if($active ?? false)
        <x-lucide-chevron-right
            x-show="sidebarOpen"
            class="w-4 h-4 ml-auto text-green-primary"
        />
    @endif
</a>
