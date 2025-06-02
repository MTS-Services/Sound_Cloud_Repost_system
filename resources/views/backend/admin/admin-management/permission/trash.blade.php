<x-admin::layout>
    <x-slot name="title">{{ __('Trashed Permission List') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Trashed Permission List') }}</x-slot>
    <x-slot name="page_slug">permission</x-slot>
    <section>

        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Trashed Permission List') }}
                </h2>
                <x-admin.primary-link href="{{ route('am.permission.index') }}">{{ __('Back') }}
                </x-admin.primary-link>
            </div>
        </div>

        <div class="glass-card rounded-2xl p-6">
            <table class="table datatable table-zebra">
                <thead>
                    <tr>
                        <th>{{ __('SL') }}</th>
                        <th>{{ __('Prefix') }}</th>
                        <th>{{ __('Name') }}</th>
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
                    ['prefix', true, true],
                    ['name', true, true],
                    ['deleted_by', true, true],
                    ['deleted_at', true, true],
                    ['action', false, false],
                ];
                const details = {
                    table_columns: table_columns,
                    main_class: '.datatable',
                    displayLength: 10,
                    main_route: "{{ route('am.permission.trash') }}",
                    order_route: "{{ route('update.sort.order') }}",
                     export_columns: [0, 1, 2, 3, 4],
                    model: 'Permission',
                };
                initializeDataTable(details);
            })
        </script>
    @endpush
</x-admin::layout>
