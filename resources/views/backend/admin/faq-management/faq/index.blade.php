<x-admin::layout>
    <x-slot name="title">{{ __('Faq ') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Faq ') }}</x-slot>
    <x-slot name="page_slug">faq</x-slot>

<section>

        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Faq List') }}</h2>
                <div class="flex items-center gap-2">
                    <x-button href="{{ route('fm.faq.trash') }}" icon="trash-2" type='secondary'
                        permission="Faq-trash">
                        {{ __('Trash') }}
                    </x-button>
                    <x-button href="{{ route('fm.faq.create') }}" icone="user-plus" permission="Faq-create">
                        {{ __('Add') }}
                    </x-button>
                </div>
            </div>
        </div>
        <div class="glass-card rounded-2xl p-6">
            <table class="table datatable table-zebra">
                <thead>
                    <tr>
                        <th>{{ __('SL') }}</th>
                        <th>{{__('Faq Category')}}</th>
                        <th>{{ __('Question') }}</th>
                      
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
                    
                    ['faq_category_id', true, true],
                    ['question', true, true],
                    
                    ['status', true, true],                 
                    ['created_at', true, true],
                    ['action', false, false],
                ];
                const details = {
                    table_columns: table_columns,
                    main_class: '.datatable',
                    displayLength: 10,
                    main_route: "{{ route('fm.faq.index') }}",
                    order_route: "{{ route('update.sort.order') }}",
                    export_columns: [0, 1, 2, 3,],
                    model: 'Faq',
                };
                // initializeDataTable(details);

                initializeDataTable(details);
            })
        </script>

        {{-- Details Modal --}}
        <script>
            document.addEventListener('DOMContentLoaded', () => {

                $(document).on('click', '.view', function() {
                    const id = $(this).data('id');
                    const route = "{{ route('fm.faq.show', ':id') }}";

                    const details = [
                        
                        {
                            label: '{{ __('Faq Category') }}',
                            key: 'faq_category_id',
                        },
                        {

                       
                            label: '{{ __('Question') }}',
                            key: 'question',
                        },
                        {
                            label: '{{ __('Description') }}',
                            key: 'description',
                        },
                       
                        {
                            label: '{{ __('Status') }}',
                            key: 'status',

                           
                        },
                       
                        
                    ];

                    showDetailsModal(route, id, '{{ __('Campaign Details') }}', details);
                });
            });
        </script>
    @endpush
    

</x-admin::layout>