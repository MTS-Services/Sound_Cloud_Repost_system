<x-admin::layout>
    <x-slot name="title">{{ __('User List') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('User List') }}</x-slot>
    <x-slot name="page_slug">user</x-slot>
    <section>
        {{-- Add Credit Modal Start --}}
        <!-- You can open the modal using ID.showModal() method -->
        {{-- <button class="btn" onclick="add_credit_modal.showModal()">open modal</button> --}}
        <dialog id="add_credit_modal"
            class="modal modal-backdrop fixed inset-0 bg-black/60 backdrop-blur-sm animate-fade-in flex items-center justify-center min-h-screen p-4">
            <div
                class="modal-boxglass-card relative w-full max-w-2xl mx-auto rounded-2xl shadow-2xl animate-slide-up bg-white/90 dark:bg-gray-800/90 backdrop-blur-lg border border-white/20 dark:border-gray-700/30">
                <!-- Header -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                            <i data-lucide="layout-list" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div>
                            <h3 id="modal-title" class="text-xl font-bold text-gray-900 dark:text-white">
                                {{ __('Add Credit') }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Add credit to user') }}
                            </p>
                        </div>
                    </div>

                    <form method="dialog">
                        <button
                            class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                            <i data-lucide="x" class="w-6 h-6"></i>
                        </button>
                    </form>
                </div>

                <!-- Content -->
                <div class="modal-action p-6">
                    <form action="" method="POST" id="add_credit_form" class="space-y-4 w-full">
                        @csrf
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text">{{ __('Credit') }}</span>
                            </label>
                            <input type="number" name="credit" id="credit"
                                class="input input-bordered text-gray-800 dark:text-gray-200 w-full" placeholder="{{ __('Enter credit') }}"
                                required>
                        </div>
                        {{-- <div class="flex items-center justify-end w-full mt-4">
                            <button type="submit" class="btn btn-primary">{{ __('Add Credit') }}</button>
                        </div> --}}
                        <div class="flex justify-end mt-5">
                            <x-button type="accent" :button="true" icon="wallet">{{ __('Add Credit') }}</x-button>
                        </div>
                    </form>
                </div>
            </div>

        </dialog>
        {{-- Add Credit Modal End --}}

        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('User List') }}</h2>
                <div class="flex items-center gap-2">
                    <x-button href="{{ route('um.user.trash') }}" icon="trash-2" type='secondary'
                        permission="admin-trash">
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
                        <th>{{ __('Soundcloud ID') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Last Synced At') }}</th>
                        <th>{{ __('Created By') }}</th>
                        <th>{{ __('Created Date') }}</th>
                        <th width="10%">{{ __('Action') }}</th>
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
                    //name and data, orderable, searchable
                    ['name', true, true],
                    ['soundcloud_id', true, true],
                    ['status', true, true],
                    ['last_synced_at', true, true],
                    ['creater_id', true, true],
                    ['created_at', true, true],
                    ['action', false, false],
                ];
                const details = {
                    table_columns: table_columns,
                    main_class: '.datatable',
                    displayLength: 10,
                    main_route: "{{ route('um.user.index') }}",
                    order_route: "{{ route('update.sort.order') }}",
                    export_columns: [0, 1, 2, 3, 4, 5, 6],
                    model: 'User',
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
        </script>


        <script>
            document.addEventListener('DOMContentLoaded', () => {

                $(document).on('click', '.add-credit', function() {
                    const user_urn = $(this).data('id');
                    const route = "{{ route('um.user.add-credit', ':urn') }}";
                    const url = route.replace(':urn', user_urn);
                    const form = document.getElementById('add_credit_form');
                    form.action = url;
                    const modal = document.getElementById('add_credit_modal');
                    modal.showModal();
                });
            });
        </script>
    @endpush
</x-admin::layout>
