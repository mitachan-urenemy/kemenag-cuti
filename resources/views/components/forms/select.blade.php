@props([
    'title',
    'name',
    'note' => '',
    'required' => false,
    'options' => [], // Expects an associative array [value => text]
    'selected' => null
])

<div class="mb-4">
    {{-- Label --}}
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">
        {{ $title }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>

    {{-- Select --}}
    <select
        id="{{ $name }}"
        name="{{ $name }}"
        {{ $attributes->merge(['class' => 'mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) }}
    >
        @foreach($options as $value => $text)
            <option value="{{ $value }}" @selected(old($name, $selected) == $value)>
                {{ $text }}
            </option>
        @endforeach
    </select>

    {{-- Note --}}
    @if($note)
        <p class="mt-2 text-sm text-gray-500">{{ $note }}</p>
    @endif

    {{-- Error --}}
    @error($name)
        <p class="mt-2 text-sm text-red-600">{{ ucfirst($message) }}</p>
    @enderror
</div>
