<x-admin::layout>
    <x-slot name="title">{{ __('Admin List') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Admin List') }}</x-slot>
    <x-slot name="page_slug">admin</x-slot>
    <section>

        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Admin List') }}</h2>
                <div class="flex items-center gap-2">
                    <x-button href="{{ route('am.admin.trash') }}" icon="trash-2" type='secondary'
                        permission="admin-trash">
                        {{ __('Trash') }}
                    </x-button>
                    <x-button href="{{ route('am.admin.create') }}" icon="plus" permission="admin-create">
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
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Role') }}</th>
                        <th>{{ __('Verified') }}</th>
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
                    ['email', true, true],
                    ['role_id', true, true],
                    ['email_verified_at', true, true],
                    ['created_by', true, true],
                    ['created_at', true, true],
                    ['action', false, false],
                ];
                const details = {
                    table_columns: table_columns,
                    main_class: '.datatable',
                    displayLength: 10,
                    main_route: "{{ route('am.admin.index') }}",
                    order_route: "{{ route('update.sort.order') }}",
                    export_columns: [0, 1, 2, 3, 4],
                    model: 'Admin',
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
                    const route = "{{ route('am.admin.show', ':id') }}";

                    const details = [{
                            label: '{{ __('Name') }}',
                            key: 'name',
                        },
                        {
                            label: '{{ __('Email') }}',
                            key: 'email',
                        },
                        {
                            label: '{{ __('Email Verified') }}',
                            key: 'verify_label',
                            label_color: 'verify_color',
                            type: 'badge',
                        },
                         {
                            label: '{{ __('Status') }}',
                            key: 'status_label',
                            label_color: 'verify_color',
                            type: 'badge',
                        },
                        {
                            label: '{{ __('Role') }}',
                            key: 'role_id',
                        },
                         {
                            label: '{{ __('Image') }}',
                            key: 'modified_image',
                            type: 'image',
                        },

                    ];

                    showDetailsModal(route, id, '{{ __('Admin Details') }}', details);
                });
            });
        </script>
    @endpush
</x-admin::layout>
