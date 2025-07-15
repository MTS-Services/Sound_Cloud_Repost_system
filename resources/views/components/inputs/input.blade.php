@props([
    'name',
    'label' => '',
    'type' => 'text',
    'placeholder' => null,
    'value' => null,
    'icon' => null,
    'messages' => [],
])

@if (!empty($label))
    <p class="label">{{ $label }}</p>
@endif
<label
    class="input flex items-center gap-1 px-0 focus:outline-0 focus-within:outline-0 focus:ring-0 focus:border-border-active focus-within:border-border-active w-full"
    @if ($type === 'password' || $type === 'password_confirmation') x-data="{ showPassword: false }" @endif>
    @if ($icon)
        <i data-lucide="{{ $icon }}" class="h-[1em] opacity-50 ml-2 mr-1"></i>
    @endif

    @if ($type === 'password' || $type === 'password_confirmation')
        <input x-bind:type="showPassword ? 'text' : 'password'" name="{{ $name }}"
            value="{{ old($name, $value) }}" placeholder="{{ $placeholder ?? $label }}"
            class="flex-1 focus:outline-0 focus-within:outline-0 focus:ring-0 focus:border-border-active focus-within:border-border-active w-full"
            {{ $attributes->except(['class', 'type', 'name', 'value', 'placeholder', 'icon', 'label', 'messages']) }} />
    @else
        <input type="{{ $type }}" name="{{ $name }}" value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder ?? $label }}"
            class="flex-1 focus:outline-0 focus-within:outline-0 focus:ring-0 focus:border-border-active focus-within:border-border-active w-full"
            {{ $attributes->except(['class', 'type', 'name', 'value', 'placeholder', 'icon', 'label', 'messages']) }} />
    @endif

    @if ($type === 'password' || $type === 'password_confirmation')
        <button type="button" class="mr-2 ml-1 focus:outline-none"
            @click="showPassword = !showPassword; $nextTick(() => lucide.createIcons())">
            <i :data-lucide="showPassword ? 'eye-off' : 'eye'" class="w-4 h-4 opacity-50"></i>
        </button>
    @endif
</label>
<x-input-error class="mt-2" :messages="$messages" />
