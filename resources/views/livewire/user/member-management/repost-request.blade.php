<div>
    <x-slot name="page_slug">request</x-slot>
    <div class="flex justify-between items-start mb-5">
        <div>
            <h1 class="text-xl text-black dark:text-gray-100 font-bold">{{ __('Repost Requests') }}</h1>
        </div>
        {{-- Set Route --}}
        <a href="{{ route('user.mm.members.index') }}" wire:navigate
            class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white px-4 py-2 rounded-xl flex items-center gap-3 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
            <x-lucide-plus class="w-5 h-5" />
            {{ __('Send Repost') }}
        </a>
    </div>
   
    <div class="mb-8">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex space-x-8">
                <button
                    class="tab-button @if ($activeMainTab === 'pending') active border-b-2 border-orange-500 text-orange-600 @else border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-3 px-2 text-sm font-semibold transition-all duration-200"
                    wire:click="setActiveTab('pending')">
                    {{ __('Incoming requests') }}
                </button>
                <button
                    class="tab-button @if ($activeMainTab === 'decline') active border-b-2 border-orange-500 text-orange-600 @else border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-3 px-2 text-sm font-semibold transition-all duration-200"
                    wire:click="setActiveTab('decline')">
                    {{ __('Outgoing request') }}
                </button>
                 <button
                    class="tab-button @if ($activeMainTab === 'expired') active border-b-2 border-orange-500 text-orange-600 @else border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif py-3 px-2 text-sm font-semibold transition-all duration-200"
                    wire:click="setActiveTab('expired')">
                    {{ __('Previously Reposted') }}
                </button>

            </nav>
        </div>
    </div>

    <!-- Repost Requests -->
    @if ($activeMainTab)
        <div class="flex flex-col md:flex-row md:items-start md:space-x-3 p-4  rounded bg-gray-100 mb-8 gap-8">
            <!-- Left Icon and "Quick Tip" -->
            <div class="flex items-top space-x-2 mb-2 md:mb-0 ">
                <!-- Lightbulb Icon -->
                <svg class="w-4 h-4 text-sky-500" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M10 2a6 6 0 00-4 10.49V15a1 1 0 001 1h6a1 1 0 001-1v-2.51A6 6 0 0010 2zm1 13h-2v-1h2v1zm1-2H8v-1h4v1zm-.44-2.93a1 1 0 01-.32 1.36L10 11l-.24-.14a1 1 0 111.36-1.36l.44.57z" />
                </svg>
                <!-- Quick Tip Text -->
                <span class="text-sm font-medium text-gray-700">Quick Tip</span>
            </div>

            <!-- Content -->
            <div class="flex-1 flex flex-col space-y-2">
                <!-- Message Text -->
                <p class="text-sm text-gray-700">
                    Your response rate could be better! Improve your response rate and get more requests by accepting OR
                    declining requests quickly.
                </p>

                <!-- Response Rate -->
                <div class="flex items-center space-x-1">
                  <div class="w-4 h-4 rounded-full bg-orange-500"></div>

                    <span class="text-xs text-orange-600 font-semibold">64% <span class="text-gray-900">Response rate.</span></span>
                    <a href="#" class="text-xs text-red-500 underline">Reset</a>
                </div>

                <!-- Toggle -->
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" checked class="sr-only peer mb-3">
                    <div class="flex items-center space-x-2">
                        <!-- Toggle Switch -->
                        <div id="toggleSwitch"
                            class="w-7 h-3 bg-gray-300 rounded-full relative cursor-pointer transition-colors">
                            <div id="toggleCircle"
                                class="absolute left-0.5 top-0  w-3 h-3 bg-white rounded-full shadow transition-transform duration-300">
                            </div>
                        </div>
                        <!-- Label -->
                        <span id="toggleLabel" class="text-sm text-gray-700">Accepting Requests</span>
                    </div>
                    
                </label>
            </div>
        </div>

   <script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleSwitch = document.getElementById('toggleSwitch');
        const toggleCircle = document.getElementById('toggleCircle');
        const toggleLabel = document.getElementById('toggleLabel');

        // Load saved state from localStorage
        let isChecked = localStorage.getItem('toggleChecked') === 'true';

        function updateToggleUI() {
            toggleSwitch.classList.toggle('bg-orange-500', isChecked);
            toggleSwitch.classList.toggle('bg-gray-300', !isChecked);
            toggleCircle.classList.toggle('translate-x-4', isChecked);
            toggleLabel.textContent = isChecked ? 'Accepting Requests' : 'Not Accepting';
        }

        // Initial render
        updateToggleUI();

        toggleSwitch.addEventListener('click', () => {
            isChecked = !isChecked;
            localStorage.setItem('toggleChecked', isChecked);
            updateToggleUI();
        });
    });
</script>
  @endif
    @foreach ($repostRequests as $repostRequest)
        <div class="bg-white dark:bg-gray-800 border border-gray-200 mb-4 dark:border-gray-700 shadow-sm">
            <div class="flex flex-col lg:flex-row" wire:key="request-{{ $repostRequest->id }}">
                <!-- Left Column - Track Info -->
                <div
                    class="w-full lg:w-1/2 border-b lg:border-b-0 lg:border-r border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                    <div class="flex flex-col md:flex-row gap-4">
                        <!-- Track Details -->
                        <div class="flex-1 flex flex-col justify-between p-2 relative">
                            <!-- SoundCloud Player with Audio Events -->
                            <div id="soundcloud-player-{{ $repostRequest->id }}"
                                data-request-id="{{ $repostRequest->id }}" wire:ignore>
                                <x-sound-cloud.sound-cloud-player :track="$repostRequest->track" :visual="false" />
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
                <div class="w-full lg:w-1/2 p-4">
                    <div class="flex flex-col h-full justify-between">
                        <!-- Avatar + Title + User Info -->
                        <div
                            class="flex flex-col sm:flex-row relative items-start sm:items-center justify-between gap-4 mb-2">
                            <div class="flex items-center gap-3">
                                <img class="w-14 h-14 rounded-full object-cover"
                                    src="{{ auth_storage_url($repostRequest->targetUser->avatar) }}"
                                    alt="{{ $repostRequest->targetUser->name }} avatar">
                                <div x-data="{ open: false }" class="inline-block text-left">
                                    <div @click="open = !open" class="flex items-center gap-1 cursor-pointer">
                                        <span
                                            class="text-slate-700 dark:text-gray-300 font-medium">{{ $repostRequest->targetUser->name }}</span>
                                        <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>

                                    <!-- Rating Stars -->
                                    <div class="flex items-center mt-1">
                                        @for ($i = 1; $i <= 1; $i++)
                                            <svg class="w-4 h-4 {{ $i <= ($repostRequest->targetUser->rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}"
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
                                        <a href="{{ $repostRequest->targetUser->soundcloud_url ?? '#' }}"
                                            target="_blank" class="block hover:bg-gray-800 px-3 py-1 rounded">Visit
                                            SoundCloud Profile</a>
                                        <a href="{{ route('user.profile', $repostRequest->targetUser->username ?? $repostRequest->targetUser->id) }}"
                                            wire:navigate class="block hover:bg-gray-800 px-3 py-1 rounded">Visit
                                            RepostChain Profile</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Stats and Repost Button -->
                            <div class="flex flex-col justify-between items-center gap-4 sm:gap-8">
                                {{-- <!-- Play Stats -->
                                <div class="text-center">
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Credits</div>
                                    <div class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        {{ $repostRequest->credits_spent ?? 0 }}
                                    </div>
                                </div> --}}

                                <!-- Repost Button -->
                                <div class="relative">
                                    <button wire:click="repost('{{ $repostRequest->id }}')"
                                        @class([
                                            'flex items-center gap-2 py-2 px-3 sm:px-5 rounded-md shadow-sm text-sm sm:text-base transition-all duration-300',
                                            'bg-orange-600 dark:bg-orange-500 hover:bg-orange-700 dark:hover:bg-orange-400 text-white cursor-pointer transform hover:scale-105' => $this->canRepost(
                                                $repostRequest->id),
                                            'bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed' => !$this->canRepost(
                                                $repostRequest->id),
                                        ]) @disabled(!$this->canRepost($repostRequest->id))>

                                        <!-- Repost Icon -->
                                        <svg width="26" height="18" viewBox="0 0 26 18" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <rect x="1" y="1" width="24" height="16" rx="3"
                                                fill="none" stroke="currentColor" stroke-width="2" />
                                            <circle cx="8" cy="9" r="3" fill="none"
                                                stroke="currentColor" stroke-width="2" />
                                        </svg>

                                        <span>{{ repostPrice($repostRequest->targetUser) }} Repost</span>
                                    </button>

                                    <!-- Success Indicator -->
                                    @if (in_array($repostRequest->id, $this->repostedRequests))
                                        <div
                                            class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-green-600 text-white text-xs px-2 py-1 rounded shadow-lg whitespace-nowrap">
                                            Reposted! ✓
                                        </div>
                                    @endif

                                    {{-- <!-- Timer for Play Requirement -->
                                    @if (!$this->canRepost($repostRequest->id) && $this->getRemainingTime($repostRequest->id) > 0)
                                        <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-blue-600 text-white text-xs px-2 py-1 rounded shadow-lg whitespace-nowrap">
                                            {{ $this->getRemainingTime($repostRequest->id) }}s remaining
                                        </div>
                                    @endif --}}
                                </div>

                                <!-- Manual Play Controls (for testing) -->
                                {{-- @if (app()->environment('local'))
                                    <div class="flex gap-1">
                                        <button wire:click="startPlaying('{{ $repostRequest->id }}')" 
                                                class="text-xs bg-green-500 text-white px-2 py-1 rounded">
                                            ▶
                                        </button>
                                        <button wire:click="stopPlaying('{{ $repostRequest->id }}')" 
                                                class="text-xs bg-red-500 text-white px-2 py-1 rounded">
                                            ⏸
                                        </button>
                                        <button wire:click="simulateAudioProgress('{{ $repostRequest->id }}', 1)" 
                                                class="text-xs bg-blue-500 text-white px-2 py-1 rounded">
                                            +1s
                                        </button>
                                    </div>
                                @endif --}}
                            </div>
                        </div>

                        <!-- Track Info -->
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">
                                {{ $repostRequest->track->title ?? 'Unknown Track' }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ Str::limit($repostRequest->track->description ?? 'No description available', 100) }}
                            </p>
                        </div>

                        <!-- Genre Badge and Request Info -->
                        <div class="flex items-center justify-between mt-auto">
                            <div class="flex items-center gap-2">
                                <span
                                    class="inline-block bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium px-3 py-1.5 rounded-md shadow-sm">
                                    {{ $repostRequest->track->genre ?? 'Unknown Genre' }}
                                </span>

                                <!-- Status Badge -->
                                {{-- <span @class([
                                    'inline-block text-xs font-medium px-2 py-1 rounded-full',
                                    'bg-yellow-100 text-yellow-800' => $repostRequest->status == App\Models\RepostRequest::STATUS_PENDING,
                                    'bg-green-100 text-green-800' => $repostRequest->status == App\Models\RepostRequest::STATUS_COMPLETED,
                                    'bg-blue-100 text-blue-800' => $repostRequest->status == App\Models\RepostRequest::STATUS_APPROVED,
                                    'bg-red-100 text-red-800' => $repostRequest->status == App\Models\RepostRequest::STATUS_REJECTED,
                                    'bg-gray-100 text-gray-800' => $repostRequest->status == App\Models\RepostRequest::STATUS_EXPIRED,
                                ])>
                                    {{ $repostRequest->status_label }}
                                </span> --}}
                            </div>

                            <!-- Request Date -->
                            {{-- <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $repostRequest->requested_at ? \Carbon\Carbon::parse($repostRequest->requested_at)->diffForHumans() : $repostRequest->created_at->diffForHumans() }}
                            </span> --}}
                            @if ($repostRequest->status == App\Models\RepostRequest::STATUS_PENDING)
                                <button wire:click="declineRepostRequest({{ $repostRequest->id }})"
                                class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">{{ $repostRequest->pending_to_declined }}</button>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Audio event handling for SoundCloud players
        document.querySelectorAll('[id^="soundcloud-player-"]').forEach(player => {
            const requestId = player.dataset.requestId;
            const iframe = player.querySelector('iframe');

            if (iframe) {
                iframe.addEventListener('load', function() {
                    // SoundCloud Widget API integration would go here
                    // This is a simplified example - you'll need to implement
                    // proper SoundCloud Widget API event handling

                    // Simulate audio events for now
                    const widget = SC.Widget(iframe);

                    widget.bind(SC.Widget.Events.PLAY, function() {
                        @this.call('handleAudioPlay', requestId);
                    });

                    widget.bind(SC.Widget.Events.PAUSE, function() {
                        @this.call('handleAudioPause', requestId);
                    });

                    widget.bind(SC.Widget.Events.PLAY_PROGRESS, function(data) {
                        @this.call('handleAudioTimeUpdate', requestId, data
                            .currentPosition / 1000);
                    });

                    widget.bind(SC.Widget.Events.FINISH, function() {
                        @this.call('handleAudioEnded', requestId);
                    });
                });
            }
        });

        // Listen for Livewire events
        Livewire.on('requestPlayedEnough', (requestId) => {
            console.log('Request played for 5+ seconds:', requestId);
            // You can add visual feedback here
        });
    });

    // Polling for play time updates (optional)
    setInterval(() => {
        @this.call('updatePlayingTimes');
    }, 1000);
</script>
