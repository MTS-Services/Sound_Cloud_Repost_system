<x-admin::layout>
    <x-slot name="title">{{ __('Faq Category Trash') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Faq Category Trash') }}</x-slot>
    <x-slot name="page_slug">faq_category</x-slot>

<section>

        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('FaqCategory Trash') }}</h2>
                <div class="flex items-center gap-2">
                
                    <x-button href="{{ route('fm.faq-category.index') }}" icone="back" permission="FaqCategory-create">
                        {{ __('Back') }}
                    </x-button>
                </div>
            </div>
        </div>
        <div class="glass-card rounded-2xl p-6">
            <table class="table datatable table-zebra">
                <thead>
                    <tr>
                        <th>{{ __('SL') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Slug') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Created By') }}</th>
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
                    main_route: "{{ route('fm.faq-category.trash') }}",
                    order_route: "{{ route('update.sort.order') }}",
                    export_columns: [0, 1, 2, 3,4,5],
                    model: 'FaqCategory',
                };
                // initializeDataTable(details);

                initializeDataTable(details);
            })
        </script>

    @endpush
    

</x-admin::layout>