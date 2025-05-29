<x-admin::layout>
    <x-slot name="title">Admin</x-slot>
    <x-slot name="breadcrumb">Admin List</x-slot>
    <x-slot name="page_slug">admin</x-slot>

    <section>

        <div class="glass-card rounded-2xl p-6">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold text-text-black dark:text-text-white">{{ __('User Management') }}</h2>
                <a href="{{ route('am.admin.create') }}"
                    class="btn-primary px-4 py-2 rounded-xl text-text-white flex items-center gap-2">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                    Add User
                </a>
            </div>
        </div>

        <div class="glass-card rounded-2xl p-6 mt-6">
            <div class="overflow-x-auto">
                <table class="table datatable table-zebra">
                    <thead>
                        <tr>
                            <th>{{ __('SL') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Created Date') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    @push('js')
        <script src="{{ asset('assets/js/datatable.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                let table_columns = [
                    //name and data, orderable, searchable
                    ['name', true, true],
                    ['email', true, true],
                    ['created_at', true, true],
                    ['action', false, false],
                ];
                const details = {
                    table_columns: table_columns,
                    main_class: '.datatable',
                    displayLength: 10,
                    main_route: "{{ route('am.admin.index') }}",
                    order_route: "{{ route('update.sort.order') }}",
                    export_columns: [0, 1, 2, 3],
                    model: 'Admin',
                };
                // initializeDataTable(details);

                initializeDataTable(details);
            })
        </script>
    @endpush
</x-admin::layout>
