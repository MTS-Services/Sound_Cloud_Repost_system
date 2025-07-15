<x-admin::layout>
    <x-slot name="title">{{ __('Trashed Credit List') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Trashed Credit List') }}</x-slot>
    <x-slot name="page_slug">credit</x-slot>
    <section>

        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Trashed Credit List') }}</h2>
                <x-button href="{{ route('pm.credit.index') }}" icon="undo-2" type='info'>
                    {{ __('Back') }}
                </x-admin.button>
            </div>
        </div>

        <div class="glass-card rounded-2xl p-6">
            <table class="table datatable table-zebra">
                <thead>
                    <tr>
                        <th width="5%">{{ __('SL') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Price') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Credits') }}</th>
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
                    ['price', true, true],
                    ['status', true, true],
                    ['credits', true, true],
                    ['deleted_by', true, true],
                    ['deleted_at', true, true],
                    ['action', false, false],
                ];
                const details = {
                    table_columns: table_columns,
                    main_class: '.datatable',
                    displayLength: 10,
                    main_route: "{{ route('pm.credit.trash') }}",
                    order_route: "{{ route('update.sort.order') }}",
                    export_columns: [0, 1, 2, 3, 4, 5],
                    model: 'Credit',
                };
                initializeDataTable(details);
            })
        </script>
    @endpush
</x-admin::layout>
