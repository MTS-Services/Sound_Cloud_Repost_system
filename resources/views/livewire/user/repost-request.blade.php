<div x-data="{ activeMainTab: @entangle('activeMainTab').live }">
    <x-slot name="page_slug">request</x-slot>
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-5 space-y-3 sm:space-y-0">
        <div>
            <h1 class="text-xl text-black dark:text-gray-100 font-bold">
                {{ __('Repost Requests') }}
            </h1>
        </div>
        <x-gbutton variant="primary" wire:navigate href="{{ route('user.members') }}" class="w-full sm:w-auto">
            <span><x-lucide-plus class="w-5 h-5 mr-1" /></span>
            Send a New Request
        </x-gbutton>
    </div>


    <div class="mb-8">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex space-x-8">
                <!-- Incoming Request Tab Button -->
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

    <!-- Repost Requests -->
    @if ($activeMainTab == 'incoming_request' || $activeMainTab == 'accept_requests')
        <div
            class="flex flex-col md:flex-row md:items-start md:space-x-3 p-4  rounded bg-gray-100 dark:bg-gray-800 mb-8 gap-8">
            <!-- Left Icon and "Quick Tip" -->
            <div class="flex items-top space-x-2 mb-2 md:mb-0 ">
                <!-- Lightbulb Icon -->
                <svg class="w-4 h-4 text-sky-600 dark:text-sky-400" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M10 2a6 6 0 00-4 10.49V15a1 1 0 001 1h6a1 1 0 001-1v-2.51A6 6 0 0010 2zm1 13h-2v-1h2v1zm1-2H8v-1h4v1zm-.44-2.93a1 1 0 01-.32 1.36L10 11l-.24-.14a1 1 0 111.36-1.36l.44.57z" />
                </svg>
                <!-- Quick Tip Text -->
                <span class="text-sm font-medium text-gray-700 dark:text-white">Quick Tip</span>
            </div>
            <!-- Content -->
            <div class="flex-1 flex flex-col space-y-2">
                <!-- Message Text -->
                <p class="text-sm text-gray-700 dark:text-white">
                    Your response rate could be better! Improve your response rate and get more requests by accepting OR
                    declining requests quickly.
                </p>
                <!-- Response Rate -->
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
                    <input type="checkbox" class="sr-only peer" wire:model.live="requestReceiveable"
                        {{ $requestReceiveable ? 'checked' : '' }} {{ user()->email_verified_at ? '' : 'disabled' }}>

                    <div class="flex items-center space-x-2">
                        <div class="w-7 h-4 rounded-full relative transition-colors"
                            :class="on ? 'bg-green-500' : 'bg-gray-300'"
                            @if (user()->email_verified_at) @click="on = !on" @endif>
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
        @if ($repostRequest->music && isset($repostRequest->music->permalink_url))
            @continue;
        @endif
        <div class="bg-white dark:bg-gray-800 border border-gray-200 mb-4 dark:border-gray-700 shadow-sm">
            <div class="flex flex-col lg:flex-row" wire:key="request-{{ $repostRequest->id }}">
                <!-- Left Column - Track Info -->
                <div
                    class="w-full lg:w-1/2 border-b lg:border-b-0 lg:border-r border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                    <div class="flex flex-col md:flex-row gap-4">
                        <!-- Track Details -->
                        <div class="flex-1 flex flex-col justify-between relative">
                            <!-- SoundCloud Player with Audio Events -->
                            <div id="soundcloud-player-{{ $repostRequest->id }}"
                                data-request-id="{{ $repostRequest->id }}" wire:ignore>
                                <x-sound-cloud.sound-cloud-player :track="$repostRequest->music" :visual="false" />
                            </div>

                            {{-- <!-- Play Time Display -->
                            <div class="absolute top-2 right-2 bg-blue-600 text-white text-xs font-semibold px-2 py-0.5 rounded shadow z-10">
                                {{ $this->getPlayTime($repostRequest->id) }}s / 5s
                            </div> --}}

                            <!-- Request Status Badge -->
                            {{-- <div
                                class="absolute top-2 left-2 bg-purple-600 text-white text-xs font-semibold px-2 py-0.5 rounded shadow z-10 tracking-wide">
                                FEATURED
                            </div> --}}

                            {{-- <!-- Play Progress Bar -->
                            <div class="absolute bottom-2 left-2 right-2 bg-gray-200 dark:bg-gray-600 rounded-full h-1.5">
                                <div class="bg-blue-500 h-1.5 rounded-full transition-all duration-300"
                                     style="width: {{ min(100, ($this->getPlayTime($repostRequest->id) / 5) * 100) }}%"></div>
                            </div> --}}
                        </div>
                    </div>
                </div>

                <!-- Right Column - Request Info -->
                <div class="w-full lg:w-1/2 p-3">
                    <div class="flex justify-between h-full">
                        <div class="w-1/2 relative">
                            <div class="flex flex-col items-start justify-between gap-0 h-full">
                                <div class="flex items-center gap-2">
                                    <img class="w-12 h-12 rounded-full object-cover"
                                        src="{{ auth_storage_url($repostRequest->requester->avatar) }}"
                                        alt="{{ $repostRequest->requester->name }} avatar">
                                    <div x-data="{ open: false }" class="inline-block text-left">
                                        <div @click="open = !open" @click.outside="open = false"
                                            class="flex items-center gap-1 cursor-pointer">
                                            <span
                                                class="text-slate-700 dark:text-gray-300 font-medium">{{ $repostRequest->requester->name }}</span>
                                            <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>

                                        <!-- Rating Stars -->
                                        <div class="flex items-center mt-1">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= ($repostRequest->requester->rating ?? 4) ? 'text-yellow-400' : 'text-gray-300' }}"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                                </svg>
                                            @endfor
                                        </div>

                                        <!-- Dropdown Menu -->
                                        <div x-show="open" x-transition.opacity
                                            class="absolute left-0 mt-2 w-56 z-50 shadow-lg bg-gray-900 text-white text-sm p-2 space-y-2"
                                            x-cloak>
                                            <a href="{{ $repostRequest->requester->soundcloud_url ?? '#' }}"
                                                target="_blank" class="block hover:bg-gray-800 px-3 py-1 rounded">Visit
                                                SoundCloud Profile</a>
                                            <a href="{{ route('user.my-account', $repostRequest->requester->name) }}"
                                                wire:navigate class="block hover:bg-gray-800 px-3 py-1 rounded">Visit
                                                RepostChain Profile</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Track Info -->
                                {{-- <div class="mb-2">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">
                                        {{ $repostRequest->track->title ?? 'Unknown Track' }}
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ Str::limit($repostRequest->track->description ?? 'No description available', 100) }}
                                    </p>
                                </div> --}}
                                <div class="flex items-center gap-2">
                                    <span
                                        class="inline-block bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium px-3 py-1.5 rounded-md shadow-sm">
                                        {{ $repostRequest->music->genre ?? 'Unknown Genre' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="w-1/2 h-fit">
                            @if ($activeMainTab == 'incoming_request' || $activeMainTab == 'previously_reposted')
                                <div class="flex flex-col items-end gap-2 h-full">
                                    <div class="flex flex-col justify-between h-full">
                                        <div class="flex gap-3">
                                            <div
                                                class="text-sm font-semibold text-gray-500 dark:text-gray-400 text-right">
                                                {{ strtoupper($repostRequest->created_at->diffForHumans()) }}
                                            </div>
                                            @if ($activeMainTab == 'incoming_request')
                                                {{-- <!-- Repost Button -->
                                                <button
                                                    wire:click="confirmRepost('{{ $repostRequest->id }}')">test</button> --}}
                                                <div class="relative flex justify-end">
                                                    <button wire:click="confirmRepost('{{ $repostRequest->id }}')"
                                                        @class([
                                                            'flex items-center gap-2 py-2 px-3 sm:px-5 rounded-md shadow-sm text-sm sm:text-base transition-all duration-300',
                                                            'bg-orange-600 dark:bg-orange-500 hover:bg-orange-700 dark:hover:bg-orange-400 text-white cursor-pointer transform hover:scale-105' => $this->canRepost(
                                                                $repostRequest->id),
                                                            'bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed' => !$this->canRepost(
                                                                $repostRequest->id),
                                                        ]) @disabled(!$this->canRepost($repostRequest->id))>

                                                        <!-- Repost Icon -->
                                                        <svg width="26" height="18" viewBox="0 0 26 18"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <rect x="1" y="1" width="24" height="16"
                                                                rx="3" fill="none" stroke="currentColor"
                                                                stroke-width="2" />
                                                            <circle cx="8" cy="9" r="3"
                                                                fill="none" stroke="currentColor"
                                                                stroke-width="2" />
                                                        </svg>

                                                        {{-- <span>{{ repostPrice($repostRequest->requester) }}
                                                            Repost</span> --}}
                                                        <span>{{ $repostRequest->requester?->repost_price }}
                                                            Repost</span>
                                                    </button>

                                                    <!-- Success Indicator -->
                                                    {{-- @if (in_array($repostRequest->id, $this->repostedRequests))
                                                        <div
                                                            class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-green-600 text-white text-xs px-2 py-1 rounded shadow-lg whitespace-nowrap">
                                                            Reposted! ✓
                                                        </div>
                                                    @endif --}}
                                                </div>
                                            @endif

                                        </div>
                                        <!-- Status Badge -->

                                        <div class="text-right my-2">
                                            <span @class([
                                                'inline-block text-xs font-medium px-2 py-1 rounded-full',
                                                'bg-yellow-100 text-yellow-800' =>
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
                                        @if ($activeMainTab == 'outgoing_request')
                                            <h2 class="text-md font-semibold text-gray-700 dark:text-gray-200">
                                                Targeted
                                                Reposter</h2>
                                        @endif
                                    </div>
                                    @if ($activeMainTab == 'outgoing_request')
                                        <div class="flex flex-col sm:flex-row justify-end gap-3">
                                            <a class="cursor-pointer" wire:navigate
                                                href="{{ route('user.my-account', $repostRequest->targetUser->name) }}">
                                                <img class="w-10 h-10 rounded-full object-cover"
                                                    src="{{ auth_storage_url($repostRequest->targetUser->avatar) }}"
                                                    alt="{{ $repostRequest->targetUser->name }} avatar">
                                            </a>
                                            <div x-data="{ open: false }" class="inline-block text-left">
                                                <div class="flex items-center gap-1 cursor-pointer">
                                                    <a class="text-slate-700 dark:text-gray-300 font-medium cursor-pointer hover:underline"
                                                        wire:navigate
                                                        href="{{ route('user.my-account', $repostRequest->targetUser->name) }}">
                                                        {{ $repostRequest->targetUser->name }}
                                                    </a>
                                                </div>

                                                <!-- Rating Stars -->
                                                <div class="flex items-center mt-1">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <svg class="w-4 h-4 {{ $i <= ($repostRequest->targetUser->rating ?? 4) ? 'text-yellow-400' : 'text-gray-300' }}"
                                                            fill="currentColor" viewBox="0 0 20 20">
                                                            <path
                                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                                        </svg>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Status Badge -->
                                    <div class="text-right">
                                        <span @class([
                                            'inline-block text-xs font-medium px-2 py-1 rounded-full',
                                            'bg-yellow-100 text-yellow-800' =>
                                                $repostRequest->status == App\Models\RepostRequest::STATUS_PENDING,
                                            'bg-green-100 text-green-800' =>
                                                $repostRequest->status == App\Models\RepostRequest::STATUS_APPROVED,
                                            'bg-blue-100 text-blue-800' =>
                                                $repostRequest->status == App\Models\RepostRequest::STATUS_APPROVED,
                                            // 'bg-red-100 text-red-800' => $repostRequest->status == App\Models\RepostRequest::STATUS_REJECTED,
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
                                            {{-- <button wire:click="cancleRepostRequest({{ $repostRequest->id }})"
                                                class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">Cancle</button> --}}
                                            <x-gbutton variant="primary" size="sm"
                                                wire:click="cancleRepostRequest({{ $repostRequest->id }})">Cancle</x-gbutton>
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
    {{-- Repost Confirmation Modal --}}
    @include('backend.user.includes.direct-repost-confirmation-modal')

    <!--Previously Reposted Requests-->


</div>

<script>
    // Replace the existing script section in your repost request blade with this complete tracking system
    function initializeSoundCloudWidgets() {
        if (typeof SC === 'undefined') {
            setTimeout(initializeSoundCloudWidgets, 500);
            return;
        }

        const playerContainers = document.querySelectorAll('[id^="soundcloud-player-"]');

        playerContainers.forEach(container => {
            const requestId = container.dataset.requestId;
            const iframe = container.querySelector('iframe');

            if (iframe && requestId) {
                const widget = SC.Widget(iframe);

                widget.bind(SC.Widget.Events.PLAY, () => {
                    @this.call('handleAudioPlay', requestId);
                });

                widget.bind(SC.Widget.Events.PAUSE, () => {
                    @this.call('handleAudioPause', requestId);
                });

                widget.bind(SC.Widget.Events.FINISH, () => {
                    @this.call('handleAudioEnded', requestId);
                });

                widget.bind(SC.Widget.Events.PLAY_PROGRESS, (data) => {
                    const currentTime = data.currentPosition / 1000;
                    @this.call('handleAudioTimeUpdate', requestId, currentTime);
                });
            }
        });
    }

    document.addEventListener('livewire:initialized', function() {
        initializeSoundCloudWidgets();
    });

    document.addEventListener('livewire:navigated', function() {
        initializeSoundCloudWidgets();
    });

    document.addEventListener('livewire:load', function() {
        initializeSoundCloudWidgets();
    });
    document.addEventListener('livewire:updated', function() {
        initializeSoundCloudWidgets();
    });

    document.addEventListener('DOMContentLoaded', function() {
        initializeSoundCloudWidgets();
    });
    // Polling for play time updates (matches campaign feed polling)
    setInterval(() => {
        @this.call('updatePlayingTimes');
    }, 1000);
</script>
