<x-admin::layout>
    <x-slot name="title">{{ __('Plans List') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Plans List') }}</x-slot>
    <x-slot name="page_slug">plan</x-slot>
    <section>

        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Plans List') }}</h2>
                <div class="flex items-center gap-2">
                    <x-button href="{{ route('pm.plan.trash') }}" icon="trash-2" type='secondary'
                        permission="admin-trash">
                        {{ __('Trash') }}
                    </x-button>
                    <x-button href="{{ route('pm.plan.create') }}" icon="user-plus" permission="admin-create">
                        {{ __('Add') }}
                    </x-button>
                </div>
            </div>
        </div>
        <div class="glass-card rounded-2xl p-6">
            <table class="table datatable table-zebra">
                <thead>
                    <tr>
                        <th width="5%">{{ __('SL') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Monthly Price') }}</th>
                        <th>{{ __('Yearly Price') }}</th>
                        <th>{{ __('Tag') }}</th>
                        <th>{{ __('Status') }}</th>
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
        <script src="{{ asset('assets/js/details-modal.js') }}"></script>
        <script src="{{ asset('assets/js/datatable.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                let table_columns = [
                    //name and data, orderable, searchable
                    ['name', true, true],
                    ['price_monthly', true, true],
                    ['price_monthly_yearly', true, true],
                    ['tag', true, true],
                    ['status', true, true],
                    ['created_by', true, true],
                    ['created_at', true, true],
                    ['action', false, false],
                ];
                const details = {
                    table_columns: table_columns,
                    main_class: '.datatable',
                    displayLength: 10,
                    main_route: "{{ route('pm.plan.index') }}",
                    order_route: "{{ route('update.sort.order') }}",
                    export_columns: [0, 1, 2, 3, 4, 5, 6, 7],
                    model: 'plan',
                };
                // initializeDataTable(details);

                initializeDataTable(details);
            })
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {

                $(document).on('click', '.view', function() {
                    const id = $(this).data('id');
                    const route = "{{ route('pm.plan.show', ':id') }}";

                    const details = [{
                            label: '{{ __('Name') }}',
                            key: 'name',
                        },
                        {
                            label: '{{ __('Slug') }}',
                            key: 'slug',
                        },
                        {
                            label: '{{ __('Notes') }}',
                            key: 'notes',
                        },
                        {
                            label: '{{ __('Status') }}',
                            key: 'status_label',
                            label_color: 'status_color',
                            type: 'badge'
                        },{
                            label:'{{ __('Monthly Price') }}',
                            key:'price_monthly',
                        },
                        {
                            label:'{{ __('Yearly Price') }}',
                            key:'price_monthly_yearly',
                        },
                        {
                            label:'{{ __('Tag') }}',
                            key:'tag',
                        }
                    ];

                    showDetailsModal(route, id, '{{ __('Credit Details') }}', details);
                });
            });
        </script>
    @endpush
</x-admin::layout>
