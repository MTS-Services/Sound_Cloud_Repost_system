<div x-data="{
    showRepostActionModal: @entangle('showRepostActionModal').live,
    isSubmitting: false,
    reset() {
        this.isSubmitting = false;
    }
}"
    x-init="
    $watch('showRepostActionModal', value => {
        if (!value) {
            reset();
        }
    });
"
    x-show="showRepostActionModal"
    x-cloak
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50"
    @keydown.escape.window="!isSubmitting && $wire.closeConfirmModal()"
    @modal-closed.window="reset()">

    @if ($showRepostActionModal && $campaign)
    <div class="w-full max-w-md mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden"
        @click.outside="!isSubmitting && $wire.closeConfirmModal()">

        <!-- Header -->
        <div
            class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20">
            <div class="flex items-center gap-3">
                <div>
                    @if (app_setting('favicon') && app_setting('favicon_dark'))
                    <img src="{{ storage_url(app_setting('favicon')) }}" alt="{{ config('app.name') }}"
                        class="w-12 dark:hidden" />
                    <img src="{{ storage_url(app_setting('favicon_dark')) }}" alt="{{ config('app.name') }}"
                        class="w-12 hidden dark:block" />
                    @else
                    <img src="{{ asset('assets/favicons/fav icon 1.svg') }}" alt="{{ config('app.name') }}"
                        class="w-12 dark:hidden" />
                    <img src="{{ asset('assets/favicons/fav icon 2 (1).svg') }}" alt="{{ config('app.name') }}"
                        class="w-12 hidden dark:block" />
                    @endif
                </div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                    {{ __('Repost Confirmation') }}
                </h2>
            </div>
            <button
                x-on:click="!isSubmitting && $wire.closeConfirmModal()"
                type="button"
                :disabled="isSubmitting"
                class="cursor-pointer w-8 h-8 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600 disabled:opacity-50 disabled:cursor-not-allowed">
                <x-lucide-x class="w-5 h-5" />
            </button>
        </div>

        <!-- Content -->
        <div class="px-6 py-4 space-y-5">
            <!-- Header Info -->
            <div class="flex items-start justify-between">
                <h3 class="text-lg font-medium uppercase text-gray-900 dark:text-white">Repost</h3>
                <span class="text-sm text-gray-700 dark:text-gray-300">
                    <span class="font-medium text-orange-500">{{ user()->repost_price }}</span>
                    Credit{{ user()->repost_price > 1 ? 's' : '' }}
                </span>
            </div>

            <!-- Track Info -->
            <div class="flex items-center space-x-3 p-2 border border-gray-200 dark:border-gray-600 rounded-md">
                <img src="{{ soundcloud_image($campaign->music->artwork_url) }}" alt="Track Cover"
                    class="w-12 h-12 rounded-md object-cover">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                        {{ $campaign->music->type }} - {{ $campaign->music->author_username }}
                    </p>
                    <p class="text-xs text-gray-500 truncate">{{ $campaign->music?->title }}</p>
                </div>
            </div>

            <!-- Follow Option -->
            <div class="space-y-2">
                <label class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        @if (!$alreadyFollowing)
                        <input type="checkbox" wire:model.live="followed" :disabled="isSubmitting"
                            class="w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500 disabled:opacity-50 disabled:cursor-not-allowed">
                        @endif
                        <label class="text-sm text-gray-800 dark:text-gray-200">
                            {{ $alreadyFollowing ? 'You are already following' : 'Follow' }}
                            <span class="font-semibold text-orange-500">{{ $campaign->music?->user?->name }}</span>
                        </label>
                    </div>
                </label>
            </div>

            <!-- Like Option -->
            <div class="flex items-center justify-between border-t pt-3 dark:border-gray-700">
                <label class="flex items-center space-x-2">
                    @if (!$alreadyLiked)
                    <input type="checkbox" wire:model.live="liked" :disabled="isSubmitting"
                        class="w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span
                        class="text-sm text-gray-800 dark:text-gray-200">{{ __('Activate HeartPush') }}</span>
                    @else
                    <span class="text-sm text-orange-500">
                        Already Liked this
                        {{ $campaign->music_type == App\Models\Track::class ? 'Track' : 'Playlist' }}
                    </span>
                    @endif
                </label>
                @if (!$alreadyLiked && $campaign->likeable == 1)
                <span class="text-sm text-gray-700 dark:text-gray-300">
                    +<span class="font-medium text-orange-500">2</span> Credits
                </span>
                @endif
            </div>

            <!-- Comment Option -->
            @if ($campaign->music_type == App\Models\Track::class)
            <div class="border-t pt-3 space-y-2 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-800 dark:text-gray-200">
                        Comment on this track (optional)
                    </span>
                    @if ($campaign->commentable == 1)
                    <span class="text-sm text-gray-700 dark:text-gray-300">
                        +<span class="font-medium text-orange-500">2</span> Credits
                    </span>
                    @endif
                </div>
                <textarea rows="3" placeholder="What did you like about the track?" wire:model.live="commented"
                    :disabled="isSubmitting"
                    class="w-full border-gray-300 rounded-lg text-sm focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 disabled:opacity-50 disabled:cursor-not-allowed"></textarea>
                <x-input-error class="mt-2" :messages="$errors->get('commented')" />
            </div>
            @endif

            <!-- Submit Button -->
            <div class="flex justify-center gap-4">
                <button
                    wire:click="repost"
                    @click="isSubmitting = true"
                    wire:loading.attr="disabled"
                    :disabled="isSubmitting"
                    class="w-full flex items-center justify-center gap-2 bg-orange-500 hover:bg-orange-600 text-white py-2 px-4 rounded-xl transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-orange-500">

                    <!-- Loading Spinner -->
                    <span wire:loading wire:target="repost" class="inline-block">
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </span>

                    <!-- Icon and Text -->
                    <span wire:loading.remove wire:target="repost" class="flex items-center gap-2">
                        <svg width="26" height="18" viewBox="0 0 26 18" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <rect x="1" y="1" width="24" height="16" rx="3" fill="none"
                                stroke="currentColor" stroke-width="2" />
                            <circle cx="8" cy="9" r="3" fill="none" stroke="currentColor"
                                stroke-width="2" />
                        </svg>
                        <span>{{ repostPrice(user()->repost_price, $commented, $liked) }}</span>
                        <span>{{ __('Repost') }}</span>
                    </span>

                    <span wire:loading wire:target="repost">
                        {{ __('Processing...') }}
                    </span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        // Reset submission state when modal closes or repost succeeds
        Livewire.on('repost-success', () => {
            // Force reset of Alpine state
            const modalElement = document.querySelector('[x-data*="showRepostActionModal"]');
            if (modalElement && modalElement.__x) {
                modalElement.__x.$data.isSubmitting = false;
            }
        });

        Livewire.on('modal-closed', () => {
            // Force reset of Alpine state
            const modalElement = document.querySelector('[x-data*="showRepostActionModal"]');
            if (modalElement && modalElement.__x) {
                modalElement.__x.$data.isSubmitting = false;
            }
        });
    });
</script>