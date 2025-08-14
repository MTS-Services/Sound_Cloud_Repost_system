@props(['label', 'name' => null, 'placeholder' => '', 'options' => [], 'required' => false, 'tip' => ''])

@php
    $name = $name ?? $attributes->get('wire:model');
    $id = $name;
@endphp

<div>
    <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
        {{ $label }}@if($required)<sup class="text-red-500">*</sup>@endif
    </label>
    <select 
        {{ $attributes->merge(['class' => 'form-select w-full px-4 py-2.5']) }}
        name="{{ $name }}"
        id="{{ $id }}"
    >
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $option)
            <option value="{{ $option }}">{{ $option }}</option>
        @endforeach
    </select>
    @if ($tip)
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $tip }}</p>
    @endif
    @error($name)
        <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
    @enderror
</div>