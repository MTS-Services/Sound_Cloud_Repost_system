<x-admin::layout>
    <x-slot name="title">{{ __('User Plan List') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('User Plan List') }}</x-slot>
    <x-slot name="page_slug">users_plane</x-slot>

<section>

        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('User Plan List') }}</h2>
                <div class="flex items-center gap-2">
                    {{-- <x-button href="{{ route('om.order.trash') }}" icon="trash-2" type='secondary'
                        permission="order-trash">
                        {{ __('Trash') }}
                    </x-button>
                     --}}
                </div>
            </div>
        </div>
        <div class="glass-card rounded-2xl p-6">
            <table class="table datatable table-zebra">
                <thead>
                    <tr>
                        <th >{{ __('SL') }}</th>
                        <th>{{ __('User Name') }}</th>
                        <th>{{ __('Plan Name') }}</th>
                        <th>{{ __('Order Number') }}</th> 
                        <th>{{ __('Price') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Start Date') }}</th>
                        <th>{{ __('End Date') }}</th>
                        <th>{{ __('Created Date') }}</th>
                        <th >{{ __('Action') }}</th>
                    </tr>
                </thead>
                
            </table>
        </div>
    </section>

    {{-- Details Modal --}}
      <x-admin.details-modal />

    @push('js')
        <script src="{{ asset('assets/js/details-modal.js') }}"></script>
        <script src="{{ asset('assets/js/datatable.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                let table_columns = [
                    //name and data, orderable, searchable
                   
                    ['user.name', true, true],
                    ['plan.name', true, true],
                    ['order.order_id', true, true],
                    ['price', true, true],
                    ['status', true, true],
                    ['start_date', true, true],
                    ['end_date', true, true],
                    ['created_at_formatted', true, true],
                    ['action', false, false],
                ];
                const details = {
                    table_columns: table_columns,
                    main_class: '.datatable',
                    displayLength: 10,
                    main_route: "{{ route('um.user-plane.index') }}",
                    order_route: "{{ route('update.sort.order') }}",
                    export_columns: [0, 1, 2, 3,4],
                    model: 'Repost',
                };
                // initializeDataTable(details);

                initializeDataTable(details);
            })
        </script>
         <script>
            document.addEventListener('DOMContentLoaded', () => {

                $(document).on('click', '.view', function() {
                    const id = $(this).data('id');
                    const route = "{{ route('um.user-plane.show', ':id') }}";

                    const details = [
                        {
                            label: '{{ __('User Name') }}',
                            key: 'user_name',
                        },
                       
                       
                        {
                            label: '{{ __('Plan Name') }}',
                            key: 'plan_name',
                        },
                        {
                            label: '{{ __('Order ID') }}',
                            key: 'order_number',
                        },
                        
                       
                        {
                            label: '{{ __('Price') }}',
                            key: 'price',
                        },
                        {
                            label: '{{ __('Note') }}',
                            key: 'notes',
                        },
                        {
                            label: '{{ __('Duration') }}',
                            key: 'duration',
                        },
                        {
                            label: '{{ __('Status') }}',
                            key: 'status_label',
                            label_color: 'status_color',
                            type: 'badge'
                        },
                        {
                            label: '{{ __('Start Date') }}',
                            key: 'start_date',
                        },
                        {
                            label: '{{ __('End Date') }}',
                            key: 'end_date',
                        },
                    ];

                    showDetailsModal(route, id, '{{ __('Repost Details') }}', details);
                });
            });
        </script>
@endpush

</x-admin::layout>