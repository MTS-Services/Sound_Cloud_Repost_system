@props([
    'name',
    'label' => '',
    'options' => [],
    'selected' => null,
    'messages' => [],
    'placeholder' => null,
])

@if (!empty($label))
    <p class="label">{{ $label }}</p>
@endif
<select name="{{ $name }}"
    class="input select select2 focus:outline-0 focus-within:outline-0 focus:ring-0 focus:border-border-active focus-within:border-border-active w-full"
    {{ $attributes->except(['class', 'name', 'label', 'options', 'selected', 'messages', 'placeholder']) }}>
    @if ($placeholder)
        <option value="" disabled selected>{{ $placeholder }}</option>
    @endif
    @foreach ($options as $value => $text)
        @php
            $optionValue = is_array($text) ? $text['id'] ?? $value : $value;
            $optionText = is_array($text) ? $text['name'] ?? $text : $text;
        @endphp
        <option value="{{ $optionValue }}" @selected($optionValue == old($name, $selected))>
            {{ $optionText }}
        </option>
    @endforeach
</select>
<x-input-error class="mt-2" :messages="$messages" />
