<a href="{{ $href }}"
    class="{{ $getButtonClasses() }} {{ $attributes->get('class', '') }}"
    @if ($disabled) disabled @endif {{ $attributes->except(['class']) }}>
    {{ $slot }}
</a>
