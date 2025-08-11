<x-admin::layout>
    <x-slot name="title">{{ __('Payments List') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Payments List') }}</x-slot>
    <x-slot name="page_slug">payment</x-slot>
    <section>

        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Payments List') }}</h2>
            </div>
        </div>
        <div class="glass-card rounded-2xl p-6">
            <table class="table datatable table-zebra">
                <thead>
                    <tr>
                        <th>{{ __('SL') }}</th>
                        <th>{{ __('User') }}</th>
                        <th>{{ __('Order ID') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Currency') }}</th>
                        <th>{{ __('Payment Gateway') }}</th>
                        <th>{{ __('Created Date') }}</th>
                        <th>{{ __('Action') }}</th>
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
                    //name and data, Paymentsable, searchable

                    ['user_urn', true, true],
                    ['order.order_id', true, true],
                    ['amount', true, true],
                    ['currency', true, true],
                    ['payment_gateway_label', true, true],
                    ['created_at', true, true],
                    ['action', false, false],
                ];
                const details = {
                    table_columns: table_columns,
                    main_class: '.datatable',
                    displayLength: 10,
                    main_route: "{{ route('om.credit-transaction.payments') }}",
                    order_route: "{{ route('update.sort.order') }}",
                    export_columns: [0, 1, 2, 3, 5, 6],
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
                    const route = "{{ route('om.Payments.show', ':id') }}";

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

                    showDetailsModal(route, id, '{{ __('Payments Details') }}', details);
                });
            });
        </script> --}}
    @endpush
</x-admin::layout>
