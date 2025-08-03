<x-admin::layout>
    <x-slot name="title">{{ __('Track Detail') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Track Detail') }}</x-slot>
    <x-slot name="page_slug"> Detail</x-slot>


    <div class="glass-card rounded-2xl p-6 mb-6">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Track Detail List') }}
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
                <div class="flex-1 text-left">
                    <h2 class="text-3xl font-bold mb-2 text-gray-900 dark:text-white">
                        Name: {{ $tracklists->user?->name ?? '' }}
                    </h2>
                </div>
            </div>

            <!-- Campaign Stats -->
            <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Kids</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->kind ?? 'N/A' }}
                    </p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Soundcloud Track Id</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->soundcloud_track_id ?? 'N/A' }}
                    </p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">User Urn</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->user?->name }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Commentable</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->commentable }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Duration</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->duration ?? 'N/A' }}</p>
                </div>

                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Comment Count</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->comment_count }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Sharing</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->sharing }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Tag List</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->tag_list ?? 'N/A' }}</p>
                </div>

                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Streamable</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->streamable }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Embeddable By</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->embeddable_by }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Purchase Url</h4>
                    <a href="{{ $tracklists->purchase_url }}"
                        class=" hover:underline text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->purchase_url }}</a>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Genre</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->genre }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Title</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->title }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Description</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->description }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Label Name</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->label_name }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Release</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->release }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Key Signature</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->key_signature }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">ISRC</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->isrc }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">BPM</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->bpm }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Release Year</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->release_year }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Release Month</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->release_month ?? 'N/A' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">License</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->license }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Uri</h4>
                    <a href="{{ $tracklists->uri }}"
                        class="hover:underline text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->uri }}</a>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Permalink Url</h4>
                    <a href="{{ $tracklists->permalink_url }}"
                        class=" hover:underline text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->permalink_url }}</a>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Artwork Url</h4>
                    <a href="{{ $tracklists->artwork_url }}"
                        class=" hover:underline text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->artwork_url ?? 'N/A' }}</a>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Stream Url</h4>
                    <a href="{{ $tracklists->stream_url }}"
                        class=" hover:underline text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->stream_url }}</a>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Download Url</h4>
                    <a href="{{ $tracklists->download_url }}"
                        class=" hover:underline text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->download_url }}</a>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Waveform Url</h4>
                    <a href="{{ $tracklists->waveform_url }}"
                        class=" hover:underline text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->waveform_url }}</a>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Available Country Codes</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->available_country_codes }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Secret Uri</h4>
                    <a href="{{ $tracklists->secret_uri }}"
                        class=" hover:underline text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->secret_uri }}</a>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">User Favorite</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->user_favorite ?? 'N/A' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">User Playback Count</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->user_playback_count }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Playback Count</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->playback_count ?? 'N/A' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Download Count</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->download_count }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Favoritings Count</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->favoritings_count ?? 'N/A' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Reposts Count</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->reposts_count }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Downloadable</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->downloadable ?? 'N/A' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Access</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->access }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Policy</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->policy }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Monetization Model</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->monetization_model }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Metadata Artist</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->metadata_artist }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Created At Soundcloud</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->created_at_soundcloud }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Type</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->type }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Author Username</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->author_username }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Author Soundcloud Urn</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->author_soundcloud_urn }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">author_soundcloud_kind</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->author_soundcloud_kind }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Author Soundcloud Permalink Url</h4>
                    <a href="{{ $tracklists->author_soundcloud_permalink_url }}"
                        class=" hover:underline text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->author_soundcloud_permalink_url }}</a>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Author Soundcloud Permalink</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->author_soundcloud_permalink }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Last Sync At</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->last_sync_at ?? 'N/A' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Created At</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->created_at }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Updated At</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $tracklists->updated_at }}</p>
                </div>


            </div>


        </div>

    </div>

</x-admin::layout>
