<div x-data="{ ...trackPlaybackManagerForRequests(), dashboardSummary: false, activeMainTab: @entangle('activeMainTab').live }" @clearRequestTracking.window="clearAllTracking()"
    @reset-widget-initiallized.window="$data.init()">

    <x-slot name="page_slug">request</x-slot>

    <section class="flex flex-col lg:flex-row gap-4 lg:gap-6">
        {{-- Left Side --}}
        <div class="w-full">
            <div class="flex justify-between 4xl:block">
                <div
                    class="flex 4xl:flex-row 4xl:justify-between 4xl:items-center mb-5 space-y-3 4xl:space-y-0 flex-col-reverse">
                    <div>
                        <h1 class="text-4xl text-black dark:text-gray-100 font-bold">
                            {{ __('Repost Requests') }}
                        </h1>
                    </div>
                    <x-gbutton variant="primary" wire:navigate href="{{ route('user.members') }}"
                        class="w-[230px] 4xl:w-auto mb-2 4xl:mb-0">
                        <span><x-lucide-plus class="w-5 h-5 mr-1" /></span>
                        Send a New Request
                    </x-gbutton>
                </div>
                <div class="4xl:hidden">
                    <button @click="dashboardSummary = !dashboardSummary"
                        class="flex items-center gap-1 text-sm text-orange-500">
                        <template x-if="!dashboardSummary">
                            <span class="flex items-center gap-1">
                                <span>Show Stats</span>
                                <x-lucide-chevron-down class="w-4 h-4" />
                            </span>
                        </template>
                        <template x-if="dashboardSummary">
                            <span class="flex items-center gap-1">
                                <span>Hide Stats</span>
                                <x-lucide-chevron-up class="w-4 h-4" />
                            </span>
                        </template>
                    </button>
                </div>
            </div>

            <div x-show="dashboardSummary" class="4xl:hidden mb-8" x-cloak x-transition>
                <x-dashboard-summary :earnings="user()->repost_price" :dailyRepostCurrent="$data['dailyRepostCurrent']" :dailyRepostMax="20" :responseRate="user()->responseRate()"
                    :pendingRequests="$data['pendingRequests']" :requestLimit="25" :credits="userCredits()" :campaigns="$data['totalMyCampaign']" :campaignLimit="proUser() ? 10 : 2" />
            </div>

            <div class="mb-8">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="-mb-px flex space-x-8">
                        <a href="{{ route('user.reposts-request') }}?tab=incoming_request" wire:navigate
                            class="tab-button @if ($activeMainTab === 'incoming_request' || $activeMainTab === 'accept_requests') active border-b-2 border-orange-500 text-orange-600 @else border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-3 px-2 text-sm font-semibold transition-all duration-200">
                            {{ __('Incoming requests') }}
                        </a>
                        <a href="{{ route('user.reposts-request') }}?tab=outgoing_request" wire:navigate
                            class="tab-button @if ($activeMainTab === 'outgoing_request') active border-b-2 border-orange-500 text-orange-600 @else border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-3 px-2 text-sm font-semibold transition-all duration-200">
                            {{ __('Outgoing request') }}
                        </a>
                        <a href="{{ route('user.reposts-request') }}?tab=previously_reposted" wire:navigate
                            class="tab-button @if ($activeMainTab === 'previously_reposted') active border-b-2 border-orange-500 text-orange-600 @else border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-3 px-2 text-sm font-semibold transition-all duration-200">
                            {{ __('Previously Reposted') }}
                        </a>
                    </nav>
                </div>
            </div>

            @if ($activeMainTab == 'incoming_request' || $activeMainTab == 'accept_requests')
                <div
                    class="flex flex-col md:flex-row md:items-start md:space-x-3 p-4 rounded bg-gray-100 dark:bg-gray-800 mb-8 gap-8">
                    <div class="flex items-top space-x-2 mb-2 md:mb-0">
                        <svg class="w-4 h-4 text-sky-600 dark:text-sky-400" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10 2a6 6 0 00-4 10.49V15a1 1 0 001 1h6a1 1 0 001-1v-2.51A6 6 0 0010 2zm1 13h-2v-1h2v1zm1-2H8v-1h4v1zm-.44-2.93a1 1 0 01-.32 1.36L10 11l-.24-.14a1 1 0 111.36-1.36l.44.57z" />
                        </svg>
                        <span class="text-sm font-medium text-gray-700 dark:text-white">Quick Tip</span>
                    </div>
                    <div class="flex-1 flex flex-col space-y-2">
                        <p class="text-sm text-gray-700 dark:text-white">
                            Your response rate could be better! Improve your response rate and get more requests by
                            accepting OR declining requests quickly.
                        </p>
                        <div class="flex items-center space-x-1">
                            <div class="w-4 h-4 rounded-full bg-orange-500"></div>
                            <span class="text-xs text-orange-600 font-semibold">{{ user()->responseRate() }}% <span
                                    class="text-gray-900 dark:text-white">Response rate.</span></span>
                            <button wire:click="responseReset" {{ user()->canResetResponseRate() ? '' : 'disabled' }}
                                class="text-xs text-red-500 font-semibold {{ user()->canResetResponseRate() ? 'underline cursor-pointer' : '!cursor-not-allowed' }}">Reset</button>
                        </div>
                        <div x-data="{ on: {{ $requestReceiveable ? 'false' : 'true' }} }"
                            class="inline-flex items-center {{ user()->email_verified_at ? 'cursor-pointer' : 'cursor-not-allowed' }}"
                            @if (user()->email_verified_at) wire:click="requestReceiveableToggle" @endif>
                            <input type="checkbox" class="sr-only peer" wire:model.live="requestReceiveable"
                                {{ $requestReceiveable ? 'checked' : '' }}
                                {{ user()->email_verified_at ? '' : 'disabled' }}>
                            <div class="flex items-center space-x-2">
                                <div class="w-7 h-4 rounded-full relative transition-colors"
                                    :class="on ? 'bg-green-500' : 'bg-gray-300'"
                                    @if (user()->email_verified_at) @click="on = !on" @endif>
                                    <div class="absolute top-0 left-0 w-4 h-4 bg-white rounded-full shadow transition-transform duration-300"
                                        :class="on ? 'translate-x-4' : 'translate-x-0'"></div>
                                </div>
                                <span class="text-sm text-gray-700 dark:text-white">
                                    Accepting Requests
                                    @unless (user()->email_verified_at)
                                        <span class="ml-2 text-red-500">(Verify email first)</span>
                                    @endunless
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @foreach ($repostRequests as $repostRequest)
                @if (!$repostRequest->music && !isset($repostRequest->music->permalink_url))
                    @continue
                @endif
                <div class="request-card bg-white dark:bg-gray-800 border border-gray-200 mb-4 dark:border-gray-700 shadow-sm"
                    data-request-id="{{ $repostRequest->id }}"
                    wire:key="request-{{ $repostRequest->id }}-{{ $activeMainTab }}">

                    <div class="flex flex-col lg:flex-row">
                        <!-- Left Column - Track Info -->
                        <div
                            class="w-full lg:w-1/2 border-b lg:border-b-0 lg:border-r border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                            <div class="flex flex-col md:flex-row gap-4">
                                <div class="flex-1 flex flex-col justify-between relative">
                                    <div id="soundcloud-player-{{ $repostRequest->id }}"
                                        data-request-id="{{ $repostRequest->id }}" wire:ignore
                                        wire:key="player-{{ $repostRequest->id }}">
                                        <x-sound-cloud.sound-cloud-player :track="$repostRequest->music" :visual="false" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Request Info -->
                        <div class="w-full lg:w-1/2 p-3">
                            <div class="flex justify-between h-full">
                                <div class="w-[40%] relative">
                                    <div class="flex flex-col items-start justify-between gap-0 h-full">
                                        <div class="flex items-center gap-2">
                                            <img class="w-12 h-12 rounded-full object-cover"
                                                src="{{ soundcloud_image($repostRequest->requester->avatar) }}"
                                                alt="{{ $repostRequest->requester->name }} avatar">
                                            <div x-data="{ open: false }" class="inline-block text-left">
                                                <div @click="open = !open" @click.outside="open = false"
                                                    class="flex items-center gap-1 cursor-pointer">
                                                    <span
                                                        class="text-slate-700 dark:text-gray-300 font-medium">{{ $repostRequest->requester->name }}</span>
                                                    <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </div>

                                                <div class="flex items-center mt-1">
                                                    @if ($activeMainTab == 'incoming_request')
                                                        <button
                                                            x-on:click="Livewire.dispatch('starMarkUser', { userUrn: '{{ $repostRequest->requester?->urn }}' })">
                                                            <x-lucide-star
                                                                class="w-5 h-5 mt-1 relative {{ $repostRequest->requester?->starredUsers?->contains('follower_urn', user()->urn) ? 'text-orange-300 ' : 'text-gray-400 dark:text-gray-500' }}"
                                                                fill="{{ $repostRequest->requester?->starredUsers?->contains('follower_urn', user()->urn) ? 'orange ' : 'none' }}" />
                                                        </button>
                                                    @endif
                                                </div>

                                                <div x-show="open" x-transition.opacity
                                                    class="absolute left-0 mt-2 w-56 z-50 shadow-lg bg-gray-900 text-white text-sm p-2 space-y-2"
                                                    x-cloak>
                                                    <a href="{{ $repostRequest->requester?->soundcloud_permalink_url ?? '#' }}"
                                                        target="_blank"
                                                        class="block hover:bg-gray-800 px-3 py-1 rounded">Visit
                                                        SoundCloud Profile</a>
                                                    <a href="{{ route('user.my-account.user', !empty($repostRequest->requester?->name) ? $repostRequest->requester?->name : $repostRequest->requester?->urn) }}"
                                                        wire:navigate
                                                        class="block hover:bg-gray-800 px-3 py-1 rounded">Visit
                                                        RepostChain Profile</a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span
                                                class="inline-block bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium px-3 py-1.5 rounded-md shadow-sm">
                                                {{ $repostRequest->music->genre ?? 'Unknown Genre' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="w-[60%] h-fit">
                                    @if ($activeMainTab == 'incoming_request' || $activeMainTab == 'previously_reposted')
                                        <div class="flex flex-col items-end gap-2 h-full">
                                            <div class="flex flex-col justify-between w-full h-full">
                                                <div class="flex gap-3" style="margin-left: auto;">
                                                    <div
                                                        class="text-sm font-semibold text-gray-500 dark:text-gray-400 text-right">
                                                        {{ strtoupper($repostRequest->created_at->diffForHumans()) }}
                                                    </div>

                                                    @if ($activeMainTab == 'incoming_request')
                                                        <div class="relative" x-data="{
                                                            showReadyTooltip: false,
                                                            justBecameEligible: false,
                                                            requestId: '{{ $repostRequest->id }}'
                                                        }"
                                                            x-init="$watch('isEligibleForRepost(requestId)', (value, oldValue) => {
                                                                if (value && !oldValue && !isReposted(requestId)) {
                                                                    justBecameEligible = true;
                                                                    showReadyTooltip = true;
                                                                    setTimeout(() => {
                                                                        showReadyTooltip = false;
                                                                        justBecameEligible = false;
                                                                    }, 3000);
                                                                }
                                                            })">

                                                            <!-- Countdown Tooltip -->
                                                            <div x-show="!isReposted(requestId) && !isEligibleForRepost(requestId) && getPlayTime(requestId) > 0"
                                                                x-transition
                                                                class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs font-medium px-3 py-1.5 rounded-lg shadow-lg whitespace-nowrap z-20 pointer-events-none">
                                                                <span
                                                                    x-text="Math.max(0, Math.ceil(15 - getPlayTime(requestId))) + 's remaining'"></span>
                                                                <div
                                                                    class="absolute top-full left-1/2 transform -translate-x-1/2 -mt-px">
                                                                    <div
                                                                        class="border-4 border-transparent border-t-gray-900">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Ready Tooltip -->
                                                            <div x-show="!isReposted(requestId) && isEligibleForRepost(requestId) && showReadyTooltip"
                                                                x-transition
                                                                class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-green-600 text-white text-xs font-medium px-3 py-1.5 rounded-lg shadow-lg whitespace-nowrap z-20 pointer-events-none"
                                                                :class="{ 'animate-pulse': justBecameEligible }">
                                                                <div class="flex items-center gap-1.5">
                                                                    <svg class="w-3.5 h-3.5" fill="currentColor"
                                                                        viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd"
                                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                            clip-rule="evenodd" />
                                                                    </svg>
                                                                    <span>Ready</span>
                                                                </div>
                                                                <div
                                                                    class="absolute top-full left-1/2 transform -translate-x-1/2 -mt-px">
                                                                    <div
                                                                        class="border-4 border-transparent border-t-green-600">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Repost Button -->
                                                            <button type="button" :data-request-id="requestId"
                                                                x-bind:disabled="!isEligibleForRepost(requestId) || isReposted(requestId)"
                                                                @click="handleRepost(requestId)"
                                                                class="repost-button relative overflow-hidden flex items-center gap-2 py-2 px-3 sm:px-5 rounded-md shadow-sm text-sm sm:text-base transition-all duration-200">

                                                                <!-- Disabled background -->
                                                                <span
                                                                    x-show="!isEligibleForRepost(requestId) && !isReposted(requestId)"
                                                                    class="absolute top-0 left-0 w-full h-full cursor-not-allowed bg-gray-300 dark:bg-gray-600"></span>

                                                                <!-- Animated fill background -->
                                                                <div x-show="!isReposted(requestId)"
                                                                    class="absolute inset-0 bg-gradient-to-r from-orange-600 to-orange-500 transition-all duration-300 ease-out z-0"
                                                                    :style="`width: ${getPlayTimePercentage(requestId)}%`">
                                                                </div>

                                                                <!-- Eligible background -->
                                                                <span
                                                                    x-show="isEligibleForRepost(requestId) && !isReposted(requestId)"
                                                                    class="absolute top-0 left-0 w-full h-full cursor-pointer bg-orange-400 dark:bg-orange-500 hover:bg-orange-500"></span>

                                                                <!-- Reposted background -->
                                                                <span x-show="isReposted(requestId)"
                                                                    class="absolute top-0 left-0 w-full h-full cursor-not-allowed bg-green-400 dark:bg-green-500"></span>

                                                                <!-- Button content -->
                                                                <div
                                                                    class="relative z-10 flex items-center gap-2 text-white">
                                                                    <template x-if="!isReposted(requestId)">
                                                                        <div class="flex items-center gap-2">
                                                                            <svg width="26" height="18"
                                                                                viewBox="0 0 26 18" fill="none"
                                                                                xmlns="http://www.w3.org/2000/svg">
                                                                                <rect x="1" y="1" width="24"
                                                                                    height="16" rx="3"
                                                                                    fill="none"
                                                                                    stroke="currentColor"
                                                                                    stroke-width="2" />
                                                                                <circle cx="8" cy="9"
                                                                                    r="3" fill="none"
                                                                                    stroke="currentColor"
                                                                                    stroke-width="2" />
                                                                            </svg>
                                                                            <span>{{ $repostRequest->targetUser?->repost_price }}
                                                                                Repost</span>
                                                                        </div>
                                                                    </template>

                                                                    <template x-if="isReposted(requestId)">
                                                                        <div class="flex items-center gap-2">
                                                                            <svg class="w-5 h-5" fill="currentColor"
                                                                                viewBox="0 0 20 20">
                                                                                <path fill-rule="evenodd"
                                                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                                    clip-rule="evenodd" />
                                                                            </svg>
                                                                            <span>Reposted</span>
                                                                        </div>
                                                                    </template>
                                                                </div>
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="text-right my-2">
                                                    <span @class([
                                                        'inline-block text-xs font-medium px-2 py-1 rounded-full',
                                                        'bg-yellow-100 text-yellow-800' =>
                                                            $repostRequest->status == App\Models\RepostRequest::STATUS_PENDING,
                                                        'bg-green-100 text-green-800' =>
                                                            $repostRequest->status == App\Models\RepostRequest::STATUS_APPROVED,
                                                        'bg-red-100 text-red-800' =>
                                                            $repostRequest->status == App\Models\RepostRequest::STATUS_DECLINE,
                                                        'bg-gray-100 text-gray-800' =>
                                                            $repostRequest->status == App\Models\RepostRequest::STATUS_EXPIRED,
                                                    ])>
                                                        {{ $repostRequest->status_label }}
                                                    </span>
                                                </div>

                                                @if ($repostRequest->status == App\Models\RepostRequest::STATUS_PENDING && $activeMainTab == 'incoming_request')
                                                    <div class="text-right">
                                                        <x-gbutton variant="primary" size="sm"
                                                            wire:click="declineRepostRequest({{ $repostRequest->id }})">Decline</x-gbutton>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @elseif ($activeMainTab == 'outgoing_request')
                                        <div class="flex flex-col justify-end gap-2">
                                            <div class="flex justify-end gap-3">
                                                <div class="text-sm font-semibold text-gray-500 dark:text-gray-400">
                                                    {{ strtoupper($repostRequest->created_at->diffForHumans()) }}
                                                </div>
                                                <h2 class="text-md font-semibold text-gray-700 dark:text-gray-200">
                                                    Targeted Reposter</h2>
                                            </div>

                                            <div class="flex flex-col sm:flex-row justify-end gap-3">
                                                <a class="cursor-pointer" wire:navigate
                                                    href="{{ route('user.my-account.user', !empty($repostRequest->targetUser->name) ? $repostRequest->targetUser->name : $repostRequest->targetUser->urn) }}">
                                                    <img class="w-10 h-10 rounded-full object-cover"
                                                        src="{{ soundcloud_image($repostRequest->targetUser->avatar) }}"
                                                        alt="{{ $repostRequest->targetUser->name }} avatar">
                                                </a>
                                                <div x-data="{ open: false }" class="inline-block text-left">
                                                    <div class="flex items-center gap-1 cursor-pointer">
                                                        <a class="text-slate-700 dark:text-gray-300 font-medium cursor-pointer hover:underline"
                                                            wire:navigate
                                                            href="{{ route('user.my-account.user', !empty($repostRequest->targetUser->name) ? $repostRequest->targetUser->name : $repostRequest->targetUser->urn) }}">
                                                            {{ $repostRequest->targetUser->name }}
                                                        </a>
                                                    </div>
                                                    <div class="flex items-center mt-1">
                                                        <button
                                                            x-on:click="Livewire.dispatch('starMarkUser', { userUrn: '{{ $repostRequest->targetUser?->urn }}' })">
                                                            <x-lucide-star
                                                                class="w-5 h-5 mt-1 relative {{ $repostRequest->targetUser?->starredUsers?->contains('follower_urn', user()->urn) ? 'text-orange-300 ' : 'text-gray-400 dark:text-gray-500' }}"
                                                                fill="{{ $repostRequest->targetUser?->starredUsers?->contains('follower_urn', user()->urn) ? 'orange ' : 'none' }}" />
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-right">
                                                <span @class([
                                                    'inline-block text-xs font-medium px-2 py-1 rounded-full',
                                                    'bg-yellow-100 text-yellow-800' =>
                                                        $repostRequest->status == App\Models\RepostRequest::STATUS_PENDING,
                                                    'bg-green-100 text-green-800' =>
                                                        $repostRequest->status == App\Models\RepostRequest::STATUS_APPROVED,
                                                    'bg-gray-100 text-gray-800' =>
                                                        $repostRequest->status == App\Models\RepostRequest::STATUS_EXPIRED,
                                                ])>
                                                    {{ $repostRequest->status_label }}
                                                </span>
                                            </div>

                                            <div class="text-right">
                                                @if (
                                                    $repostRequest->status !== App\Models\RepostRequest::STATUS_APPROVED &&
                                                        $repostRequest->status !== App\Models\RepostRequest::STATUS_EXPIRED &&
                                                        $repostRequest->status !== App\Models\RepostRequest::STATUS_DECLINE)
                                                    <x-gbutton variant="primary" size="sm"
                                                        wire:click="cancleRepostRequest({{ $repostRequest->id }})">Cancel</x-gbutton>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            @if ($repostRequests->isEmpty())
                <div class="text-center py-12">
                    <div class="text-gray-500 dark:text-gray-400 text-lg mb-2">
                        No repost requests found
                    </div>
                    <p class="text-gray-400 dark:text-gray-500 text-sm">
                        When others request reposts from you, they'll appear here.
                    </p>
                </div>
            @endif
        </div>

        {{-- Right Side --}}
        @php
            $repostLimit = proUser() ? 100 : 20;
        @endphp
        <div class="max-w-[400px] hidden 4xl:block" x-cloak x-transition>
            <x-dashboard-summary :earnings="user()->repost_price" :dailyRepostCurrent="$data['dailyRepostCurrent']" :dailyRepostMax="20" :responseRate="user()->responseRate()"
                :pendingRequests="$data['pendingRequests']" :requestLimit="$repostLimit" :credits="userCredits()" :campaigns="$data['totalMyCampaign']" :campaignLimit="proUser() ? 10 : 2" />
        </div>
    </section>

    {{-- Repost Confirmation Modal --}}
    @include('backend.user.includes.direct-repost-confirmation-modal')

    <script>
        window.requestSessionData = @json(session('repost_request_playback_tracking', []));
        // window.trackRequestPlaybackRoute = "{{ route('user.api.request.track-playback') }}";
        // window.clearRequestSessionRoute = "{{ route('user.api.request.clear-tracking') }}";

        function trackPlaybackManagerForRequests() {
            return {
                tracks: {},
                updateInterval: null,
                isInitialized: false,
                sessionSyncInProgress: false,
                widgetCleanupTimeout: null,
                playCountDispatched: {},

                init() {
                    console.log('🎵 Initializing trackPlaybackManagerForRequests');

                    if (!this.isInitialized) {
                        this.clearSessionData();
                        localStorage.removeItem('request_tracking_data');
                        this.playCountDispatched = {};
                        this.isInitialized = true;
                    }

                    this.loadFromSession();
                    this.loadPersistedTrackingData();
                    this.initializeSoundCloudWidgets();
                    this.startUpdateLoop();
                    window.addEventListener('repost-success', (event) => {
                        const requestId = event.detail.requestId;
                        if (this.tracks[requestId]) {
                            this.tracks[requestId].reposted = true;
                            this.saveTrackingData();
                        }
                    });

                    Livewire.hook('morph.updated', () => {
                        if (this.widgetCleanupTimeout) {
                            clearTimeout(this.widgetCleanupTimeout);
                        }
                        this.widgetCleanupTimeout = setTimeout(() => {
                            this.preserveStateOnMorph();
                        }, 150);
                    });
                },

                preserveStateOnMorph() {
                    const currentTracks = JSON.parse(JSON.stringify(this.tracks));
                    this.initializeSoundCloudWidgets();

                    Object.keys(currentTracks).forEach(requestId => {
                        if (!this.tracks[requestId]) {
                            this.tracks[requestId] = currentTracks[requestId];
                            this.tracks[requestId].widget = null;
                            this.tracks[requestId].isPlaying = false;
                        } else {
                            this.tracks[requestId].actualPlayTime = currentTracks[requestId].actualPlayTime;
                            this.tracks[requestId].isEligible = currentTracks[requestId].isEligible;
                            this.tracks[requestId].reposted = currentTracks[requestId].reposted;
                            this.tracks[requestId].lastPosition = currentTracks[requestId].lastPosition;
                        }
                    });

                    this.saveTrackingData();
                },

                loadFromSession() {
                    const sessionData = typeof window.requestSessionData !== 'undefined' ? window.requestSessionData : {};

                    if (sessionData && Object.keys(sessionData).length > 0) {
                        Object.keys(sessionData).forEach(requestId => {
                            if (!this.tracks[requestId]) {
                                this.tracks[requestId] = {
                                    isPlaying: false,
                                    actualPlayTime: 0,
                                    isEligible: false,
                                    lastPosition: 0,
                                    playStartTime: null,
                                    seekDetected: false,
                                    widget: null,
                                    reposted: false,
                                };
                            }

                            this.tracks[requestId].actualPlayTime = parseFloat(sessionData[requestId]
                                .actual_play_time) || 0;
                            this.tracks[requestId].isEligible = sessionData[requestId].is_eligible || false;
                            this.tracks[requestId].reposted = sessionData[requestId].reposted || false;
                        });

                        this.saveTrackingData();
                    }
                },

                loadPersistedTrackingData() {
                    const stored = localStorage.getItem('request_tracking_data');

                    if (stored) {
                        try {
                            const data = JSON.parse(stored);

                            Object.keys(data).forEach(requestId => {
                                if (!this.tracks[requestId]) {
                                    this.tracks[requestId] = {
                                        isPlaying: false,
                                        actualPlayTime: 0,
                                        isEligible: false,
                                        lastPosition: 0,
                                        playStartTime: null,
                                        seekDetected: false,
                                        widget: null,
                                        reposted: false,
                                    };
                                }

                                if (data[requestId].actualPlayTime > this.tracks[requestId].actualPlayTime) {
                                    this.tracks[requestId].actualPlayTime = parseFloat(data[requestId]
                                        .actualPlayTime) || 0;
                                    this.tracks[requestId].isEligible = data[requestId].isEligible || false;
                                    this.tracks[requestId].lastPosition = parseFloat(data[requestId]
                                        .lastPosition) || 0;
                                }

                                this.tracks[requestId].reposted = data[requestId].reposted || this.tracks[requestId]
                                    .reposted;
                            });
                        } catch (e) {
                            console.error('❌ Error loading tracking data:', e);
                        }
                    }
                },

                saveTrackingData() {
                    const dataToSave = {};
                    Object.keys(this.tracks).forEach(requestId => {
                        dataToSave[requestId] = {
                            actualPlayTime: this.tracks[requestId].actualPlayTime,
                            isEligible: this.tracks[requestId].isEligible,
                            lastPosition: this.tracks[requestId].lastPosition,
                            reposted: this.tracks[requestId].reposted,
                        };
                    });

                    localStorage.setItem('request_tracking_data', JSON.stringify(dataToSave));
                },

                startUpdateLoop() {
                    if (this.updateInterval) {
                        clearInterval(this.updateInterval);
                    }

                    this.updateInterval = setInterval(() => {
                        this.tracks = Object.assign({}, this.tracks);
                    }, 500);
                },

                initializeSoundCloudWidgets() {
                    if (typeof SC === 'undefined') {
                        setTimeout(() => this.initializeSoundCloudWidgets(), 500);
                        return;
                    }

                    const playerContainers = document.querySelectorAll('[id^="soundcloud-player-"]');

                    playerContainers.forEach(container => {
                        const requestId = container.dataset.requestId;
                        const currentRequestCard = container.closest('.request-card');

                        if (!currentRequestCard) return;

                        if (!this.tracks[requestId]) {
                            this.tracks[requestId] = {
                                isPlaying: false,
                                actualPlayTime: 0,
                                isEligible: false,
                                lastPosition: 0,
                                playStartTime: null,
                                seekDetected: false,
                                widget: null,
                                reposted: false,
                            };
                        }

                        const iframe = container.querySelector('iframe');
                        if (!iframe || !requestId) return;

                        if (this.tracks[requestId].widget) {
                            try {
                                this.tracks[requestId].widget.isPaused((isPaused) => {});
                                return;
                            } catch (e) {
                                this.tracks[requestId].widget = null;
                            }
                        }

                        const nextRequestCard = currentRequestCard.nextElementSibling;
                        let nextIframe = null;
                        let nextRequestId = null;

                        if (nextRequestCard && nextRequestCard.classList.contains('request-card')) {
                            const nextPlayerContainer = nextRequestCard.querySelector('[id^="soundcloud-player-"]');
                            if (nextPlayerContainer) {
                                nextIframe = nextPlayerContainer.querySelector('iframe');
                                nextRequestId = nextPlayerContainer.dataset.requestId;
                            }
                        }

                        const widget = SC.Widget(iframe);
                        this.tracks[requestId].widget = widget;

                        this.bindWidgetEvents(widget, requestId, nextIframe, nextRequestId);
                    });
                },

                bindWidgetEvents(widget, requestId, nextIframe, nextRequestId) {
                    widget.bind(SC.Widget.Events.PLAY, () => {
                        const track = this.tracks[requestId];
                        track.isPlaying = true;
                        track.playStartTime = Date.now();

                        if (!this.playCountDispatched[requestId]) {
                            Livewire.dispatch('updatePlayCount', {
                                requestId: requestId
                            });
                            this.playCountDispatched[requestId] = true;
                        }

                        this.syncToBackend(requestId, 'play');
                    });

                    widget.bind(SC.Widget.Events.PAUSE, () => {
                        const track = this.tracks[requestId];
                        track.isPlaying = false;
                        track.playStartTime = null;
                        this.syncToBackend(requestId, 'pause');
                        this.saveTrackingData();
                    });

                    widget.bind(SC.Widget.Events.FINISH, () => {
                        const track = this.tracks[requestId];
                        track.isPlaying = false;
                        track.playStartTime = null;
                        this.syncToBackend(requestId, 'finish');
                        this.saveTrackingData();

                        if (nextRequestId && nextIframe) {
                            const nextWidget = SC.Widget(nextIframe);
                            setTimeout(() => nextWidget.play(), 100);
                        }
                    });

                    widget.bind(SC.Widget.Events.PLAY_PROGRESS, (data) => {
                        const currentPosition = data.currentPosition / 1000;
                        const track = this.tracks[requestId];

                        const positionDiff = Math.abs(currentPosition - track.lastPosition);

                        if (positionDiff > 1.5 && track.lastPosition > 0) {
                            track.seekDetected = true;
                            track.lastPosition = currentPosition;
                            return;
                        }

                        if (track.isPlaying && !track.seekDetected) {
                            const increment = currentPosition - track.lastPosition;

                            if (increment > 0 && increment < 15) {
                                track.actualPlayTime += increment;

                                if (track.actualPlayTime >= 15 && !track.isEligible) {
                                    track.isEligible = true;
                                    this.syncToBackend(requestId, 'eligible');
                                    this.saveTrackingData();
                                }

                                if (Math.floor(track.actualPlayTime) % 2 === 0) {
                                    this.saveTrackingData();
                                }
                            }
                        }

                        track.lastPosition = currentPosition;
                        track.seekDetected = false;
                    });

                    widget.bind(SC.Widget.Events.SEEK, (data) => {
                        const track = this.tracks[requestId];
                        track.seekDetected = true;
                        track.lastPosition = data.currentPosition / 1000;
                    });
                },

                syncToBackend(requestId, action) {
                    const track = this.tracks[requestId];
                    if (!track) return Promise.resolve();

                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (!csrfToken) {
                        console.error('❌ CSRF token not found');
                        return Promise.resolve();
                    }

                    return fetch(window.trackRequestPlaybackRoute || '/api/request/track-playback', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken.content,
                            },
                            body: JSON.stringify({
                                requestId: requestId,
                                actualPlayTime: track.actualPlayTime,
                                isEligible: track.isEligible,
                                reposted: track.reposted,
                                action: action
                            })
                        })
                        .then(response => response.json())
                        .catch(err => {
                            console.error('❌ Failed to sync:', err);
                        });
                },

                clearSessionData() {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (!csrfToken) return;

                    fetch(window.clearRequestSessionRoute || '/api/request/clear-tracking', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken.content,
                            }
                        })
                        .then(() => console.log('✅ Request session cleared'))
                        .catch(err => console.error('❌ Failed to clear session:', err));
                },

                isEligibleForRepost(requestId) {
                    return this.tracks[requestId]?.isEligible || false;
                },

                isReposted(requestId) {
                    return this.tracks[requestId]?.reposted || false;
                },

                getPlayTime(requestId) {
                    return this.tracks[requestId]?.actualPlayTime || 0;
                },

                getPlayTimePercentage(requestId) {
                    const playTime = this.getPlayTime(requestId);
                    const percentage = Math.min((playTime / 15) * 100, 100);
                    return percentage.toFixed(2);
                },

                handleRepost(requestId) {
                    if (!this.isEligibleForRepost(requestId) || this.isReposted(requestId)) {
                        return;
                    }

                    console.log('🔄 Initiating repost for request:', requestId);
                    Livewire.dispatch('confirmRepost', {
                        requestId: requestId
                    });
                },

                clearAllTracking() {
                    Object.keys(this.tracks).forEach(requestId => {
                        const track = this.tracks[requestId];
                        if (track.widget && track.isPlaying) {
                            try {
                                track.widget.pause();
                            } catch (e) {
                                console.warn('Widget already destroyed');
                            }
                        }
                    });

                    this.tracks = {};
                    localStorage.removeItem('request_tracking_data');

                    if (this.updateInterval) {
                        clearInterval(this.updateInterval);
                    }

                    this.clearSessionData();
                }
            };
        }

        document.addEventListener('livewire:initialized', function() {
            Alpine.data('trackPlaybackManagerForRequests', trackPlaybackManagerForRequests);
        });

        document.addEventListener('livewire:navigated', function() {
            setTimeout(() => {
                const mainElement = document.querySelector(
                    'div[x-data*="trackPlaybackManagerForRequests"]');
                if (mainElement && mainElement.__x) {
                    const trackManager = mainElement.__x.$data;
                    if (trackManager && trackManager.initializeSoundCloudWidgets) {
                        trackManager.initializeSoundCloudWidgets();
                    }
                }
            }, 100);
        });

        window.addEventListener('beforeunload', function() {
            const mainElement = document.querySelector('div[x-data*="trackPlaybackManagerForRequests"]');
            if (mainElement && mainElement.__x) {
                const trackManager = mainElement.__x.$data;
                if (trackManager) {
                    trackManager.saveTrackingData();
                }
            }
        });
    </script>
</div>
