<x-admin::layout>
    <x-slot name="title">{{ __(' User Detail') }}</x-slot>
    <x-slot name="breadcrumb">{{ __(' User Detail') }}</x-slot>
    <x-slot name="page_slug">user</x-slot>


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


            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg p-6 md:p-8">
                <div class="flex flex-col md:flex-row gap-8 items-start">
                    <!-- Profile Image -->
                    <div class="w-full md:w-1/3 max-w-56 rounded-2xl overflow-hidden shadow-lg">
                        <img class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                            src="{{ auth_storage_url($user->avatar) }}" alt="{{ $user->name }}">
                    </div>

                    <!-- User Info -->
                    <div class="flex-1 text-left space-y-4">
                        <!-- Name -->
                        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">
                            {{ $user->name }}
                        </h2>

                        <!-- Email -->
                        <p class="text-sm text-orange-500 font-medium">
                            {{ $user->email }}
                        </p>

                        <!-- Genres -->
                        <div>
                            <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">Genres:</p>
                            <div class="flex flex-wrap gap-2">
                                @forelse ($user->genres as $index => $genre)
                                    @php
                                        $colors = [
                                            'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300',
                                            'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300',
                                            'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                                            'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                                            'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                                        ];
                                        $colorClass = $colors[$index % count($colors)];
                                    @endphp
                                    <span
                                        class="{{ $colorClass }} px-3 py-1.5 rounded-full text-xs font-medium shadow-sm">
                                        {{ $genre->genre }}
                                    </span>
                                @empty
                                    <span class="text-gray-500 dark:text-gray-400">Unknown</span>
                                @endforelse
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Status:</span>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold badge badge-soft {{ $user->status_color }}">
                                {{ $user?->status_label }}
                            </span>
                        </div>

                    </div>
                </div>
            </div>



            <!-- Campaign Stats -->
            <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">First Name</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->first_name ?? 'N/A' }}
                    </p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Last Name</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->last_name ?? 'N/A' }}
                    </p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">{{ __('Full Name') }}</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->full_name ?? 'N/A' }}
                    </p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Username </h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->username ?? 'N/A' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Soundcloud ID</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->soundcloud_id ?? 'N/A' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Soundcloud URN</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->soundcloud_urn ?? 'N/A' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Soundcloud ID</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->soundcloud_id ?? 'N/A' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Soundcloud Kind</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->soundcloud_kind ?? 'N/A' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Soundcloud Permalink URL</h4>
                    <a class="text-xl font-bold text-black dark:text-white hover:underline"
                        href="{{ $userinfo->soundcloud_permalink_url ?? '' }}">{{ $userinfo->soundcloud_permalink_url ?? 'N/A' }}
                    </a>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Soundcloud Permalink</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->soundcloud_permalink ?? 'N/A' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Soundcloud URI</h4>
                    <a href="{{ $userinfo->soundcloud_uri ?? '' }}"
                        class="hover:underline text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->soundcloud_uri ?? 'N/A' }}</a>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Soundcloud Created At</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->soundcloud_created_at ?? 'N/A' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Soundcloud Last Modified</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->soundcloud_last_modified ?? 'N/A' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Description</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->description ?? 'N/A' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Country</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->country ?? 'N/A' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">City</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->city ?? 'N/A' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">track_count</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->track_count ?? '0' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">public_favorites_count</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->public_favorites_count ?? '0' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">reposts_count</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->reposts_count ?? '0' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">followers_count</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->followers_count ?? '0' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Plan</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->plan ?? 'N/A' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Myspace Name</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->myspace_name ?? 'N/A' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Discogs Name</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->discogs_name ?? 'N/A' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Website Title</h4>
                    <a href="{{ $userinfo->website_title ?? '' }}"
                        class=" hover:underline text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->website_title ?? 'N/A' }}</a>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Website</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->website ?? 'N/A' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Online</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->online ?? 'N/A' }}</p>
                </div>

                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Comments Count</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->comments_count ?? '0' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Like Count</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->like_count ?? '0' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Playlist Count</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->playlist_count ?? '0' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Private Playlist Count</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->private_playlist_count ?? '0' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Private Tracks Count</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->private_tracks_count ?? 'N/A' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Primary Email Confirmed</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->primary_email_confirmed ?? 'N/A' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Local</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->local ?? 'N/A' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Upload Seconds Left</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->upload_seconds_left ?? 'N/A' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Created At</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->created_at_formatted ?? 'N/A' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Updated At</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $userinfo->updated_at_formatted ?? 'N/A' }}</p>
                </div>




            </div>


        </div>

    </div>

</x-admin::layout>
