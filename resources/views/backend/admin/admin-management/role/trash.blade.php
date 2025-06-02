<x-admin::layout>
    <x-slot name="title">{{ __('Trashed Role List') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Trashed Role List') }}</x-slot>
    <x-slot name="page_slug">role</x-slot>
    <section>

        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Trashed Role List') }}
                </h2>
                <x-admin.primary-link href="{{ route('am.role.index') }}">{{ __('Back') }}
                </x-admin.primary-link>
            </div>
        </div>

        <div class="glass-card rounded-2xl p-6">
            <table class="table datatable table-zebra">
                <thead>
                    <tr>
                        <th>{{ __('SL') }}</th>
                        <th>{{ __('Role') }}</th>
                        <th>{{ __('Deleted By') }}</th>
                        <th>{{ __('Deleted Date') }}</th>
                        <th>{{ __('Action') }}</th>
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
                    ['deleted_by', true, true],
                    ['deleted_at', true, true],
                    ['action', false, false],
                ];
                const details = {
                    table_columns: table_columns,
                    main_class: '.datatable',
                    displayLength: 10,
                    main_route: "{{ route('am.role.trash') }}",
                    order_route: "{{ route('update.sort.order') }}",
                    export_columns: [0, 1, 2, 3],
                    model: 'Role',
                };
                initializeDataTable(details);
            })
        </script>
    @endpush
</x-admin::layout>
