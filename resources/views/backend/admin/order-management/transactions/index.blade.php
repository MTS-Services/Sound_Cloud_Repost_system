<x-admin::layout>
    <x-slot name="title">{{ __('Credit transaction History') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Credit transaction History') }}</x-slot>
    <x-slot name="page_slug">credit-transaction</x-slot>

    <section>

        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Transaction List') }}</h2>
                <div class="flex items-center gap-2">
                    
                </div>
            </div>
        </div>
        <div class="glass-card rounded-2xl p-6">
            <table class="table datatable table-zebra">
                <thead>
                    <tr>
                        <th>{{ __('SL') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Credits') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Created Date') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
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
                    ['amount', true, true],
                    ['credits', true, true],
                    ['status', true, true],
                    ['created_at', true, true],
                    ['action', false, false],
                ];
                const details = {
                    table_columns: table_columns,
                    main_class: '.datatable',
                    displayLength: 10,
                    main_route: "{{ route('om.credit-transaction.index') }}",
                    order_route: "{{ route('update.sort.order') }}",
                    export_columns: [0, 1,2,3,4,5],
                    model: 'CreditTransaction',
                };
                // initializeDataTable(details);

                initializeDataTable(details);
            })
        </script>

        <!--- Details Modal-->
        <script>
            document.addEventListener('DOMContentLoaded', () => {

                $(document).on('click', '.view', function() {
                    const id = $(this).data('id');
                    const route = "{{ route('om.credit-transaction.show', ':id') }}";

                    const details = [{
                            label: '{{ __('Name') }}',
                            key: 'receiver_urn',
                        },
                        {
                            label: '{{ __('Sender ') }}',
                            key: 'sender_urn',
                        },
                        {
                            label: '{{ __('Calculation ') }}',
                            key: 'calculation_type',
                        },
                        {
                            label: '{{ __('Source ') }}',
                            key: 'source_id',
                        },
                        {
                            label: '{{ __('Source Type ') }}',
                            key: 'source_type',
                        },
                        {
                            label: '{{ __('Transaction Type ') }}',
                            key: 'transaction_type',
                        },
                        {
                            label: '{{ __('Amount') }}',
                            key: 'amount',
                        },
                        {
                            label: '{{ __('Status') }}',
                            key: 'status',
                            label_color: 'status_color',
                            type: 'badge'
                        },
                       
                        {
                            label: '{{ __('Credits') }}',
                            key: 'credits',
                        },
                        {
                            label: '{{ __('Metadata') }}',
                            key: 'metadata',
                        },
                         {
                            label: '{{ __('Date') }}',
                            key: 'created_at',
                        },
                    ];

                    showDetailsModal(route, id, '{{ __('Credits Details') }}', details);
                });
            });
        </script>
    @endpush

</x-admin::layout>
