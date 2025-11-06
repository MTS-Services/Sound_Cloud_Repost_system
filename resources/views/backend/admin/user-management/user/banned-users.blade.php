<x-admin::layout>
    <x-slot name="title">{{ __('Banned User List') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Banned User List') }}</x-slot>
    <x-slot name="page_slug">banned-users</x-slot>
    <section>

        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Banned User List') }}</h2>
                <div class="flex items-center gap-2">
                    <x-button href="{{ route('um.user.trash') }}" icon="trash-2" type='secondary' permission="user-trash">
                        {{ __('Trash') }}
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
                        <th>{{ __('Profile Link') }}</th>
                        <th>{{ __('Status') }}</th>
                        {{-- <th>{{ __('Banned Status') }}</th> --}}
                        {{-- <th>{{ __('Last Synced At') }}</th> --}}
                        <th>{{ __('Banned By') }}</th>
                        <th>{{ __('Banned Date') }}</th>
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
                    ['profile_link', true, true],
                    ['status', true, true],
                    // ['banned_label', true, true],
                    // ['last_synced_at', true, true],
                    ['banned_by', true, true],
                    ['banned_at_formatted', true, true],
                    ['action', false, false],
                ];
                const details = {
                    table_columns: table_columns,
                    main_class: '.datatable',
                    displayLength: 10,
                    main_route: "{{ route('um.user.banned-users') }}",
                    order_route: "{{ route('update.sort.order') }}",
                    export_columns: [0, 1, 2, 3, 4, 5, 6],
                    model: 'User',
                };
                // initializeDataTable(details);

                initializeDataTable(details);
            })
        </script>
    @endpush
</x-admin::layout>
