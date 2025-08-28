@props([
    'label',
    'name' => null,
    'placeholder' => '',
    'required' => false,
    'tip' => '',
    'prefix' => '',
    'input_id' => null,
    'type' => 'text',
])

@php
    $name = $name ?? $attributes->get('wire:model');
    $id = $input_id ?? $name;
@endphp

<div>
    <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
        {{ $label }}@if ($required)
            <sup class="text-red-500">*</sup>
        @endif
    </label>
    <div class="relative">
        @if ($prefix)
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 dark:text-gray-400 text-sm">
                {{ $prefix }}
            </span>
        @endif
        <input {{ $attributes->merge(['class' => 'form-input w-full px-4 py-2.5 ' . ($prefix ? 'pl-[12.5rem]' : '')]) }}
            type="{{ $type }}" name="{{ $name }}" id="{{ $id }}"
            placeholder="{{ $placeholder }}">
    </div>
    @if ($tip)
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $tip }}</p>
    @endif
    @error($name)
        <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
    @enderror
</div>
