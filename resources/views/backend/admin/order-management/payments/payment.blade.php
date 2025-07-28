<x-admin::layout>
    <x-slot name="title">{{ __('Order List') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Order List') }}</x-slot>
    <x-slot name="page_slug">order</x-slot>
    <section>

        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Order List') }}</h2>
                <div class="flex items-center gap-2">
                    <x-button href="{{ route('om.order.trash') }}" icon="trash-2" type='secondary'
                        permission="order-trash">
                        {{ __('Trash') }}
                    </x-button>
                    
                </div>
            </div>
        </div>
        <div class="glass-card rounded-2xl p-6">
            <table class="table datatable table-zebra">
                <thead>
                    <tr>
                        <th >{{ __('SL') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Email') }}</th>    
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Transaction ID') }}</th>
                        <th>{{ __('currency') }}</th>
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
                 
                    ['name', true, true],
                    ['email_address', true, true],
                    ['amount', true, true],
                    ['payment_provider_id', true, true],
                    ['currency', true, true],
                    ['created_at', true, true],
                    ['action', false, false],
                ];
                const details = {
                    table_columns: table_columns,
                    main_class: '.datatable',
                    displayLength: 10,
                    main_route: "{{ route('om.credit-transaction.payments') }}",
                    order_route: "{{ route('update.sort.order') }}",
                    export_columns: [0, 1, 2, 3,5,6],
                    model: 'payment',
                };

                // initializeDataTable(details);

                initializeDataTable(details);
            })
        </script>

        {{-- Details Modal --}}
        {{-- <script>
            document.addEventListener('DOMContentLoaded', () => {

                $(document).on('click', '.view', function() {
                    const id = $(this).data('id');
                    const route = "{{ route('om.order.show', ':id') }}";

                    const details = [
                        {
                            label: '{{ __('User') }}',
                            key: 'user_urn',
                        },
                       
                       
                        {
                            label: '{{ __('Cradits') }}',
                            key: 'credits',
                        },
                        {
                            label: '{{ __('Amount') }}',
                            key: 'amount',
                        },
                        
                       
                         {
                            label: '{{ __('Status') }}',
                            key: 'status_label',
                            label_color: 'status_color',
                            type: 'badge'
                        }
                    ];

                    showDetailsModal(route, id, '{{ __('Order Details') }}', details);
                });
            });
        </script> --}}
    @endpush
</x-admin::layout>
