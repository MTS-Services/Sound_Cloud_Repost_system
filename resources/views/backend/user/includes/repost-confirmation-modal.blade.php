<div x-data="{ showRepostConfirmationModal: @entangle('showRepostConfirmationModal').live }" x-show="showRepostConfirmationModal" x-cloak
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
    class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">
    @if ($campaign)
        <div
            class="w-full max-w-md mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden">
            <div
                class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20">
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
                <button x-on:click="showRepostConfirmationModal = false"
                    class="cursor-pointer w-8 h-8 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>
            <div class="px-6 py-4 space-y-5">
                <div class="flex items-start justify-between">
                    <h3 class="text-lg font-medium uppercase text-gray-900 dark:text-white">Repost</h3>
                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ repostPrice($campaign->user) }}
                        Credits</span>
                </div>
                <div class="flex items-center space-x-3 p-2 border border-gray-200 dark:border-gray-600 rounded-md">
                    <img src="{{ soundcloud_image($campaign->music->artwork_url) }}" alt="Track Cover"
                        class="w-12 h-12 rounded-md object-cover">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                            {{ $campaign->music->type }} -
                            {{ $campaign->music->author_username }}</p>
                        <p class="text-xs text-gray-500">{{ $campaign->music?->title }}</p>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" wire:model.live="followed"
                                class="w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500">
                            <span class="text-sm text-gray-800 dark:text-gray-200">Follow <span
                                    class="font-semibold text-orange-500">{{ $campaign->user?->name }}</span></span>
                        </div>
                    </label>
                </div>

                <!-- Like Plus -->
                <div class="flex items-center justify-between border-t pt-3 dark:border-gray-700">
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" wire:model.live="liked"
                            class="w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500">
                        <span class="text-sm text-gray-800 dark:text-gray-200">{{ __('Activate HeartPush') }}</span>
                    </label>
                    <span class="text-sm text-gray-700 dark:text-gray-300">+2 credits</span>
                </div>

                <!-- Comment Plus -->
                <div class="border-t pt-3 space-y-2 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-800 dark:text-gray-200">Comment on this
                            track (optional)</span>
                        <span class="text-sm text-gray-700 dark:text-gray-300">+2 credits</span>
                    </div>
                    <textarea rows="3" placeholder="What did you like about the track?" wire:model.live="commented"
                        class="w-full border-gray-300 rounded-lg text-sm focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200"></textarea>
                </div>

                <div class="flex justify-center gap-4">
                    <button @click="showRepostConfirmationModal = false" wire:click="repost('{{ $campaign->id }}')"
                        class="w-full flex items-center justify-center gap-2 bg-orange-500 hover:bg-orange-600 text-white py-2 px-4 rounded-xl transition-all duration-200">
                        <svg width="26" height="18" viewBox="0 0 26 18" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <rect x="1" y="1" width="24" height="16" rx="3" fill="none"
                                stroke="currentColor" stroke-width="2" />
                            <circle cx="8" cy="9" r="3" fill="none" stroke="currentColor"
                                stroke-width="2" />
                        </svg>
                        <span>{{ repostPrice() + ($liked ? 2 : 0) + ($commented ? 2 : 0) }}</span>
                        {{ __('Repost') }}
                    </button>
                </div>

            </div>
        </div>
    @endif
</div>
