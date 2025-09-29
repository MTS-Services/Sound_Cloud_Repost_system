<div x-data="{ activeMainTab: @entangle('activeMainTab').live, showDashboard: true }">
    <x-slot name="page_slug">request</x-slot>
        
    <div class="flex justify-end items-center gap-2 text-xl text-black dark:text-gray-100 font-bold">
        <button >  
            Show Stats</button>
            <span @click="showDashboard = !showDashboard"><x-lucide-menu class="w-5 h-5 " /></span>
    </div>

    <div x-show="showDashboard" class="w-full p-4">
            <x-dashboard-summary :earnings="user()->repost_price" :dailyRepostCurrent="$data['dailyRepostCurrent']" :dailyRepostMax="proUser() ? 100 : 20" :responseRate="30"
                :pendingRequests="$pendingRequestCount" :requestLimit="25" :credits="userCredits()" :campaigns="$data['totalMyCampaign']" :campaignLimit="proUser() ? 10 : 2" />
    </div>

    <section class="flex flex-col lg:flex-row gap-4 lg:gap-6">
        {{-- Left Side --}}
    <div class="w-full mx-auto pt-52 lg:pt-0 p-4">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-5 space-y-3 sm:space-y-0">
                <div>
                    <h1 class="text-xl text-black dark:text-gray-100 font-bold">
                        Repost Requests
                    </h1>
                </div>
                <button type="button" class="font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 rounded-lg inline-flex items-center justify-center cursor-pointer disabled:cursor-not-allowed bg-orange-600 text-white hover:bg-orange-500 active:bg-orange-700 disabled:bg-orange-500 disabled:text-gray-50 disabled:cursor-not-allowed px-4 py-2 text-base w-full sm:w-auto" wire:navigate="" href="https://repostchain.com/user/members">
                <span><svg class="w-5 h-5 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14"></path>
                <path d="M12 5v14"></path>
                </svg></span>
                Send a New Request
                </button>
             </div>

            <div class="mb-8">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="-mb-px flex space-x-8">
                        <!-- Incoming Request Tab Button -->
                        <a href="https://repostchain.com/user/reposts-request?tab=incoming_request" wire:navigate="" class="tab-button  active border-b-2 border-orange-500 text-orange-600  py-3 px-2 text-sm font-semibold transition-all duration-200">
                            Incoming requests
                        </a>
                        <a href="https://repostchain.com/user/reposts-request?tab=outgoing_request" wire:navigate="" class="tab-button  border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300  py-3 px-2 text-sm font-semibold transition-all duration-200">
                            Outgoing request
                        </a>
                        <a href="https://repostchain.com/user/reposts-request?tab=previously_reposted" wire:navigate="" class="tab-button  border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300  py-3 px-2 text-sm font-semibold transition-all duration-200">
                            Previously Reposted
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Repost Requests -->
            <!--[if BLOCK]><![endif]--> 
            <div class="flex flex-col md:flex-row md:items-start md:space-x-3 p-4  rounded bg-gray-100 dark:bg-gray-800 mb-8 gap-8">
                    <!-- Left Icon and "Quick Tip" -->
                    <div class="flex items-top space-x-2 mb-2 md:mb-0 ">
                        <!-- Lightbulb Icon -->
                        <svg class="w-4 h-4 text-sky-600 dark:text-sky-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a6 6 0 00-4 10.49V15a1 1 0 001 1h6a1 1 0 001-1v-2.51A6 6 0 0010 2zm1 13h-2v-1h2v1zm1-2H8v-1h4v1zm-.44-2.93a1 1 0 01-.32 1.36L10 11l-.24-.14a1 1 0 111.36-1.36l.44.57z"></path>
                        </svg>
                        <!-- Quick Tip Text -->
                        <span class="text-sm font-medium text-gray-700 dark:text-white">Quick Tip</span>
                    </div>
                    <!-- Content -->
                    <div class="flex-1 flex flex-col space-y-2">
                        <!-- Message Text -->
                        <p class="text-sm text-gray-700 dark:text-white">
                            Your response rate could be better! Improve your response rate and get more requests by
                            accepting OR
                            declining requests quickly.
                        </p>
                        <!-- Response Rate -->
                        <div class="flex items-center space-x-1">
                            <div class="w-4 h-4 rounded-full bg-orange-500"></div>

                            <span class="text-xs text-orange-600 font-semibold">100% <span class="text-gray-900 dark:text-white">Response rate.</span></span>
                            <a href="https://repostchain.com/user/reposts-request" wire:navigate="" class="text-xs text-red-500 underline">Reset</a>
                        </div>
                        <div x-data="{ on: false }" class="inline-flex items-center cursor-not-allowed" data-has-alpine-state="true">
                            <input type="checkbox" class="sr-only peer" wire:model.live="requestReceiveable" checked="" disabled="">

                            <div class="flex items-center space-x-2">
                                <div class="w-7 h-4 rounded-full relative transition-colors bg-gray-300" :class="on ? 'bg-green-500' : 'bg-gray-300'">
                                    <div class="absolute top-0 left-0 w-4 h-4 bg-white rounded-full shadow transition-transform duration-300 translate-x-0" :class="on ? 'translate-x-4' : 'translate-x-0'">
                                    </div>
                                </div>
                                <span class="text-sm text-gray-700 dark:text-white">
                                    Accepting Requests
                                    <!--[if BLOCK]><![endif]--> 
                                    <span class="ml-2 text-red-500">(Verify email first)</span>
                                    <!--[if ENDBLOCK]><![endif]-->
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            <!--[if ENDBLOCK]><![endif]-->
            <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->

            <!--[if BLOCK]><![endif]-->         
             <div class="text-center py-12">
                    <div class="text-gray-500 dark:text-gray-400 text-lg mb-2">
                        No repost requests found
                    </div>
                    <p class="text-gray-400 dark:text-gray-500 text-sm">
                        When others request reposts from you, they'll appear here.
                    </p>
                </div>
            <!--[if ENDBLOCK]><![endif]-->

        </div>
    </section>
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
