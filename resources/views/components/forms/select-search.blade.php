@props([
    'title' => '',
    'name',
    'placeholder' => 'Pilih salah satu...',
    'options' => [],
    'selected' => null,
    'required' => false,
    'note' => '',
])

<div class="w-full">
    @if($title)
        <label for="{{ $name }}" class="block mb-2 text-sm font-medium text-gray-700">
            {{ $title }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div
        wire:ignore
        x-data="{
            selected: @js($selected),
            options: @js(collect($options)->map(fn($label, $value) => ['value' => $value, 'label' => $label])->values()->all()),
            init() {
                const tomselect = new window.TomSelect(this.$refs.select, {
                    items: this.selected ? [this.selected] : [],
                    options: this.options,
                    valueField: 'value',
                    labelField: 'label',
                    searchField: 'label',
                    placeholder: '{{ $placeholder }}',
                    create: false,
                    // Optional: customize rendering to show more info if needed
                    // render: {
                    //     option: function(data, escape) {
                    //         return '<div>' + escape(data.label) + '</div>';
                    //     },
                    //     item: function(item, escape) {
                    //         return '<div>' + escape(item.label) + '</div>';
                    //     }
                    // }
                });
            }
        }"
    >
        <select
            x-ref="select"
            id="{{ $name }}"
            name="{{ $name }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'tom-select']) }}
        ></select>
    </div>

    @if($note)
        <p class="mt-2 text-xs text-gray-500">{{ $note }}</p>
    @endif

    @error($name)
        <p class="mt-2 text-xs text-red-600">{{ ucfirst($message) }}</p>
    @enderror
</div>
