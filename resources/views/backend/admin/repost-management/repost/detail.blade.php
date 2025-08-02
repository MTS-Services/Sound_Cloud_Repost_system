<x-admin::layout>
    <x-slot name="title">{{ __('Repost Detail') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Repost Detail') }}</x-slot>
    <x-slot name="page_slug">Detail</x-slot>


    <div class="glass-card rounded-2xl p-6 mb-6">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Repost Detail List') }}</h2>
            <div class="flex items-center gap-2">

                <x-button href="{{ route('rm.repost.index') }}" permission="credit-create">
                    {{ __('Back') }}
                </x-button>
            </div>
        </div>
    </div>
    <div
        class="w-full max-w-8xl mx-auto rounded-2xl shadow-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden">
        <div class="p-6 text-center">
            <div class="flex flex-col md:flex-row gap-6">
                <div class="flex-1">
                    <h2 class="text-3xl font-bold mb-2 text-gray-900 dark:text-white">Name:
                        {{ $reposts->reposter?->name ?? '' }}</h2>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">
                        Title: {{ $reposts->campaign?->title ?? '' }}
                    </p>


                </div>
            </div>
            <!-- Campaign Stats -->
            <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">SoundCloud</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $reposts->soundcloud_repost_id ?? '' }}
                    </p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Credits Earned</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $reposts->credits_earned }}
                    </p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Reposter URN</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $reposts->reposter_urn }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Reposted At</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $reposts->reposted_at }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Created At</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $reposts->created_at }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Updated At</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $reposts->updated_at }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Deleted At</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $reposts->deleted_at ?? 'N/A' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Updated By</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $reposts->updated_by ?? 'N/A' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Updated By</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $reposts->updated_by ?? 'N/A' }}</p>
                </div>

            </div>


        </div>

    </div>

</x-admin::layout>
