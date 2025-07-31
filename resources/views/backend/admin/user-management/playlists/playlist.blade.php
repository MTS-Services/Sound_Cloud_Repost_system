<x-admin::layout>
    <x-slot name="title">{{ __('User Playlist List') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('User Playlist List') }}</x-slot>
    <x-slot name="page_slug">user</x-slot>

      <section>

        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('User Playlist List') }}</h2>
                <div class="flex items-center gap-2">

                    {{-- <x-button href="{{ route('um.playlist.trash') }}" icon="trash-2" type='secondary'
                        permission="admin-trash">
                        {{ __('Trash') }}
                    </x-button> --}}
                     <x-button href="{{ route('um.user.index') }}"  type='primary'
                        permission="admin-trash">
                        {{ __('back') }}
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
                        <th>{{ __('Soundcloud ID') }}</th>
                        <th>{{ __('Soundcloud Kind') }}</th>
                        <th>{{ __('Release Year') }}</th>
                        <th>{{ __('Playlist Type') }}</th>
                        <th>{{ __('Tracks') }}</th>
                        <th>{{ __('Duration') }}</th>
                        <th>{{ __('Likes') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </section>

    {{-- Details Modal --}}
    <x-admin.details-modal />

    @push('js')
        <script src="{{ asset('assets/js/details-modal.js') }}"></script>
        <script src="{{ asset('assets/js/datatable.js') }}"></script>
         
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                let table_columns = [
                    ['title', true, true],
                    ['tag_list', true, true],
                    ['user_urn', true, true],
                    ['soundcloud_id', true, true],
                    ['soundcloud_kind', true, true],
                    ['release_year', true, true],
                    ['playlist_type', true, true],
                    ['track_count', true, true],
                    ['duration', true, false],
                    ['likes_count', true, false],
                    ['action', false, false],
                ];
                const details = {
                    table_columns: table_columns,
                    main_class: '.datatable',
                    displayLength: 10,
                    main_route: "{{ route('um.user.playlist', Auth::user()->id) }}",
                    order_route: "{{ route('update.sort.order') }}",
                    export_columns: [0, 1, 2, 3, 4, 5, 6,7,8,9,10],
                    model: 'Playlist',
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
                    const route = "{{ route('um.user.playlist.show',':id') }}";

                    const details = [{
                            label: '{{ __('Title') }}',
                            key: 'title'
                        },
                        {
                            label: '{{ __('User URN') }}',
                            key: 'user_urn'
                        },
                        {
                            label: '{{ __('Duration') }}',
                            key: 'duration'
                        },
                        {
                            label: '{{ __('Label ID') }}',
                            key: 'label_id'
                        },
                        {
                            label: '{{ __('Genre') }}',
                            key: 'genre'
                        },
                        {
                            label: '{{ __('Release Day') }}',
                            key: 'release_day'
                        },
                        {
                            label: '{{ __('Permalink') }}',
                            key: 'permalink'
                        },
                        {
                            label: '{{ __('Permalink URL') }}',
                            key: 'permalink_url'
                        },
                        {
                            label: '{{ __('Release Month') }}',
                            key: 'release_month'
                        },
                        {
                            label: '{{ __('Release Year') }}',
                            key: 'release_year'
                        },
                        {
                            label: '{{ __('Description') }}',
                            key: 'description'
                        },
                        {
                            label: '{{ __('URI') }}',
                            key: 'uri'
                        },
                        {
                            label: '{{ __('Label Name') }}',
                            key: 'label_name'
                        },
                        {
                            label: '{{ __('Label') }}',
                            key: 'label'
                        },
                        {
                            label: '{{ __('Tag List') }}',
                            key: 'tag_list'
                        },
                        {
                            label: '{{ __('Track Count') }}',
                            key: 'track_count'
                        },
                        {
                            label: '{{ __('Last Modified') }}',
                            key: 'last_modified'
                        },
                        {
                            label: '{{ __('License') }}',
                            key: 'license'
                        },
                        {
                            label: '{{ __('Playlist Type') }}',
                            key: 'playlist_type'
                        },
                        {
                            label: '{{ __('Type') }}',
                            key: 'type'
                        },
                        {
                            label: '{{ __('SoundCloud ID') }}',
                            key: 'soundcloud_id'
                        },
                        {
                            label: '{{ __('SoundCloud URN') }}',
                            key: 'soundcloud_urn'
                        },
                        {
                            label: '{{ __('Downloadable') }}',
                            key: 'downloadable'
                        },
                        {
                            label: '{{ __('Likes Count') }}',
                            key: 'likes_count'
                        },
                        {
                            label: '{{ __('Sharing') }}',
                            key: 'sharing'
                        },
                        {
                            label: '{{ __('SoundCloud Created At') }}',
                            key: 'soundcloud_created_at'
                        },
                        {
                            label: '{{ __('Release') }}',
                            key: 'release'
                        },
                        {
                            label: '{{ __('Tags') }}',
                            key: 'tags'
                        },
                        {
                            label: '{{ __('SoundCloud Kind') }}',
                            key: 'soundcloud_kind'
                        },
                        {
                            label: '{{ __('Purchase Title') }}',
                            key: 'purchase_title'
                        },
                        {
                            label: '{{ __('EAN') }}',
                            key: 'ean'
                        },
                        {
                            label: '{{ __('Streamable') }}',
                            key: 'streamable'
                        },
                        {
                            label: '{{ __('Embeddable By') }}',
                            key: 'embeddable_by'
                        },
                        {
                            label: '{{ __('Artwork URL') }}',
                            key: 'artwork_url'
                        },
                        {
                            label: '{{ __('Purchase URL') }}',
                            key: 'purchase_url'
                        },
                        {
                            label: '{{ __('Tracks URI') }}',
                            key: 'tracks_uri'
                        },
                        {
                            label: '{{ __('Secret Token') }}',
                            key: 'secret_token'
                        },
                        {
                            label: '{{ __('Secret URI') }}',
                            key: 'secret_uri'
                        },
                        {
                            label: '{{ __('Created At') }}',
                            key: 'created_at'
                        },
                        {
                            label: '{{ __('Updated At') }}',
                            key: 'updated_at'
                        },
                    ];
                    showDetailsModal(route, id, '{{ __('Playlist Details') }}', details);
                });
            });
        </script>
    @endpush
    
</x-admin::layout>
