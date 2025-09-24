<x-admin::layout>
    <x-slot name="title">{{ __('Purchase History') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Purchase History') }}</x-slot>
    <x-slot name="page_slug">purchase</x-slot>

    <section>

        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Purchase List') }}</h2>
                <div class="flex items-center gap-2">

                </div>
            </div>
        </div>
        <div class="glass-card rounded-2xl p-6">
            <table class="table datatable table-zebra">
                <thead>
                    <tr>
                        <th width="5%">{{ __('SL') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Credits') }}</th>
                        <th width="10%">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </section>

    {{-- Details Modal --}}
    {{-- <x-admin.details-modal /> --}}

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
                    ['action', false, false],
                ];
                const details = {
                    table_columns: table_columns,
                    main_class: '.datatable',
                    displayLength: 10,
                    main_route: "{{ route('om.credit-transaction.purchase') }}",
                    order_route: "{{ route('update.sort.order') }}",
                    export_columns: [0, 1],
                    model: 'CreditTransaction',
                };
                // initializeDataTable(details);

                initializeDataTable(details);
            })
        </script>
    @endpush

</x-admin::layout>
