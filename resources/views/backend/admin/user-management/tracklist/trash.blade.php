<x-admin::layout>
    <x-slot name="title">{{ __('Trashed Tracklist List') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Trashed Tracklist List') }}</x-slot>
    <x-slot name="page_slug">user-tracklist</x-slot>
    <section>
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">
                    {{ __('Trashed Tracklist List') }}
                </h2>
                <div class="flex items-center gap-2">
                    <x-button href="{{ route('um.tracklist.index') }}" icon="arrow-left" type='secondary'>
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
                        <th>{{ __('Soundcloud ID') }}</th>
                        <th>{{ __('Soundcloud Kind') }}</th>
                        <th>{{ __('Release Year') }}</th>
                        <th>{{ __('Duration') }}</th>
                        <th>{{ __('Deleted By') }}</th>
                        <th>{{ __('Deleted Date') }}</th>
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
                    ['title', true, true],
                    ['tag_list', true, true],
                    ['user_urn', true, true],
                    ['author_soundcloud_id', true, true],
                    ['author_soundcloud_kind', true, true],
                    ['release_year', true, true],
                    ['duration', true, false],
                    ['deleter_id', true, true],
                    ['deleted_at', true, true],
                    ['action', false, false],
                ];

                const details = {
                    table_columns: table_columns,
                    main_class: '.datatable',
                    displayLength: 10,
                    main_route: "{{ route('um.tracklist.trash') }}",
                    order_route: "{{ route('update.sort.order') }}",
                    export_columns: [1, 2],
                    model: 'Playlist',
                };

                initializeDataTable(details);
            });
        </script>
    @endpush
</x-admin::layout>
