<x-admin::layout>
    <x-slot name="title">{{ __('Faq Category List') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Faq Category List') }}</x-slot>
    <x-slot name="page_slug">faq_category</x-slot>
    <section>

        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Faq Category List') }}</h2>
                <div class="flex items-center gap-2">
                    <x-button href="{{ route('fm.faq-category.trash') }}" icon="trash-2" type='secondary'
                        permission="Faq-trash">
                        {{ __('Trash') }}
                    </x-button>
                    <x-button href="{{ route('fm.faq-category.create') }}"  permission="faq_category-create">
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
                        <th>{{ __('Slug') }}</th>
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


    @push('js')
        <script src="{{ asset('assets/js/details-modal.js') }}"></script>
        <script src="{{ asset('assets/js/datatable.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                let table_columns = [
                    //name and data, orderable, searchable
                    ['name', true, true],
                    ['slug', true, true],
                    ['status', true, true],
                    ['created_by', true, true],
                    ['created_at', true, true],
                    ['action', false, false],
                ];
                const details = {
                    table_columns: table_columns,
                    main_class: '.datatable',
                    displayLength: 10,
                    main_route: "{{ route('fm.faq-category.index') }}",
                    order_route: "{{ route('update.sort.order') }}",
                    export_columns: [0, 1, 2, 3, 4, 5],
                    model: 'FaqCategory',
                };
                // initializeDataTable(details);

                initializeDataTable(details);
            })
        </script>

         <script>
           document.addEventListener('DOMContentLoaded', () => {

                $(document).on('click', '.view', function() {
                    const id = $(this).data('id');
                    const route = "{{ route('fm.faq-category.show', ':id') }}";

                    const details = [
                        {
                       
                            label: '{{ __('Name') }}',
                            key: 'name',
                        },
                        {
                            label: '{{ __('Slug') }}',
                            key: 'slug',
                        },
                        
                        {
                            label: '{{ __('Status') }}',
                            key: 'status',

                           
                        },
                        {
                            label: '{{ __('Created By') }}',
                            key: 'created_by',
                        },
                        {
                            label: '{{ __('Created Date') }}',
                            key: 'created_at',
                        }
                    ];

                    showDetailsModal(route, id, '{{ __('Faq Category Details') }}', details);
                });
            });
        </script>
    @endpush


</x-admin::layout>
