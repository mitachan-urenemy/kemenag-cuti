@props([
    'icon' => 'activity',
    'title' => 'Title',
    'value' => '0',
    'color' => 'blue',
    'subtitle' => null,
])

@php
    $bgColors = [
        'blue' => 'bg-blue-50',
        'green' => 'bg-green-50',
        'orange' => 'bg-orange-50',
        'indigo' => 'bg-indigo-50',
        'red' => 'bg-red-50',
        'purple' => 'bg-purple-50',
        'yellow' => 'bg-yellow-50',
        'pink' => 'bg-pink-50',
    ];
    $textColors = [
        'blue' => 'text-blue-600',
        'green' => 'text-green-600',
        'orange' => 'text-orange-600',
        'indigo' => 'text-indigo-600',
        'red' => 'text-red-600',
        'purple' => 'text-purple-600',
        'yellow' => 'text-yellow-600',
        'pink' => 'text-pink-600',
    ];
    $gradientColors = [
        'blue' => 'from-blue-500 to-blue-600',
        'green' => 'from-green-500 to-green-600',
        'orange' => 'from-orange-500 to-orange-600',
        'indigo' => 'from-indigo-500 to-indigo-600',
        'red' => 'from-red-500 to-red-600',
        'purple' => 'from-purple-500 to-purple-600',
        'yellow' => 'from-yellow-500 to-yellow-600',
        'pink' => 'from-pink-500 to-pink-600',
    ];

    $bgClass = $bgColors[$color] ?? 'bg-gray-100';
    $textClass = $textColors[$color] ?? 'text-gray-600';
    $gradientClass = $gradientColors[$color] ?? 'from-gray-500 to-gray-600';
@endphp

<div class="group relative overflow-hidden bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
    <!-- Gradient accent bar di atas -->
    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r {{ $gradientClass }}"></div>

    <!-- Dekorasi lingkaran background -->
    <div class="absolute right-0 top-0 -mr-8 -mt-8 w-32 h-32 rounded-full {{ $bgClass }} opacity-30 group-hover:opacity-50 transition-opacity duration-300"></div>

    <div class="flex items-center justify-between p-6 relative">
        <div class="flex flex-col flex-1">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">{{ $title }}</span>
            <div class="flex items-baseline gap-2">
                <span class="text-4xl font-black text-gray-800 transition-colors duration-300">
                    {{ $value }}
                </span>
                @if($subtitle)
                    <span class="text-xs text-gray-400 font-medium">{{ $subtitle }}</span>
                @endif
            </div>
        </div>
        <div class="p-4 rounded-2xl bg-gradient-to-br {{ $bgClass }} {{ $textClass }} shadow-sm group-hover:shadow-md group-hover:scale-110 transition-all duration-300">
            <x-lucide-{{ $icon }} class="w-8 h-8" />
        </div>
    </div>
</div>
