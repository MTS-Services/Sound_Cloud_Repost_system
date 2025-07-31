<x-admin::layout>
    <x-slot name="title">{{ __('Repost List') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Repost List') }}</x-slot>
    <x-slot name="page_slug">repost</x-slot>



<section>

        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Order List') }}</h2>
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
                        <th>{{ __('User') }}</th>
                        <th>{{ __('Credits') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Status') }}</th>
                       
                        <th>{{ __('Created Date') }}</th>
                        <th >{{ __('Action') }}</th>
                    </tr>
                </thead>
                
            </table>
        </div>
    </section>

    {{-- Details Modal --}}
    <x-admin.details-modal />

    {{-- @push('js')
        <script src="{{ asset('assets/js/details-modal.js') }}"></script>
        <script src="{{ asset('assets/js/datatable.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                let table_columns = [
                    //name and data, orderable, searchable
                 
                    ['user_urn', true, true],
                    ['credits', true, true],
                    ['amount', true, true],
                    ['status', true, true],
                   
                    ['created_at', true, true],
                    ['action', false, false],
                ];
                const details = {
                    table_columns: table_columns,
                    main_class: '.datatable',
                    displayLength: 10,
                    main_route: "{{ route('om.order.index') }}",
                    order_route: "{{ route('update.sort.order') }}",
                    export_columns: [0, 1, 2, 3,5],
                    model: 'Order',
                };
                // initializeDataTable(details);

                initializeDataTable(details);
            })
        </script> --}}






</x-admin::layout>