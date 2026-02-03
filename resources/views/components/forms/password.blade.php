@props([
    'title',
    'name',
    'placeholder' => '',
    'note' => '',
    'required' => false,
    'srOnlyTitle' => false,
])

<div class="mb-4">
    {{-- Label --}}
    <label for="{{ $name }}" @class([
        'block text-sm font-medium text-gray-700',
        'sr-only' => $srOnlyTitle,
    ])>
        {{ $title }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>

    {{-- Input with Show/Hide Toggle --}}
    <div
        x-data="{ show: false }"
        @class(['relative', 'mt-1' => !$srOnlyTitle])
    >
        <input
            :type="show ? 'text' : 'password'"
            id="{{ $name }}"
            name="{{ $name }}"
            placeholder="{{ $placeholder }}"
            {{ $attributes->merge(['class' => 'block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm pr-10']) }}
        >

        <button
            type="button"
            @click="show = !show"
            class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-gray-700 focus:outline-none"
        >
            <template x-if="!show">
                <x-lucide-eye class="w-5 h-5" />
            </template>
            <template x-if="show">
                <x-lucide-eye-off class="w-5 h-5" />
            </template>
        </button>
    </div>


    {{-- Note --}}
    @if($note)
        <p class="mt-2 text-sm text-gray-500">{{ $note }}</p>
    @endif

    {{-- Error --}}
    @error($name)
        <p class="mt-2 text-sm text-red-600">{{ ucfirst($message) }}</p>
    @enderror
</div>
