{{-- <div x-data="{ showSubmitModal: @entangle('showSubmitModal').live }" x-show="showSubmitModal" x-cloak x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">

    <div
        class="w-full max-w-4xl mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col max-h-[85vh] overflow-hidden">

        <!-- Header -->
        <div
            class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20">
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
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ __('Create a campaign') }}
                </h2>
            </div>
            <button x-on:click="showSubmitModal = false"
                class="w-10 h-10 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                <x-lucide-x class="w-5 h-5" />
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 overflow-y-auto" x-data="{
            momentumEnabled: @js(proUser()),
            showGenreRadios: false,
            showRepostPerDay: false,
            showOptions: false,
            localCredit: @entangle('credit').defer || 50,
            localMaxFollower: @entangle('maxFollower').defer || 5000,
            localMaxRepostsPerDay: @entangle('maxRepostsPerDay').defer
        }" x-init="// Initialize maxFollower according to credit (1 credit = 100 followers)
        if (!localCredit) localCredit = 50;
        localMaxFollower = localCredit * 100;
        $wire.set('credit', localCredit);
        $wire.set('maxFollower', localMaxFollower);
        
        // Watch credit changes and update maxFollower accordingly
        $watch('localCredit', value => {
            $wire.set('credit', value);
            localMaxFollower = value * 100;
            $wire.set('maxFollower', localMaxFollower);
        });
        
        // Watch maxRepostsPerDay changes
        $watch('localMaxRepostsPerDay', value => $wire.set('maxRepostsPerDay', value));">

            <!-- Selected Track -->
            @if ($track)
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-md font-medium text-gray-900 dark:text-white">Selected Track</h3>
                        <button x-on:click="showSubmitModal = false"
                            class="bg-gray-100 dark:bg-slate-700 py-1.5 px-3 rounded-xl text-orange-500 text-sm font-medium hover:text-orange-600">Edit</button>
                    </div>
                    <div
                        class="p-4 flex items-center space-x-4 dark:bg-slate-700 rounded-xl transition-all duration-200 border border-orange-200">
                        <img src="{{ soundcloud_image($track->artwork_url) }}" alt="Album cover"
                            class="w-12 h-12 rounded">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-white">{{ $track->type }} -
                                {{ $track->author_username }}</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $track->title }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form -->
            <form wire:submit.prevent="createCampaign" class="space-y-6">
                <!-- Budget -->
                <div class="mt-4">
                    <div class="flex items-center space-x-2 mb-2">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white">Set budget</h3>
                        <div class="w-4 h-4 bg-gray-400 rounded-full flex items-center justify-center">
                            <span class="text-white text-xs">i</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-700 dark:text-gray-400 mb-4">A potential 10,000 people reached per
                        campaign</p>

                    <div class="flex items-center justify-center space-x-2 mb-4">
                        <svg class="w-8 h-8 text-orange-500" width="26" height="18" viewBox="0 0 26 18"
                            fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="1" y="1" width="24" height="16" rx="3" fill="none"
                                stroke="currentColor" stroke-width="2" />
                            <circle cx="8" cy="9" r="3" fill="none" stroke="currentColor"
                                stroke-width="2" />
                        </svg>
                        <span class="text-2xl font-bold text-orange-500" x-text="localCredit"></span>
                    </div>

                    @error('credit')
                        <p class="text-xs text-red-500 mb-4">{{ $message }}</p>
                    @enderror

                    <div class="relative">
                        <input type="range" x-init="localCredit = @entangle('credit').defer || 50" x-model="localCredit" min="50"
                            step="10" max="{{ userCredits() }}"
                            class="w-full h-2 border-0 cursor-pointer outline-none transition-all duration-200">
                    </div>
                </div>

                <!-- Campaign Settings -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mt-4">Campaign Settings</h2>
                    <p class="text-sm text-gray-700 dark:text-gray-400 mb-4 mt-2">Select amount of credits to be spent
                    </p>
                    <div class="flex items-start space-x-3">
                        <input type="checkbox" wire:model="commentable" checked
                            class="mt-1 w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">Activate Feedback</h4>
                            <p class="text-xs text-gray-700 dark:text-gray-400">Encourage listeners to comment (2
                                credits per comment).</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-start space-x-3">
                    <input type="checkbox" wire:model="likeable" checked
                        class="mt-1 w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Activate HeartPush</h4>
                        <p class="text-xs text-gray-700 dark:text-gray-400">Motivate users to like your track (2 credits
                            per like).</p>
                    </div>
                </div>

                <!-- Max Follower -->
                <div class="flex flex-col space-y-2">
                    <div class="flex items-start space-x-3">
                        <input type="checkbox" @change="showOptions = !showOptions"
                            class="mt-1 w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500">
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Limit to users with max follower
                            count</span>
                    </div>
                    <div x-show="showOptions" x-transition class="p-3">
                        <div class="flex justify-between items-center gap-4">
                            <div class="w-full relative">
                                <input type="range" x-init="localMaxFollower = @entangle('maxFollower').defer || 100" x-model="localMaxFollower"
                                    min="100" :max="localCredit * 100" class="w-full h-2 cursor-pointer">
                            </div>
                            <div
                                class="min-w-[80px] px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-md flex items-center justify-center">
                                <span class="text-sm font-medium text-gray-900 dark:text-white"
                                    x-text="localMaxFollower * 100"></span>
                            </div>
                        </div>
                        @error('maxFollower')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- PRO Features, Audience Filtering, Genres (unchanged)... -->
                <!-- Enable Campaign Accelerator -->
                <div class="flex items-start space-x-3 {{ !proUser() ? 'opacity-30' : '' }}">
                    <input type="checkbox" wire:click="profeature( {{ $proFeatureValue }} )"
                        {{ !proUser() ? 'disabled' : '' }}
                        class="mt-1 w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500 {{ !proUser() ? 'cursor-not-allowed' : 'cursor-pointer' }}">
                    <div>
                        <div class="flex items-center space-x-2">
                            <h4 class="text-sm font-medium text-dark dark:text-white">
                                {{ __('Turn on Momentum+ (') }}
                                <span class="text-md font-semibold">PRO</span>{{ __(')') }}
                            </h4>
                            <div
                                class="w-4 h-4 text-gray-700 dark:text-gray-400 rounded-full flex items-center justify-center">
                                <span class="text-white text-xs">i</span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-700 dark:text-gray-400">Use Campaign Accelerator (+50 credits)
                        </p>
                    </div>
                </div>


                <!-- Campaign Targeting -->
                <div class="border border-gray-200 dark:border-gray-700 bg-gray-200 dark:bg-gray-900 rounded-lg p-4"
                    :class="momentumEnabled ? 'opacity-100' : 'opacity-30 border-opacity-10'">
                    <div class=" mb-4">
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                            {{ __('Audience Filtering (PRO Feature)') }}</h4>
                        <p class="text-sm  text-gray-700 dark:text-gray-400 mb-4 mt-2">Fine-tune who can support
                            your track:</p>
                    </div>

                    <div class="space-y-3 ml-4">
                        <div class="flex flex-col space-y-2">
                            <div class="flex items-start space-x-3">
                                <input type="checkbox" :disabled="!momentumEnabled"
                                    class="mt-1 w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500"
                                    :class="momentumEnabled ? 'cursor-pointer' : 'cursor-not-allowed'">
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-gray-700 dark:text-gray-400">Exclude users who repost
                                        too often (last
                                        24h)</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col space-y-2">
                            <div class="flex items-start space-x-3">
                                <input type="checkbox" @click="showRepostPerDay = !showRepostPerDay"
                                    :disabled="!momentumEnabled"
                                    class="mt-1 w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500"
                                    :class="momentumEnabled ? 'cursor-pointer' : 'cursor-not-allowed'">
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-gray-700 dark:text-gray-400">Limit average repost
                                        frequency per
                                        day</span>
                                </div>
                            </div>
                            <div x-show="showRepostPerDay" x-transition class="p-3">
                                <div class="flex justify-between items-center gap-4">
                                    <div class="w-full relative">
                                        <input type="range" x-data :disabled="!momentumEnabled"
                                            x-on:input="$wire.set('maxRepostsPerDay', $event.target.value)"
                                            min="0" max="100" value="{{ $maxRepostsPerDay }}"
                                            class="w-full h-2  cursor-pointer"
                                            :class="momentumEnabled ? 'cursor-pointer' : 'cursor-not-allowed'">
                                    </div>
                                    <div
                                        class="min-w-[80px] px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-md flex items-center justify-center">
                                        <span
                                            class="text-sm font-medium text-gray-900 dark:text-white">{{ $maxRepostsPerDay }}</span>
                                    </div>
                                    @error('maxRepostsPerDay')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Genre Selection -->
                    <div class="mt-4">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Genre
                            Preferences for
                            Sharers</h2>
                        <p class="text-sm text-gray-700 dark:text-gray-400 mb-3 mt-2">Reposters must have
                            the
                            following genres:</p>
                        <div class="space-y-2 ml-4">
                            <div class="flex items-center space-x-2">
                                <input type="radio" name="targetGenre" wire:model='targetGenre' value="anyGenre"
                                    checked @click="showGenreRadios = false" :disabled="!momentumEnabled"
                                    class="w-4 h-4 text-orange-500 border-gray-300 focus:ring-orange-500"
                                    :class="momentumEnabled ? 'cursor-pointer' : 'cursor-not-allowed'">
                                <span class="text-sm text-gray-700 dark:text-gray-400">Open to all music
                                    types</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <input type="radio" name="targetGenre" value="{{ $track?->genre }}"
                                    @click="showGenreRadios = false" wire:model='targetGenre'
                                    :disabled="!momentumEnabled"
                                    class="w-4 h-4 text-orange-500 border-gray-300 focus:ring-orange-500"
                                    :class="momentumEnabled ? 'cursor-pointer' : 'cursor-not-allowed'">
                                <span class="text-sm text-gray-700 dark:text-gray-400">Match track genre –
                                    {{ $track?->genre }}</span>
                            </div>
                            <div class="space-y-3">
                                <div class="flex items-center space-x-2">
                                    <input type="radio" name="targetGenre"
                                        @click="showGenreRadios = !showGenreRadios" :disabled="!momentumEnabled"
                                        class="w-4 h-4 text-orange-500 border-gray-300 focus:ring-orange-500"
                                        :class="momentumEnabled ? 'cursor-pointer' : 'cursor-not-allowed'">
                                    <span class="text-sm text-gray-700 dark:text-gray-400">Match one of
                                        your
                                        profile's chosen
                                        genres</span>
                                </div>
                                <div x-show="showGenreRadios" x-transition class="ml-6 space-y-2">
                                    @forelse (user()->genres as $genre)
                                        <div class="flex items-center space-x-2">
                                            <input type="radio" name="targetGenre" wire:model='targetGenre'
                                                value="{{ $genre->genre }}"
                                                class="w-4 h-4 text-orange-500 border-gray-300 focus:ring-orange-500">
                                            <span
                                                class="text-sm text-gray-700 dark:text-gray-400">{{ $genre->genre }}</span>
                                        </div>
                                    @empty
                                        <div class="">
                                            <span class="text-sm text-gray-700 dark:text-gray-400">No genres
                                                found</span>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="pt-4">
                    <button type="submit"
                        class="w-full transition-all duration-300 flex items-center justify-center gap-3 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-bold py-2 px-4 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 rounded-lg {{ !$canSubmit ? 'bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white' : 'bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed' }}">
                        <span>
                            <svg class="w-8 h-8 text-white" width="26" height="18" viewBox="0 0 26 18"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="1" y="1" width="24" height="16" rx="3" fill="none"
                                    stroke="currentColor" stroke-width="2" />
                                <circle cx="8" cy="9" r="3" fill="none" stroke="currentColor"
                                    stroke-width="2" />
                            </svg>
                        </span>
                        <span>{{ $proFeatureEnabled ? $credit * 1.5 : $credit }}</span>
                        <span wire:loading.remove wire:target="createCampaign">{{ __('Create Campaign') }}</span>
                        <span wire:loading wire:target="createCampaign">{{ __('Creating...') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> --}}



<div x-data="{ showSubmitModal: @entangle('showSubmitModal').live }" x-show="showSubmitModal" x-cloak x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">

    <div
        class="w-full max-w-4xl mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col max-h-[85vh] overflow-hidden">

        <!-- Header -->
        <div
            class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20">
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
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ __('Create a campaign') }}
                </h2>
            </div>
            <button x-on:click="showSubmitModal = false"
                class="w-10 h-10 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                <x-lucide-x class="w-5 h-5" />
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 overflow-y-auto" x-data="{
            momentumEnabled: @js(proUser()),
            showGenreRadios: false,
            showRepostPerDay: false,
            showOptions: false,
            localCredit: @entangle('credit').defer || 50,
            localMaxFollower: @entangle('maxFollower').defer || 5000,
            localMaxRepostsPerDay: @entangle('maxRepostsPerDay').defer
        }" x-init="// Initialize maxFollower according to credit (1 credit = 100 followers)
        if (!localCredit) localCredit = 50;
        localMaxFollower = localCredit * 100;
        $wire.set('credit', localCredit);
        $wire.set('maxFollower', localMaxFollower);
        
        // Watch credit changes and update maxFollower accordingly
        $watch('localCredit', value => {
            $wire.set('credit', value);
            localMaxFollower = value * 100;
            $wire.set('maxFollower', localMaxFollower);
        });
        
        // Watch maxRepostsPerDay changes
        $watch('localMaxRepostsPerDay', value => $wire.set('maxRepostsPerDay', value));">

            <!-- Selected Track -->
            @if ($track)
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-md font-medium text-gray-900 dark:text-white">Selected Track</h3>
                        <button x-on:click="showSubmitModal = false"
                            class="bg-gray-100 dark:bg-slate-700 py-1.5 px-3 rounded-xl text-orange-500 text-sm font-medium hover:text-orange-600">Edit</button>
                    </div>
                    <div
                        class="p-4 flex items-center space-x-4 dark:bg-slate-700 rounded-xl transition-all duration-200 border border-orange-200">
                        <img src="{{ soundcloud_image($track->artwork_url) }}" alt="Album cover"
                            class="w-12 h-12 rounded">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-white">{{ $track->type }} -
                                {{ $track->author_username }}</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $track->title }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form -->
            <form wire:submit.prevent="createCampaign" class="space-y-6">
                <!-- Budget -->
                <div class="mt-4">
                    <div class="flex items-center space-x-2 mb-2">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white">Set budget</h3>
                        <div class="w-4 h-4 bg-gray-400 rounded-full flex items-center justify-center">
                            <span class="text-white text-xs">i</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-700 dark:text-gray-400 mb-4">A potential 10,000 people reached per
                        campaign</p>

                    <div class="flex items-center justify-center space-x-2 mb-4">
                        <svg class="w-8 h-8 text-orange-500" width="26" height="18" viewBox="0 0 26 18"
                            fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="1" y="1" width="24" height="16" rx="3" fill="none"
                                stroke="currentColor" stroke-width="2" />
                            <circle cx="8" cy="9" r="3" fill="none" stroke="currentColor"
                                stroke-width="2" />
                        </svg>
                        <span class="text-2xl font-bold text-orange-500" x-text="localCredit"></span>
                    </div>

                    @error('credit')
                        <p class="text-xs text-red-500 mb-4">{{ $message }}</p>
                    @enderror

                    <div class="relative">
                        <input type="range" x-init="localCredit = @entangle('credit').defer || 50" x-model="localCredit" min="50"
                            step="10" max="{{ userCredits() }}"
                            class="w-full h-2 border-0 cursor-pointer outline-none transition-all duration-200">
                    </div>
                </div>

                <!-- Campaign Settings -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mt-4">Campaign Settings</h2>
                    <p class="text-sm text-gray-700 dark:text-gray-400 mb-4 mt-2">Select amount of credits to be spent
                    </p>
                    <div class="flex items-start space-x-3">
                        <input type="checkbox" wire:model="commentable" checked
                            class="mt-1 w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">Activate Feedback</h4>
                            <p class="text-xs text-gray-700 dark:text-gray-400">Encourage listeners to comment (2
                                credits per comment).</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-start space-x-3">
                    <input type="checkbox" wire:model="likeable" checked
                        class="mt-1 w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Activate HeartPush</h4>
                        <p class="text-xs text-gray-700 dark:text-gray-400">Motivate users to like your track (2 credits
                            per like).</p>
                    </div>
                </div>

                <!-- Max Follower -->
                <div class="flex flex-col space-y-2">
                    <div class="flex items-start space-x-3">
                        <input type="checkbox" @change="showOptions = !showOptions"
                            class="mt-1 w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500">
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Limit to users with max follower
                            count</span>
                    </div>
                    <div x-show="showOptions" x-transition class="p-3">
                        <div class="flex justify-between items-center gap-4">
                            <div class="w-full relative">
                                <input type="range" x-init="localMaxFollower = @entangle('maxFollower').defer || 5000" x-model="localMaxFollower"
                                    min="100" :max="localCredit * 100" class="w-full h-2 cursor-pointer">
                            </div>
                            <div
                                class="min-w-[80px] px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-md flex items-center justify-center">
                                <span class="text-sm font-medium text-gray-900 dark:text-white"
                                    x-text="localMaxFollower"></span>
                            </div>
                        </div>
                        @error('maxFollower')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- PRO Features, Audience Filtering, Genres (unchanged)... -->
                <!-- Enable Campaign Accelerator -->
                <div class="flex items-start space-x-3 {{ !proUser() ? 'opacity-30' : '' }}">
                    <input type="checkbox" wire:click="profeature( {{ $proFeatureValue }} )"
                        {{ !proUser() ? 'disabled' : '' }}
                        class="mt-1 w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500 {{ !proUser() ? 'cursor-not-allowed' : 'cursor-pointer' }}">
                    <div>
                        <div class="flex items-center space-x-2">
                            <h4 class="text-sm font-medium text-dark dark:text-white">
                                {{ __('Turn on Momentum+ (') }}
                                <span class="text-md font-semibold">PRO</span>{{ __(')') }}
                            </h4>
                            <div
                                class="w-4 h-4 text-gray-700 dark:text-gray-400 rounded-full flex items-center justify-center">
                                <span class="text-white text-xs">i</span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-700 dark:text-gray-400">Use Campaign Accelerator (+50 credits)
                        </p>
                    </div>
                </div>


                <!-- Campaign Targeting -->
                <div class="border border-gray-200 dark:border-gray-700 bg-gray-200 dark:bg-gray-900 rounded-lg p-4"
                    :class="momentumEnabled ? 'opacity-100' : 'opacity-30 border-opacity-10'">
                    <div class=" mb-4">
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                            {{ __('Audience Filtering (PRO Feature)') }}</h4>
                        <p class="text-sm  text-gray-700 dark:text-gray-400 mb-4 mt-2">Fine-tune who can support
                            your track:</p>
                    </div>

                    <div class="space-y-3 ml-4">
                        <div class="flex flex-col space-y-2">
                            <div class="flex items-start space-x-3">
                                <input type="checkbox" :disabled="!momentumEnabled"
                                    class="mt-1 w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500"
                                    :class="momentumEnabled ? 'cursor-pointer' : 'cursor-not-allowed'">
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-gray-700 dark:text-gray-400">Exclude users who repost
                                        too often (last
                                        24h)</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col space-y-2">
                            <div class="flex items-start space-x-3">
                                <input type="checkbox" @click="showRepostPerDay = !showRepostPerDay"
                                    :disabled="!momentumEnabled"
                                    class="mt-1 w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500"
                                    :class="momentumEnabled ? 'cursor-pointer' : 'cursor-not-allowed'">
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-gray-700 dark:text-gray-400">Limit average repost
                                        frequency per
                                        day</span>
                                </div>
                            </div>
                            <div x-show="showRepostPerDay" x-transition class="p-3">
                                <div class="flex justify-between items-center gap-4">
                                    <div class="w-full relative">
                                        <input type="range" x-data :disabled="!momentumEnabled"
                                            x-on:input="$wire.set('maxRepostsPerDay', $event.target.value)"
                                            min="0" max="100" value="{{ $maxRepostsPerDay }}"
                                            class="w-full h-2  cursor-pointer"
                                            :class="momentumEnabled ? 'cursor-pointer' : 'cursor-not-allowed'">
                                    </div>
                                    <div
                                        class="min-w-[80px] px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-md flex items-center justify-center">
                                        <span
                                            class="text-sm font-medium text-gray-900 dark:text-white">{{ $maxRepostsPerDay }}</span>
                                    </div>
                                    @error('maxRepostsPerDay')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Genre Selection -->
                    <div class="mt-4">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Genre
                            Preferences for
                            Sharers</h2>
                        <p class="text-sm text-gray-700 dark:text-gray-400 mb-3 mt-2">Reposters must have
                            the
                            following genres:</p>
                        <div class="space-y-2 ml-4">
                            <div class="flex items-center space-x-2">
                                <input type="radio" name="targetGenre" wire:model='targetGenre' value="anyGenre"
                                    checked @click="showGenreRadios = false" :disabled="!momentumEnabled"
                                    class="w-4 h-4 text-orange-500 border-gray-300 focus:ring-orange-500"
                                    :class="momentumEnabled ? 'cursor-pointer' : 'cursor-not-allowed'">
                                <span class="text-sm text-gray-700 dark:text-gray-400">Open to all music
                                    types</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <input type="radio" name="targetGenre" value="{{ $track?->genre }}"
                                    @click="showGenreRadios = false" wire:model='targetGenre'
                                    :disabled="!momentumEnabled"
                                    class="w-4 h-4 text-orange-500 border-gray-300 focus:ring-orange-500"
                                    :class="momentumEnabled ? 'cursor-pointer' : 'cursor-not-allowed'">
                                <span class="text-sm text-gray-700 dark:text-gray-400">Match track genre –
                                    {{ $track?->genre }}</span>
                            </div>
                            <div class="space-y-3">
                                <div class="flex items-center space-x-2">
                                    <input type="radio" name="targetGenre"
                                        @click="showGenreRadios = !showGenreRadios" :disabled="!momentumEnabled"
                                        class="w-4 h-4 text-orange-500 border-gray-300 focus:ring-orange-500"
                                        :class="momentumEnabled ? 'cursor-pointer' : 'cursor-not-allowed'">
                                    <span class="text-sm text-gray-700 dark:text-gray-400">Match one of
                                        your
                                        profile's chosen
                                        genres</span>
                                </div>
                                <div x-show="showGenreRadios" x-transition class="ml-6 space-y-2">
                                    @forelse (user()->genres as $genre)
                                        <div class="flex items-center space-x-2">
                                            <input type="radio" name="targetGenre" wire:model='targetGenre'
                                                value="{{ $genre->genre }}"
                                                class="w-4 h-4 text-orange-500 border-gray-300 focus:ring-orange-500">
                                            <span
                                                class="text-sm text-gray-700 dark:text-gray-400">{{ $genre->genre }}</span>
                                        </div>
                                    @empty
                                        <div class="">
                                            <span class="text-sm text-gray-700 dark:text-gray-400">No genres
                                                found</span>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="pt-4">
                    <button type="submit"
                        class="w-full transition-all duration-300 flex items-center justify-center gap-3 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-bold py-2 px-4 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 rounded-lg {{ !$canSubmit ? 'bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white' : 'bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed' }}">
                        <span>
                            <svg class="w-8 h-8 text-white" width="26" height="18" viewBox="0 0 26 18"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="1" y="1" width="24" height="16" rx="3" fill="none"
                                    stroke="currentColor" stroke-width="2" />
                                <circle cx="8" cy="9" r="3" fill="none" stroke="currentColor"
                                    stroke-width="2" />
                            </svg>
                        </span>
                        <span>{{ $proFeatureEnabled ? $credit * 1.5 : $credit }}</span>
                        <span wire:loading.remove wire:target="createCampaign">{{ __('Create Campaign') }}</span>
                        <span wire:loading wire:target="createCampaign">{{ __('Creating...') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
