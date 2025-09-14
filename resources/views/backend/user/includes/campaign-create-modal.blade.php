<div x-data="{ showSubmitModal: @entangle('showSubmitModal').live }" x-show="showSubmitModal" x-cloak x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">

    <div
        class="w-full max-w-4xl mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 
                border border-gray-200 dark:border-gray-700 flex flex-col max-h-[85vh] overflow-hidden">

        <!-- Header -->
        <div
            class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 
                    bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20">
            <div class="flex items-center gap-3">
                <div>
                    @if (app_setting('favicon') && app_setting('favicon_dark'))
                        <img src="{{ storage_url(app_setting('favicon')) }}" class="w-12 dark:hidden" />
                        <img src="{{ storage_url(app_setting('favicon_dark')) }}" class="w-12 hidden dark:block" />
                    @else
                        <img src="{{ asset('assets/favicons/fav icon 1.svg') }}" class="w-12 dark:hidden" />
                        <img src="{{ asset('assets/favicons/fav icon 2 (1).svg') }}" class="w-12 hidden dark:block" />
                    @endif
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Create a campaign') }}</h2>
            </div>
            <button x-on:click="showSubmitModal = false"
                class="w-10 h-10 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 
                       text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 
                       transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                <x-lucide-x class="w-5 h-5" />
            </button>
        </div>

        <!-- Body -->
        <div x-data="{
            momentumEnabled: @js(proUser()),
            showGenreRadios: false,
            showRepostPerDay: false,
            showOptions: false,
            localCredit: @entangle('credit').defer,
            localMaxFollower: @entangle('maxFollower').defer,
            localMaxRepostsPerDay: @entangle('maxRepostsPerDay').defer
        }" x-init="$watch('localCredit', value => {
            $wire.set('credit', value);
        
            
            if (localMaxFollower > value) {
                localMaxFollower = value;
                $wire.set('maxFollower', value);
            }
                
            if (localMaxFollower < value) {
                localMaxFollower = value;
                $wire.set('maxFollower', value);
            }
        });
        
        $watch('localMaxFollower', value => {
            $wire.set('maxFollower', value);
        });
        
        $watch('localMaxRepostsPerDay', value => {
            $wire.set('maxRepostsPerDay', value);
        });" class="flex-grow overflow-y-auto p-6">

            <!-- Selected Track -->
            @if ($track)
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-md font-medium text-gray-900 dark:text-white">Selected Track</h3>
                        <button x-on:click="showSubmitModal = false"
                            class="bg-gray-100 dark:bg-slate-700 py-1.5 px-3 rounded-xl text-orange-500 text-sm font-medium hover:text-orange-600">
                            Edit
                        </button>
                    </div>
                    <div class="p-4 flex items-center space-x-4 dark:bg-slate-700 rounded-xl border border-orange-200">
                        <img src="{{ soundcloud_image($track->artwork_url) }}" class="w-12 h-12 rounded">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-white">{{ $track->type }} -
                                {{ $track->author_username }}</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $track->title }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <form wire:submit.prevent="createCampaign" class="space-y-6">
                <!-- Set Budget -->
                <div class="mt-4">
                    <div class="flex items-center space-x-2 mb-2">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white">Set budget</h3>
                        <div class="w-4 h-4 bg-gray-400 rounded-full flex items-center justify-center">
                            <span class="text-white text-xs">i</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-700 dark:text-gray-400 mb-4">
                        A potential 10,000 people reached per campaign
                    </p>

                    <!-- Budget Display -->
                    <div class="flex items-center justify-center space-x-2 mb-4">
                        <svg class="w-8 h-8 text-orange-500" width="26" height="18" viewBox="0 0 26 18"
                            fill="none">
                            <rect x="1" y="1" width="24" height="16" rx="3" stroke="currentColor"
                                stroke-width="2" />
                            <circle cx="8" cy="9" r="3" stroke="currentColor" stroke-width="2" />
                        </svg>
                        <span class="text-2xl font-bold text-orange-500" x-text="localCredit"></span>
                    </div>
                    @error('credit')
                        <p class="text-xs text-red-500 mb-4">{{ $message }}</p>
                    @enderror

                    <!-- Slider -->
                    <div class="relative">
                        <input type="range" x-model="localCredit" min="50" step="10"
                            max="{{ userCredits() }}"
                            class="w-full h-2 border-0 cursor-pointer outline-none transition-all duration-200">
                    </div>
                </div>

                <!-- Campaign Settings -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mt-4">Campaign Settings</h2>
                    <p class="text-sm text-gray-700 dark:text-gray-400 mb-4 mt-2">
                        Select amount of credits to be spent
                    </p>
                    <!-- CommentPlus -->
                    <div class="flex items-start space-x-3">
                        <input type="checkbox" wire:model="commentable" checked
                            class="mt-1 w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">Activate Feedback</h4>
                            <p class="text-xs text-gray-700 dark:text-gray-400">Encourage listeners to comment (2
                                credits per comment).</p>
                        </div>
                    </div>

                    <!-- LikePlus -->
                    <div class="flex items-start space-x-3">
                        <input type="checkbox" wire:model="likeable" checked
                            class="mt-1 w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">Activate HeartPush</h4>
                            <p class="text-xs text-gray-700 dark:text-gray-400">Motivate real users to like your track
                                (2 credits per like).</p>
                        </div>
                    </div>
                </div>

                <!-- Max Follower Limit -->
                <div class="flex flex-col space-y-2">
                    <div class="flex items-start space-x-3">
                        <input type="checkbox" @change="showOptions = !showOptions"
                            {{ $maxFollower > 100 ? 'checked' : '' }}
                            class="mt-1 w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500">
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Limit to users with max follower
                            count</span>
                    </div>
                    <div x-show="showOptions" x-transition class="p-3">
                        <div class="flex justify-between items-center gap-4">
                            <input type="range" x-model="localMaxFollower" min="100" :max="localCredit"
                                class="w-full h-2 cursor-pointer">
                            <div class="max-w-[80px] px-3 py-2 border rounded-md text-gray-700 dark:text-gray-400">
                                <span x-text="localMaxFollower"></span>
                            </div>
                        </div>
                        @error('maxFollower')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Reposts Per Day -->
                <div class="flex flex-col space-y-2">
                    <div class="flex items-start space-x-3">
                        <input type="checkbox" @click="showRepostPerDay = !showRepostPerDay"
                            :disabled="!momentumEnabled"
                            class="mt-1 w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500"
                            :class="momentumEnabled ? 'cursor-pointer' : 'cursor-not-allowed'">
                        <span class="text-sm text-gray-700 dark:text-gray-400">Limit average repost frequency per
                            day</span>
                    </div>
                    <div x-show="showRepostPerDay" x-transition class="p-3">
                        <div class="flex justify-between items-center gap-4">
                            <input type="range" x-model="localMaxRepostsPerDay" min="0" max="100"
                                class="w-full h-2 cursor-pointer"
                                :class="momentumEnabled ? 'cursor-pointer' : 'cursor-not-allowed'">
                            <div class="w-14 h-8 border rounded-md flex items-center justify-center">
                                <span x-text="localMaxRepostsPerDay"></span>
                            </div>
                            @error('maxRepostsPerDay')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="pt-4">
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-3 font-bold py-2 px-4 rounded-lg 
                        {{ !$canSubmit ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white' : 'bg-gray-300 dark:bg-gray-600 text-gray-500 cursor-not-allowed' }}">
                        <span>
                            <svg class="w-8 h-8 text-white" width="26" height="18" viewBox="0 0 26 18"
                                fill="none">
                                <rect x="1" y="1" width="24" height="16" rx="3" stroke="currentColor"
                                    stroke-width="2" />
                                <circle cx="8" cy="9" r="3" stroke="currentColor"
                                    stroke-width="2" />
                            </svg>
                        </span>
                        <span x-text="momentumEnabled ? (localCredit * 1.5) : localCredit"></span>
                        <span wire:loading.remove wire:target="createCampaign">{{ __('Create Campaign') }}</span>
                        <span wire:loading wire:target="createCampaign">{{ __('Creating...') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
