<x-admin::layout>
    <x-slot name="title">{{ __('Banned User List') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Banned User List') }}</x-slot>
    <x-slot name="page_slug">banned-users</x-slot>
    <section>

        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Banned User List') }}</h2>
                <div class="flex items-center gap-2">
                    <x-button href="{{ route('um.user.trash') }}" icon="trash-2" type='secondary' permission="user-trash">
                        {{ __('Trash') }}
                    </x-button>
                </div>
            </div>
        </div>
        <div class="glass-card rounded-2xl p-6">
            <table class="table datatable table-zebra">
                <thead>
                    <tr>
                        <th width="5%">{{ __('SL') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Profile Link') }}</th>
                        <th>{{ __('Banned Status') }}</th>
                        <th>{{ __('Last Synced At') }}</th>
                        <th>{{ __('Banned By') }}</th>
                        <th>{{ __('Banned Date') }}</th>
                        <th width="10%">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </section>

    @push('js')
        <script src="{{ asset('assets/js/details-modal.js') }}"></script>
        <script src="{{ asset('assets/js/datatable.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                let table_columns = [
                    //name and data, orderable, searchable
                    ['name', true, true],
                    ['profile_link', true, true],
                    ['banned_label', true, true],
                    ['last_synced_at', true, true],
                    ['banned_by', true, true],
                    ['banned_at_formatted', true, true],
                    ['action', false, false],
                ];
                const details = {
                    table_columns: table_columns,
                    main_class: '.datatable',
                    displayLength: 10,
                    main_route: "{{ route('um.user.banned-users') }}",
                    order_route: "{{ route('update.sort.order') }}",
                    export_columns: [0, 1, 2, 3, 4, 5, 6],
                    model: 'User',
                };
                // initializeDataTable(details);

                initializeDataTable(details);
            })
        </script>

        {{-- Details Modal
        <script>
            document.addEventListener('DOMContentLoaded', () => {

                $(document).on('click', '.view', function() {
                    const id = $(this).data('id');
                    const route = "{{ route('um.user.show', ':id') }}";

                    const details = [{
                            label: '{{ __('Name') }}',
                            key: 'name',
                            icon: 'user'
                        },
                        {
                            label: '{{ __('Nickname') }}',
                            key: 'nickname',
                            icon: 'user-circle'
                        },
                        {
                            label: '{{ __('SoundCloud ID') }}',
                            key: 'soundcloud_id',
                            icon: 'fingerprint'
                        },
                        {
                            label: '{{ __('Status') }}',
                            key: 'status_label',
                            label_color: 'status_color',
                            type: 'badge'
                        },
                        {
                            label: '{{ __('Last Synced At') }}',
                            key: 'last_synced_at',
                            icon: 'clock'
                        },
                        {
                            label: '{{ __('Image') }}',
                            key: 'modified_image',
                            type: 'image'
                        },

                        {
                            label: '{{ __('First Name') }}',
                            key: 'userInfo?.first_name',
                            icon: 'user'
                        },
                        {
                            label: '{{ __('Last Name') }}',
                            key: 'userInfo?.last_name',
                            icon: 'user'
                        },
                        {
                            label: '{{ __('Full Name') }}',
                            key: 'userInfo?.full_name',
                            icon: 'user'
                        },
                        {
                            label: '{{ __('Username') }}',
                            key: 'userInfo?.username',
                            icon: 'at-sign'
                        },
                        {
                            label: '{{ __('Description') }}',
                            key: 'userInfo?.description',
                            icon: 'align-left'
                        },

                        {
                            label: '{{ __('Country') }}',
                            key: 'userInfo?.country',
                            icon: 'globe'
                        },
                        {
                            label: '{{ __('City') }}',
                            key: 'userInfo?.city',
                            icon: 'map-pin'
                        },
                        {
                            label: '{{ __('Plan') }}',
                            key: 'userInfo?.plan',
                            icon: 'award'
                        },
                        {
                            label: '{{ __('MySpace Name') }}',
                            key: 'userInfo?.myspace_name',
                            icon: 'music'
                        },
                        {
                            label: '{{ __('Discogs Name') }}',
                            key: 'userInfo?.discogs_name',
                            icon: 'disc'
                        },

                        {
                            label: '{{ __('Website Title') }}',
                            key: 'userInfo?.website_title',
                            icon: 'text-align-left'
                        },
                        {
                            label: '{{ __('Website') }}',
                            key: 'userInfo?.website',
                            icon: 'link'
                        },
                        {
                            label: '{{ __('Local') }}',
                            key: 'userInfo?.local',
                            icon: 'map-pin'
                        },

                        {
                            label: '{{ __('SoundCloud URN') }}',
                            key: 'userInfo?.soundcloud_urn',
                            icon: 'link'
                        },
                        {
                            label: '{{ __('SoundCloud Kind') }}',
                            key: 'userInfo?.soundcloud_kind',
                            icon: 'tag'
                        },
                        {
                            label: '{{ __('Permalink URL') }}',
                            key: 'userInfo?.soundcloud_permalink_url',
                            icon: 'globe'
                        },
                        {
                            label: '{{ __('Permalink') }}',
                            key: 'userInfo?.soundcloud_permalink',
                            icon: 'link'
                        },
                        {
                            label: '{{ __('SoundCloud URI') }}',
                            key: 'userInfo?.soundcloud_uri',
                            icon: 'link-2'
                        },

                        {
                            label: '{{ __('Created At') }}',
                            key: 'userInfo?.soundcloud_created_at',
                            icon: 'calendar'
                        },
                        {
                            label: '{{ __('Last Modified') }}',
                            key: 'userInfo?.soundcloud_last_modified',
                            icon: 'clock'
                        },

                        {
                            label: '{{ __('Track Count') }}',
                            key: 'userInfo?.track_count',
                            icon: 'music'
                        },
                        {
                            label: '{{ __('Favorites Count') }}',
                            key: 'userInfo?.public_favorites_count',
                            icon: 'star'
                        },
                        {
                            label: '{{ __('Reposts Count') }}',
                            key: 'userInfo?.reposts_count',
                            icon: 'refresh-ccw'
                        },
                        {
                            label: '{{ __('Followers Count') }}',
                            key: 'userInfo?.followers_count',
                            icon: 'users'
                        },
                        {
                            label: '{{ __('Following Count') }}',
                            key: 'userInfo?.following_count',
                            icon: 'user-plus'
                        },

                        {
                            label: '{{ __('Comments Count') }}',
                            key: 'userInfo?.comments_count',
                            icon: 'message-circle'
                        },
                        {
                            label: '{{ __('Like Count') }}',
                            key: 'userInfo?.like_count',
                            icon: 'thumbs-up'
                        },
                        {
                            label: '{{ __('Playlist Count') }}',
                            key: 'userInfo?.playlist_count',
                            icon: 'list-music'
                        },
                        {
                            label: '{{ __('Private Playlist Count') }}',
                            key: 'userInfo?.private_playlist_count',
                            icon: 'lock'
                        },
                        {
                            label: '{{ __('Private Tracks Count') }}',
                            key: 'userInfo?.private_tracks_count',
                            icon: 'lock'
                        },

                        {
                            label: '{{ __('Email Confirmed') }}',
                            key: 'userInfo?.primary_email_confirmed',
                            type: 'boolean'
                        },
                        {
                            label: '{{ __('Upload Seconds Left') }}',
                            key: 'userInfo?.upload_seconds_left',
                            icon: 'clock'
                        },
                    ];

                    showDetailsModal(route, id, '{{ __('User Details') }}', details);
                });
            });
        </script> --}}
    @endpush
</x-admin::layout>
