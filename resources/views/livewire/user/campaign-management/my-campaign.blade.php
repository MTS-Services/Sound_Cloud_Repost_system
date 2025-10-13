<section x-data="{ $activeMainTab: @entangle('activeMainTab').live }">

    <x-slot name="page_slug">campaigns</x-slot>

    <div class="md:p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-5 gap-3">
            <!-- Title -->
            <div>
                <h1 class="text-xl text-black dark:text-gray-100 font-bold">
                    {{ __('My Campaigns') }}
                </h1>
            </div>

            <!-- Button -->
            <x-gbutton variant="primary" wire:click="toggleCampaignsModal" x-on:click="showCampaignsModal = true">
                <span><x-lucide-plus class="w-5 h-5 mr-1" /></span>
                {{ __('Start a new campaign') }}
            </x-gbutton>
        </div>


        <div class="mb-8">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="-mb-px flex space-x-8">
                    <a href="{{ route('user.cm.my-campaigns') }}?tab=all" wire:navigate
                        class="tab-button @if ($activeMainTab === 'all') active border-b-2 border-orange-500 text-orange-600 @else border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-3 px-2 text-sm font-semibold transition-all duration-200">ALL
                        Campaigns</a>
                    <a href="{{ route('user.cm.my-campaigns') }}?tab=active" wire:navigate
                        class="tab-button @if ($activeMainTab === 'active') active border-b-2 border-orange-500 text-orange-600 @else border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-3 px-2 text-sm font-semibold transition-all duration-200">Active</a>
                    <a href="{{ route('user.cm.my-campaigns') }}?tab=completed" wire:navigate
                        class="tab-button @if ($activeMainTab === 'completed') active border-b-2 border-orange-500 text-orange-600 @else border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-3 px-2 text-sm font-semibold transition-all duration-200">Completed</a>
                    <a href="{{ route('user.cm.my-campaigns') }}?tab=cancelled" wire:navigate
                        class="tab-button @if ($activeMainTab === 'cancelled') active border-b-2 border-orange-500 text-orange-600 @else border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-3 px-2 text-sm font-semibold transition-all duration-200">Cancelled
                        / Stop</a>
                </nav>
            </div>
        </div>

        <div class="space-y-6" id="campaigns-list">

            <div class="flex flex-col lg:flex-row justify-between gap-6 ">
                <!-- Main Content -->
                <div class="w-full flex flex-col gap-6">
                    @forelse ($campaigns as $campaign_)
                        <div class="rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden relative">
                            <div class="p-2 sm:p-4">
                                <div class="flex justify-between gap-2 md:gap-4">
                                    <div class="flex gap-2 md:gap-4">
                                        <img src="{{ soundcloud_image($campaign_->music?->artwork_url) }}"
                                            alt="Sample Track 3" class="w-20 h-20 rounded-lg mx-auto sm:mx-0">
                                        <div class="flex-1">
                                            <div class="flex space-x-2 md:space-x-3 mb-2">
                                                <h3 class="text-black dark:text-gray-100 font-semibold text-lg">
                                                    {{ $campaign_->music?->title }}
                                                </h3>
                                                <a href="{{ $campaign_->music?->permalink_url }}" target="_blank"
                                                    class="cursor-pointer">
                                                    <!-- Pencil Icon -->
                                                    <x-lucide-external-link
                                                        class="w-6 h-6 text-gray-500 hover:text-orange-500 transition-colors" />
                                                </a>
                                                <div class="flex items-center gap-2">
                                                    @if (!featuredAgain() && $campaign_->is_featured)
                                                        <span
                                                            class="text-xs font-semibold mr-2 px-2.5 py-0.5 rounded bg-orange-500 text-white">
                                                            {{ !featuredAgain() ? 'Featured' : '' }}
                                                        </span>
                                                    @endif
                                                    @if (!boostAgain() && $campaign_->is_boost)
                                                        <span
                                                            class="text-xs font-semibold mr-2 px-2.5 py-0.5 rounded bg-orange-500 text-white">
                                                            {{ !boostAgain() ? 'Boosted' : '' }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="mb-4 text-sm text-slate-400">
                                                Budget used: {{ number_format($campaign_->credits_spent) }} /
                                                {{ number_format($campaign_->budget_credits) }} credits
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right Stats Block -->
                                    <div class="text-right">
                                        <div class="flex items-center justify-center sm:justify-end">
                                            @if ($campaign_->status == \App\Models\Campaign::STATUS_OPEN)
                                                <x-lucide-trending-up class="m-2 w-5 h-5  text-green-600" />
                                                <span class=" text-green-600 dark:text-gray-100"> Running</span>
                                            @elseif ($campaign_->status == \App\Models\Campaign::STATUS_COMPLETED)
                                                <x-lucide-check-circle class="m-2 w-5 h-5  text-green-600" />
                                                <span class=" text-green-600 dark:text-gray-100"> Completed</span>
                                            @elseif ($campaign_->status == \App\Models\Campaign::STATUS_CANCELLED)
                                                <x-lucide-x-circle class="m-2 w-5 h-5  text-red-600" />
                                                <span class=" text-red-600 dark:text-gray-100"> Cancelled</span>
                                            @elseif ($campaign_->status == \App\Models\Campaign::STATUS_STOP)
                                                <x-lucide-x-circle class="m-2 w-5 h-5  text-red-600" />
                                                <span class=" text-red-600 dark:text-gray-100"> Stopped</span>
                                            @endif
                                        </div>
                                        <p class="text-slate-400 text-sm">{{ $campaign_->created_at_formatted }}</p>
                                        @if ($campaign_->status == \App\Models\Campaign::STATUS_OPEN)
                                            <div class="flex flex-wrap justify-end items-center mt-2">
                                                <button class="flex items-center cursor-pointer justify-start"
                                                    wire:click="stopCampaign({{ $campaign_->id }})">
                                                    <x-lucide-ban class="w-5 h-5 m-2 dark:text-white text-gray-500" />
                                                    <span class="text-slate-500">Stop</span>
                                                </button>
                                                <div wire:click="editCampaign({{ $campaign_->id }})"
                                                    class="flex items-center cursor-pointer">
                                                    <x-lucide-square-pen
                                                        class="w-5 h-5 m-2 dark:text-white text-gray-500" />
                                                    <span
                                                        class="font-medium cursor-pointer text-gray-800 dark:text-gray-100">Edit</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <hr class="my-1 border-gray-300 dark:border-gray-600" />

                                <!-- Stats -->
                                <div class="flex justify-between gap-6">
                                    <div class="flex gap-2 md:gap-6">
                                        <div class="text-center">
                                            <div class="flex items-center justify-center ">

                                                <x-lucide-repeat class="text-gray-500 w-5 h-5 m-2 dark:text-white" />
                                                <span
                                                    class=" text-black dark:text-white">{{ totalReposts($campaign_) }}</span>
                                            </div>

                                        </div>
                                        <!-- Repeat block with different data -->
                                        <div class="text-center">
                                            <div class="flex items-center justify-center ">
                                                <x-lucide-user-plus class="text-gray-500 w-5 h-5 m-2 dark:text-white" />
                                                <span
                                                    class=" text-black dark:text-gray-100">{{ $campaign_->followers_count ?? 0 }}</span>
                                            </div>

                                        </div>
                                        <div class="text-center">
                                            <div class="flex items-center justify-center ">
                                                <x-lucide-heart class="text-gray-500 w-5 h-5 m-2 dark:text-white" />
                                                <span
                                                    class=" text-black dark:text-gray-100">{{ $campaign_->like_count ?? 0 }}</span>
                                            </div>

                                        </div>
                                        <div class="text-center">
                                            <div class="flex items-center justify-center ">
                                                <x-lucide-message-square
                                                    class="text-gray-500 w-5 h-5 m-2 dark:text-white" />
                                                <span
                                                    class=" text-black dark:text-gray-100">{{ $campaign_->comment_count ?? 0 }}</span>
                                            </div>

                                        </div>
                                        <div class="text-center">
                                            <div class="flex items-center justify-center ">
                                                <x-lucide-play class="text-gray-500 w-5 h-5 m-2 dark:text-white" />
                                                <span
                                                    class=" text-black dark:text-gray-100">{{ $campaign_->playback_count ?? 0 }}</span>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <div class="flex items-center justify-center ">

                                            <span wire:click="openViewDetailsModal({{ $campaign_->id }})"
                                                class="text-orange-500 items-end font-medium mt-2 cursor-pointer hover:underline transition-all duration-300">Show
                                                All</span>
                                        </div>

                                    </div>

                                    {{-- <div>
                                        <p class="text-slate-400 text-sm">-.- avg. rating</p>
                                    </div> --}}
                                </div>
                                @if ($campaign_->status !== \App\Models\Campaign::STATUS_STOP)
                                    <div class="flex justify-end items-center gap-4 mt-2">
                                        @if (featuredAgain() && !$campaign_->is_featured)
                                            <div class="flex flex-wrap justify-center sm:justify-end gap-4">
                                                @if (proUser())
                                                    <x-gbutton variant="secondary"
                                                        wire:click="setFeatured({{ $campaign_->id }})">{{ __('Set Featured') }}</x-gbutton>
                                                @else
                                                    <x-gabutton variant="primary" wire:navigate
                                                        href="{{ route('user.plans') }}">Need to get featured?
                                                        (Pro)
                                                    </x-gabutton>
                                                @endif
                                            </div>
                                        @endif
                                        @if (boostAgain() && !$campaign_->is_boost)
                                            @if (proUser())
                                                <div>
                                                    <x-gbutton variant="secondary"
                                                        wire:click="freeBoost({{ $campaign_->id }})">{{ __('Free Boost') }}</x-gbutton>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        @if ($activeMainTab === 'all')
                            <div
                                class="flex flex-col items-center justify-center py-20 text-center bg-white dark:bg-gray-800 rounded-2xl shadow-lg">
                                <div
                                    class="w-20 h-20 bg-gradient-to-br from-orange-100 to-orange-200 dark:from-orange-900/20 dark:to-orange-800/20 rounded-full flex items-center justify-center mb-6">
                                    <x-lucide-megaphone class="w-10 h-10 text-orange-600 dark:text-orange-400" />
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-3">
                                    {{ __('No active campaigns found') }}
                                </h3>

                                <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-md">
                                    {{ __('Looks like there are no active campaigns right now. Why not start a new one and watch it grow?') }}
                                </p>
                                <button wire:click="toggleCampaignsModal" x-on:click="showCampaignsModal = true"
                                    class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white px-6 py-3 rounded-xl flex items-center gap-2 transition-all duration-300 shadow-lg hover:shadow-xl">
                                    <x-lucide-plus class="w-5 h-5" />
                                    {{ __('Create Your First Campaign') }}
                                </button>
                            </div>
                        @elseif ($activeMainTab === 'active')
                            <div
                                class="flex flex-col items-center justify-center py-20 text-center bg-white dark:bg-gray-800 rounded-2xl shadow-lg">
                                <div
                                    class="w-20 h-20 bg-gradient-to-br from-orange-100 to-orange-200 dark:from-orange-900/20 dark:to-orange-800/20 rounded-full flex items-center justify-center mb-6">
                                    <x-lucide-megaphone class="w-10 h-10 text-orange-600 dark:text-orange-400" />
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-3">
                                    {{ __('No active campaigns found') }}
                                </h3>
                                <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-md">
                                    {{ __("You haven't started any active campaigns yet. You can create new campaigns or view active campaigns.") }}
                                </p>
                            </div>
                        @elseif ($activeMainTab === 'completed')
                            <div
                                class="flex flex-col items-center justify-center py-20 text-center bg-white dark:bg-gray-800 rounded-2xl shadow-lg">
                                <div
                                    class="w-20 h-20 bg-gradient-to-br from-orange-100 to-orange-200 dark:from-orange-900/20 dark:to-orange-800/20 rounded-full flex items-center justify-center mb-6">
                                    <x-lucide-megaphone class="w-10 h-10 text-orange-600 dark:text-orange-400" />
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-3">
                                    {{ __('Oops! No completed campaigns yet.') }}
                                </h3>
                                <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-md">
                                    {{ __('It looks like there are no completed campaigns at the moment. Start a campaign today and track your progress!') }}
                                </p>
                            </div>
                        @elseif ($activeMainTab === 'cancelled')
                            <div
                                class="flex flex-col items-center justify-center py-20 text-center bg-white dark:bg-gray-800 rounded-2xl shadow-lg">
                                <div
                                    class="w-20 h-20 bg-gradient-to-br from-orange-100 to-orange-200 dark:from-orange-900/20 dark:to-orange-800/20 rounded-full flex items-center justify-center mb-6">
                                    <x-lucide-megaphone class="w-10 h-10 text-orange-600 dark:text-orange-400" />
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-3">
                                    {{ __('Oops! No cancelled campaigns yet.') }}
                                </h3>
                                <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-md">
                                    {{ __('It looks like there are no cancelled campaigns at the moment. Start a campaign today and track your progress!') }}
                                </p>
                            </div>
                        @endif
                    @endforelse

                    @if (isset($campaigns) && method_exists($campaigns, 'hasPages') && $campaigns->hasPages())
                        @if ($campaigns->hasPages())
                            <div class="mt-6">
                                {{ $campaigns->links('components.pagination.wire-navigate', [
                                    'pageName' => $activeMainTab . 'Page',
                                    'keep' => ['tab' => $activeMainTab],
                                ]) }}
                            </div>
                        @endif
                    @endif
                </div>

                <!-- Sidebar -->
                <aside class="lg:w-1/3 space-y-6 bg-white dark:bg-slate-800 rounded-sm lg:mt-0">
                    <!-- Tools Section -->
                    <div class=" dark:bg-slate-800 rounded-sm p-6">
                        <h2 class="text-lg font-bold text-gray-800 mb-4 text-black dark:text-gray-100">Tools and
                            FAQs</h2>
                        {{-- <ul class="space-y-3">
                            <li><a href="#" class="text-red-500 hover:underline">What is a campaign?</a>
                            </li>
                            <li><a href="#" class="text-red-500 hover:underline">How do I get the best out
                                    of my campaigns?</a></li>
                            <li><a href="#" class="text-red-500 hover:underline">Why did my campaign finish
                                    so quickly?</a></li>
                            <li><a href="#" class="text-red-500 hover:underline">Why is my campaign running
                                    slowly?</a></li>
                        </ul> --}}
                        <ul class="space-y-3">
                            @foreach ($faqs as $faq)
                                <li><a href="{{ route('user.faq') }}#faq-{{ $faq->id }}"
                                        class="text-red-500 hover:underline">{{ $faq->question }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Reach More Section -->
                    {{-- <div class="dark:bg-slate-800 rounded-sm p-6 text-black dark:text-gray-100">
                        <h2 class="text-lg font-bold text-gray-800 mb-4 dark:text-gray-100">Reach more people</h2>
                        <hr class="text-red-500 mb-4">
                        @if (featuredAgain() && $latestCampaign)
                            <div class="flex flex-col sm:flex-row items-center gap-4 mb-4">
                                <img src="{{ soundcloud_image($latestCampaign?->music?->artwork_url) }}"
                                    alt="Reach people icon" class="w-16 h-16 rounded-lg object-cover">
                                <div>
                                    <p class="font-bold text-gray-800  dark:text-gray-100">
                                        {{ $latestCampaign?->music?->title }}</p>
                                    <p class="text-gray-500 text-sm">{{ $latestCampaign?->music?->genre }}</p>
                                </div>
                            </div>
                        @elseif($featuredCampaign)
                            <div
                                class="flex flex-col sm:flex-row items-center gap-4 mb-4 relative border border-gray-200 dark:border-gray-700 p-2 rounded-lg">
                                <img src="{{ soundcloud_image($featuredCampaign?->music?->artwork_url) }}"
                                    alt="Reach people icon" class="w-16 h-16 rounded-lg object-cover">
                                <div>
                                    <p class="font-bold text-gray-800  dark:text-gray-100">
                                        {{ $featuredCampaign?->music?->title }}</p>
                                    <p class="text-gray-500 text-sm">{{ $featuredCampaign?->music?->genre }}</p>
                                    <div class="flex items-center gap-2 mt-2">
                                        @if (!featuredAgain($featuredCampaign->id) && $featuredCampaign->is_featured)
                                            <span
                                                class="text-xs font-semibold mr-2 px-2.5 py-0.5 rounded bg-orange-500 text-white">
                                                {{ !featuredAgain() ? 'Featured' : '' }}
                                            </span>
                                        @endif
                                        @if (!boostAgain($featuredCampaign->id) && $featuredCampaign->is_boost)
                                            <span
                                                class="text-xs font-semibold mr-2 px-2.5 py-0.5 rounded bg-orange-500 text-white">
                                                {{ !boostAgain() ? 'Boosted' : '' }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="flex justify-center">
                            @if (proUser())
                                @if (featuredAgain() && $latestCampaign)
                                    <x-gbutton wire:click="setFeatured({{ $latestCampaign->id }})"
                                        variant="primary">Get featured</x-gbutton>
                                @endif
                            @else
                                <x-gabutton variant="primary" wire:navigate href="{{ route('user.plans') }}"
                                    x-on:click="showUpgradeModal = true">Need to get featured?(Pro)</x-gabutton>
                            @endif
                        </div>
                    </div> --}}
                </aside>
            </div>
        </div>
    </div>

    {{-- ================================ Modals ================================ --}}

    {{-- Choose a track or playlist Modal --}}
    <div x-data="{ showCampaignsModal: @entangle('showCampaignsModal').live }" x-show="showCampaignsModal" x-cloak
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">

        <div
            class="w-full max-w-3xl mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col max-h-[80vh] overflow-hidden">
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
                            <img src="{{ asset('assets/favicons/fav icon 2 (1).svg') }}"
                                alt="{{ config('app.name') }}" class="w-12 hidden dark:block" />
                        @endif
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ __('Choose a track or playlist') }}
                    </h2>
                </div>
                <button x-on:click="showCampaignsModal = false"
                    class="w-10 h-10 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>

            @if ($showCampaignsModal)
                <div class="flex border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <button wire:click="selectModalTab('tracks')"
                        class="flex-1 py-4 px-6 text-center font-semibold text-base transition-all duration-300 ease-in-out border-b-2 hover:bg-white dark:hover:bg-gray-700 {{ $activeModalTab === 'tracks' ? 'border-orange-500 text-orange-600 bg-white dark:bg-gray-700' : 'border-transparent text-gray-600 dark:text-gray-400' }}">
                        <div class="flex items-center justify-center gap-2">
                            <x-lucide-music class="w-4 h-4" />
                            {{ __('Tracks') }}
                        </div>
                    </button>
                    <button wire:click="selectModalTab('playlists')"
                        class="flex-1 py-4 px-6 text-center font-semibold text-base transition-all duration-300 ease-in-out border-b-2 hover:bg-white dark:hover:bg-gray-700 {{ $activeModalTab === 'playlists' ? 'border-orange-500 text-orange-600 bg-white dark:bg-gray-700' : 'border-transparent text-gray-600 dark:text-gray-400' }}">
                        <div class="flex items-center justify-center gap-2">
                            <x-lucide-list-music class="w-4 h-4" />
                            {{ __('Playlists') }}
                        </div>
                    </button>
                </div>
                <div class="flex-grow overflow-y-auto">
                    <div class="p-5 sticky top-0 bg-white dark:bg-slate-800">
                        <label for="track-link-search" class="text-xl font-semibold text-gray-700 dark:text-gray-200">
                            @if ($activeModalTab === 'tracks')
                                Paste a SoundCloud track link
                            @else
                                Paste a SoundCloud playlist link
                            @endif
                        </label>
                        <form wire:submit.prevent="searchSoundcloud">
                            <div class="flex w-full mt-2">
                                <input wire:model="searchQuery" type="text" id="track-link-search"
                                    placeholder="{{ $activeModalTab === 'tracks' ? 'Paste a SoundCloud track link' : 'Paste a SoundCloud playlist link' }}"
                                    class="flex-grow p-3 text-gray-700 dark:text-gray-200 bg-white dark:bg-slate-700 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-0 focus:border-orange-500 transition-colors duration-200 border-2 border-gray-300 dark:border-gray-600 ">
                                <button type="submit"
                                    class="bg-orange-500 text-white p-3 w-14 flex items-center justify-center hover:bg-orange-600 transition-colors duration-200">

                                    <span wire:loading.remove wire:target="searchSoundcloud">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </span>

                                    <span wire:loading wire:target="searchSoundcloud">
                                        <!-- Loading Spinner -->
                                        <svg class="animate-spin h-5 w-5 text-white"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16v-4l-3 3 3 3v-4a8 8 0 01-8-8z">
                                            </path>
                                        </svg>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="p-4">
                        @if ($activeModalTab === 'tracks' || $playListTrackShow == true)
                            <div class="space-y-3" wire:loading.remove wire:target="searchSoundcloud">
                                @forelse ($tracks as $track_)
                                    <div wire:click="toggleSubmitModal('track', {{ $track_->id }})"
                                        class="p-2 flex items-center space-x-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700 rounded-xl transition-all duration-200 border border-transparent hover:border-orange-200 dark:hover:border-orange-800 group">
                                        <div class="flex-shrink-0">
                                            <img class="h-14 w-14 rounded-xl object-cover shadow-md"
                                                src="{{ soundcloud_image($track_->artwork_url) }}"
                                                alt="{{ $track_->title }}" />
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p
                                                class="text-base font-semibold text-gray-900 dark:text-white truncate group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">
                                                {{ $track_->title }}
                                            </p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                                {{ __('by') }}
                                                <strong
                                                    class="text-orange-600 dark:text-orange-400">{{ $track_->author_username }}</strong>
                                                <span class="ml-2 text-xs text-gray-400">{{ $track_->genre }}</span>
                                            </p>
                                            <span
                                                class="bg-gray-100 dark:bg-slate-600 text-xs px-3 py-1 rounded-full text-gray-700 dark:text-gray-300 mt-2 font-mono flex items-start justify-center w-fit gap-3">
                                                <x-lucide-audio-lines class="w-4 h-4" />
                                                {{ $track_->playback_count }}</span>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <x-lucide-chevron-right
                                                class="w-5 h-5 text-gray-400 group-hover:text-orange-500 transition-colors" />
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-16 text-gray-500 dark:text-gray-400">
                                        <div
                                            class="w-16 h-16 bg-orange-100 dark:bg-orange-900/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <x-lucide-music class="w-8 h-8 text-orange-500" />
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                            {{ __('No tracks found') }}
                                        </h3>
                                        <p class="text-gray-500 dark:text-gray-400">
                                            {{ __('Add one to get started with campaigns.') }}
                                        </p>
                                    </div>
                                @endforelse

                                {{-- Load More Button for Tracks --}}
                                @if ($hasMoreTracks)
                                    <div class="text-center mt-4">
                                        <button wire:click="loadMoreTracks" wire:loading.attr="disabled"
                                            class="bg-orange-500 text-white font-semibold py-2 px-4 rounded-lg hover:bg-orange-600 transition-colors duration-200 disabled:bg-orange-300 disabled:cursor-not-allowed">
                                            <span wire:loading.remove wire:target="loadMoreTracks">
                                                Load More
                                            </span>
                                            <span wire:loading wire:target="loadMoreTracks">
                                                Loading...
                                            </span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <div wire:loading wire:target="searchSoundcloud"
                                class="w-full flex justify-center items-center">
                                <div class="text-center py-16 text-orange-600">
                                    <div
                                        class="w-16 h-16 bg-orange-100 dark:bg-orange-900/20 rounded-full flex items-center justify-center mx-auto mb-4 animate-spin">
                                        <svg class="w-8 h-8 text-orange-500" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16v-4l-3 3 3 3v-4a8 8 0 01-8-8z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium">Searching Track...</p>
                                </div>
                            </div>
                        @elseif($activeModalTab === 'playlists')
                            <div class="space-y-3" wire:loading.remove wire:target="searchSoundcloud">
                                @forelse ($playlists as $playlist_)
                                    <div wire:click="toggleSubmitModal('playlist', {{ $playlist_->id }})"
                                        class="p-4 flex items-center space-x-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700 rounded-xl transition-all duration-200 border border-transparent hover:border-orange-200 dark:hover:border-orange-800 group">
                                        <div class="flex-shrink-0">
                                            <img class="h-14 w-14 rounded-xl object-cover shadow-md"
                                                src="{{ soundcloud_image($playlist_->artwork_url) }}"
                                                alt="{{ $playlist_->title }}" />
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p
                                                class="text-base font-semibold text-gray-900 dark:text-white truncate group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">
                                                {{ $playlist_->title }}
                                            </p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                                {{ $playlist_->track_count }} {{ __('tracks') }}
                                            </p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <x-lucide-chevron-right
                                                class="w-5 h-5 text-gray-400 group-hover:text-orange-500 transition-colors" />
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-16 text-gray-500 dark:text-gray-400">
                                        <div
                                            class="w-16 h-16 bg-orange-100 dark:bg-orange-900/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <x-lucide-list-music class="w-8 h-8 text-orange-500" />
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                            {{ __('No playlists found') }}
                                        </h3>
                                        <p class="text-gray-500 dark:text-gray-400">
                                            {{ __('Add one to get started with campaigns.') }}
                                        </p>
                                    </div>
                                @endforelse

                                {{-- Load More Button for Playlists --}}
                                @if ($hasMorePlaylists)
                                    <div class="text-center mt-4">
                                        <button wire:click="loadMorePlaylists" wire:loading.attr="disabled"
                                            class="bg-orange-500 text-white font-semibold py-2 px-4 rounded-lg hover:bg-orange-600 transition-colors duration-200 disabled:bg-orange-300 disabled:cursor-not-allowed">
                                            <span wire:loading.remove wire:target="loadMorePlaylists">
                                                Load More
                                            </span>
                                            <span wire:loading wire:target="loadMorePlaylists">
                                                Loading...
                                            </span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <div wire:loading wire:target="searchSoundcloud"
                                class="w-full flex justify-center items-center">
                                <div class="text-center py-16 text-orange-600">
                                    <div
                                        class="w-16 h-16 bg-orange-100 dark:bg-orange-900/20 rounded-full flex items-center justify-center mx-auto mb-4 animate-spin">
                                        <svg class="w-8 h-8 text-orange-500" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16v-4l-3 3 3 3v-4a8 8 0 01-8-8z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium">Searching Playlist...</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Low Credit Warning Modal --}}
    <div x-data="{ showLowCreditWarningModal: @entangle('showLowCreditWarningModal').live }" x-show="showLowCreditWarningModal" x-cloak
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">

        <div
            class="w-full max-w-md mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden">
            <div
                class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-500 rounded-xl flex items-center justify-center">
                        <x-lucide-triangle-alert class="w-5 h-5 text-white" />
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ __('Low Credit Warning') }}
                    </h2>
                </div>
                <button x-on:click="showLowCreditWarningModal = false"
                    class="w-10 h-10 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>

            <div class="p-6 text-center">
                <div
                    class="w-20 h-20 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <x-lucide-wallet class="w-10 h-10 text-red-600 dark:text-red-400" />
                </div>
                <p class="text-lg text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('You need a minimum of 50 credits to create a campaign.') }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                    {{ __('Please add more credits to your account to proceed with campaign creation.') }}
                </p>
                <a href="{{ route('user.add-credits') }}" wire:navigate
                    class="inline-flex items-center justify-center w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <x-lucide-plus class="w-5 h-5 inline mr-2" />
                    {{ __('Buy Credits Now') }}
                </a>
            </div>
        </div>
    </div>

    {{-- Create/Edit Campaign Modal --}}
    @include('backend.user.includes.campaign-create-modal')

    {{-- Add Credit Modal --}}
    <div x-data="{ showAddCreditModal: @entangle('showAddCreditModal').live }" x-show="showAddCreditModal" x-cloak
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">

        <div
            class="w-full max-w-xl mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden">
            <div
                class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center">
                        <x-lucide-wallet class="w-5 h-5 text-white" />
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ __('Add Credits to Campaign') }}
                    </h2>
                </div>
                <button x-on:click="showAddCreditModal = false"
                    class="w-10 h-10 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>

            <div class="flex-grow overflow-y-auto p-6">
                <form wire:submit.prevent="addCreditsToCampaign" class="space-y-6">
                    <div class="space-y-2">
                        <label for="add_credit_cost_per_repost"
                            class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                            <x-lucide-coins class="w-4 h-4 text-orange-500" />
                            {{ __('New Cost per Repost (credits)') }}
                        </label>
                        <input type="number" id="add_credit_cost_per_repost"
                            wire:model.live="addCreditCostPerRepost" min="1"
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                            placeholder="{{ __('Enter new cost per repost') }}">
                        @error('addCreditCostPerRepost')
                            <div class="flex items-center gap-2 text-red-600 dark:text-red-400 text-sm">
                                <x-lucide-alert-circle class="w-4 h-4" />
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    {{-- Budget Warning Display --}}
                    @if ($showBudgetWarning)
                        <div
                            class="bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-xl p-4 border border-red-200 dark:border-red-800">
                            <div class="flex items-center gap-3 flex-col">
                                <div class="flex items-center gap-3">
                                    <x-lucide-alert-triangle class="w-5 h-5 text-red-600 dark:text-red-400" />
                                    <div>
                                        <p class="text-sm font-semibold text-red-900 dark:text-red-100">
                                            {{ __('Budget Warning') }}
                                        </p>
                                        <p class="text-sm text-red-600 dark:text-red-400">
                                            {{ $budgetWarningMessage }}
                                        </p>
                                    </div>
                                </div>
                                @if (str_contains($budgetWarningMessage, 'need') && str_contains($budgetWarningMessage, 'more credits'))
                                    <a href="{{ route('user.add-credits') }}" wire:navigate
                                        class="ml-auto bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg text-xs font-medium transition-colors">
                                        {{ __('Buy Credits') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Budget Display --}}
                    @if (
                        $addCreditCostPerRepost &&
                            $addCreditTargetReposts &&
                            $addCreditCostPerRepost > 0 &&
                            $addCreditTargetReposts > 0 &&
                            !$showBudgetWarning)
                        <div
                            class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-4 border border-blue-200 dark:border-blue-800">
                            <div class="flex items-center gap-3">
                                <x-lucide-calculator class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                                <div>
                                    <p class="text-sm font-semibold text-blue-900 dark:text-blue-100">
                                        {{ __('New Total Campaign Budget') }}
                                    </p>
                                    <p class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                        {{ number_format($addCreditCostPerRepost * $addCreditTargetReposts) }}
                                        {{ __('credits') }}
                                    </p>
                                    @if ($addCreditCreditsNeeded > 0)
                                        <p class="text-sm text-blue-600 dark:text-blue-400">
                                            {{ __('Additional credits needed:') }}
                                            {{ number_format($addCreditCreditsNeeded) }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit"
                            class="w-full transition-all duration-300 flex items-center justify-center gap-3 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-bold py-4 px-6 rounded-xl {{ $canSubmit ? 'bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white' : 'bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed' }}"
                            wire:loading.attr="disabled" @if (!$canSubmit) disabled @endif>
                            <x-lucide-plus class="w-4 h-4" />
                            <span wire:loading.remove wire:target="addCreditsToCampaign">
                                {{ __('Add Credits') }}
                            </span>
                            <span wire:loading wire:target="addCreditsToCampaign">
                                {{ __('Adding...') }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Campaign Modal --}}
    <div x-data="{ showEditCampaignModal: @entangle('showEditCampaignModal').live }" x-show="showEditCampaignModal" x-cloak
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">

        <div
            class="w-full max-w-4xl mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col max-h-[85vh] overflow-hidden">
            <div
                class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center">
                        <x-lucide-edit class="w-5 h-5 text-white" />
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ __('Edit Campaign') }}
                    </h2>
                </div>
                <button x-on:click="showEditCampaignModal = false"
                    class="w-10 h-10 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>

            <div class="flex-grow overflow-y-auto p-6">
                <form wire:submit.prevent="updateCampaign" class="space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="edit_campaign_title"
                                class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                <x-lucide-type class="w-4 h-4 text-orange-500" />
                                {{ __('Campaign name') }}
                            </label>
                            <input type="text" id="edit_campaign_title" wire:model.live="editTitle"
                                class="w-full rounded-xl border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                                placeholder="{{ __('Enter campaign name') }}">
                            @error('editTitle')
                                <div class="flex items-center gap-2 text-red-600 dark:text-red-400 text-sm">
                                    <x-lucide-alert-circle class="w-4 h-4" />
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="edit_campaign_end_date"
                                class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                <x-lucide-calendar class="w-4 h-4 text-orange-500" />
                                {{ __('Campaign expiration date') }}
                            </label>
                            <input type="date" id="edit_campaign_end_date" wire:model.live="editEndDate"
                                class="w-full rounded-xl border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200">
                            @error('editEndDate')
                                <div class="flex items-center gap-2 text-red-600 dark:text-red-400 text-sm">
                                    <x-lucide-alert-circle class="w-4 h-4" />
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="edit_campaign_description"
                            class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                            <x-lucide-file-text class="w-4 h-4 text-orange-500" />
                            {{ __('Campaign description') }}
                        </label>
                        <textarea id="edit_campaign_description" wire:model.live="editDescription" rows="4"
                            class="w-full rounded-xl border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 resize-none"
                            placeholder="{{ __('Describe your campaign goals and target audience...') }}"></textarea>
                        @error('editDescription')
                            <div class="flex items-center gap-2 text-red-600 dark:text-red-400 text-sm">
                                <x-lucide-alert-circle class="w-4 h-4" />
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="edit_campaign_cost_per_repost"
                                class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                <x-lucide-coins class="w-4 h-4 text-orange-500" />
                                {{ __('Cost per repost (credits)') }}
                            </label>
                            <input type="number" id="edit_campaign_cost_per_repost"
                                wire:model.live="editCostPerRepost" min="1"
                                class="w-full rounded-xl border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                                placeholder="{{ __('Enter cost per repost') }}">
                            @error('editCostPerRepost')
                                <div class="flex items-center gap-2 text-red-600 dark:text-red-400 text-sm">
                                    <x-lucide-alert-circle class="w-4 h-4" />
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="edit_campaign_target_reposts"
                                class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                <x-lucide-target class="w-4 h-4 text-orange-500" />
                                {{ __('Campaign target repost count') }}
                            </label>
                            <input type="number" id="edit_campaign_target_reposts"
                                wire:model.live="editTargetReposts" min="1"
                                class="w-full rounded-xl border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                                placeholder="{{ __('Enter target reposts') }}">
                            @error('editTargetReposts')
                                <div class="flex items-center gap-2 text-red-600 dark:text-red-400 text-sm">
                                    <x-lucide-alert-circle class="w-4 h-4" />
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>

                    {{-- Budget Warning Display --}}
                    @if ($showBudgetWarning)
                        <div
                            class="bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-xl p-4 border border-red-200 dark:border-red-800">
                            <div class="flex items-center gap-3">
                                <x-lucide-alert-triangle class="w-5 h-5 text-red-600 dark:text-red-400" />
                                <div>
                                    <p class="text-sm font-semibold text-red-900 dark:text-red-100">
                                        {{ __('Budget Warning') }}
                                    </p>
                                    <p class="text-sm text-red-600 dark:text-red-400">
                                        {{ $budgetWarningMessage }}
                                    </p>
                                </div>
                                @if (str_contains($budgetWarningMessage, 'need') && str_contains($budgetWarningMessage, 'more credits'))
                                    <a href="{{ route('user.add-credits') }}" wire:navigate
                                        class="ml-auto bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg text-xs font-medium transition-colors">
                                        {{ __('Buy Credits') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Budget Display --}}
                    @if ($editCostPerRepost && $editTargetReposts && $editCostPerRepost > 0 && $editTargetReposts > 0 && !$showBudgetWarning)
                        <div
                            class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-4 border border-blue-200 dark:border-blue-800">
                            <div class="flex items-center gap-3">
                                <x-lucide-calculator class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                                <div>
                                    <p class="text-sm font-semibold text-blue-900 dark:text-blue-100">
                                        {{ __('New Total Campaign Budget') }}
                                    </p>
                                    <p class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                        {{ number_format($editCostPerRepost * $editTargetReposts) }}
                                        {{ __('credits') }}
                                    </p>
                                    @if ($editOriginalBudget && $editCostPerRepost * $editTargetReposts > $editOriginalBudget)
                                        <p class="text-sm text-blue-600 dark:text-blue-400">
                                            {{ __('Additional credits needed:') }}
                                            {{ number_format($editCostPerRepost * $editTargetReposts - $editOriginalBudget) }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit"
                            class="w-full transition-all duration-300 flex items-center justify-center gap-3 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-bold py-4 px-6 rounded-xl {{ $canSubmit ? 'bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white' : 'bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed' }}"
                            wire:loading.attr="disabled" @if (!$canSubmit) disabled @endif>
                            <span wire:loading.remove wire:target="updateCampaign">
                                <x-lucide-save class="w-5 h-5" />
                            </span>
                            <span wire:loading wire:target="updateCampaign">
                                <svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </span>
                            <span wire:loading.remove wire:target="updateCampaign">
                                {{ __('Save Changes') }}
                            </span>
                            <span wire:loading wire:target="updateCampaign">{{ __('Saving...') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Cancel Warning Modal --}}
    <div x-data="{ showCancelWarningModal: @entangle('showCancelWarningModal').live }" x-show="showCancelWarningModal" x-cloak
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">

        <div
            class="w-full max-w-lg mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden">
            <div
                class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-500 rounded-xl flex items-center justify-center">
                        <x-lucide-alert-triangle class="w-5 h-5 text-white" />
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ __('Confirm Cancellation') }}
                    </h2>
                </div>
                <button x-on:click="showCancelWarningModal = false"
                    class="w-10 h-10 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>

            <div class="p-6 text-center">
                <div
                    class="w-20 h-20 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <x-lucide-trash-2 class="w-10 h-10 text-red-600 dark:text-red-400" />
                </div>
                <p class="text-lg text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('Are you sure you want to cancel this campaign? You will not be able to recover it.') }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                    {{ __('If you cancel this campaign, you will receive a 50% refund of the remaining budget: :amount credits.', ['amount' => number_format($refundAmount)]) }}
                </p>
                <div class="flex justify-center gap-4">
                    <button type="button" x-on:click="showCancelWarningModal = false"
                        class="flex-1 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-bold py-3 px-6 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        {{ __('Keep Campaign') }}
                    </button>
                    <button wire:click="cancelCampaign"
                        class="flex-1 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="cancelCampaign">{{ __('Confirm') }}</span>
                        <span wire:loading wire:target="cancelCampaign">{{ __('Cancelling...') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Already Cancel Warning Modal --}}
    <div x-data="{ showAlreadyCancelledModal: @entangle('showAlreadyCancelledModal').live }" x-show="showAlreadyCancelledModal" x-cloak
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">

        <div
            class="w-full max-w-lg mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden">
            <div
                class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-500 rounded-xl flex items-center justify-center">
                        <x-lucide-alert-triangle class="w-5 h-5 text-white" />
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ __('Campaign Cancelled') }}
                    </h2>
                </div>
                <button x-on:click="showAlreadyCancelledModal = false"
                    class="w-10 h-10 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>

            <div class="p-6 text-center">
                <div
                    class="w-20 h-20 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <x-lucide-alert-triangle class="w-10 h-10 text-red-600 dark:text-red-400" />
                </div>
                <p class="text-lg text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('This campaign has already been cancelled.') }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                    {{ __('This campaign has already been cancelled. You cannot make any changes to this campaign.') }}
                </p>
            </div>
        </div>
    </div>

    {{-- Details Modal --}}
    <div x-data="{ showDetailsModal: @entangle('showDetailsModal').live }" x-show="showDetailsModal" x-cloak
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">

        <div
            class="w-full max-w-5xl mx-auto rounded-2xl shadow-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden">

            @if ($showDetailsModal)
                <div
                    class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20">
                    <div class="flex items-center gap-3">
                        <div>
                            @if (app_setting('favicon') && app_setting('favicon_dark'))
                                <img src="{{ storage_url(app_setting('favicon')) }}"
                                    alt="{{ config('app.name') }}" class="w-12 dark:hidden" />
                                <img src="{{ storage_url(app_setting('favicon_dark')) }}"
                                    alt="{{ config('app.name') }}" class="w-12 hidden dark:block" />
                            @else
                                <img src="{{ asset('assets/favicons/fav icon 1.svg') }}"
                                    alt="{{ config('app.name') }}" class="w-12 dark:hidden" />
                                <img src="{{ asset('assets/favicons/fav icon 2 (1).svg') }}"
                                    alt="{{ config('app.name') }}" class="w-12 hidden dark:block" />
                            @endif
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ __('Campaign Details') }}
                        </h2>
                    </div>
                    <button wire:click="closeViewDetailsModal()"
                        class="w-10 h-10 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-all duration-200 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                        <x-lucide-x class="w-5 h-5" />
                    </button>
                </div>

                <div class="p-6 text-center max-h-[80vh] overflow-y-auto">
                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="w-full md:w-1/3 max-w-56 rounded-xl shadow-lg overflow-hidden">
                            <img class="w-full h-full object-cover"
                                src="{{ soundcloud_image($campaign->music?->artwork_url) }}">
                        </div>

                        <div class="flex-1">
                            <h2 class="text-3xl font-bold mb-2 text-gray-900 dark:text-white">
                                {{ $campaign->title ?? $campaign->music?->title }}</h2>
                            <p class="text-orange-500 mb-2">by {{ $campaign->user?->name ?? 'Unknown' }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                Genre: <span
                                    class="text-black dark:text-white">{{ $campaign->music?->genre ?? 'Unknown' }}</span>
                            </p>
                            <p class="text-gray-700 dark:text-gray-300 text-sm mb-4">
                                {{ $campaign->description ?? 'No description provided' }}
                            </p>

                            {{-- <div class="bg-gray-200 dark:bg-gray-800 p-4 rounded-lg">
                                <p class="text-gray-600 dark:text-gray-400 text-sm mb-2">Repost Progress:</p>
                                <div class="w-full h-3 bg-gray-300 dark:bg-gray-700 rounded-full">
                                    <div class="h-3 bg-orange-500 rounded-full"
                                        style="width: {{ ($campaign->completed_reposts / $campaign->target_reposts) * 100 }}%">
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>

                    <!-- Campaign Stats -->
                    <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        <div class="bg-gray-100 dark:bg-slate-700 p-5 rounded-lg shadow">
                            <h4 class="text-gray-600 dark:text-gray-400 text-sm">Total Reposts</h4>
                            <p class="text-xl font-bold text-black dark:text-white">
                                {{ totalReposts($campaign) }}
                            </p>
                        </div>
                        <div class="bg-gray-100 dark:bg-slate-700 p-5 rounded-lg shadow">
                            <h4 class="text-gray-600 dark:text-gray-400 text-sm">Playback Count</h4>
                            <p class="text-xl font-bold text-black dark:text-white">{{ $campaign->playback_count }}
                            </p>
                        </div>
                        <div class="bg-gray-100 dark:bg-slate-700 p-5 rounded-lg shadow">
                            <h4 class="text-gray-600 dark:text-gray-400 text-sm">Budget</h4>
                            <p class="text-xl font-bold text-black dark:text-white">
                                {{ number_format($campaign->budget_credits) }}</p>
                        </div>
                        <div class="bg-gray-100 dark:bg-slate-700 p-5 rounded-lg shadow">
                            <h4 class="text-gray-600 dark:text-gray-400 text-sm">Credits Spent</h4>
                            <p class="text-xl font-bold text-black dark:text-white">
                                {{ number_format($campaign->credits_spent) }}</p>
                        </div>
                        <div class="bg-gray-100 dark:bg-slate-700 p-5 rounded-lg shadow">
                            <h4 class="text-gray-600 dark:text-gray-400 text-sm">Max Reposts Last 24h</h4>
                            <p class="text-xl font-bold text-black dark:text-white">
                                {{ number_format($campaign->max_repost_last_24_h) }}</p>
                        </div>
                        <div class="bg-gray-100 dark:bg-slate-700 p-5 rounded-lg shadow">
                            <h4 class="text-gray-600 dark:text-gray-400 text-sm">Max Reposts per Day</h4>
                            <p class="text-xl font-bold text-black dark:text-white">
                                {{ number_format($campaign->max_repost_per_day) }}</p>
                        </div>
                        <div class="bg-gray-100 dark:bg-slate-700 p-5 rounded-lg shadow">
                            <h4 class="text-gray-600 dark:text-gray-400 text-sm">Max Followers</h4>
                            <p class="text-xl font-bold text-black dark:text-white">
                                {{ number_format($campaign->max_followers) }}</p>
                        </div>
                        <div class="bg-gray-100 dark:bg-slate-700 p-5 rounded-lg shadow">
                            <h4 class="text-gray-600 dark:text-gray-400 text-sm">Favourites</h4>
                            <p class="text-xl font-bold text-black dark:text-white">
                                {{ number_format($campaign->favorite_count) }}</p>
                        </div>
                        <div class="bg-gray-100 dark:bg-slate-700 p-5 rounded-lg shadow">
                            <h4 class="text-gray-600 dark:text-gray-400 text-sm">Followers</h4>
                            <p class="text-xl font-bold text-black dark:text-white">
                                {{ number_format($campaign->followers_count) }}</p>
                        </div>
                        <div class="bg-gray-100 dark:bg-slate-700 p-5 rounded-lg shadow">
                            <h4 class="text-gray-600 dark:text-gray-400 text-sm">Likes</h4>
                            <p class="text-xl font-bold text-black dark:text-white">
                                {{ number_format($campaign->like_count) }}</p>
                        </div>
                        <div class="bg-gray-100 dark:bg-slate-700 p-5 rounded-lg shadow">
                            <h4 class="text-gray-600 dark:text-gray-400 text-sm">Comments</h4>
                            <p class="text-xl font-bold text-black dark:text-white">
                                {{ number_format($campaign->comment_count) }}</p>
                        </div>
                    </div>

                    <!-- Campaign Info -->
                    <div class="mt-10 bg-gray-100 dark:bg-slate-700 p-6 rounded-xl shadow">
                        <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Campaign Settings</h3>
                        <div
                            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 text-sm text-gray-700 dark:text-gray-300">
                            <p><span class="font-medium text-black dark:text-white">Status:</span>
                                {{ $campaign->status_label }}</p>
                            <p><span class="font-medium text-black dark:text-white">Start Date:</span>
                                {{ $campaign->start_date_formatted }}</p>
                            <p><span class="font-medium text-black dark:text-white">End Date:</span>
                                {{ $campaign->end_date_formatted }}</p>
                            <p><span class="font-medium text-black dark:text-white">Featured:</span>
                                {{ $campaign->feature_label }}</p>
                        </div>
                    </div>
                    <div
                        class="mt-10 w-full mx-auto bg-gray-100 dark:bg-gradient-to-r dark:from-zinc-700 dark:to-zinc-900 p-4 rounded-lg">
                        <div id="soundcloud-player-{{ $campaign->id }}" data-campaign-id="{{ $campaign->id }}"
                            wire:ignore>
                            <x-sound-cloud.sound-cloud-player :track="$campaign->music" :height="166" :visual="false" />
                        </div>
                    </div>

                </div>
            @endif
        </div>
    </div>
</section>
