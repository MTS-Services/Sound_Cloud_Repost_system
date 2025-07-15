@props(['name', 'label', 'accept' => 'image/*', 'messages' => []])


@if (!empty($label))
    <p class="label">{{ $label }}</p>
@endif
<input type="file" name="{{ $name }}" class="filepond" id="{{ $name }}" accept="{{ $accept }}"
    {{ $attributes->except(['class', 'type', 'name', 'accept', 'label', 'messages']) }}>
<x-input-error class="mt-2" :messages="$messages" />
