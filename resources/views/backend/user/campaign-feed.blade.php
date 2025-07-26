<x-user::layout>
    <x-slot name="page_slug">campaign-feed</x-slot>
    <div class="flex justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ __('Repost Feed') }}</h2>
            <p class="text-gray-600 dark:text-gray-400">{{ __('Repost tracks to earn credits') }}</p>
        </div>
        <div
            class="shadow-sm border border-gray-100 dark:border-gray-700 flex items-center gap-2 px-3 py-2 rounded-md w-24 h-10 dark:bg-gray-800">
            <i data-lucide="filter" class="w-4 h-4"></i>
            <span>{{ __('Filter') }}</span>
        </div>
    </div>
    {{-- @dd($campaigns) --}}

    <h2 class="text-lg font-semibold text-gray-800 ">Featured campaign</h2>
    <div class="my-6 bg-white border border-gray-200  shadow-sm">
        <div class="flex flex-col md:flex-row w-full gap-4 items-start">
            <!-- Album + Info + Waveform -->
            <div class="flex w-full md:w-1/2 gap-4 border border-gray-200 bg-gray-100 rounded-lg  h-44">
                <!-- Album Cover -->
                <div class="relative w-44 h-44 shrink-0 overflow-hidden flex items-center justify-center">
                    <img src="{{ asset('default_img/other.png') }}" alt="Album Cover"
                        class="w-full h-full object-cover" />
                    <div
                        class="absolute top-2 left-2 bg-cyan-600 text-white text-xs font-semibold px-2 py-0.5 rounded shadow z-10 tracking-wide">
                        FEATURED
                    </div>
                </div>
                <!-- Details & Waveform -->
                <div class="flex flex-col flex-1 h-44 relative justify-between">
                    <!-- Title and Artist -->
                    <div class="flex  justify-between">
                        <div class="flex items-center gap-4 py-2">
                            <button
                                class="w-10 h-10 bg-orange-500 text-white rounded-full flex items-center justify-center shadow transition hover:bg-orange-600 focus:outline-none"
                                aria-label="Play" type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 5v14l11-7z" />
                                </svg>
                            </button>
                            <div>
                                <p class="text-xs text-gray-500 font-medium">Terrik</p>
                                <h3 class="text-lg font-bold text-gray-900 leading-tight">Sinphony</h3>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('soundcloud.redirect') }}"
                                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M7 12.5c0 .28-.22.5-.5.5s-.5-.22-.5-.5.22-.5.5-.5.5.22.5.5zm1.5-.5c-.28 0-.5.22-.5.5s.22.5.5.5.5-.22.5-.5-.22-.5-.5-.5zm1.5.5c0-.28.22-.5.5-.5s.5.22.5.5-.22.5-.5.5-.5-.22-.5-.5zm2.5-.5c.28 0 .5.22.5.5s-.22.5-.5.5-.5-.22-.5-.5.22-.5.5-.5zm1.5.5c0 .28-.22.5-.5.5s-.5-.22-.5-.5.22-.5.5-.5.5.22.5.5zm1.5-.5c-.28 0-.5.22-.5.5s.22.5.5.5.5-.22.5-.5-.22-.5-.5-.5zm2.5.5c0-.28.22-.5.5-.5s.5.22.5.5-.22.5-.5.5-.5-.22-.5-.5zm-12-2c-.28 0-.5.22-.5.5s.22.5.5.5.5-.22.5-.5-.22-.5-.5-.5zm2 0c-.28 0-.5.22-.5.5s.22.5.5.5.5-.22.5-.5-.22-.5-.5-.5z" />
                                </svg>
                                SoundCloud
                            </a>
                        </div>
                    </div>
                    <!-- Waveform mimic -->
                    <div class="w-full h-24 bg-gray-200 rounded flex items-center justify-center text-sm text-gray-500">
                        Music Player
                    </div>
                    <div class="flex justify-between items-center text-xs text-gray-500 w-full pt-2">
                        <a href="#" class="hover:underline">Privacy policy</a>
                        <span class="flex items-center gap-1 text-gray-400">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M3 22v-20l18 10-18 10z"></path>
                            </svg>
                            1 2 3
                        </span>
                    </div>
                </div>
            </div>
            <!-- Right section (optional/empty) -->
            <div class="flex w-full md:w-1/2 flex-col gap-3 items-start md:items-end ml-auto"></div>
        </div>

    </div>




</x-user::layout>
