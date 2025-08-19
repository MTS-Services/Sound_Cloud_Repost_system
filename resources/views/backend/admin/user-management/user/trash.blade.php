<x-admin::layout>
    <x-slot name="title">{{ __('Trashed User List') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Trashed User List') }}</x-slot>
    <x-slot name="page_slug">user</x-slot>
    <section>

        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Trashed User List') }}</h2>
                <x-button href="{{ route('um.user.index') }}" icon="undo-2" type='info' permission="user-list">
                    {{ __('Back') }}
                </x-button>
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
                        <th>{{ __('Deleted By') }}</th>
                        <th>{{ __('Deleted Date') }}</th>
                        <th width="10%">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </section>

    @push('js')
        <script src="{{ asset('assets/js/datatable.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                let table_columns = [
                    //name and data, orderable, searchable
                    ['name', true, true],
                    ['soundcloud_id', true, true],
                    ['status', true, true],
                    ['last_synced_at', true, true],
                    ['deleter_id', true, true],
                    ['deleted_at', true, true],
                    ['action', false, false],
                ];
                const details = {
                    table_columns: table_columns,
                    main_class: '.datatable',
                    displayLength: 10,
                    main_route: "{{ route('um.user.trash') }}",
                    order_route: "{{ route('update.sort.order') }}",
                    export_columns: [0, 1, 2, 3, 4],
                    model: 'User',
                };
                initializeDataTable(details);
            })
        </script>
    @endpush
</x-admin::layout>
