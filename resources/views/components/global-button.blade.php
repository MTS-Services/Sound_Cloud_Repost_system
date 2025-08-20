<div>
    <button type="{{ $type }}" class="{{ $getButtonClasses() }} {{ $attributes->get('class', '') }}"
        @if ($disabled) disabled @endif {{ $attributes->except(['class']) }}>
        {{ $slot }}
    </button>
</div>
