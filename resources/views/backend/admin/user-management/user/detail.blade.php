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
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">First Name</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->first_name }}
                    </p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Last Name</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->last_name }}
                    </p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">{{ __('Full Name') }}</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->full_name }}
                    </p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Username </h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->username }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Soundcloud ID</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->soundcloud_id }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Soundcloud URN</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->soundcloud_urn }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Soundcloud ID</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->soundcloud_id }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Soundcloud Kind</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->soundcloud_kind }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Soundcloud Permalink URL</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->soundcloud_permalink_url }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Soundcloud Permalink</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->soundcloud_permalink }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Soundcloud URI</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->soundcloud_uri }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Soundcloud Created At</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->soundcloud_created_at }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Soundcloud Last Modified</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->soundcloud_last_modified }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Description</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->description }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Country</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->country }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">City</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->city }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">track_count</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->track_count }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">public_favorites_count</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->public_favorites_count }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">reposts_count</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->reposts_count }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">followers_count</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->followers_count }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Plan</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->plan }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Myspace Name</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->myspace_name }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Discogs Name</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->discogs_name }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Website Title</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->website_title }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Website</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->website }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Online</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->online?? 'N/A' }}</p>
                </div>

                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Comments Count</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->comments_count }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Like Count</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->like_count }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Playlist Count</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->playlist_count }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Private Playlist Count</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->private_playlist_count }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Private Tracks Count</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->private_tracks_count }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Primary Email Confirmed</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->primary_email_confirmed?? 'N/A' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Local</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->local }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Upload Seconds Left</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->upload_seconds_left }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Created At</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->created_at }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Updated At</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->updated_at }}</p>
                </div>
               



            </div>


        </div>

    </div>

</x-admin::layout>
