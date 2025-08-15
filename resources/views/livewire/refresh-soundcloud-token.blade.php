<div>
    <x-slot name="title">Refresh SoundCloud Token</x-slot>
    <x-slot name="page_slug">refresh-soundcloud-token</x-slot>

    <button wire:click="refreshSoundcloudToken"
        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
        Refresh SoundCloud Token
    </button>

    @if ($message)
        <p class="mt-2 text-sm text-gray-600">{{ $message }}</p>
    @endif
</div>
