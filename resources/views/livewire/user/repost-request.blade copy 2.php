<div x-data="audioTracker()">
    <x-slot name="page_slug">request</x-slot>
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-5 space-y-3 sm:space-y-0">
        <div>
            <h1 class="text-xl text-black dark:text-gray-100 font-bold">
                {{ __('Repost Requests') }}
            </h1>
        </div>
        <x-gbutton variant="primary" wire:navigate href="{{ route('user.members') }}" class="w-full sm:w-auto">
            <span><x-lucide-plus class="w-5 h-5 mr-1" /></span>
            {{ __('Send a New Request') }}
        </x-gbutton>
    </div>

    <div class="mb-8">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex space-x-8">
                <button
                    class="tab-button @if ($activeMainTab === 'incoming_request' || $activeMainTab === 'accept_requests') active border-b-2 border-orange-500 text-orange-600 @else border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-3 px-2 text-sm font-semibold transition-all duration-200"
                    wire:click="setActiveTab('incoming_request')">
                    {{ __('Incoming requests') }}
                </button>
                <button
                    class="tab-button @if ($activeMainTab === 'outgoing_request') active border-b-2 border-orange-500 text-orange-600 @else border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-3 px-2 text-sm font-semibold transition-all duration-200"
                    wire:click="setActiveTab('outgoing_request')">
                    {{ __('Outgoing request') }}
                </button>
                <button
                    class="tab-button @if ($activeMainTab === 'previously_reposted') active border-b-2 border-orange-500 text-orange-600 @else border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-3 px-2 text-sm font-semibold transition-all duration-200"
                    wire:click="setActiveTab('previously_reposted')">
                    {{ __('Previously Reposted') }}
                </button>
            </nav>
        </div>
    </div>

    @if ($activeMainTab == 'incoming_request')
    <div
        class="flex flex-col md:flex-row md:items-start md:space-x-3 p-4 rounded bg-gray-100 dark:bg-gray-800 mb-8 gap-8">
        <div class="flex items-top space-x-2 mb-2 md:mb-0 ">
            <svg class="w-4 h-4 text-sky-600 dark:text-sky-400" fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 2a6 6 0 00-4 10.49V15a1 1 0 001 1h6a1 1 0 001-1v-2.51A6 6 0 0010 2zm1 13h-2v-1h2v1zm1-2H8v-1h4v1zm-.44-2.93a1 1 0 01-.32 1.36L10 11l-.24-.14a1 1 0 111.36-1.36l.44.57z" />
            </svg>
            <span class="text-sm font-medium text-gray-700 dark:text-white">Quick Tip</span>
        </div>
        <div class="flex-1 flex flex-col space-y-2">
            <p class="text-sm text-gray-700 dark:text-white">
                Your response rate could be better! Improve your response rate and get more requests by accepting OR
                declining requests quickly.
            </p>
            <div class="flex items-center space-x-1">
                <div class="w-4 h-4 rounded-full bg-orange-500"></div>
                <span class="text-xs text-orange-600 font-semibold">{{ user()->responseRate() }}% <span
                        class="text-gray-900 dark:text-white">Response rate.</span></span>
                <a href="{{ route('user.reposts-request') }}" wire:navigate
                    class="text-xs text-red-500 underline">Reset</a>
            </div>
            <div x-data="{ on: {{ $requestReceiveable ? 'false' : 'true' }} }"
                class="inline-flex items-center {{ user()->email_verified_at ? 'cursor-pointer' : 'cursor-not-allowed' }}"
                @if (user()->email_verified_at) wire:click="requestReceiveableToggle" @endif>
                <input type="checkbox" class="sr-only peer" wire:model.live="requestReceiveable" {{ $requestReceiveable
                    ? 'checked' : '' }} {{ user()->email_verified_at ? '' : 'disabled' }}>
                <div class="flex items-center space-x-2">
                    <div class="w-7 h-4 rounded-full relative transition-colors"
                        :class="on ? 'bg-green-500' : 'bg-gray-300'" @if (user()->email_verified_at) @click="on = !on"
                        @endif>
                        <div class="absolute top-0 left-0 w-4 h-4 bg-white rounded-full shadow transition-transform duration-300"
                            :class="on ? 'translate-x-4' : 'translate-x-0'">
                        </div>
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
    <div class="bg-white dark:bg-gray-800 border border-gray-200 mb-4 dark:border-gray-700 shadow-sm"
        x-data="trackPlayer({{ $repostRequest->id }})">
        <div class="flex flex-col lg:flex-row" wire:key="request-{{ $repostRequest->id }}">
            <!-- Left Column - Track Info -->
            <div
                class="w-full lg:w-1/2 border-b lg:border-b-0 lg:border-r border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 flex flex-col justify-between relative">
                        <div id="soundcloud-player-{{ $repostRequest->id }}" data-request-id="{{ $repostRequest->id }}"
                            x-ref="player{{ $repostRequest->id }}">
                            <x-sound-cloud.sound-cloud-player :track="$repostRequest->track" :visual="false" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Request Info -->
            <div class="w-full lg:w-1/2 p-3">
                <div class="flex justify-between mb-2">
                    <div class="w-1/2 relative">
                        <div class="flex flex-col items-start gap-0">
                            <div class="flex items-center gap-2">
                                <img class="w-12 h-12 rounded-full object-cover"
                                    src="{{ auth_storage_url($repostRequest->requester->avatar) }}"
                                    alt="{{ $repostRequest->requester->name }} avatar">
                                <div x-data="{ open: false }" class="inline-block text-left">
                                    <div @click="open = !open" @click.outside="open = false"
                                        class="flex items-center gap-1 cursor-pointer">
                                        <span class="text-slate-700 dark:text-gray-300 font-medium">{{
                                            $repostRequest->requester->name }}</span>
                                        <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>

                                    <div class="flex items-center mt-1">
                                        @for ($i = 1; $i <= 5; $i++) <svg
                                            class="w-4 h-4 {{ $i <= ($repostRequest->requester->rating ?? 4) ? 'text-yellow-400' : 'text-gray-300' }}"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                            </svg>
                                            @endfor
                                    </div>

                                    <div x-show="open" x-transition.opacity
                                        class="absolute left-0 mt-2 w-56 z-50 shadow-lg bg-gray-900 text-white text-sm p-2 space-y-2"
                                        x-cloak>
                                        <a href="{{ $repostRequest->requester->soundcloud_url ?? '#' }}" target="_blank"
                                            class="block hover:bg-gray-800 px-3 py-1 rounded">Visit
                                            SoundCloud Profile</a>
                                        <a href="{{ route('user.my-account', $repostRequest->requester->urn) }}"
                                            wire:navigate class="block hover:bg-gray-800 px-3 py-1 rounded">Visit
                                            RepostChain Profile</a>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-2">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">
                                    {{ $repostRequest->track->title ?? 'Unknown Track' }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ Str::limit($repostRequest->track->description ?? 'No description available', 100)
                                    }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span
                                    class="inline-block bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium px-3 py-1.5 rounded-md shadow-sm">
                                    {{ $repostRequest->track->genre ?? 'Unknown Genre' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="w-1/2">
                        @if ($activeMainTab == 'incoming_request' || $activeMainTab == 'previously_reposted')
                        <div class="flex flex-col items-end gap-2 h-full">
                            <div class="flex flex-col justify-between h-full">
                                <div class="flex gap-3">
                                    <div class="text-sm font-semibold text-gray-500 dark:text-gray-400 text-right">
                                        {{ strtoupper($repostRequest->created_at->diffForHumans()) }}
                                    </div>
                                    @if ($activeMainTab == 'incoming_request')
                                    <div class="relative flex justify-end">
                                        <button @click="handleRepost()" x-bind:class="{
                                                            'bg-orange-600 dark:bg-orange-500 hover:bg-orange-700 dark:hover:bg-orange-400 text-white cursor-pointer transform hover:scale-105 transition-all duration-300': canRepost,
                                                            'bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed':
                                                                !canRepost
                                                        }" x-bind:disabled="!canRepost"
                                            class="flex items-center gap-2 py-2 px-3 sm:px-5 rounded-md shadow-sm text-sm sm:text-base">
                                            <svg width="26" height="18" viewBox="0 0 26 18" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <rect x="1" y="1" width="24" height="16" rx="3" fill="none"
                                                    stroke="currentColor" stroke-width="2" />
                                                <circle cx="8" cy="9" r="3" fill="none" stroke="currentColor"
                                                    stroke-width="2" />
                                            </svg>
                                            <!-- <span>{{ repostPrice($repostRequest->requester) }}
                                                            Repost</span> -->
                                            <span>{{ $repostRequest->requester?->repost_price }}
                                                Repost</span>
                                        </button>
                                    </div>
                                    @endif
                                </div>
                                <div class="text-right my-2">
                                    <span @class([ 'inline-block text-xs font-medium px-2 py-1 rounded-full'
                                        , 'bg-yellow-100 text-yellow-800'=>
                                        $repostRequest->status == App\Models\RepostRequest::STATUS_PENDING,
                                        'bg-green-100 text-green-800' =>
                                        $repostRequest->status == App\Models\RepostRequest::STATUS_APPROVED,
                                        'bg-blue-100 text-blue-800' =>
                                        $repostRequest->status == App\Models\RepostRequest::STATUS_APPROVED,
                                        'bg-red-100 text-red-800' =>
                                        $repostRequest->status == App\Models\RepostRequest::STATUS_DECLINE,
                                        'bg-gray-100 text-gray-800' =>
                                        $repostRequest->status == App\Models\RepostRequest::STATUS_EXPIRED,
                                        ])>
                                        {{ $repostRequest->status_label }}
                                    </span>
                                </div>
                                @if ($repostRequest->status == App\Models\RepostRequest::STATUS_PENDING &&
                                $activeMainTab == 'incoming_request')
                                <div class="text-right">
                                    <x-gbutton variant="primary" size="sm"
                                        wire:click="declineRepostRequest({{ $repostRequest->id }})">Decline</x-gbutton>
                                </div>
                                @endif
                            </div>
                        </div>
                        @elseif ($activeMainTab == 'outgoing_request')
                        <!-- Outgoing request content unchanged -->
                        <div class="flex flex-col justify-end gap-2">
                            <div class="flex justify-end gap-3">
                                <div class="text-sm font-semibold text-gray-500 dark:text-gray-400">
                                    {{ strtoupper($repostRequest->created_at->diffForHumans()) }}
                                </div>
                                @if ($activeMainTab == 'outgoing_request')
                                <h2 class="text-md font-semibold text-gray-700 dark:text-gray-200">Targeted
                                    Reposter</h2>
                                @endif
                            </div>
                            @if ($activeMainTab == 'outgoing_request')
                            <div class="flex flex-col sm:flex-row justify-end gap-3">
                                <a class="cursor-pointer" wire:navigate
                                    href="{{ route('user.my-account', $repostRequest->targetUser->urn) }}">
                                    <img class="w-10 h-10 rounded-full object-cover"
                                        src="{{ auth_storage_url($repostRequest->targetUser->avatar) }}"
                                        alt="{{ $repostRequest->targetUser->name }} avatar">
                                </a>
                                <div x-data="{ open: false }" class="inline-block text-left">
                                    <div class="flex items-center gap-1 cursor-pointer">
                                        <a class="text-slate-700 dark:text-gray-300 font-medium cursor-pointer hover:underline"
                                            wire:navigate
                                            href="{{ route('user.my-account', $repostRequest->targetUser->urn) }}">
                                            {{ $repostRequest->targetUser->name }}
                                        </a>
                                    </div>
                                    <div class="flex items-center mt-1">
                                        @for ($i = 1; $i <= 5; $i++) <svg
                                            class="w-4 h-4 {{ $i <= ($repostRequest->targetUser->rating ?? 4) ? 'text-yellow-400' : 'text-gray-300' }}"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                            </svg>
                                            @endfor
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="text-right">
                                <span @class([ 'inline-block text-xs font-medium px-2 py-1 rounded-full'
                                    , 'bg-yellow-100 text-yellow-800'=>
                                    $repostRequest->status == App\Models\RepostRequest::STATUS_PENDING,
                                    'bg-green-100 text-green-800' =>
                                    $repostRequest->status == App\Models\RepostRequest::STATUS_APPROVED,
                                    'bg-blue-100 text-blue-800' =>
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

    <!-- Modal unchanged -->
    <div x-data="{ showRepostConfirmationModal: @entangle('showRepostConfirmationModal').live }"
        x-show="showRepostConfirmationModal" x-cloak x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">
        @if ($request)
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
                    <!-- <span class="text-sm text-gray-700 dark:text-gray-300">{{ repostPrice($request->requester) }}
                        Credits</span> -->
                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ $request->requester?->repost_price}}
                        Credits</span>
                </div>
                <div class="flex items-center space-x-3 p-2 border border-gray-200 dark:border-gray-600 rounded-md">
                    <img src="{{ soundcloud_image($request->track->artwork_url) }}" alt="Track Cover"
                        class="w-12 h-12 rounded-md object-cover">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                            {{ $request->track->type }} - {{ $request->track->author_username }}</p>
                        <p class="text-xs text-gray-500">{{ $request->track->title }}</p>
                    </div>
                </div>
                <p
                    class="text-sm capitalize text-gray-700 dark:text-gray-300 {{ $request->description ? '' : 'hidden' }}">
                    {{ $request->description }}</p>

                <div class="space-y-2">
                    <label class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" checked
                                class="w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500">
                            <span class="text-sm text-gray-800 dark:text-gray-200">Follow <span
                                    class="font-semibold text-orange-500">{{ $request->requester?->name }}</span></span>
                        </div>
                    </label>
                </div>

                @if ($request->likeable)
                <div class="flex items-center justify-between border-t pt-3 dark:border-gray-700">
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" wire:model.live="liked"
                            class="w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500">
                        <span class="text-sm text-gray-800 dark:text-gray-200">{{ __('Activate HeartPush') }}</span>
                    </label>
                    <span class="text-sm text-gray-700 dark:text-gray-300">+2 credits</span>
                </div>
                @endif

                @if ($request->commentable)
                <div class="border-t pt-3 space-y-2 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-800 dark:text-gray-200">Comment on this
                            track (optional)</span>
                        <span class="text-sm text-gray-700 dark:text-gray-300">+2 credits</span>
                    </div>
                    <textarea rows="3" placeholder="What did you like about the track?" wire:model.live="commented"
                        class="w-full border-gray-300 rounded-lg text-sm focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200"></textarea>
                </div>
                @endif

                <div class="flex justify-center gap-4">
                    <button @click="showRepostConfirmationModal = false" wire:click="repost('{{ $request->id }}')"
                        class="w-full flex items-center justify-center gap-2 bg-orange-500 hover:bg-orange-600 text-white py-2 px-4 rounded-xl transition-all duration-200">
                        <svg width="26" height="18" viewBox="0 0 26 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="1" y="1" width="24" height="16" rx="3" fill="none" stroke="currentColor"
                                stroke-width="2" />
                            <circle cx="8" cy="9" r="3" fill="none" stroke="currentColor" stroke-width="2" />
                        </svg>
                        <!-- <span>{{ repostPrice() + ($liked ? 2 : 0) + ($commented ? 2 : 0) }}</span>
                        {{ __('Repost') }} -->
                        <span>{{ repostPrice(user()->repost_price, $commented, $liked) }}</span>
                        {{ __('Repost') }}
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
    function audioTracker() {
        return {
            tracks: {},
            globalDebug: true
        }
    }

    function trackPlayer(requestId) {
        return {
            requestId: requestId,
            playTime: 0,
            isPlaying: false,
            canRepost: false,
            hasStartedPlaying: false,
            widget: null,
            playProgressInterval: null,
            lastLoggedTime: 0,

            init() {
                console.log(`🎯 Initializing player for request ${this.requestId}`);

                // Find iframe in this component
                this.$nextTick(() => {
                    const playerContainer = this.$refs[`player${this.requestId}`];
                    const iframe = playerContainer?.querySelector('iframe');

                    if (!iframe) {
                        console.error(`❌ No iframe found for request ${this.requestId}`);
                        return;
                    }

                    console.log(`✅ Found iframe for request ${this.requestId}`);

                    // Wait for SoundCloud widget to load
                    const initWidget = () => {
                        if (typeof SC === 'undefined' || !SC.Widget) {
                            console.log(`⏳ Waiting for SoundCloud API...`);
                            setTimeout(initWidget, 500);
                            return;
                        }

                        try {
                            this.widget = SC.Widget(iframe);
                            console.log(`🔗 Widget created for request ${this.requestId}`);
                            this.bindEvents();
                        } catch (error) {
                            console.error(`❌ Widget creation failed for request ${this.requestId}:`, error);
                            setTimeout(initWidget, 1000);
                        }
                    };

                    // Try to initialize immediately if iframe is loaded
                    if (iframe.src && iframe.contentWindow) {
                        setTimeout(initWidget, 1000);
                    } else {
                        // Wait for iframe load event
                        iframe.addEventListener('load', () => {
                            console.log(`📡 Iframe loaded for request ${this.requestId}`);
                            setTimeout(initWidget, 500);
                        });
                    }
                });
            },

            bindEvents() {
                if (!this.widget) {
                    console.error(`❌ No widget available for request ${this.requestId}`);
                    return;
                }

                console.log(`🔄 Binding events for request ${this.requestId}`);

                this.widget.bind(SC.Widget.Events.READY, () => {
                    console.log(`🎵 Widget READY for request ${this.requestId}`);
                });

                this.widget.bind(SC.Widget.Events.PLAY, () => {
                    console.log(`▶️ PLAY started for request ${this.requestId}`);
                    this.isPlaying = true;
                    this.hasStartedPlaying = true;
                    this.startPlayTracking();
                });

                this.widget.bind(SC.Widget.Events.PAUSE, () => {
                    console.log(`⏸️ PAUSE for request ${this.requestId}`);
                    this.isPlaying = false;
                    this.stopPlayTracking();
                });

                this.widget.bind(SC.Widget.Events.FINISH, () => {
                    console.log(`⏹️ FINISHED for request ${this.requestId}`);
                    this.isPlaying = false;
                    this.stopPlayTracking();
                });

                this.widget.bind(SC.Widget.Events.PLAY_PROGRESS, (data) => {
                    if (data && data.currentPosition !== undefined) {
                        const currentSeconds = data.currentPosition / 1000;
                        this.playTime = Math.max(this.playTime, currentSeconds);
                        this.checkCanRepost();

                        // Log progress every second
                        const currentFloor = Math.floor(currentSeconds);
                        if (currentFloor !== this.lastLoggedTime && currentFloor % 1 === 0) {
                            console.log(`⏰ Request ${this.requestId}: ${currentSeconds.toFixed(1)}s played`);
                            this.lastLoggedTime = currentFloor;
                        }
                    }
                });

                console.log(`✅ All events bound for request ${this.requestId}`);
            },

            startPlayTracking() {
                if (this.playProgressInterval) return;

                this.playProgressInterval = setInterval(() => {
                    if (this.isPlaying && this.hasStartedPlaying) {
                        this.playTime += 0.1;
                        this.checkCanRepost();
                    }
                }, 100);
            },

            stopPlayTracking() {
                if (this.playProgressInterval) {
                    clearInterval(this.playProgressInterval);
                    this.playProgressInterval = null;
                }
            },

            checkCanRepost() {
                if (!this.canRepost && this.playTime >= 5) {
                    console.log(`🎉 ENABLING REPOST BUTTON for request ${this.requestId}!`);
                    console.log(`📊 Total play time: ${this.playTime.toFixed(1)}s (required: 5.0s)`);

                    this.canRepost = true;

                    // Force Alpine reactivity update
                    this.$nextTick(() => {
                        console.log(`🔄 Alpine state updated. canRepost: ${this.canRepost}`);

                        // Verify button state
                        const button = this.$el.querySelector('button[x-bind\\:disabled]');
                        if (button) {
                            console.log(`🎨 Button found. Disabled: ${button.disabled}`);
                            console.log(`🎨 Button classes: ${button.className}`);
                        }
                    });

                    // Stop intensive tracking once enabled
                    this.stopPlayTracking();
                }
            },

            handleRepost() {
                console.log(`🎯 Repost button clicked for request ${this.requestId}`);
                console.log(`🔍 canRepost: ${this.canRepost}, playTime: ${this.playTime.toFixed(1)}s`);

                if (!this.canRepost) {
                    const remaining = Math.max(0, 5 - this.playTime).toFixed(1);
                    console.log(`❌ Cannot repost yet. Need ${remaining}s more`);

                    alert(
                        `Please listen to at least 5 seconds of the track before reposting.\n\nCurrent play time: ${this.playTime.toFixed(1)}s\nRemaining: ${remaining}s`
                    );
                    return false;
                }

                console.log(`✅ Proceeding with repost for request ${this.requestId}`);

                // Call Livewire method
                try {
                    this.$wire.call('confirmRepost', this.requestId);
                } catch (error) {
                    console.error(`❌ Error calling confirmRepost:`, error);
                }

                return true;
            },

            destroy() {
                console.log(`🧹 Cleaning up player for request ${this.requestId}`);
                this.stopPlayTracking();

                if (this.widget) {
                    try {
                        // Unbind all events
                        this.widget.unbind(SC.Widget.Events.READY);
                        this.widget.unbind(SC.Widget.Events.PLAY);
                        this.widget.unbind(SC.Widget.Events.PAUSE);
                        this.widget.unbind(SC.Widget.Events.FINISH);
                        this.widget.unbind(SC.Widget.Events.PLAY_PROGRESS);
                    } catch (error) {
                        console.log(`⚠️ Error unbinding events:`, error);
                    }
                }
            }
        }
    }

    // Global initialization
    document.addEventListener('DOMContentLoaded', function () {
        console.log('🚀 DOM loaded - SoundCloud Widget API should be available');
    });

    document.addEventListener('livewire:initialized', function () {
        console.log('🔄 Livewire initialized');
    });

    document.addEventListener('livewire:navigated', function () {
        console.log('🧭 Livewire navigated - Components will reinitialize');
    });

    // Handle component cleanup on navigation
    document.addEventListener('livewire:navigating', function () {
        console.log('🧹 Livewire navigating - Cleaning up audio trackers');
    });
</script>