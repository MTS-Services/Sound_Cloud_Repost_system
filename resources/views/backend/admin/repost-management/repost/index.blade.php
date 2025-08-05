<x-admin::layout>
    <x-slot name="title">{{ __('Repost List') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Repost List') }}</x-slot>
    <x-slot name="page_slug">repost</x-slot>



<section>

        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Repost List') }}</h2>
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
                        <th>{{ __('Requester Name') }}</th>
                        <th>{{ __('Reposter Name') }}</th>
                        <th>{{ __('Type') }}</th> 
                        <th>{{ __('Credits') }}</th>
                        <th>{{ __('Reposted At') }}</th>
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
                   
                    ['requester_name', true, true],
                    ['reposter_name', true, true],
                    ['repost_type', true, true],
                    ['credits_earned', true, true],
                    ['reposted_at', true, true],
                   
                    ['created_at', true, true],
                    ['action', false, false],
                ];
                const details = {
                    table_columns: table_columns,
                    main_class: '.datatable',
                    displayLength: 10,
                    main_route: "{{ route('rm.repost.index') }}",
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
                    const route = "{{ route('rm.repost.show', ':id') }}";

                    const details = [
                        {
                            label: '{{ __('reposter') }}',
                            key: 'name',
                        },
                       
                       
                        {
                            label: '{{ __('Camping Title') }}',
                            key: 'title',
                        },
                        {
                            label: '{{ __('Soundcloud ID') }}',
                            key: 'soundcloud_repost_id',
                        },
                        
                       
                        {
                            label: '{{ __('Credits') }}',
                            key: 'credits_earned',
                        },
                        {
                            label: '{{ __('Reposted At') }}',
                            key: 'reposted_at',
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