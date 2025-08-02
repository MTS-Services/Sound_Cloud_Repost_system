<x-admin::layout>
    <x-slot name="title">{{ __('Repost Request List') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Repost Request List') }}</x-slot>
    <x-slot name="page_slug">repostRequest</x-slot>

<section>

        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Repost Request List') }}</h2>
                <div class="flex items-center gap-2">
                    {{-- <x-button href="{{ route('om.order.trash') }}" icon="trash-2" type='secondary'
                        permission="order-trash">
                        {{ __('Trash') }}
                    </x-button>
                     --}}
                      {{-- <x-button href="{{ route('rrm.request.index') }}"  type='secondary'
                        permission="order-trash">
                        {{ __('Back') }}
                    </x-button> --}}
                    
                </div>
            </div>
        </div>
        <div class="glass-card rounded-2xl p-6">
            <table class="table datatable table-zebra">
                <thead>
                    <tr>
                        <th >{{ __('SL') }}</th>
                        <th>{{ __('Request Name') }}</th>
                    
                        <th>{{ __('Campaign Title') }}</th>
                        <th>{{ __('Credits') }}</th>
                        <th>{{ __('Requested At') }}</th>
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
                  
                    ['title', true, true],                 
                    ['credits_spent', true, true],
                    ['requested_at', true, true],                  
                    ['created_at', true, true],
                    ['action', false, false],
                ];
                const details = {
                    table_columns: table_columns,
                    main_class: '.datatable',
                    displayLength: 10,
                    main_route: "{{ route('rrm.request.index') }}",
                    order_route: "{{ route('update.sort.order') }}",
                    export_columns: [0, 1, 2, 3,4],
                    model: 'RepostRequest',
                };
                // initializeDataTable(details);

                initializeDataTable(details);
            })
        </script>
         <script>
            document.addEventListener('DOMContentLoaded', () => {

                $(document).on('click', '.view', function() {
                    const id = $(this).data('id');
                    const route = "{{ route('rrm.request.show', ':id') }}";

                    const details = [
                        {
                            label: '{{ __('requester') }}',
                            key: 'name',
                        },
                       
                       
                        {
                            label: '{{ __('Camping Title') }}',
                            key: 'title',
                        },
                        {
                            label: '{{ __('Target User') }}',
                            key: 'target_user_urn',
                        },
                         {
                            label: '{{ __('Track') }}',
                            key: 'track_urn',
                        },
                        
                       
                        {
                            label: '{{ __('Credits') }}',
                            key: 'credits_spent',
                        },
                        {
                            label: '{{ __('Status') }}',
                            key: 'status',
                        },
                        {
                            label: '{{ __('Rejection Reason') }}',
                            key: 'rejection_reason',
                        },
                        {
                            label: '{{ __('Expired At') }}',
                            key: 'expired_at',
                        },
                        {
                            label: '{{ __('Requested At') }}',
                            key: 'requested_at',
                        },
                        {
                            label: '{{ __('Reposted At') }}',
                            key: 'reposted_at',
                        },
                        {
                            label: '{{ __('Completed At') }}',
                            key: 'completed_at',
                        },
                        {
                            label: '{{ __('Created Date') }}',
                            key: 'created_at',
                        },
                    ];

                    showDetailsModal(route, id, '{{ __('Repost Details') }}', details);
                });
            });
        </script>
@endpush


</x-admin::layout>