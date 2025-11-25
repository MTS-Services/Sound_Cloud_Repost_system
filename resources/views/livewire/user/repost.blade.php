<div 
    x-data="repostModal()" 
    x-show="showModal" 
    x-cloak 
    x-transition:enter="transition ease-out duration-100"
    x-transition:enter-start="opacity-0 scale-95" 
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-75" 
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50"
    @keydown.escape.window="closeModal()"
    @callRepostAction.window="handleRepostAction($event)"
    @closeRepostModal.window="closeModal()"
    @repost-success.window="closeModal()">
    
    <div 
        class="w-full max-w-md mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden"
        @click.outside="closeModal()">
        
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20">
            <div class="flex items-center gap-3">
                <div>
                    @if (app_setting('favicon') && app_setting('favicon_dark'))
                        <img src="{{ storage_url(app_setting('favicon')) }}" 
                             alt="{{ config('app.name') }}"
                             class="w-12 dark:hidden" />
                        <img src="{{ storage_url(app_setting('favicon_dark')) }}" 
                             alt="{{ config('app.name') }}"
                             class="w-12 hidden dark:block" />
                    @else
                        <img src="{{ asset('assets/favicons/fav icon 1.svg') }}" 
                             alt="{{ config('app.name') }}"
                             class="w-12 dark:hidden" />
                        <img src="{{ asset('assets/favicons/fav icon 2 (1).svg') }}"
                             alt="{{ config('app.name') }}" 
                             class="w-12 hidden dark:block" />
                    @endif
                </div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                    {{ __('Repost Confirmation') }}
                </h2>
            </div>
            <button 
                @click="closeModal()"
                type="button"
                :disabled="isSubmitting"
                class="cursor-pointer w-8 h-8 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600 disabled:opacity-50 disabled:cursor-not-allowed">
                <x-lucide-x class="w-5 h-5" />
            </button>
        </div>

        <!-- Debug Info (Remove in production) -->
        <div x-show="true" class="px-4 py-2 bg-yellow-50 dark:bg-yellow-900/20 border-b border-yellow-200 dark:border-yellow-800 text-xs">
            <p class="text-yellow-800 dark:text-yellow-200">
                <strong>Debug:</strong> 
                Loading: <span x-text="$wire.isLoading"></span> | 
                Campaign: <span x-text="$wire.campaign ? 'Loaded' : 'Not loaded'"></span> |
                Modal: <span x-text="showModal ? 'Open' : 'Closed'"></span>
            </p>
        </div>

        <!-- Loading State -->
        <div x-show="$wire.isLoading" class="px-6 py-12">
            <div class="flex flex-col items-center justify-center space-y-4">
                <div class="relative">
                    <div class="w-16 h-16 border-4 border-orange-200 dark:border-orange-900 rounded-full"></div>
                    <div class="w-16 h-16 border-4 border-orange-500 border-t-transparent rounded-full animate-spin absolute top-0 left-0"></div>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">Loading repost details...</p>
            </div>
        </div>

        <!-- Content -->
        <div x-show="!$wire.isLoading && $wire.campaign" class="px-6 py-4 space-y-5">
            @if ($campaign)
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
                    <img src="{{ soundcloud_image($campaign->music->artwork_url) }}" 
                         alt="Track Cover"
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
                                <input 
                                    type="checkbox" 
                                    wire:model.live="followed"
                                    :disabled="isSubmitting"
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
                            <input 
                                type="checkbox" 
                                wire:model.live="liked"
                                :disabled="isSubmitting"
                                class="w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span class="text-sm text-gray-800 dark:text-gray-200">{{ __('Activate HeartPush') }}</span>
                        @else
                            <span class="text-sm text-orange-500">
                                Already Liked this {{ $campaign->music_type == App\Models\Track::class ? 'Track' : 'Playlist' }}
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
                        <textarea 
                            rows="3" 
                            placeholder="What did you like about the track?" 
                            wire:model.live="commented"
                            :disabled="isSubmitting"
                            class="w-full border-gray-300 rounded-lg text-sm focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 disabled:opacity-50 disabled:cursor-not-allowed"></textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('commented')" />
                    </div>
                @endif

                <!-- Submit Button -->
                <div class="flex justify-center gap-4">
                    <button 
                        @click="submitRepost()"
                        :disabled="isSubmitting"
                        class="w-full flex items-center justify-center gap-2 bg-orange-500 hover:bg-orange-600 text-white py-2 px-4 rounded-xl transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-orange-500">
                        
                        <!-- Loading Spinner -->
                        <template x-if="isSubmitting">
                            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </template>

                        <!-- Icon and Text -->
                        <template x-if="!isSubmitting">
                            <div class="flex items-center gap-2">
                                <svg width="26" height="18" viewBox="0 0 26 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="1" y="1" width="24" height="16" rx="3" fill="none" stroke="currentColor" stroke-width="2" />
                                    <circle cx="8" cy="9" r="3" fill="none" stroke="currentColor" stroke-width="2" />
                                </svg>
                                <span>{{ repostPrice(user()->repost_price, $commented, $liked) }}</span>
                                <span>{{ __('Repost') }}</span>
                            </div>
                        </template>

                        <template x-if="isSubmitting">
                            <span>{{ __('Processing...') }}</span>
                        </template>
                    </button>
                </div>
            @endif
        </div>

        <!-- Error State -->
        <div x-show="!$wire.isLoading && !$wire.campaign" class="px-6 py-12">
            <div class="flex flex-col items-center justify-center space-y-4 text-center">
                <div class="w-16 h-16 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center">
                    <x-lucide-alert-circle class="w-8 h-8 text-red-600 dark:text-red-400" />
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">Failed to load campaign data</p>
                <button @click="closeModal()" class="text-sm text-orange-500 hover:text-orange-600">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function repostModal() {
        return {
            showModal: false,
            isSubmitting: false,
            
            handleRepostAction(event) {
                console.log('🎯 Repost action received:', event.detail);
                
                // Open modal immediately
                this.showModal = true;
                this.isSubmitting = false;
                
                // Call Livewire in next tick
                this.$nextTick(() => {
                    console.log('📞 Calling Livewire callRepostAction...');
                    // Extract campaignId from event detail
                    const campaignId = event.detail.campaignId || event.detail;
                    this.$wire.callRepostAction(campaignId);
                });
            },
            
            closeModal() {
                console.log('❌ Closing modal');
                if (!this.isSubmitting) {
                    this.showModal = false;
                    
                    // Clear Livewire data after animation
                    setTimeout(() => {
                        this.$wire.closeConfirmModal();
                    }, 100);
                }
            },
            
            submitRepost() {
                console.log('✅ Submitting repost');
                this.isSubmitting = true;
                
                this.$wire.repost().then(() => {
                    console.log('✅ Repost successful');
                    this.isSubmitting = false;
                }).catch((error) => {
                    console.error('❌ Repost failed:', error);
                    this.isSubmitting = false;
                });
            }
        }
    }

    // Debug event listeners
    document.addEventListener('livewire:init', () => {
        console.log('🚀 Livewire initialized');
        
        // Listen to all Livewire events for debugging
        Livewire.on('callRepostAction', (event) => {
            console.log('📡 Livewire event: callRepostAction', event);
        });

        Livewire.on('closeRepostModal', () => {
            console.log('📡 Livewire event: closeRepostModal');
        });

        Livewire.on('repost-success', (event) => {
            console.log('📡 Livewire event: repost-success', event);
        });
    });
</script>