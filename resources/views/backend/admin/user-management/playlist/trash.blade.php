<x-admin::layout>
    <x-slot name="title">{{ __('Trashed Playlist List') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Trashed Playlist List') }}</x-slot>
    <x-slot name="page_slug">user_playlist</x-slot>

    <section>
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">
                    {{ __('Trashed Playlist List') }}
                </h2>
                <div class="flex items-center gap-2">
                    <x-button href="{{ route('um.playlist.index') }}" icon="arrow-left" type='secondary'>
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
                        <th>{{ __('Deleted At') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </section>

    @push('js')
        <script src="{{ asset('assets/js/datatable.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                let table_columns = [
                    { data: 'title', name: 'title', orderable: true, searchable: true },
                    { data: 'deleted_at', name: 'deleted_at', orderable: true, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ];

                const details = {
                    table_columns: table_columns,
                    main_class: '.datatable',
                    displayLength: 10,
                    main_route: "{{ route('um.playlist.trash') }}",
                    order_route: "{{ route('update.sort.order') }}",
                    export_columns: [1, 2],
                    model: 'Playlist',
                };

                initializeDataTable(details);
            });
        </script>
    @endpush
</x-admin::layout>
