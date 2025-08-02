<x-admin::layout>
    <x-slot name="title">{{ __(' User Detail') }}</x-slot>
    <x-slot name="breadcrumb">{{ __(' User Detail') }}</x-slot>
    <x-slot name="page_slug">User Detail</x-slot>


    <div class="glass-card rounded-2xl p-6 mb-6">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('User Detail List') }}</h2>
            <div class="flex items-center gap-2">

                <x-button href="{{ route('um.user.index') }}" permission="credit-create">
                    {{ __('Back') }}
                </x-button>
            </div>
        </div>
    </div>
    <div
        class="w-full max-w-8xl mx-auto rounded-2xl shadow-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden">


        <div class="p-6 text-center">


            <div class="flex flex-col md:flex-row gap-6 items-start">
                <!-- Image -->
                <div class="w-full md:w-1/3 max-w-56 rounded-xl shadow-lg overflow-hidden">
                    <img class="w-full h-full object-cover"
                        src="{{ asset($user->avatar) ?: asset('images/default-profile.png') }}" alt="{{ $user->name }}">
                </div>

                <!-- User Info -->
                <div class="flex-1 text-left">
                    <h2 class="text-3xl font-bold mb-2 text-gray-900 dark:text-white">
                        {{ $user->name }}
                    </h2>

                    <p class="text-orange-500 mb-2">by {{ $user->email }}</p>

                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Genre:
                        <span class="text-black dark:text-white">
                            {{ $user->gender ?? 'Unknown' }}
                        </span>
                    </p>

                    <p class="text-sm flex items-start gap-2 mb-2">
                        <span class="font-medium text-black dark:text-white">Status:</span>
                        <span class="text-green-400 font-semibold">{{ $user->status_label }}</span>
                    </p>

                </div>
            </div>


            <!-- Campaign Stats -->
            <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">User Name</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $user->name }}
                    </p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">User Nickname</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $user->nickname }}
                    </p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">{{ __('Email') }}</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $user->email }}
                    </p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Token </h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $user->token }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Refresh Token</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $user->refresh_token }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Expires In</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $user->expires_in }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Soundcloud ID</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $user->soundcloud_id }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">URN</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $user->urn }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Last Sync</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $user->last_synced_at }}</p>
                </div>

            </div>


        </div>

    </div>

</x-admin::layout>
