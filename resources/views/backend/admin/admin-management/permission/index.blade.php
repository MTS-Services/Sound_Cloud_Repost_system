<x-admin::layout>
    <x-slot name="title">Permission</x-slot>
    <x-slot name="breadcrumb">Permission List</x-slot>
    <x-slot name="page_slug">permission</x-slot>
    <section>

        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Admin List') }}</h2>
                <div class="flex items-center gap-2">
                    <a href="{{ route('am.admin.trash') }}"
                        class="btn-primary px-4 py-2 rounded-xl flex items-center gap-2">
                        {{ __('Trash') }}
                    </a>
                    <a href="{{ route('am.admin.create') }}"
                        class="btn-primary px-4 py-2 rounded-xl text-text-white flex items-center gap-2">
                        {{ __('Add New') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="glass-card rounded-2xl p-6">
            <table class="table datatable table-zebra">
                <thead>
                    <tr>
                        <th width="5%">{{ __('SL') }}</th>
                        <th>{{ __('Prefix') }}</th>
                        <th>{{ __('Permisson') }}</th>
                        <th>{{ __('Created Date') }}</th>
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
                    ['prefix', true, true],
                    ['name', true, true],
                    ['created_at', true, true],
                    ['action', false, false],
                ];
                const details = {
                    table_columns: table_columns,
                    main_class: '.datatable',
                    displayLength: 10,
                    main_route: "{{ route('am.permission.index') }}",
                    order_route: "{{ route('update.sort.order') }}",
                    export_columns: [0, 1, 2, 3],
                    model: 'Permission',
                };
                // initializeDataTable(details);

                initializeDataTable(details);
            })
        </script>
    @endpush
</x-admin::layout>
