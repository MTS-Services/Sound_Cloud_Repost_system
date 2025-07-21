<x-admin::layout>
    <x-slot name="title">{{ __('User Playlist List') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('User Playlist List') }}</x-slot>
    <x-slot name="page_slug">user-playlist</x-slot>
    <section>

        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('User Playlist List') }}</h2>
                <div class="flex items-center gap-2">
                    <x-button href="{{ route('um.playlist.trash') }}" icon="trash-2" type='secondary' permission="admin-trash">
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
                    main_route: "{{ route('um.playlist.index') }}",
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
                    const route = "{{ route('um.playlist.show', ':id') }}";

                    const details = [{
                        
                    },
                ]
                    showDetailsModal(route, id, '{{ __('User Details') }}', details);
                });
            });
        </script>
    @endpush
</x-admin::layout>
