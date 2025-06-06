<x-user::layout>
    <x-slot name="page_slug">repost-feed</x-slot>
    <div class="flex justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ __('Repost Feed') }}</h2>
            <p class="text-gray-600">{{ __('Repost tracks to earn credits') }}</p>
        </div>
        <div class="shadow-sm border border-gray-100 flex items-center gap-2 px-3 py-2 rounded-md w-24 h-10">
            <i data-lucide="filter" class="w-4 h-4"></i>
            <span>{{ __('Filter') }}</span>
        </div>
    </div>
    <div class="p-3 my-6 shadow-md rounded-lg">
        <div class="flex justify-between">
            <div class="flex justify-start gap-3">
                <div class="w-56 h-36 rounded-md overflow-hidden">
                    <img src="{{ asset('frontend/user/image/music-1.jpg') }}" class="w-full h-full" alt="">
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">{{ __('Summer Hits') }}</h2>
                    <p class="text-gray-600 text-sm">{{ __('WaveMaker') }}</p>
                    <div class="flex items-center space-x-4 mt-2 text-sm text-gray-600">
                        <div class="flex items-center">
                            <i data-lucide="timer" class="w-4 h-4 mr-1"></i>
                            4:45
                        </div>
                        <div class="flex items-center">
                            <i data-lucide="volume-2" class="w-4 h-4 mr-1"></i>
                            {{ __('2,458 plays') }}
                        </div>
                        <div class="flex items-center">
                            <i data-lucide="heart" class="w-4 h-4 mr-1"></i>
                            {{ __('458 likes') }}
                        </div>
                    </div>
                    <div class="flex space-x-2 mt-4">
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs">
                            {{ __('Electronic') }}</span>
                        <span
                            class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs">{{ __('Child') }}</span>
                    </div>
                </div>
            </div>
            <div class="flex flex-col">
                <div class="flex justify-center items-center gap-2">
                    <p class="text-sm text-gray-600">{{ __('Crediblity') }}</p>
                    <div class="w-32 bg-gray-200 rounded-full h-2">
                        <div class="bg-orange-500 h-2 rounded-full" style="width: 70%"></div>
                    </div>
                    <p class="text-sm text-gray-600">90%</p>
                </div>
                <div class="w-42 ms-auto flex items-center gap-2 mt-4 bg-orange-600 text-white py-2 px-4 rounded-md">
                    <i data-lucide="refresh cw" class="w-4 h-4"></i>
                    <a href="#" class="text-sm">{{ __('Submit Track') }}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="p-3 my-6 shadow-md rounded-lg">
        <div class="flex justify-between">
            <div class="flex justify-start gap-3">
                <div class="w-56 h-36 rounded-md overflow-hidden">
                    <div class="w-full h-full">
                        <img src="{{ asset('frontend/user/image/music-2.jpg') }}" class="w-full h-full" alt="">
                    </div>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">{{ __('Summer Hits') }}</h2>
                    <p class="text-gray-600 text-sm">{{ __('WaveMaker') }}</p>
                    <div class="flex items-center space-x-4 mt-2 text-sm text-gray-600">
                        <div class="flex items-center">
                            <i data-lucide="timer" class="w-4 h-4 mr-1"></i>
                            4:45
                        </div>
                        <div class="flex items-center">
                            <i data-lucide="volume-2" class="w-4 h-4 mr-1"></i>
                            {{ __('2,458 plays') }}
                        </div>
                        <div class="flex items-center">
                            <i data-lucide="heart" class="w-4 h-4 mr-1"></i>
                            {{ __('458 likes') }}
                        </div>
                    </div>
                    <div class="flex space-x-2 mt-4">
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs">
                            {{ __('Electronic') }}</span>
                        <span
                            class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs">{{ __('Child') }}</span>
                    </div>
                </div>
            </div>
            <div class="flex flex-col">
                <div class="flex justify-center items-center gap-2">
                    <p class="text-sm text-gray-600">{{ __('Crediblity') }}</p>
                    <div class="w-32 bg-gray-200 rounded-full h-2">
                        <div class="bg-orange-500 h-2 rounded-full" style="width: 70%"></div>
                    </div>
                    <p class="text-sm text-gray-600">90%</p>
                </div>
                <div class="w-42 ms-auto flex items-center gap-2 mt-4 bg-orange-600 text-white py-2 px-4 rounded-md">
                    <i data-lucide="refresh cw" class="w-4 h-4"></i>
                    <a href="#" class="text-sm">{{ __('Submit Track') }}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="p-3 my-6 shadow-md rounded-lg">
        <div class="flex justify-between">
            <div class="flex justify-start gap-3">
                <div class="w-56 h-36 rounded-md overflow-hidden">
                    <img src="{{ asset('frontend/user/image/music-3.jpg') }}" class="w-full h-full" alt="">
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">{{ __('Summer Hits') }}</h2>
                    <p class="text-gray-600 text-sm">{{ __('WaveMaker') }}</p>
                    <div class="flex items-center space-x-4 mt-2 text-sm text-gray-600">
                        <div class="flex items-center">
                            <i data-lucide="timer" class="w-4 h-4 mr-1"></i>
                            4:45
                        </div>
                        <div class="flex items-center">
                            <i data-lucide="volume-2" class="w-4 h-4 mr-1"></i>
                            {{ __('2,458 plays') }}
                        </div>
                        <div class="flex items-center">
                            <i data-lucide="heart" class="w-4 h-4 mr-1"></i>
                            {{ __('458 likes') }}
                        </div>
                    </div>
                    <div class="flex space-x-2 mt-4">
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs">
                            {{ __('Electronic') }}</span>
                        <span
                            class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs">{{ __('Child') }}</span>
                    </div>
                </div>
            </div>
            <div class="flex flex-col">
                <div class="flex justify-center items-center gap-2">
                    <p class="text-sm text-gray-600">{{ __('Crediblity') }}</p>
                    <div class="w-32 bg-gray-200 rounded-full h-2">
                        <div class="bg-orange-500 h-2 rounded-full" style="width: 70%"></div>
                    </div>
                    <p class="text-sm text-gray-600">90%</p>
                </div>
                <div class="w-42 ms-auto flex items-center gap-2 mt-4 bg-orange-600 text-white py-2 px-4 rounded-md">
                    <i data-lucide="refresh cw" class="w-4 h-4"></i>
                    <a href="#" class="text-sm">{{ __('Submit Track') }}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="p-3 my-6 shadow-md rounded-lg">
        <div class="flex justify-between">
            <div class="flex justify-start gap-3">
                <div class="w-56 h-36 rounded-md overflow-hidden">
                    <img src="{{ asset('frontend/user/image/music-4.jpg') }}" class="w-full h-full" alt="">
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">{{ __('Summer Hits') }}</h2>
                    <p class="text-gray-600 text-sm">{{ __('WaveMaker') }}</p>
                    <div class="flex items-center space-x-4 mt-2 text-sm text-gray-600">
                        <div class="flex items-center">
                            <i data-lucide="timer" class="w-4 h-4 mr-1"></i>
                            4:45
                        </div>
                        <div class="flex items-center">
                            <i data-lucide="volume-2" class="w-4 h-4 mr-1"></i>
                            {{ __('2,458 plays') }}
                        </div>
                        <div class="flex items-center">
                            <i data-lucide="heart" class="w-4 h-4 mr-1"></i>
                            {{ __('458 likes') }}
                        </div>
                    </div>
                    <div class="flex space-x-2 mt-4">
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs">
                            {{ __('Electronic') }}</span>
                        <span
                            class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs">{{ __('Child') }}</span>
                    </div>
                </div>
            </div>
            <div class="flex flex-col">
                <div class="flex justify-center items-center gap-2">
                    <p class="text-sm text-gray-600">{{ __('Crediblity') }}</p>
                    <div class="w-32 bg-gray-200 rounded-full h-2">
                        <div class="bg-orange-500 h-2 rounded-full" style="width: 70%"></div>
                    </div>
                    <p class="text-sm text-gray-600">90%</p>
                </div>
                <div class="w-42 ms-auto flex items-center gap-2 mt-4 bg-orange-600 text-white py-2 px-4 rounded-md">
                    <i data-lucide="refresh cw" class="w-4 h-4"></i>
                    <a href="#" class="text-sm">{{ __('Submit Track') }}</a>
                </div>
            </div>
        </div>
    </div>
</x-user::layout>
