<x-admin::layout>
    <x-slot name="title">{{ __('Campaign Details') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Campaign Details') }}</x-slot>
    <x-slot name="page_slug">campaign</x-slot>

    <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="bg-white dark:bg-bg-dark-primary rounded-2xl shadow-sm p-6 mb-6 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Campaign Details') }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ __('Comprehensive information about this campaign') }}</p>
                </div>
                <x-button href="{{ session('back_route', url()->previous()) }}" permission="credit-create"
                    class="flex items-center gap-2  dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 border-0 transition-colors duration-200">
                    <x-lucide-arrow-left class="h-5 w-5" />
                    {{ __('Back') }}
                </x-button>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white dark:bg-bg-dark-primary rounded-2xl shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700">
            <!-- Campaign Header -->
            <div class="p-8 border-b border-gray-200 dark:border-gray-700">
                <div class="flex flex-col md:flex-row items-start gap-6">
                    <!-- Album Art -->
                    <div class="w-full md:w-1/3 max-w-56 rounded-xl shadow-lg overflow-hidden">
                        <img class="w-full h-full object-cover"
                            src="{{ soundcloud_image($campaigns->music?->artwork_url) }}" alt="Album Art">
                    </div>

                    <!-- Campaign Info -->
                    <div class="flex-1">
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-2">
                            {{ $campaigns->title ?? $campaigns->music?->title }}
                        </h2>
                        <p class="text-orange-500 dark:text-orange-400 mb-2">by
                            {{ $campaigns->user?->name ?? 'Unknown' }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Genre: <span class="text-black dark:text-white font-medium">{{ $campaigns->music?->genre ?? 'Unknown' }}</span>
                        </p>
                        <p class="text-gray-700 dark:text-gray-300 text-sm mb-6">
                            {{ $campaigns->description ?? 'No description provided' }}
                        </p>

                        <!-- Status Badge -->
                        <div class="flex items-center gap-3 mb-4">
                            <span
                                class="px-3 py-1 rounded-full text-xs font-medium {{ $campaigns->status == 1
                                    ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'
                                    : ($campaigns->status == 2
                                        ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400'
                                        : ($campaigns->status == 3
                                            ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400'
                                            : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400')) }}">
                                {{ $campaigns->status_label }}
                            </span>

                            @if ($campaigns->is_featured)
                                <span
                                    class="px-3 py-1 bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400 rounded-full text-xs font-medium">
                                    Featured
                                </span>
                            @endif
                        </div>

                        <!-- Action Button -->
                        @if ($campaigns->status == 1 || $campaigns->status == 2)
                            <button
                                class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                                {{ $campaigns->status_btn_label }}
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="p-8">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <x-lucide-bar-chart class="h-5 w-5 text-gray-500" />
                    {{ __('Campaign Statistics') }}
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                    <!-- Budget Card -->
                    <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                                <x-lucide-dollar-sign class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Budget') }}</p>
                                <p class="text-xl font-semibold text-gray-900 dark:text-white">
                                    {{ $campaigns->budget_credits }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Credits Spent Card -->
                    <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                                <x-lucide-credit-card class="h-6 w-6 text-green-600 dark:text-green-400" />
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Credits Spent') }}</p>
                                <p class="text-xl font-semibold text-gray-900 dark:text-white">
                                    {{ $campaigns->credits_spent }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Max Followers Card -->
                    <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                                <x-lucide-users class="h-6 w-6 text-purple-600 dark:text-purple-400" />
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Max Followers') }}</p>
                                <p class="text-xl font-semibold text-gray-900 dark:text-white">
                                    {{ $campaigns->max_followers }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Min Followers Card -->
                    <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-amber-100 dark:bg-amber-900/30 rounded-lg">
                                <x-lucide-user-check class="h-6 w-6 text-amber-600 dark:text-amber-400" />
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Min Followers') }}</p>
                                <p class="text-xl font-semibold text-gray-900 dark:text-white">
                                    {{ $campaigns->min_followers }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Campaign Settings -->
            <div class="p-8 border-t border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <x-lucide-settings class="h-5 w-5 text-gray-500" />
                    {{ __('Campaign Settings') }}
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    <!-- Refund Credits -->
                    <div class="bg-gray-50 dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-600">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">{{ __('Refund Credits') }}</h4>
                        <p class="text-xl font-semibold text-gray-900 dark:text-white">{{ $campaigns->refund_credits }}</p>
                    </div>

                    <!-- Start Date -->
                    <div class="bg-gray-50 dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-600">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">{{ __('Start Date') }}</h4>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $campaigns->start_date_formatted }}</p>
                    </div>

                    <!-- End Date -->
                    <div class="bg-gray-50 dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-600">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">{{ __('End Date') }}</h4>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $campaigns->end_date_formatted }}</p>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="p-8 border-t border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <x-lucide-info class="h-5 w-5 text-gray-500" />
                    {{ __('Additional Information') }}
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Featured Status -->
                    <div class="bg-gray-50 dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-600">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">{{ __('Featured Status') }}</h4>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $campaigns->feature_label }}</p>
                    </div>

                    <!-- Playback Count -->
                    <div class="bg-gray-50 dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-600">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">{{ __('Playback Count') }}</h4>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $campaigns->playback_count ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin::layout>
