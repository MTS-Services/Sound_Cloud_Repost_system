<x-admin::layout>
    <x-slot name="title">{{ __('Admin List') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Admin List') }}</x-slot>
    <x-slot name="page_slug">admin</x-slot>
    <section>

        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Admin List') }}</h2>
                <div class="flex items-center gap-2">
                    <x-admin.secondary-link error="true" href="{{ route('am.admin.trash') }}">{{ __('Trash') }}
                    </x-admin.secondary-link>
                    <x-admin.primary-link href="{{ route('am.admin.create') }}">{{ __('Add') }}
                    </x-admin.primary-link>
                </div>
            </div>
        </div>

        <div class="glass-card rounded-2xl p-6">
            <table class="table datatable table-zebra">
                <thead>
                    <tr>
                        <th width="5%">{{ __('SL') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Email') }}</th>
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

    @push('js')
        <script src="{{ asset('assets/js/datatable.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                let table_columns = [
                    //name and data, orderable, searchable
                    ['name', true, true],
                    ['email', true, true],
                    ['created_by', true, true],
                    ['created_at', true, true],
                    ['action', false, false],
                ];
                const details = {
                    table_columns: table_columns,
                    main_class: '.datatable',
                    displayLength: 10,
                    main_route: "{{ route('am.admin.index') }}",
                    order_route: "{{ route('update.sort.order') }}",
                    export_columns: [0, 1, 2, 3],
                    model: 'Admin',
                };
                // initializeDataTable(details);

                initializeDataTable(details);
            })
        </script>
    @endpush
</x-admin::layout>
