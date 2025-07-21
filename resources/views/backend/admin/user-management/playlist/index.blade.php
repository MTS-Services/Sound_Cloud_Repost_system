<x-admin::layout>
    <x-slot name="title">{{ __('User Playlist List') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('User Playlist List') }}</x-slot>
    <x-slot name="page_slug">user_playlist</x-slot>

    <section>
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">
                    {{ __('User Playlist List') }}
                </h2>
                <div class="flex items-center gap-2">
                    <x-button href="{{ route('um.playlist.trash') }}" icon="trash-2" type='secondary'
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
                        <th>{{ __('SL') }}</th>
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Genre') }}</th>
                        <th>{{ __('Tracks') }}</th>
                        <th>{{ __('Duration') }}</th>
                        <th>{{ __('Likes') }}</th>
                        <th>{{ __('Created At') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody></tbody>
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
                let table_columns = [{
                    ['title', true, true],
                    ['genre', true, true],
                    ['track_count', true, true],
                    ['duration', true, false],
                    ['likes_count', true, false],
                    ['created_at', true, false],
                    ['action', false, false],
                }];

                const details = {
                    table_columns: table_columns,
                    main_class: '.datatable',
                    displayLength: 10,
                    main_route: "{{ route('um.playlist.index') }}",
                    order_route: "{{ route('update.sort.order') }}",
                    export_columns: [1, 2, 3, 4, 5, 6],
                    model: 'Playlist',
                };

                initializeDataTable(details);
            });
        </script>

        {{-- Details Modal --}}
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                $(document).on('click', '.view', function() {
                    const id = $(this).data('id');
                    const route = "{{ route('um.playlist.show', ':id') }}".replace(':id', id);

                    const details = [{
                            label: '{{ __('Title') }}',
                            key: 'title'
                        },
                        {
                            label: '{{ __('Genre') }}',
                            key: 'genre'
                        },
                        {
                            label: '{{ __('Track Count') }}',
                            key: 'track_count'
                        },
                        {
                            label: '{{ __('Duration') }}',
                            key: 'duration'
                        },
                        {
                            label: '{{ __('Likes') }}',
                            key: 'likes_count'
                        },
                        {
                            label: '{{ __('Created At') }}',
                            key: 'created_at'
                        },
                        {
                            label: '{{ __('Permalink URL') }}',
                            key: 'permalink_url'
                        },
                        {
                            label: '{{ __('Description') }}',
                            key: 'description'
                        },
                    ];

                    showDetailsModal(route, id, '{{ __('Playlist Details') }}', details);
                });
            });
        </script>
    @endpush
</x-admin::layout>
