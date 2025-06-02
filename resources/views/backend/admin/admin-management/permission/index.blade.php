<x-admin::layout>
    <x-slot name="title">{{ __('Permission List') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Permission List') }}</x-slot>
    <x-slot name="page_slug">permission</x-slot>
    <section>

        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Permission List') }}</h2>
                <div class="flex items-center gap-2">
                    <x-admin.secondary-link error="true" href="{{ route('am.permission.trash') }}">{{ __('Trash') }}
                    </x-admin.secondary-link>
                    <x-admin.primary-link href="{{ route('am.permission.create') }}">{{ __('Add') }}
                    </x-admin.primary-link>
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

    <x-admin.details-modal />

    @push('js')
        <script src="{{ asset('assets/js/datatable.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                let table_columns = [
                    //name and data, orderable, searchable
                    ['prefix', true, true],
                    ['name', true, true],
                    ['created_by', true, true],
                    ['created_at', true, true],
                    ['action', false, false],
                ];
                const details = {
                    table_columns: table_columns,
                    main_class: '.datatable',
                    displayLength: 10,
                    main_route: "{{ route('am.permission.index') }}",
                    order_route: "{{ route('update.sort.order') }}",
                    export_columns: [0, 1, 2, 3, 4],
                    model: 'Permission',
                };
                // initializeDataTable(details);

                initializeDataTable(details);
            })
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', () => {

                $(document).on('click', '.view', function() {
                    const id = $(this).data('id');
                    const route = "{{ route('am.permission.show', ':id') }}";

                    const details = [{
                            label: '{{ __('Prefix') }}',
                            key: 'prefix',
                        },
                        {
                            label: '{{ __('Permisisons Name') }}',
                            key: 'name',
                            icon: 'shield-check',
                        },
                    ];

                    showDetailsModal(route, id, '{{ __('Permisisons Details') }}', details);
                });
            });
        </script>
    @endpush
</x-admin::layout>
