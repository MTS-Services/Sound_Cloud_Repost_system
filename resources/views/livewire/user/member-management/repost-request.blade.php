<div>
    <x-slot name="page_slug">request</x-slot>

    <h1>This is Repost Request page</h1>
    @foreach ($repostRequests as $repostRequest)
        <div class="bg-white dark:bg-gray-800 border border-gray-200 mb-4 dark:border-gray-700 shadow-sm">
            <div class="flex flex-col lg:flex-row" wire:key="featured-{{ $repostRequest->id }}">
                <!-- Left Column - Track Info -->
                <div
                    class="w-full lg:w-1/2 border-b lg:border-b-0 lg:border-r border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                    <div class="flex flex-col md:flex-row gap-4">
                        <!-- Track Details -->
                        <div class="flex-1 flex flex-col justify-between p-2 relative">
                            <!-- Your Original SoundCloud Player -->
                            <div id="soundcloud-player-{{ $repostRequest->id }}"
                                data-repostRequest-id="{{ $repostRequest->id }}" wire:ignore>
                                <x-sound-cloud.sound-cloud-player :track="$repostRequest->track" :visual="false" />
                            </div>
                            <div
                                class="absolute top-2 left-2 bg-cyan-600 text-white text-xs font-semibold px-2 py-0.5 rounded shadow z-10 tracking-wide">
                                FEATURED
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - repostRequest Info -->
                <div class="w-full lg:w-1/2 p-4">
                    <div class="flex flex-col h-full justify-between">
                        <!-- Avatar + Title + Icon -->
                        <div
                            class="flex flex-col sm:flex-row relative items-start sm:items-center justify-between gap-4 mb-4">
                            <div class="flex items-center gap-3">
                                <img class="w-14 h-14 rounded-full object-cover"
                                    src="{{ auth_storage_url($repostRequest->requester->avatar) }}"
                                    alt="Audio Cure avatar">
                                <div x-data="{ open: false }" class="inline-block text-left">
                                    <div @click="open = !open" class="flex items-center gap-1 cursor-pointer">
                                        <span
                                            class="text-slate-700 dark:text-gray-300 font-medium">{{ $repostRequest->requester->name }}</span>
                                        <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 mt-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                        </path>
                                    </svg>

                                    <div x-show="open" x-transition.opacity
                                        class="absolute left-0 mt-2 w-56 z-50 shadow-lg bg-gray-900 text-white text-sm p-2 space-y-2"
                                        x-cloak>
                                        <a href=""
                                            target="_blank" class="block hover:bg-gray-800 px-3 py-1 rounded">Visit
                                            SoundCloud
                                            Profile</a>
                                        <a href="{{ route('user.profile') }}" wire:navigate
                                            class="block hover:bg-gray-800 px-3 py-1 rounded">Visit
                                            RepostChain Profile</a>
                                    </div>
                                </div>
                            </div>
                            <!-- Stats and Repost Button -->
                            <div class="flex items-center gap-4 sm:gap-8">
                                <div class="relative">
                                    <!-- Repost Button -->
                                    <button wire:click="repost('{{ $repostRequest->id }}')" class="flex items-center gap-1.5">
                                        <svg width="26" height="18" viewBox="0 0 26 18" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <rect x="1" y="1" width="24" height="16" rx="3"
                                                fill="none" stroke="currentColor" stroke-width="2" />
                                            <circle cx="8" cy="9" r="3" fill="none"
                                                stroke="currentColor" stroke-width="2" />
                                        </svg>
                                        <span>{{ $repostRequest->credits_spent }}</span>
                                        <span>
                                            Repost</span>
                                    </button>
                                    {{-- @if (in_array($repostRequest->id, $this->repostedrepostRequests))
                                        <div
                                            class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-green-600 text-white text-xs px-2 py-1 rounded shadow-lg whitespace-nowrap">
                                            Reposted! ✓
                                        </div>
                                    @endif --}}
                                </div>
                            </div>
                        </div>

                        <!-- Genre Badge -->
                        <div class="mt-auto">
                            <span
                                class="inline-block bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium px-3 py-1.5 rounded-md shadow-sm">
                                {{ $repostRequest->music->genre ?? 'Unknown Genre' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
