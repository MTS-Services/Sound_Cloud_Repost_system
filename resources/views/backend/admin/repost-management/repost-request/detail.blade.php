<x-admin::layout>
    <x-slot name="title">{{ __('Repost Request Detail') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Repost Request Detail') }}</x-slot>
    <x-slot name="page_slug">repost_request</x-slot>

    <div
        class="glass-card rounded-xl p-6 mb-6 bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ __('Repost Request Details') }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    {{ __('View comprehensive information about this repost request') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <x-button href="{{ session('back_route', url()->previous()) }}"
                    class="flex items-center gap-2  dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 transition-colors duration-200 rounded-lg px-4 py-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ __('Back') }}
                </x-button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Request Overview Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Request Overview</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="flex items-center mb-4">
                                <div
                                    class="flex-shrink-0 h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Requester</h4>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $requests->requester?->name ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <div class="flex items-center mb-4">
                                <div
                                    class="flex-shrink-0 h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Campaign</h4>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $requests->campaign?->title ?? 'Direct Repost' }}</p>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 h-12 w-12 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Track URN</h4>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white break-all">
                                        {{ $requests->track_urn }}</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="mb-6">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Status</h4>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $requests->status == \App\Models\RepostRequest::STATUS_APPROVED
                                        ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'
                                        : ($requests->status == \App\Models\RepostRequest::STATUS_PENDING
                                            ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400'
                                            : ($requests->status == \App\Models\RepostRequest::STATUS_DECLINE
                                                ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'
                                                : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300')) }}">
                                    {{ $requests->status_label }}
                                </span>
                            </div>

                            <div class="mb-6">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Credits Spent</h4>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ $requests->credits_spent }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Target User URN
                                </h4>
                                <p class="text-md font-medium text-gray-900 dark:text-white break-all">
                                    {{ $requests->target_user_urn ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Request Timeline</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex">
                            <div class="flex-shrink-0 flex flex-col items-center">
                                <div
                                    class="h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="h-12 w-0.5 bg-gray-200 dark:bg-gray-700 mt-1"></div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">Requested</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $requests->requested_at ?? 'N/A' }}
                                </p>
                            </div>
                        </div>

                        @if ($requests->reposted_at)
                            <div class="flex">
                                <div class="flex-shrink-0 flex flex-col items-center">
                                    <div
                                        class="h-8 w-8 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 text-green-600 dark:text-green-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div class="h-12 w-0.5 bg-gray-200 dark:bg-gray-700 mt-1"></div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">Reposted</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($requests->reposted_at)->format('M j, Y g:i A') }}</p>
                                </div>
                            </div>
                        @endif

                        @if ($requests->completed_at)
                            <div class="flex">
                                <div class="flex-shrink-0 flex flex-col items-center">
                                    <div
                                        class="h-8 w-8 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 text-purple-600 dark:text-purple-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="h-12 w-0.5 bg-gray-200 dark:bg-gray-700 mt-1"></div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">Completed</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($requests->completed_at)->format('M j, Y g:i A') }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div
                                    class="h-8 w-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 text-gray-500 dark:text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">Created</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $requests->created_at ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Request Status</h3>
                </div>
                <div class="p-6">
                    <div class="text-center mb-4">
                        <div
                            class="inline-flex items-center justify-center h-16 w-16 rounded-full {{ $requests->status == \App\Models\RepostRequest::STATUS_APPROVED
                                ? 'bg-green-100 dark:bg-green-900/30'
                                : ($requests->status == \App\Models\RepostRequest::STATUS_PENDING
                                    ? 'bg-yellow-100 dark:bg-yellow-900/30'
                                    : ($requests->status == \App\Models\RepostRequest::STATUS_DECLINE
                                        ? 'bg-red-100 dark:bg-red-900/30'
                                        : 'bg-gray-100 dark:bg-gray-700')) }}">
                            @if ($requests->status == \App\Models\RepostRequest::STATUS_APPROVED)
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-8 w-8 text-green-600 dark:text-green-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            @elseif($requests->status == \App\Models\RepostRequest::STATUS_PENDING)
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-8 w-8 text-yellow-600 dark:text-yellow-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @elseif($requests->status == \App\Models\RepostRequest::STATUS_DECLINE)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-600 dark:text-red-400"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-8 w-8 text-gray-500 dark:text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            @endif
                        </div>
                    </div>

                    <h4 class="text-lg font-semibold text-center text-gray-900 dark:text-white mb-2">
                        {{ $requests->status_label }}</h4>

                    @if ($requests->rejection_reason)
                        <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rejection Reason</h5>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $requests->rejection_reason }}</p>
                        </div>
                    @endif

                    {{-- @if ($requests->status == \App\Models\RepostRequest::STATUS_PENDING)
                        <div class="mt-6 grid grid-cols-2 gap-3">
                            <button
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                                Approve
                            </button>
                            <button
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                                Decline
                            </button>
                        </div>
                    @endif --}}
                </div>
            </div>

            <!-- Additional Information Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Additional Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    {{-- <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Sort Order</h4>
                        <p class="text-sm text-gray-900 dark:text-white">{{ $requests->sort_order ?? 'N/A' }}</p>
                    </div> --}}

                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Expired At</h4>
                        <p class="text-sm text-gray-900 dark:text-white">
                            {{ $requests->expired_at ?? 'N/A'}}
                        </p>
                    </div>

                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</h4>
                        <p class="text-sm text-gray-900 dark:text-white">
                            {{ $requests->updated_at ?? 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin::layout>
