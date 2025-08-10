<x-admin::layout>
    <x-slot name="title">{{ __('Campaign Details') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Campaign Details') }}</x-slot>
    <x-slot name="page_slug">campaign</x-slot>


    <div class="glass-card rounded-2xl p-6 mb-6">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Details List') }}</h2>
            <div class="flex items-center gap-2">

                <x-button href="{{ route('cm.campaign.index') }}" permission="campaign-view">
                    {{ __('Back') }}
                </x-button>
            </div>
        </div>
    </div>
    <div
        class="w-full max-w-8xl mx-auto rounded-2xl shadow-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden">


        <div class="p-6 text-center">


            <div class="flex flex-col md:flex-row gap-6">
                <div class="w-full md:w-1/3 max-w-56 rounded-xl shadow-lg overflow-hidden">
                    <img class="w-full h-full object-cover"
                        src="{{ soundcloud_image($campaigns->music?->artwork_url) }}">
                </div>

                <div class="flex-1">
                    <h2 class="text-3xl font-bold mb-2 text-gray-900 dark:text-white">
                        {{ $campaigns->title ?? $campaign->music?->title }}</h2>
                    <p class="text-orange-500 mb-2">by {{ $campaigns->user?->name ?? 'Unknown' }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Genre: <span
                            class="text-black dark:text-white">{{ $campaigns->music?->genre ?? 'Unknown' }}</span>
                    </p>
                    <p class="text-gray-700 dark:text-gray-300 text-sm mb-4">
                        {{ $campaigns->description ?? 'No description provided' }}
                    </p>

                    <div class="bg-gray-200 dark:bg-gray-800 p-4 rounded-lg">
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-2">Repost Progress:</p>
                        <div class="w-full h-3 bg-gray-300 dark:bg-gray-700 rounded-full">
                            <div class="h-3 bg-orange-500 rounded-full"
                                style="width: {{ ($campaigns->completed_reposts / $campaigns->target_reposts) * 100 }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Campaign Stats -->
            <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                {{-- <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Target Reposts</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $campaigns->target_reposts }}
                    </p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Completed Reposts</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $campaigns->completed_reposts }}
                    </p>
                </div> --}}
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Playback Count</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $campaigns->cost_per_repost }}
                    </p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Budget</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $campaigns->budget_credits }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Credits Spent</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $campaigns->credits_spent }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Cost Per Repost</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $campaigns->cost_per_repost }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Max Followers</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $campaigns->max_followers }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Min Followers</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $campaigns->min_followers }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Refund Credits</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $campaigns->refund_credits }}</p>
                </div>
            </div>

            <!-- Campaign Info -->
            <div class="mt-10 bg-gray-100 dark:bg-slate-800 p-6 rounded-xl shadow">
                <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Campaign Settings</h3>
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 text-sm text-gray-700 dark:text-gray-300">
                    <p>
                        <span class="font-medium text-black dark:text-white">Featured:</span>
                        {{ $campaigns->feature_label }}
                    </p>
                    <p>
                        <span class="font-medium text-black dark:text-white">Start Date:</span>
                        {{ $campaigns->start_date }}
                    </p>
                    <p>
                        <span class="font-medium text-black dark:text-white">End Date:</span>
                        {{ $campaigns->end_date }}
                    </p>

                    <!-- Improved Status Row -->
                    <div
                        class="col-span-1 flex items-center justify-between p-3 bg-gray-200 dark:bg-slate-700 rounded-lg mt-2 gap-5">
                        <!-- Status label and value inline -->
                        <p class="text-sm flex items-center gap-2">
                            <span class="font-medium text-black dark:text-white">Status:</span>
                            <span class="text-green-400 font-semibold">{{ $campaigns->status_label }}</span>
                        </p>
                        <!-- Buttons group (right side) -->
                        @if ($campaigns->status == 1 || $campaigns->status == 2)
                            <button
                                class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                                {{ $campaigns->status_btn_label }}
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

</x-admin::layout>
