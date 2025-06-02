<x-admin::layout>
    <x-slot name="title">{{ __('Role List') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Role List') }}</x-slot>
    <x-slot name="page_slug">role</x-slot>
    <section>

        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Role List') }}</h2>
                <div class="flex items-center gap-2">
                    <x-admin.secondary-link error="true" href="{{ route('am.role.trash') }}">{{ __('Trash') }}
                    </x-admin.secondary-link>
                    <x-admin.primary-link href="{{ route('am.role.create') }}">{{ __('Add') }}
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

    {{-- Details Modal --}}
    <x-admin.details-modal/>

    @push('js')
        <script src="{{ asset('assets/js/datatable.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                let table_columns = [
                    //name and data, orderable, searchable
                    ['name', true, true],
                    ['created_by', true, true],
                    ['created_at', true, true],
                    ['action', false, false],
                ];
                const details = {
                    table_columns: table_columns,
                    main_class: '.datatable',
                    displayLength: 10,
                    main_route: "{{ route('am.role.index') }}",
                    order_route: "{{ route('update.sort.order') }}",
                    export_columns: [0, 1, 2, 3],
                    model: 'Role',
                };
                // initializeDataTable(details);

                initializeDataTable(details);
            })
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {

                $(document).on('click', '.view', function() {
                    const id = $(this).data('id');
                    const route = "{{ route('am.role.show', ':id') }}";

                    const details = [{
                            label: '{{ __('Role Name') }}',
                            key: 'name',
                        },
                        {
                            label: '{{ __('Permisisons') }}',
                            key: 'permissions',
                            loop: true,
                            loopKey: 'name',
                        },
                    ];

                    showDetailsModal(route, id, '{{ __('Role Details') }}', details);
                });
            });
        </script>
    @endpush
</x-admin::layout>
