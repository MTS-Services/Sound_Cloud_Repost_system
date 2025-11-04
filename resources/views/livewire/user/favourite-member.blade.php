<div>
    <x-slot name="page_slug">favourite-member</x-slot>
    <!-- Title Section -->
    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
        @if ($starred == 'favourite')
            {{ __("Members you've starred") }}
        @elseif($starred == 'favourited')
            {{ __('Members who starred you') }}
        @endif
    </h2>
    <div>
        @if ($starred == 'favourite')
            @forelse ($favouriteUsers as $favouriteUser)
                <div
                    class="p-6 mt-4 bg-card-blue rounded-lg p-6 bg-white dark:bg-gray-800 shadow-lg dark:shadow-[0_4px_20px_rgba(0,0,0,0.8)] flex justify-between items-center space-y-4 md:space-y-0">
                    <!-- Member Card -->
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center">
                            <img src="{{ auth_storage_url($favouriteUser->follower?->avatar) }}" alt="Member Avatar"
                                class="w-12 h-12 rounded-full">
                        </div>
                        <div class="relative">
                            <div x-data="{ open: false }" class="inline-block text-left">
                                <div class="flex items-center gap-3">
                                    <div @click="open = !open" @click.outside="open = false"
                                        class="flex items-center gap-1 cursor-pointer">
                                        <span
                                            class="text-slate-700 dark:text-gray-300 font-medium">{{ $favouriteUser->follower?->name }}</span>
                                        <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7">
                                            </path>
                                        </svg>
                                    </div>
                                    @if (proUser(user()->urn))
                                        <span
                                            class="text-sm badge badge-soft badge-warning rounded-full font-semibold">{{ userPlanName(user()->urn) }}</span>
                                    @else
                                        <span
                                            class="text-sm badge badge-soft badge-info rounded-full font-semibold">{{ userPlanName(user()->urn) }}</span>
                                    @endif
                                </div>
                                <div x-show="open" x-transition.opacity
                                    class="absolute left-0 mt-2 w-56 z-50 shadow-lg bg-gray-900 text-white text-sm p-2 space-y-2"
                                    x-cloak>
                                    <a href="{{ $favouriteUser->follower?->soundcloud_permalink_url }}" target="_blank"
                                        class="block hover:bg-gray-800 px-3 py-1 rounded">Visit
                                        SoundCloud
                                        Profile</a>
                                    <a href="{{ route('user.my-account.user', !empty($favouriteUser->follower?->name) ? $favouriteUser->follower?->name : $favouriteUser->follower_urn) }}"
                                        wire:navigate class="block hover:bg-gray-800 px-3 py-1 rounded">Visit
                                        RepostChain Profile</a>
                                </div>
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                {{ $favouriteUser->follower?->created_at->format('M d, Y') }}</div>
                        </div>
                    </div>


                    <!-- Tags Section -->
                    <div class="flex space-x-2 mt-4">
                        @foreach ($favouriteUser->follower?->genres as $genre)
                            <span
                                class="bg-gray-600 text-white text-xs px-2 py-1 rounded-full">{{ $genre->genre }}</span>
                        @endforeach
                    </div>

                    <!-- Request Button -->
                    <div class="flex justify-end">
                        <button class="bg-orange-500 text-white px-6 py-2 rounded-md hover:bg-orange-600">
                            10 Request
                        </button>
                    </div>
                </div>
            @empty
                <div
                    class="flex items-center p-4 bg-white dark:bg-gray-800 shadow-lg dark:shadow-[0_4px_20px_rgba(0,0,0,0.8)] space-x-4 mt-4">
                    <!-- Avatar placeholder -->
                    <div class="w-12 h-12 rounded-full bg-gray-200 animate-pulse"></div>
                    <div class="flex flex-col gap-2">
                        <div class="w-48 h-3 bg-gray-300 animate-pulse"></div>
                        <div class="w-40 h-3 bg-gray-200 animate-pulse"></div>
                    </div>

                    <!-- Text -->
                    <p class="flex-1 text-gray-400 text-sm select-none lg:ms-10">
                        Seems like you haven't starred any members yet
                    </p>

                    <!-- Discover Members button -->
                    <a href="{{ route('user.members') }}" wire:navigate
                        class="inline-block px-5 py-2 text-white bg-orange-600 hover:bg-orange-700 rounded transition">
                        Discover Members
                    </a>
                </div>
            @endforelse
        @elseif ($starred == 'favourited')
            @forelse ($favouriteUsers as $favouriteUser)
                <div
                    class="p-6 mt-4 bg-card-blue rounded-lg p-6 bg-white dark:bg-gray-800 shadow-lg dark:shadow-[0_4px_20px_rgba(0,0,0,0.8)] flex justify-between items-center space-y-4 md:space-y-0">
                    <!-- Member Card -->
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center">
                            <img src="{{ auth_storage_url($favouriteUser->following?->avatar) }}" alt="Member Avatar"
                                class="w-12 h-12 rounded-full">
                        </div>
                        <div class="relative">
                            <div x-data="{ open: false }" class="inline-block text-left">
                                <div class="flex items-center gap-3">
                                    <div @click="open = !open" @click.outside="open = false"
                                        class="flex items-center gap-1 cursor-pointer">
                                        <span
                                            class="text-slate-700 dark:text-gray-300 font-medium">{{ $favouriteUser->following?->name }}</span>
                                        <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7">
                                            </path>
                                        </svg>
                                    </div>
                                    @if (proUser(user()->urn))
                                        <span
                                            class="text-sm badge badge-soft badge-warning rounded-full font-semibold">{{ userPlanName(user()->urn) }}</span>
                                    @else
                                        <span
                                            class="text-sm badge badge-soft badge-info rounded-full font-semibold">{{ userPlanName(user()->urn) }}</span>
                                    @endif
                                </div>
                                <div x-show="open" x-transition.opacity
                                    class="absolute left-0 mt-2 w-56 z-50 shadow-lg bg-gray-900 text-white text-sm p-2 space-y-2"
                                    x-cloak>
                                    <a href="{{ $favouriteUser->following?->soundcloud_permalink_url }}"
                                        target="_blank" class="block hover:bg-gray-800 px-3 py-1 rounded">Visit
                                        SoundCloud
                                        Profile</a>
                                    <a href="{{ route('user.my-account.user', !empty($favouriteUser->following?->name) ? $favouriteUser->following?->name : $favouriteUser->following_urn) }}"
                                        wire:navigate class="block hover:bg-gray-800 px-3 py-1 rounded">Visit
                                        RepostChain Profile</a>
                                </div>
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                {{ $favouriteUser->following?->created_at->format('M d, Y') }}</div>
                        </div>
                    </div>


                    <!-- Tags Section -->
                    <div class="flex space-x-2 mt-4">
                        @foreach ($favouriteUser->following?->genres as $genre)
                            <span
                                class="bg-gray-600 text-white text-xs px-2 py-1 rounded-full">{{ $genre->genre }}</span>
                        @endforeach
                    </div>

                    <!-- Request Button -->
                    <div class="flex justify-end">
                        <button class="bg-orange-500 text-white px-6 py-2 rounded-md hover:bg-orange-600">
                            10 Request
                        </button>
                    </div>
                </div>
            @empty
                <div
                    class="flex items-center p-4 bg-white dark:bg-gray-800 shadow-lg dark:shadow-[0_4px_20px_rgba(0,0,0,0.8)] space-x-4 mt-4">
                    <!-- Avatar placeholder -->
                    <div class="w-12 h-12 rounded-full bg-gray-200 animate-pulse"></div>
                    <div class="flex flex-col gap-2">
                        <div class="w-48 h-3 bg-gray-300 dark:bg-gray-700 animate-pulse"></div>
                        <div class="w-40 h-3 bg-gray-200 dark:bg-gray-600 animate-pulse"></div>
                    </div>

                    <!-- Text -->
                    <p class="flex-1 text-gray-400 text-sm select-none lg:ms-10">
                        Seems like you haven't starred any members yet
                    </p>

                    <!-- Discover Members button -->
                    <a href="{{ route('user.members') }}" wire:navigate
                        class="inline-block px-5 py-2 text-white bg-orange-600 hover:bg-orange-700 rounded transition">
                        Discover Members
                    </a>
                </div>
            @endforelse
        @endif
    </div>

</div>
