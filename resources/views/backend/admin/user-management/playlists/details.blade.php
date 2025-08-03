<x-admin::layout>
    <x-slot name="title">{{ __('Playlist Detail') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Playlist Detail') }}</x-slot>
    <x-slot name="page_slug"> Detail</x-slot>


    <div class="glass-card rounded-2xl p-6 mb-6">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Playlist Detail List') }}
            </h2>
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
            <div class="flex flex-col md:flex-row gap-6">
                <div class="flex-1">
                    <h2 class="text-3xl font-bold mb-2 text-gray-900 dark:text-white">Name:
                        {{ $playlists->user?->name ?? '' }}</h2>
                </div>
            </div>
            <!-- Campaign Stats -->
            <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Duration</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $playlists->duration ?? 'N/A' }}
                    </p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Label ID</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $playlists->label_id ?? 'N/A'}}
                    </p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Genre</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $playlists->genre }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Release Day</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $playlists->release_day }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Permalink</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $playlists->permalink ?? 'N/A' }}</p>
                </div>
             
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Premalink URL</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $playlists->permalink_url}}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Release Month</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $playlists->release_month}}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Release Year</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $playlists->release_year ?? 'N/A' }}</p>
                </div>
                
                 <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Description</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $playlists->description}}</p>
                </div>
                 <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">URI</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $playlists->uri}}</p>
                </div>
                 <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Label Nmae</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $playlists->label_name}}</p>
                </div>
                 <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Label</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $playlists->label}}</p>
                </div>
                 <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Tag List</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $playlists->tag_list}}</p>
                </div>
                 <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">track_count</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $playlists->track_count}}</p>
                </div>
                 <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Last Modified</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $playlists->last_modified}}</p>
                </div>
                 <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">License</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $playlists->license}}</p>
                </div>
                 <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Playlist Type</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $playlists->playlist_type}}</p>
                </div>
                 <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Type</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $playlists->type}}</p>
                </div>
                 <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Soundcloud ID</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $playlists->soundcloud_id}}</p>
                </div>
                 <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Soundcloud URN</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $playlists->soundcloud_urn}}</p>
                </div>
                 <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Downloadable</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $playlists->downloadable ?? 'N/A'}}</p>
                </div>
                 <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">likes_count</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $playlists->likes_count}}</p>
                </div>
                 <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">sharing</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $playlists->sharing}}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Soundcloud Created At</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $playlists->soundcloud_created_at}}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Release</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $playlists->release}}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Tags</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $playlists->tags}}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Soundcloud Kind</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $playlists->soundcloud_kind}}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Title</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $playlists->title}}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Purchase Title</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $playlists->purchase_title}}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Ean</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $playlists->ean}}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Streamable</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $playlists->streamable}}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Embeddable By</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $playlists->embeddable_by}}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Artwork Url</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $playlists->artwork_url ?? 'N/A'}}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Purchase Url</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $playlists->purchase_url}}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Tracks Uri</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $playlists->tracks_uri}}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Secret Token</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $playlists->secret_token}}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Secret Uri</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $playlists->secret_uri}}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Created At</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $playlists->created_at}}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Updated At</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $playlists->updated_at}}</p>
                </div>

            </div>


        </div>

    </div>

</x-admin::layout>
