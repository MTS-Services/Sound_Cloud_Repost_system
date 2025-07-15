@props(['name', 'label', 'placeholder' => null, 'value' => null, 'rows' => 3, 'messages' => []])

@if (!empty($label))
    <p class="label">{{ $label }}</p>
@endif
<textarea name="{{ $name }}" placeholder="{{ $placeholder }}" rows="{{ $rows }}"
    class="input textarea focus:outline-0 focus-within:outline-0 focus:ring-0 focus:border-border-active focus-within:border-border-active w-full"
    {{ $attributes->except(['class', 'name', 'placeholder', 'value', 'rows', 'label', 'messages']) }}>{{ old($name, $value) }}</textarea>
<x-input-error class="mt-2" :messages="$messages" />
