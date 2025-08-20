<x-admin::layout>
    <x-slot name="title">{{ __('Package Credit Detail') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Package Credit Detail') }}</x-slot>
    <x-slot name="page_slug">credit</x-slot>

    <div class="max-w-8xl mx-auto">
        <!-- Header Section -->
        <div class="bg-white dark:bg-bg-dark-primary rounded-xl shadow-sm p-6 mb-6 border border-gray-100 dark:border-gray-700">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Package Credit Details') }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ __('View comprehensive information about this credit package') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    {{-- <x-button href="{{ route('pm.credit.index') }}" permission="credit-create"
                        class="flex items-center gap-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 border-0">

                        {{ __('Back to List') }}
                    </x-button> --}}
                    <x-button href="{{ route('pm.credit.index') }}" permission="credit-create"
                        class="flex items-center gap-2">
                        <x-lucide-arrow-left class="w-4 h-4" />
                        {{ __('Back to Users') }}
                    </x-button>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div
            class="bg-white dark:bg-bg-dark-primary rounded-xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700">
            <!-- Package Header -->
            <div class="p-8 border-b border-gray-100 dark:border-gray-700">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="p-3 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                                {{ $credits->name ?? 'Unnamed Package' }}</h2>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 ml-12">
                            {{ __('Credit package details and specifications') }}</p>
                    </div>
                    <div
                        class="px-4 py-2 rounded-full text-sm font-medium {{ $credits->status ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                        {{ $credits->status_label ?? 'N/A' }}
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="p-8">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    {{ __('Package Statistics') }}
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                    <!-- Price Card -->
                    <div
                        class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-700 p-5 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-400"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Price') }}</p>
                                <p class="text-xl font-semibold text-gray-900 dark:text-white">
                                    {{ $credits->price ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Credits Card -->
                    <div
                        class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-700 p-5 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6 text-green-600 dark:text-green-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Credits') }}</p>
                                <p class="text-xl font-semibold text-gray-900 dark:text-white">{{ $credits->credits }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Created At Card -->
                    <div
                        class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-700 p-5 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Created At') }}</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $credits->created_at_formatted }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Updated At Card -->
                    <div
                        class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-700 p-5 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Updated At') }}</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $credits->updated_at_formatted }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="p-8 border-t border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ __('Additional Information') }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Notes -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-5 rounded-xl">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                            {{ __('Credit Notes') }}
                        </h4>
                        <p class="text-gray-900 dark:text-white">{{ $credits->notes ?? 'No notes available' }}</p>
                    </div>

                    <!-- System Information -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-5 rounded-xl">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">
                            {{ __('System Information') }}</h4>

                        <div class="space-y-3">

                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">{{ __('Updated By') }}:</span>
                                <span
                                    class="font-medium text-gray-900 dark:text-white">{{ $updater_name ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .glass-card {
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            background-color: rgba(255, 255, 255, 0.75);
            border: 1px solid rgba(209, 213, 219, 0.3);
        }

        .dark .glass-card {
            background-color: rgba(17, 25, 40, 0.75);
            border: 1px solid rgba(255, 255, 255, 0.125);
        }

        .bg-gradient-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(249, 250, 251, 0.9) 100%);
        }

        .dark .bg-gradient-card {
            background: linear-gradient(135deg, rgba(31, 41, 55, 0.9) 0%, rgba(17, 24, 39, 0.9) 100%);
        }
    </style>
</x-admin::layout>
