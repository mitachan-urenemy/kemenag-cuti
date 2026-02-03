@props([
    'title',
    'name',
    'note' => '',
    'required' => false,
    'value' => '1', // Default value for a single checkbox
    'checked' => false
])

<div class="mb-4">
    <div class="flex items-start">
        <div class="flex h-5 items-center">
            <input
                id="{{ $name }}"
                name="{{ $name }}"
                type="checkbox"
                value="{{ $value }}"
                @if($checked) checked @endif
                {{ $attributes->merge(['class' => 'h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500']) }}
            >
        </div>
        <div class="ml-3 text-sm">
            {{-- Label --}}
            <label for="{{ $name }}" class="font-medium text-gray-700">
                {{ $title }}
                @if($required)
                    <span class="text-red-500">*</span>
                @endif
            </label>
            {{-- Note --}}
            @if($note)
                <p class="text-gray-500">{{ $note }}</p>
            @endif
        </div>
    </div>

    {{-- Error --}}
    @error($name)
        <p class="mt-2 text-sm text-red-600">{{ ucfirst($message) }}</p>
    @enderror
</div>
