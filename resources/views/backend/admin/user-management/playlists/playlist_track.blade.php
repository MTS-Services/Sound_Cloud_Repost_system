<x-admin::layout>
    <x-slot name="title">{{ __('Playlist List Track') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Playlist List Track') }}</x-slot>
    <x-slot name="page_slug">user</x-slot>
    <section>

        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Playlist List Track') }}</h2>
                <div class="flex items-center gap-2">
                    <x-button href="{{ route('um.user.index') }}"  type='primary'
                        permission="admin-trash">
                        {{ __('Back') }}
                    </x-button>
                </div>
            </div>
        </div>
        <div class="glass-card rounded-2xl p-6">
            <table class="table datatable table-zebra">
                <thead>
                    <tr>
                        <th>{{ __('SL') }}</th>
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Tag List') }}</th>
                        <th>{{ __('User Urn') }}</th>
                        <th>{{ __('Release Year') }}</th>
                      
                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </section>

    {{-- Details Modal --}}
    {{-- <x-admin.details-modal /> --}}

    @push('js')
        <script src="{{ asset('assets/js/details-modal.js') }}"></script>
        <script src="{{ asset('assets/js/datatable.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                let table_columns = [
                    ['title', true, true],
                    ['tag_list', true, true],
                    ['user_urn', true, true],
                    ['release_month', true, true],
                    ['action', false, false],
                ];
                const details = {
                    table_columns: table_columns,
                    main_class: '.datatable',
                    displayLength: 10,
                    main_route: "{{ route('um.user.playlist.track-list', $palaylistUrn) }}",
                    order_route: "{{ route('update.sort.order') }}",
                    export_columns: [0, 1,2,3,4],
                    model: 'Track',
                };
                // initializeDataTable(details);

                initializeDataTable(details);
            })
        </script>

        {{-- Details Modal --}}
        <script>
            document.addEventListener('DOMContentLoaded', () => {

                $(document).on('click', '.view', function() {
                    const id = $(this).data('id');
                    const route = "{{ route('um.user.playlist.show', ':id') }}";

                    const details = [{
                            label: '{{ __('User Urn') }}',
                            key: 'user_urn'
                        },
                        {
                            label: '{{ __('Kind') }}',
                            key: 'kind'
                        },
                        {
                            label: '{{ __('SoundCloud Track ID') }}',
                            key: 'soundcloud_track_id'
                        },
                        {
                            label: '{{ __('URN') }}',
                            key: 'urn'
                        },
                        {
                            label: '{{ __('Duration') }}',
                            key: 'duration'
                        },
                        {
                            label: '{{ __('Commentable') }}',
                            key: 'commentable'
                        },
                        {
                            label: '{{ __('Comment Count') }}',
                            key: 'comment_count'
                        },
                        {
                            label: '{{ __('Sharing') }}',
                            key: 'sharing'
                        },
                        {
                            label: '{{ __('Tag List') }}',
                            key: 'tag_list'
                        }, // Note: Original key has a typo
                        {
                            label: '{{ __('Streamable') }}',
                            key: 'streamable'
                        },
                        {
                            label: '{{ __('Embeddable By') }}',
                            key: 'embeddable_by'
                        },
                        {
                            label: '{{ __('Purchase URL') }}',
                            key: 'purchase_url'
                        },
                        {
                            label: '{{ __('Purchase Title') }}',
                            key: 'purchase_title'
                        },
                        {
                            label: '{{ __('Genre') }}',
                            key: 'genre'
                        },
                        {
                            label: '{{ __('Title') }}',
                            key: 'title'
                        },
                        {
                            label: '{{ __('Description') }}',
                            key: 'description'
                        },
                        {
                            label: '{{ __('Label Name') }}',
                            key: 'label_name'
                        },
                        {
                            label: '{{ __('Release') }}',
                            key: 'release'
                        },
                        {
                            label: '{{ __('Key Signature') }}',
                            key: 'key_signature'
                        },
                        {
                            label: '{{ __('ISRC') }}',
                            key: 'isrc'
                        },
                        {
                            label: '{{ __('BPM') }}',
                            key: 'bpm'
                        },
                        {
                            label: '{{ __('Release Year') }}',
                            key: 'release_year'
                        },
                        {
                            label: '{{ __('Release Month') }}',
                            key: 'release_month'
                        },
                        {
                            label: '{{ __('License') }}',
                            key: 'license'
                        },
                        {
                            label: '{{ __('URI') }}',
                            key: 'uri'
                        },
                        {
                            label: '{{ __('Permalink URL') }}',
                            key: 'permalink_url'
                        },
                        {
                            label: '{{ __('Artwork URL') }}',
                            key: 'artwork_url'
                        },
                        {
                            label: '{{ __('Stream URL') }}',
                            key: 'stream_url'
                        },
                        {
                            label: '{{ __('Download URL') }}',
                            key: 'download_url'
                        },
                        {
                            label: '{{ __('Waveform URL') }}',
                            key: 'waveform_url'
                        },
                        {
                            label: '{{ __('Available Country Codes') }}',
                            key: 'available_country_codes'
                        },
                        {
                            label: '{{ __('Secret URI') }}',
                            key: 'secret_uri'
                        },
                        {
                            label: '{{ __('User Favorite') }}',
                            key: 'user_favorite'
                        },
                        {
                            label: '{{ __('User Playback Count') }}',
                            key: 'user_playback_count'
                        },
                        {
                            label: '{{ __('Playback Count') }}',
                            key: 'playback_count'
                        },
                        {
                            label: '{{ __('Download Count') }}',
                            key: 'download_count'
                        },
                        {
                            label: '{{ __('Favoritings Count') }}',
                            key: 'favoritings_count'
                        },
                        {
                            label: '{{ __('Reposts Count') }}',
                            key: 'reposts_count'
                        },
                        {
                            label: '{{ __('Downloadable') }}',
                            key: 'downloadable'
                        },
                        {
                            label: '{{ __('Access') }}',
                            key: 'access'
                        },
                        {
                            label: '{{ __('Policy') }}',
                            key: 'policy'
                        },
                        {
                            label: '{{ __('Monetization Model') }}',
                            key: 'monetization_model'
                        },
                        {
                            label: '{{ __('Metadata Artist') }}',
                            key: 'metadata_artist'
                        },
                        {
                            label: '{{ __('Created At SoundCloud') }}',
                            key: 'created_at_soundcloud'
                        },
                        {
                            label: '{{ __('Type') }}',
                            key: 'type'
                        },

                        // Author details
                        {
                            label: '{{ __('Author Username') }}',
                            key: 'author_username'
                        },
                        {
                            label: '{{ __('Author SoundCloud ID') }}',
                            key: 'author_soundcloud_id'
                        },
                        {
                            label: '{{ __('Author SoundCloud URN') }}',
                            key: 'author_soundcloud_urn'
                        },
                        {
                            label: '{{ __('Author SoundCloud Kind') }}',
                            key: 'author_soundcloud_kind'
                        },
                        {
                            label: '{{ __('Author SoundCloud Permalink URL') }}',
                            key: 'author_soundcloud_permalink_url'
                        },
                        {
                            label: '{{ __('Author SoundCloud Permalink') }}',
                            key: 'author_soundcloud_permalink'
                        },
                        {
                            label: '{{ __('Author SoundCloud URI') }}',
                            key: 'author_soundcloud_uri'
                        },
                    ];

                    showDetailsModal(route, id, '{{ __('User Details') }}', details);
                });
            });
        </script>
    @endpush
</x-admin::layout>
