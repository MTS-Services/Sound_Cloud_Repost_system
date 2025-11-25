<div x-data="{ showModal: @entangle('showRepostActionModal').live }" x-cloak>

    <div x-show="showModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">

        <div x-show="showModal" x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
            class="bg-white rounded-lg shadow-lg w-11/12 max-w-md p-6">

            <h2 class="text-xl font-semibold mb-4">Repost Campaign</h2>

            <p class="mb-4">Are you sure you want to repost the campaign for
                "<strong>{{ $campaign->music->title ?? '' }}</strong>" by
                <strong>{{ $campaign->user->name ?? '' }}</strong>?
            </p>

            <div class="flex justify-end space-x-4">
                <button @click="showModal = false"
                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                <button wire:click="repostCampaign({{ $campaign->id ?? '' }})"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Confirm Repost</button>
            </div>
        </div>

    </div>

</div>
